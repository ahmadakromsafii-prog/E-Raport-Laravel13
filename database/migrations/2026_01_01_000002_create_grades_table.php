<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('grades', function(Blueprint $t){
   $t->id();$t->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();$t->foreignId('student_id')->constrained('users')->cascadeOnDelete();
   $t->foreignId('classroom_id')->constrained()->cascadeOnDelete();$t->foreignId('subject_id')->constrained()->cascadeOnDelete();
   $t->foreignId('academic_year_id')->constrained()->cascadeOnDelete();$t->foreignId('semester_id')->constrained()->cascadeOnDelete();
   $t->decimal('knowledge_score',5,2);$t->decimal('skill_score',5,2);$t->decimal('final_score',5,2);
   $t->text('description')->nullable();$t->enum('status',['draft','submitted','revision','approved','published'])->default('draft');
   $t->text('teacher_note')->nullable();$t->text('admin_note')->nullable();
   $t->timestamp('submitted_at')->nullable();$t->timestamp('approved_at')->nullable();$t->timestamp('published_at')->nullable();
   $t->timestamps();$t->unique(['student_id','subject_id','academic_year_id','semester_id'],'grade_student_subject_period_unique');
  });
 }
 public function down(): void {Schema::dropIfExists('grades');}
};