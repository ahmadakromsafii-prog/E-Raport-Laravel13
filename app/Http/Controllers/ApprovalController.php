<?php
namespace App\Http\Controllers;
use App\Models\Grade; use App\Models\AcademicYear; use App\Models\Semester; use App\Models\Classroom; use App\Models\ReportCard; use Illuminate\Http\Request; use Illuminate\Support\Facades\DB;
class ApprovalController extends Controller {
 public function index(Request $request){
  $years=AcademicYear::orderByDesc('id')->get(); $semesters=Semester::with('academicYear')->orderBy('id')->get(); $classes=Classroom::orderBy('name')->get();
  $query=Grade::with(['student','teacher','classroom','subject','academicYear','semester'])->whereIn('status',['submitted','approved','revision']);
  foreach(['academic_year_id','semester_id','classroom_id'] as $field) if($request->filled($field)) $query->where($field,$request->integer($field));
  if($request->filled('status') && in_array($request->status,['submitted','approved','revision'],true)) $query->where('status',$request->status);
  $grades=$query->orderByRaw("CASE WHEN status='submitted' THEN 0 WHEN status='revision' THEN 1 ELSE 2 END")->latest('submitted_at')->paginate(30)->withQueryString();
  $selectedYear=$request->query('academic_year_id'); $selectedSemester=$request->query('semester_id');
  return view('admin.approvals',compact('grades','years','semesters','classes','selectedYear','selectedSemester'));
 }
 public function approve(Grade $grade){abort_unless($grade->status==='submitted',422,'Nilai ini bukan dalam antrean validasi.');$grade->update(['status'=>'approved','approved_at'=>now(),'admin_note'=>null]);return back()->with('success','Nilai disetujui dan siap dipublikasikan.');}
 public function revision(Request $request,Grade $grade){$d=$request->validate(['admin_note'=>['required','string','max:1000']]);abort_unless($grade->status==='submitted',422);$grade->update(['status'=>'revision','admin_note'=>$d['admin_note']]);return back()->with('success','Nilai dikembalikan kepada guru untuk diperbaiki.');}
 public function approveClass(Request $request){$d=$request->validate(['classroom_id'=>'required|exists:classrooms,id','academic_year_id'=>'required|exists:academic_years,id','semester_id'=>'required|exists:semesters,id']);$count=Grade::where($d)->where('status','submitted')->update(['status'=>'approved','approved_at'=>now(),'admin_note'=>null,'updated_at'=>now()]);return back()->with('success',"Validasi kelas selesai: $count nilai disetujui.");}
 public function publishClass(Request $request){$d=$request->validate(['classroom_id'=>'required|exists:classrooms,id','academic_year_id'=>'required|exists:academic_years,id','semester_id'=>'required|exists:semesters,id']);$count=Grade::where($d)->where('status','approved')->update(['status'=>'published','published_at'=>now(),'updated_at'=>now()]);return back()->with('success',"Publikasi kelas selesai: $count nilai dipublikasikan.");}
 public function reportCards(Request $request){
  $query=ReportCard::with(['student','classroom','homeroomTeacher','academicYear','semester']);
  foreach(['academic_year_id','semester_id','classroom_id'] as $field) if($request->filled($field)) $query->where($field,$request->integer($field));
  if($request->filled('status') && in_array($request->status,['draft','submitted','approved','revision'],true)) $query->where('status',$request->status);
  // Tanpa filter status, tampilkan semua status termasuk draft agar admin dapat melacak pengolahan yang belum terkirim.
  $cards=$query->orderByRaw("CASE WHEN status='submitted' THEN 0 WHEN status='revision' THEN 1 ELSE 2 END")->orderByDesc('submitted_at')->orderByDesc('updated_at')->paginate(30)->withQueryString();
  $pendingCount=ReportCard::where('status','submitted')->count(); $years=AcademicYear::orderByDesc('id')->get(); $semesters=Semester::with('academicYear')->get(); $classes=Classroom::orderBy('name')->get();
  return view('admin.report_card_approvals',compact('cards','years','semesters','classes','pendingCount'));
 }
 public function approveReportCard(ReportCard $reportCard){abort_unless($reportCard->status==='submitted',422);$reportCard->update(['status'=>'approved','approved_at'=>now(),'admin_note'=>null]);return back()->with('success','Pengolahan rapor disetujui dan dapat dicetak.');}
 public function reviseReportCard(Request $request,ReportCard $reportCard){$d=$request->validate(['admin_note'=>'required|string|max:1000']);abort_unless($reportCard->status==='submitted',422);$reportCard->update(['status'=>'revision','admin_note'=>$d['admin_note']]);return back()->with('success','Pengolahan rapor dikembalikan kepada wali kelas.');}
 public function publish(Request $request){$d=$request->validate(['academic_year_id'=>'required|exists:academic_years,id','semester_id'=>'required|exists:semesters,id']);$count=Grade::where($d)->where('status','approved')->update(['status'=>'published','published_at'=>now()]);return back()->with('success',"$count nilai berhasil dipublikasikan kepada siswa.");}
}
