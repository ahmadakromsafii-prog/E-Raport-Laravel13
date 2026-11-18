<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
 public function up(): void {
  Schema::create('task_components', function(Blueprint $t) {
   $t->id(); $t->foreignId('classroom_id')->constrained()->cascadeOnDelete();
   $t->foreignId('subject_id')->constrained()->cascadeOnDelete();
   $t->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
   $t->foreignId('semester_id')->constrained()->cascadeOnDelete();
   $t->string('name',80); $t->unsignedInteger('position')->default(1);
   $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
   $t->timestamps();
   $t->unique(['classroom_id','subject_id','academic_year_id','semester_id','position'],'task_component_position_unique');
  });
  Schema::create('task_scores', function(Blueprint $t) {
   $t->id(); $t->foreignId('task_component_id')->constrained('task_components')->cascadeOnDelete();
   $t->foreignId('student_id')->constrained('users')->cascadeOnDelete();
   $t->decimal('score',5,2)->nullable(); $t->timestamps();
   $t->unique(['task_component_id','student_id'],'task_score_unique');
  });
  // Migrasikan nilai Tugas lama ke kolom dinamis pertama agar data sebelumnya tidak hilang.
  $legacy=DB::table('grades')->whereNotNull('tugas_score')->get();
  foreach($legacy as $grade) {
   $component=DB::table('task_components')->where('classroom_id',$grade->classroom_id)->where('subject_id',$grade->subject_id)->where('academic_year_id',$grade->academic_year_id)->where('semester_id',$grade->semester_id)->first();
   if(!$component) {
    $id=DB::table('task_components')->insertGetId([
     'classroom_id'=>$grade->classroom_id,'subject_id'=>$grade->subject_id,'academic_year_id'=>$grade->academic_year_id,
     'semester_id'=>$grade->semester_id,'name'=>'Tugas 1','position'=>1,'created_by'=>$grade->teacher_id,
     'created_at'=>now(),'updated_at'=>now()
    ]);
   } else $id=$component->id;
   DB::table('task_scores')->updateOrInsert(['task_component_id'=>$id,'student_id'=>$grade->student_id],['score'=>$grade->tugas_score,'created_at'=>now(),'updated_at'=>now()]);
  }
 }
 public function down(): void { Schema::dropIfExists('task_scores'); Schema::dropIfExists('task_components'); }
};
