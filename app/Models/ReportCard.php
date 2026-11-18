<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Support\SchoolContext;
class ReportCard extends Model {
 protected $fillable=['student_id','classroom_id','academic_year_id','semester_id','skill_score','attitude_score','motivation','suggestion','homeroom_teacher_id','status','admin_note','submitted_at','approved_at'];
 protected $casts=['submitted_at'=>'datetime','approved_at'=>'datetime'];
 protected static function booted(): void { static::addGlobalScope('school_level',function(Builder $q){$level=SchoolContext::level();if($level && !SchoolContext::isSuperAdmin())$q->whereHas('classroom',fn($c)=>$c->where('school_level',$level));}); }
 public function student(){return $this->belongsTo(User::class,'student_id');}
 public function classroom(){return $this->belongsTo(Classroom::class);}
 public function academicYear(){return $this->belongsTo(AcademicYear::class);}
 public function semester(){return $this->belongsTo(Semester::class);}
 public function homeroomTeacher(){return $this->belongsTo(User::class,'homeroom_teacher_id');}
}
