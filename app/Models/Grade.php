<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Support\SchoolContext;
class Grade extends Model {
 protected $fillable=['teacher_id','student_id','classroom_id','subject_id','academic_year_id','semester_id','knowledge_score','skill_score','tugas_score','uts_score','uas_score','tugas_weight','uts_weight','uas_weight','final_score','description','status','teacher_note','admin_note','submitted_at','approved_at','published_at'];
 protected $casts=['submitted_at'=>'datetime','approved_at'=>'datetime','published_at'=>'datetime'];
 protected static function booted(): void {
  static::addGlobalScope('school_level',function(Builder $q) {
   $level=SchoolContext::level();
   if($level && !SchoolContext::isSuperAdmin()) $q->whereHas('classroom',fn($c)=>$c->where('school_level',$level));
  });
 }
 public function teacher(){return $this->belongsTo(User::class,'teacher_id');}
 public function student(){return $this->belongsTo(User::class,'student_id');}
 public function classroom(){return $this->belongsTo(Classroom::class);}
 public function subject(){return $this->belongsTo(Subject::class);}
 public function academicYear(){return $this->belongsTo(AcademicYear::class);}
 public function semester(){return $this->belongsTo(Semester::class);}
}
