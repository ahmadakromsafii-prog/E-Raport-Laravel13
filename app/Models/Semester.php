<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Semester extends Model
{
    protected $fillable = ['name','academic_year_id','is_active'];
    protected $casts = ['is_active'=>'boolean'];
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
}