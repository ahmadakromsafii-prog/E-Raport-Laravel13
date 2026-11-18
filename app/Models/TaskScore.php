<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TaskScore extends Model {
 protected $fillable=['task_component_id','student_id','score'];
}
