<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Support\SchoolContext;
class TeacherAssignment extends Model {
 protected $fillable=['teacher_id','classroom_id','subject_id'];
 protected static function booted(): void { static::addGlobalScope('school_level',function(Builder $q){$level=SchoolContext::level();if($level && !SchoolContext::isSuperAdmin())$q->whereHas('classroom',fn($c)=>$c->where('school_level',$level));}); }
 public function teacher(){return $this->belongsTo(User::class,'teacher_id');}
 public function classroom(){return $this->belongsTo(Classroom::class);}
 public function subject(){return $this->belongsTo(Subject::class);}
}
