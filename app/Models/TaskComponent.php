<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Support\SchoolContext;
class TaskComponent extends Model {
 protected $fillable=['classroom_id','subject_id','academic_year_id','semester_id','name','position','created_by'];
 protected static function booted(): void { static::addGlobalScope('school_level',function(Builder $q){$level=SchoolContext::level();if($level && !SchoolContext::isSuperAdmin())$q->whereHas('classroom',fn($c)=>$c->where('school_level',$level));}); }
 public function scores(){return $this->hasMany(TaskScore::class);}
}
