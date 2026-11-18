<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Support\SchoolContext;
class Classroom extends Model {
 protected $fillable=['name','level','school_level','homeroom_teacher_id'];
 protected static function booted(): void {
  static::addGlobalScope('school_level',function(Builder $q) {
   $level=SchoolContext::level();
   if($level && !SchoolContext::isSuperAdmin()) $q->where('school_level',$level);
  });
 }
 public function students(){return $this->hasMany(User::class,'classroom_id')->where('role','siswa');}
 public function homeroomTeacher(){return $this->belongsTo(User::class,'homeroom_teacher_id');}
}
