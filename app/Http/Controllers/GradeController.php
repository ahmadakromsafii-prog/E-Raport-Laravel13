<?php
namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\TaskComponent;
use App\Models\TaskScore;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::with(['student','classroom','subject','semester','academicYear'])
            ->where('teacher_id',auth()->id())->latest()->paginate(15)->withQueryString();
        return view('grades.index', compact('grades'));
    }

    public function create()
    {
        return view('grades.form', [
            'grade'=>new Grade(),
            'classrooms'=>Classroom::orderBy('name')->get(),
            'subjects'=>Subject::orderBy('name')->get(),
            'years'=>AcademicYear::orderByDesc('id')->get(),
            'semesters'=>Semester::with('academicYear')->orderBy('id')->get(),
            'students'=>User::where('role','siswa')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['teacher_id'] = auth()->id();
        $data['final_score'] = round(($data['knowledge_score'] + $data['skill_score']) / 2, 2);
        $data['status'] = 'published';
        $data['published_at'] = now();
        Grade::updateOrCreate([
            'student_id'=>$data['student_id'],'subject_id'=>$data['subject_id'],
            'academic_year_id'=>$data['academic_year_id'],'semester_id'=>$data['semester_id']
        ], $data);
        return redirect()->route('grades.index')->with('success','Nilai berhasil disimpan dan langsung ditampilkan kepada siswa.');
    }

    public function edit(Grade $grade)
    {
        abort_unless($grade->teacher_id === auth()->id() && in_array($grade->status,['draft','revision','published']),403);
        return view('grades.form', [
            'grade'=>$grade,'classrooms'=>Classroom::orderBy('name')->get(),
            'subjects'=>Subject::orderBy('name')->get(),'years'=>AcademicYear::all(),
            'semesters'=>Semester::all(),'students'=>User::where('role','siswa')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Grade $grade)
    {
        abort_unless($grade->teacher_id === auth()->id() && in_array($grade->status,['draft','revision','published']),403);
        $data = $this->validated($request);
        $data['final_score'] = round(($data['knowledge_score'] + $data['skill_score']) / 2, 2);
        $data['status'] = 'published';
        $data['published_at'] = now();
        $grade->update($data);
        return redirect()->route('grades.index')->with('success','Perubahan nilai tersimpan dan langsung diperbarui pada akun siswa.');
    }

    public function submit(Request $request)
    {
        $ids = $request->validate(['ids'=>['required','array'],'ids.*'=>['integer','exists:grades,id']])['ids'];
        $count = Grade::whereIn('id',$ids)->where('teacher_id',auth()->id())->whereIn('status',['draft','revision'])
            ->update(['status'=>'submitted','submitted_at'=>now(),'teacher_note'=>null]);
        return back()->with('success',"$count data nilai dikirim untuk validasi admin.");
    }



    /** Kirim seluruh nilai draft pada satu kelas/mapel/periode ke antrean validasi admin. */
    public function submitBulk(Request $request)
    {
        $d = $request->validate([
            'classroom_id' => ['required','exists:classrooms,id'],
            'subject_id' => ['required','exists:subjects,id'],
            'academic_year_id' => ['required','exists:academic_years,id'],
            'semester_id' => ['required','exists:semesters,id'],
        ]);

        $user = auth()->user();
        if ($user->role === 'guru') {
            $allowed = \App\Models\TeacherAssignment::where('teacher_id',$user->id)
                ->where('classroom_id',$d['classroom_id'])->where('subject_id',$d['subject_id'])->exists();
            abort_unless($allowed,403,'Anda tidak ditugaskan mengajar mapel dan kelas ini.');
        } else {
            abort_unless($user->role === 'admin',403);
        }

        $query = Grade::where($d)->whereIn('status',['draft','revision']);
        if ($user->role === 'guru') $query->where('teacher_id',$user->id);
        $count = $query->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'teacher_note' => null,
            'updated_at' => now(),
        ]);

        return redirect()->route('grades.bulk', $d + ['mode'=>'kelas'])
            ->with('success', $count > 0
                ? "$count nilai berhasil dikirim ke Validasi Nilai admin."
                : 'Tidak ada nilai Draft/Revisi yang siap dikirim. Pastikan komponen nilai sudah tersimpan.');
    }

    public function saveWeights(Request $request){
        $d=$request->validate(['academic_year_id'=>'required|exists:academic_years,id','semester_id'=>'required|exists:semesters,id','classroom_id'=>'required|exists:classrooms,id','tugas_weight'=>'required|numeric|min:0|max:100','uts_weight'=>'required|numeric|min:0|max:100','uas_weight'=>'required|numeric|min:0|max:100']);
        if(round($d['tugas_weight']+$d['uts_weight']+$d['uas_weight'],2)!==100.0) return back()->withErrors(['weights'=>'Jumlah seluruh bobot wajib tepat 100%.']);
        \Illuminate\Support\Facades\DB::table('grade_weights')->updateOrInsert(['academic_year_id'=>$d['academic_year_id'],'semester_id'=>$d['semester_id'],'school_level'=>Classroom::findOrFail($d['classroom_id'])->school_level],['tugas_weight'=>$d['tugas_weight'],'uts_weight'=>$d['uts_weight'],'uas_weight'=>$d['uas_weight'],'updated_at'=>now(),'created_at'=>now()]);
        return back()->with('success','Bobot penilaian berhasil diperbarui. Nilai akhir akan mengikuti bobot ini.');
    }

    public function bulk(Request $request) {
        $user=auth()->user();
        if(in_array($user->role,['admin','super_admin'],true)){ $classrooms=Classroom::orderBy('name')->get(); $subjects=Subject::orderBy('name')->get(); } else { $assignments=\App\Models\TeacherAssignment::with(['classroom','subject'])->where('teacher_id',$user->id)->get(); $classrooms=$assignments->pluck('classroom')->unique('id')->values(); $subjects=$assignments->pluck('subject')->unique('id')->values(); } $years=AcademicYear::orderByDesc('id')->get(); $semesters=Semester::with('academicYear')->orderBy('id')->get();
        $nis=trim((string)$request->query('nis','')); $mode=$request->query('mode','kelas');
        $classId=$request->integer('classroom_id'); $subjectId=$request->integer('subject_id'); $yearId=$request->integer('academic_year_id'); $semesterId=$request->integer('semester_id');
        $students=collect(); $existing=collect(); $weights=null; $one=null; $selectedSchoolLevel=$user->school_level ?? 'SD';
        if($mode==='nis' && $nis!==''){
            $one=User::where('role','siswa')->where('nis',$nis)->first();
            if($one) $classId=(int)$one->classroom_id;
        }
        if($classId && $subjectId && $yearId && $semesterId){
            $selectedSchoolLevel=Classroom::whereKey($classId)->value('school_level') ?? $selectedSchoolLevel;
            if($user->role==='guru'){ $allowed=\App\Models\TeacherAssignment::where('teacher_id',$user->id)->where('classroom_id',$classId)->where('subject_id',$subjectId)->exists(); abort_unless($allowed,403,'Anda tidak ditugaskan mengajar mata pelajaran ini di kelas tersebut.'); }
            $students=$mode==='nis' ? ($one ? collect([$one]) : collect()) : User::where('role','siswa')->where('classroom_id',$classId)->orderBy('name')->get();
            $existing=Grade::where('classroom_id',$classId)->where('subject_id',$subjectId)->where('academic_year_id',$yearId)->where('semester_id',$semesterId)->get()->keyBy('student_id');
            $weights=\Illuminate\Support\Facades\DB::table('grade_weights')->where('academic_year_id',$yearId)->where('semester_id',$semesterId)->where('school_level',$selectedSchoolLevel)->first();
            if(!$weights){\Illuminate\Support\Facades\DB::table('grade_weights')->insert(['academic_year_id'=>$yearId,'semester_id'=>$semesterId,'school_level'=>$selectedSchoolLevel,'tugas_weight'=>30,'uts_weight'=>30,'uas_weight'=>40,'created_at'=>now(),'updated_at'=>now()]);$weights=\Illuminate\Support\Facades\DB::table('grade_weights')->where('academic_year_id',$yearId)->where('semester_id',$semesterId)->where('school_level',$selectedSchoolLevel)->first();}
        }
        $tasks=collect(); $taskScores=collect();
        if($classId && $subjectId && $yearId && $semesterId && $students->isNotEmpty()) {
            $taskContext=['classroom_id'=>$classId,'subject_id'=>$subjectId,'academic_year_id'=>$yearId,'semester_id'=>$semesterId];
            $tasks=TaskComponent::where($taskContext)->orderBy('position')->get();
            if($tasks->isEmpty()) {
                TaskComponent::create($taskContext+['name'=>'Tugas 1','position'=>1,'created_by'=>$user->id]);
                $tasks=TaskComponent::where($taskContext)->orderBy('position')->get();
            }
            $taskScores=TaskScore::whereIn('task_component_id',$tasks->pluck('id'))->whereIn('student_id',$students->pluck('id'))->get()->groupBy('student_id');
        }
        return view('grades.bulk',compact('classrooms','subjects','years','semesters','students','existing','weights','classId','subjectId','yearId','semesterId','nis','mode','tasks','taskScores'));
    }
    public function autosave(Request $request) {
        $d=$request->validate([
            'student_id'=>'required|exists:users,id','classroom_id'=>'required|exists:classrooms,id',
            'subject_id'=>'required|exists:subjects,id','academic_year_id'=>'required|exists:academic_years,id',
            'semester_id'=>'required|exists:semesters,id','uts_score'=>'nullable|numeric|between:0,100',
            'uas_score'=>'nullable|numeric|between:0,100','task_scores'=>'nullable|array',
            'task_scores.*'=>'nullable|numeric|between:0,100'
        ]);
        $student=User::where('role','siswa')->findOrFail($d['student_id']);
        abort_unless((int)$student->classroom_id===(int)$d['classroom_id'],422,'Siswa bukan bagian dari kelas ini.');
        $this->assertCanInput((int)$d['classroom_id'],(int)$d['subject_id']);
        $context=['classroom_id'=>$d['classroom_id'],'subject_id'=>$d['subject_id'],'academic_year_id'=>$d['academic_year_id'],'semester_id'=>$d['semester_id']];
        $row=Grade::firstOrNew(['student_id'=>$student->id,'subject_id'=>$d['subject_id'],'academic_year_id'=>$d['academic_year_id'],'semester_id'=>$d['semester_id']]);
        if($row->exists && !in_array($row->status,['draft','revision','published'])) abort(422,'Nilai tidak dapat diubah karena sudah masuk proses validasi.');
        $tasks=TaskComponent::where($context)->get()->keyBy('id');
        $scores=$d['task_scores']??[];
        foreach($scores as $taskId=>$score) {
            abort_unless($tasks->has((int)$taskId),403,'Komponen tugas tidak sesuai kelas/mapel/periode.');
            TaskScore::updateOrCreate(['task_component_id'=>(int)$taskId,'student_id'=>$student->id],['score'=>$score===''?null:$score]);
        }
        $taskValues=TaskScore::whereIn('task_component_id',$tasks->keys())->where('student_id',$student->id)->whereNotNull('score')->pluck('score')->map(fn($v)=>(float)$v);
        $tugasScore=$taskValues->isNotEmpty()?round($taskValues->avg(),2):null;
        $weight=\Illuminate\Support\Facades\DB::table('grade_weights')->where('academic_year_id',$d['academic_year_id'])->where('semester_id',$d['semester_id'])->where('school_level',Classroom::whereKey($d['classroom_id'])->value('school_level'))->first();
        abort_unless($weight,422,'Bobot penilaian belum diatur.');
        $uts=$d['uts_score']??null; $uas=$d['uas_score']??null;
        $final=($tugasScore!==null && $uts!==null && $uas!==null)
            ? round(($tugasScore*$weight->tugas_weight+$uts*$weight->uts_weight+$uas*$weight->uas_weight)/100,2) : null;
        $row->fill(['teacher_id'=>auth()->id(),'classroom_id'=>$d['classroom_id'],'tugas_score'=>$tugasScore,'uts_score'=>$uts,'uas_score'=>$uas,
            'tugas_weight'=>$weight->tugas_weight,'uts_weight'=>$weight->uts_weight,'uas_weight'=>$weight->uas_weight,
            'knowledge_score'=>$uts??0,'skill_score'=>$uas??0,'final_score'=>$final??0,
            'status'=>$final!==null?'published':'draft','published_at'=>$final!==null?now():$row->published_at]);
        $row->save();
        return response()->json(['ok'=>true,'final'=>$final,'tugas'=>$tugasScore,'message'=>$final===null?'Tersimpan sementara':'Tersimpan · Nilai akhir '.number_format($final,2)]);
    }

    private function assertCanInput(int $classroomId, int $subjectId): void {
        $user=auth()->user();
        if($user->role==='guru') {
            abort_unless(\App\Models\TeacherAssignment::where('teacher_id',$user->id)->where('classroom_id',$classroomId)->where('subject_id',$subjectId)->exists(),403,'Anda tidak ditugaskan pada mapel dan kelas ini.');
        } else {
            abort_unless(in_array($user->role,['admin','super_admin'],true),403);
        }
    }

    public function addTask(Request $request) {
        $d=$request->validate(['classroom_id'=>'required|exists:classrooms,id','subject_id'=>'required|exists:subjects,id','academic_year_id'=>'required|exists:academic_years,id','semester_id'=>'required|exists:semesters,id','name'=>'required|string|max:80']);
        $this->assertCanInput((int)$d['classroom_id'],(int)$d['subject_id']);
        abort_if(Grade::where(['classroom_id'=>$d['classroom_id'],'subject_id'=>$d['subject_id'],'academic_year_id'=>$d['academic_year_id'],'semester_id'=>$d['semester_id']])->whereIn('status',['submitted','approved'])->exists(),422,'Kolom tugas tidak dapat diubah setelah nilai dikirim untuk validasi.');
        $ctx=collect($d)->only(['classroom_id','subject_id','academic_year_id','semester_id'])->all();
        $position=(int)TaskComponent::where($ctx)->max('position')+1;
        TaskComponent::create($ctx+['name'=>$d['name'],'position'=>$position,'created_by'=>auth()->id()]);
        return back()->with('success','Kolom tugas berhasil ditambahkan.');
    }

    public function removeTask(TaskComponent $taskComponent) {
        $this->assertCanInput((int)$taskComponent->classroom_id,(int)$taskComponent->subject_id);
        abort_if(Grade::where(['classroom_id'=>$taskComponent->classroom_id,'subject_id'=>$taskComponent->subject_id,'academic_year_id'=>$taskComponent->academic_year_id,'semester_id'=>$taskComponent->semester_id])->whereIn('status',['submitted','approved'])->exists(),422,'Kolom tugas tidak dapat diubah setelah nilai dikirim untuk validasi.');
        $count=TaskComponent::where(['classroom_id'=>$taskComponent->classroom_id,'subject_id'=>$taskComponent->subject_id,'academic_year_id'=>$taskComponent->academic_year_id,'semester_id'=>$taskComponent->semester_id])->count();
        abort_if($count<=1,422,'Minimal harus tersedia satu kolom tugas.');
        $context=['classroom_id'=>$taskComponent->classroom_id,'subject_id'=>$taskComponent->subject_id,'academic_year_id'=>$taskComponent->academic_year_id,'semester_id'=>$taskComponent->semester_id];
        $taskComponent->delete();
        $weight=\Illuminate\Support\Facades\DB::table('grade_weights')->where('academic_year_id',$taskComponent->academic_year_id)->where('semester_id',$taskComponent->semester_id)->where('school_level',Classroom::whereKey($taskComponent->classroom_id)->value('school_level'))->first();
        foreach(Grade::where($context)->get() as $grade) {
            $avg=TaskScore::whereIn('task_component_id',TaskComponent::where($context)->pluck('id'))->where('student_id',$grade->student_id)->whereNotNull('score')->avg('score');
            $final=($avg!==null && $grade->uts_score!==null && $grade->uas_score!==null && $weight)
                ? round(($avg*$weight->tugas_weight+$grade->uts_score*$weight->uts_weight+$grade->uas_score*$weight->uas_weight)/100,2) : null;
            $grade->update(['tugas_score'=>$avg,'final_score'=>$final??0]);
        }
        return back()->with('success','Kolom tugas berhasil dihapus dan rata-rata tugas dihitung ulang.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'student_id'=>['required','exists:users,id'],
            'classroom_id'=>['required','exists:classrooms,id'],
            'subject_id'=>['required','exists:subjects,id'],
            'academic_year_id'=>['required','exists:academic_years,id'],
            'semester_id'=>['required','exists:semesters,id'],
            'knowledge_score'=>['required','numeric','between:0,100'],
            'skill_score'=>['required','numeric','between:0,100'],
            'description'=>['nullable','string','max:1000'],
        ]);
        $student=User::where('role','siswa')->findOrFail($data['student_id']);
        abort_unless((int)$student->classroom_id===(int)$data['classroom_id'],422,'Siswa tidak berada di kelas yang dipilih.');
        if(auth()->user()->role==='guru'){
            $allowed=\App\Models\TeacherAssignment::where('teacher_id',auth()->id())->where('classroom_id',$data['classroom_id'])->where('subject_id',$data['subject_id'])->exists();
            abort_unless($allowed,403,'Anda tidak ditugaskan pada mapel dan kelas ini.');
        }
        return $data;
    }
}
