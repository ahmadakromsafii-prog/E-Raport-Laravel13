<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use App\Support\SchoolContext;
class User extends Authenticatable {
 use HasFactory, Notifiable;
 protected $fillable=['name','username','email','password','role','school_level','classroom_id','nip','nis','phone','residence','previous_school','parent_name','avatar_path'];
 protected $hidden=['password','remember_token'];
 protected function casts(): array { return ['password'=>'hashed']; }
 protected static function booted(): void {
  static::addGlobalScope('school_level', function(Builder $q) {
   $level=SchoolContext::level();
   if ($level && !SchoolContext::isSuperAdmin()) {
    $q->where(function($w) use($level) {
     $w->where(function($staff) use($level) {
      $staff->whereIn('role',['admin','guru'])->where('school_level',$level);
     })->orWhere(function($students) use($level) {
      $students->where('role','siswa')->whereHas('classroom',fn($c)=>$c->where('school_level',$level));
     });
    });
   }
  });
 }
 public function isSuperAdmin(): bool { return $this->role==='super_admin'; }
 public function classroom(){return $this->belongsTo(Classroom::class);}
 public function grades(){return $this->hasMany(Grade::class,'teacher_id');}
 public function studentGrades(){return $this->hasMany(Grade::class,'student_id');}
 public function teachingAssignments(){return $this->hasMany(TeacherAssignment::class,'teacher_id');}
 public function reportCards(){return $this->hasMany(ReportCard::class,'student_id');}
}
