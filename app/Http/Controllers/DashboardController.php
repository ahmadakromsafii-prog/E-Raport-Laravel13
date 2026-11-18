<?php
namespace App\Http\Controllers;
use App\Models\Grade; use App\Models\User; use App\Models\Classroom; use App\Models\Subject; use App\Models\ReportCard;
class DashboardController extends Controller
{
 public function index(){
  $user=auth()->user(); $stats=[]; $pendingReports=collect();
  if(in_array($user->role,['admin','super_admin'],true)){
   $pendingReports=ReportCard::with(['student','classroom','homeroomTeacher','academicYear','semester'])->where('status','submitted')->latest('submitted_at')->limit(8)->get();
   $stats=['guru'=>User::where('role','guru')->count(),'siswa'=>User::where('role','siswa')->count(),'kelas'=>Classroom::count(),'mapel'=>Subject::count(),'rapor_menunggu_validasi'=>ReportCard::where('status','submitted')->count()];
   $recent=Grade::with(['student','subject','teacher'])->where('status','published')->latest('published_at')->limit(8)->get();
  } elseif($user->role==='guru'){
   $stats=['nilai_diberikan'=>Grade::where('teacher_id',$user->id)->where('status','published')->count(),'kelas_wali'=>Classroom::where('homeroom_teacher_id',$user->id)->count(),'rapor_draft'=>ReportCard::where('homeroom_teacher_id',$user->id)->whereIn('status',['draft','revision'])->count()];
   $recent=Grade::with(['student','subject'])->where('teacher_id',$user->id)->where('status','published')->latest('published_at')->limit(8)->get();
  } else {
   $stats=['jumlah_nilai'=>Grade::where('student_id',$user->id)->where('status','published')->count()];
   $recent=Grade::with(['subject','teacher','academicYear','semester'])->where('student_id',$user->id)->where('status','published')->latest('published_at')->limit(10)->get();
  }
  return view('dashboard',compact('stats','recent','pendingReports'));
 }
}
