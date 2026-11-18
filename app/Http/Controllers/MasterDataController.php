<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Semester;

class MasterDataController extends Controller
{
    private array $types = ['users','classrooms','subjects','academic-years','semesters'];

    public function users(string $type)
    {
        abort_unless(in_array($type, ['siswa', 'guru'], true), 404);
        $role = $type;
        $items = User::with('classroom')->where('role', $role)->orderBy('name')->paginate(15)->withQueryString();
        $editItem = request()->filled('edit') ? User::where('role', $role)->findOrFail(request('edit')) : null;
        $classrooms = Classroom::orderBy('name')->get();
        return view('admin.master', compact('items', 'editItem', 'classrooms', 'role') + ['type' => 'users']);
    }

    public function storeUser(Request $request, string $type)
    {
        abort_unless(in_array($type, ['siswa', 'guru'], true), 404);
        $rules = [
            'name' => 'required|string|max:150',
            'username' => 'required|string|max:80|unique:users,username',
            'password' => 'required|string|min:8',
            'school_level' => 'nullable|in:SD,SMP,SMA',
            'classroom_id' => $type === 'siswa' ? 'required|exists:classrooms,id' : 'nullable|exists:classrooms,id',
            'nip' => 'nullable|string|max:50', 'nis' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:30', 'residence' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:150', 'parent_name' => 'nullable|string|max:150',
        ];
        $data = $request->validate($rules);
        $data['role'] = $type;
        $data['school_level'] = auth()->user()->role === 'super_admin' ? $request->input('school_level','SD') : auth()->user()->school_level;
        $data['password'] = Hash::make($data['password']);
        if ($type === 'guru') { $data['classroom_id'] = null; $data['nis'] = null; }
        else { $data['nip'] = null; $data['school_level'] = Classroom::findOrFail($data['classroom_id'])->school_level; }
        User::create($data);
        return redirect()->route('admin.master.index', $type)->with('success', 'Data ' . $type . ' berhasil ditambahkan.');
    }

    public function updateUser(Request $request, string $type, int $id)
    {
        abort_unless(in_array($type, ['siswa', 'guru'], true), 404);
        $item = User::where('role', $type)->findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'username' => ['required','string','max:80',\Illuminate\Validation\Rule::unique('users','username')->ignore($id)],
            'password' => 'nullable|string|min:8',
            'school_level' => 'nullable|in:SD,SMP,SMA',
            'classroom_id' => $type === 'siswa' ? 'required|exists:classrooms,id' : 'nullable|exists:classrooms,id',
            'nip' => 'nullable|string|max:50', 'nis' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:30', 'residence' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:150', 'parent_name' => 'nullable|string|max:150',
        ]);
        if (empty($data['password'])) unset($data['password']); else $data['password'] = Hash::make($data['password']);
        $data['role'] = $type;
        $data['school_level'] = auth()->user()->role === 'super_admin' ? $request->input('school_level', $item->school_level) : auth()->user()->school_level;
        if ($type === 'guru') { $data['classroom_id'] = null; $data['nis'] = null; }
        else { $data['nip'] = null; $data['school_level'] = Classroom::findOrFail($data['classroom_id'])->school_level; }
        $item->update($data);
        return redirect()->route('admin.master.index', $type)->with('success', 'Data ' . $type . ' berhasil diperbarui.');
    }

    public function destroyUser(string $type, int $id)
    {
        abort_unless(in_array($type, ['siswa', 'guru'], true), 404);
        User::where('role', $type)->findOrFail($id)->delete();
        return redirect()->route('admin.master.index', $type)->with('success', 'Data ' . $type . ' berhasil dihapus.');
    }

    public function index(string $type)
    {
        abort_unless(in_array($type,$this->types,true),404);
        $map = [
            'users'=>User::with('classroom')->orderBy('name')->paginate(20)->withQueryString(),
            'classrooms'=>Classroom::with('homeroomTeacher')->orderBy('name')->paginate(20)->withQueryString(),
            'subjects'=>Subject::orderBy('name')->paginate(20)->withQueryString(),
            'academic-years'=>AcademicYear::orderByDesc('id')->paginate(20)->withQueryString(),
            'semesters'=>Semester::with('academicYear')->orderBy('id')->paginate(20)->withQueryString(),
        ];
        return view('admin.master', ['type'=>$type,'items'=>$map[$type],'editItem'=>request()->filled('edit') ? (['users'=>User::class,'classrooms'=>Classroom::class,'subjects'=>Subject::class,'academic-years'=>AcademicYear::class,'semesters'=>Semester::class][$type]::find(request('edit'))) : null]);
    }

    public function schoolAdmins()
    {
        abort_unless(auth()->user()->role==='super_admin',403);
        $items=User::where('role','admin')->orderBy('school_level')->orderBy('name')->get();
        $editItem=request()->filled('edit') ? User::where('role','admin')->findOrFail(request('edit')) : null;
        return view('admin.school_admins',compact('items','editItem'));
    }

    public function storeSchoolAdmin(Request $request)
    {
        abort_unless(auth()->user()->role==='super_admin',403);
        $d=$request->validate(['name'=>'required|string|max:150','username'=>'required|string|max:80|unique:users,username','password'=>'required|string|min:8','school_level'=>'required|in:SD,SMP,SMA']);
        $d['role']='admin'; $d['password']=Hash::make($d['password']); User::create($d);
        return back()->with('success','Akun kepala sekolah berhasil dibuat.');
    }

    public function updateSchoolAdmin(Request $request,int $id)
    {
        abort_unless(auth()->user()->role==='super_admin',403);
        $user=User::where('role','admin')->findOrFail($id);
        $d=$request->validate(['name'=>'required|string|max:150','username'=>['required','string','max:80',\Illuminate\Validation\Rule::unique('users','username')->ignore($id)],'password'=>'nullable|string|min:8','school_level'=>'required|in:SD,SMP,SMA']);
        if(empty($d['password'])) unset($d['password']); else $d['password']=Hash::make($d['password']);
        $user->update($d); return back()->with('success','Akun kepala sekolah berhasil diperbarui.');
    }

    public function destroySchoolAdmin(int $id)
    {
        abort_unless(auth()->user()->role==='super_admin',403);
        User::where('role','admin')->findOrFail($id)->delete();
        return back()->with('success','Akun kepala sekolah berhasil dihapus.');
    }

    public function biodata(){ $teachers=User::where('role','guru')->with('classroom')->orderBy('name')->get(); $teachers->each(function($teacher){ $teacher->homeroom_classroom=Classroom::where('homeroom_teacher_id',$teacher->id)->first(); }); $students=User::where('role','siswa')->with('classroom')->orderBy('name')->get(); return view('admin.biodata',compact('teachers','students')); }

    public function store(Request $request, string $type)
    {
        abort_unless(in_array($type,$this->types,true),404);
        if ($type === 'users') {
            $d=$request->validate([
                'name'=>'required|string|max:150','username'=>'required|string|max:80|unique:users,username',
                'password'=>'required|string|min:8','role'=>'required|in:admin,guru,siswa,super_admin',
                'school_level'=>'nullable|in:SD,SMP,SMA','classroom_id'=>'nullable|exists:classrooms,id',
                'nip'=>'nullable|string|max:50','nis'=>'nullable|string|max:50'
            ]);
            if(auth()->user()->role!=='super_admin') $d['school_level']=auth()->user()->school_level;
            if(($d['role']??'')==='siswa') $d['school_level']=Classroom::findOrFail($d['classroom_id'])->school_level;
            if(($d['role']??'')==='super_admin') $d['school_level']=null;
            $d['password']=Hash::make($d['password']); User::create($d);
        } elseif ($type === 'classrooms') {
            $d=$request->validate([
                'name'=>'required|string|max:80','level'=>'nullable|string|max:30',
                'school_level'=>'nullable|in:SD,SMP,SMA',
                'homeroom_teacher_id'=>['nullable',\Illuminate\Validation\Rule::exists('users','id')->where('role','guru')]
            ]);
            if(auth()->user()->role!=='super_admin') $d['school_level']=auth()->user()->school_level;
            $d['school_level']=$d['school_level']??'SD';
            if(!empty($d['homeroom_teacher_id'])) { $teacher=User::where('role','guru')->findOrFail($d['homeroom_teacher_id']); abort_unless($teacher->school_level===$d['school_level'],422,'Wali kelas harus berasal dari jenjang yang sama.'); }
            Classroom::create($d);
        } elseif ($type === 'subjects') {
            Subject::create($request->validate(['name'=>'required|string|max:120','code'=>'nullable|string|max:30','description'=>'nullable|string']));
        } elseif ($type === 'academic-years') {
            AcademicYear::create($request->validate(['name'=>'required|string|max:30','is_active'=>'nullable|boolean']));
        } else {
            Semester::create($request->validate(['name'=>'required|in:Ganjil,Genap','academic_year_id'=>'required|exists:academic_years,id','is_active'=>'nullable|boolean']));
        }
        return back()->with('success','Data berhasil ditambahkan.');
    }

    public function update(Request $request, string $type, int $id)
    {
        abort_unless(in_array($type,$this->types,true),404);
        $models=['users'=>User::class,'classrooms'=>Classroom::class,'subjects'=>Subject::class,'academic-years'=>AcademicYear::class,'semesters'=>Semester::class];
        $item=$models[$type]::findOrFail($id);
        if($type==='users') {
            $data=$request->validate([
                'name'=>'required|string|max:150',
                'username'=>['required','string','max:80',\Illuminate\Validation\Rule::unique('users','username')->ignore($id)],
                'password'=>'nullable|string|min:8','role'=>'required|in:admin,guru,siswa,super_admin',
                'school_level'=>'nullable|in:SD,SMP,SMA','classroom_id'=>'nullable|exists:classrooms,id',
                'nip'=>'nullable|string|max:50','nis'=>'nullable|string|max:50','phone'=>'nullable|string|max:30',
                'residence'=>'nullable|string|max:255','previous_school'=>'nullable|string|max:150','parent_name'=>'nullable|string|max:150'
            ]);
            if(auth()->user()->role!=='super_admin') $data['school_level']=auth()->user()->school_level;
            if(($data['role']??'')==='siswa') $data['school_level']=Classroom::findOrFail($data['classroom_id'])->school_level;
            if(($data['role']??'')==='super_admin') $data['school_level']=null;
            if(empty($data['password'])) unset($data['password']); else $data['password']=Hash::make($data['password']);
        } elseif($type==='classrooms') {
            $data=$request->validate([
                'name'=>'required|string|max:80','level'=>'nullable|string|max:30','school_level'=>'nullable|in:SD,SMP,SMA',
                'homeroom_teacher_id'=>['nullable',\Illuminate\Validation\Rule::exists('users','id')->where('role','guru')]
            ]);
            if(auth()->user()->role!=='super_admin') $data['school_level']=auth()->user()->school_level;
            $data['school_level']=$data['school_level']??$item->school_level;
            if(!empty($data['homeroom_teacher_id'])) { $teacher=User::where('role','guru')->findOrFail($data['homeroom_teacher_id']); abort_unless($teacher->school_level===$data['school_level'],422,'Wali kelas harus berasal dari jenjang yang sama.'); }
        } elseif($type==='subjects') {
            $data=$request->validate(['name'=>'required|string|max:120','code'=>['nullable','string','max:30',\Illuminate\Validation\Rule::unique('subjects','code')->ignore($id)],'description'=>'nullable|string']);
        } elseif($type==='academic-years') {
            $data=$request->validate(['name'=>['required','string','max:30',\Illuminate\Validation\Rule::unique('academic_years','name')->ignore($id)],'is_active'=>'nullable|boolean']); $data['is_active']=$request->boolean('is_active');
        } else {
            $data=$request->validate(['name'=>'required|in:Ganjil,Genap','academic_year_id'=>'required|exists:academic_years,id','is_active'=>'nullable|boolean']); $data['is_active']=$request->boolean('is_active');
        }
        $item->update($data);
        return redirect('/'.['classrooms'=>'kelas','subjects'=>'mata-pelajaran','academic-years'=>'tahun-ajaran','semesters'=>'semester'][$type])->with('success','Data berhasil diperbarui.');
    }

    public function destroy(string $type, int $id)
    {
        abort_unless(in_array($type,$this->types,true),404);
        $models=['users'=>User::class,'classrooms'=>Classroom::class,'subjects'=>Subject::class,'academic-years'=>AcademicYear::class,'semesters'=>Semester::class];
        $model = $models[$type];
        $model::findOrFail($id)->delete();
        return back()->with('success','Data berhasil dihapus.');
    }
}
