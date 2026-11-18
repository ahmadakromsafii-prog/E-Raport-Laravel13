<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('teacher_assignments', function(Blueprint $t){
   $t->id(); $t->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
   $t->foreignId('classroom_id')->constrained()->cascadeOnDelete(); $t->foreignId('subject_id')->constrained()->cascadeOnDelete();
   $t->timestamps(); $t->unique(['teacher_id','classroom_id','subject_id'],'teacher_class_subject_unique');
  });
  Schema::create('report_cards', function(Blueprint $t){
   $t->id(); $t->foreignId('student_id')->constrained('users')->cascadeOnDelete();
   $t->foreignId('classroom_id')->constrained()->cascadeOnDelete(); $t->foreignId('academic_year_id')->constrained()->cascadeOnDelete(); $t->foreignId('semester_id')->constrained()->cascadeOnDelete();
   $t->decimal('skill_score',5,2)->nullable(); $t->decimal('attitude_score',5,2)->nullable(); $t->text('motivation')->nullable(); $t->text('suggestion')->nullable();
   $t->foreignId('homeroom_teacher_id')->constrained('users')->cascadeOnDelete();
   $t->enum('status',['draft','submitted','revision','approved'])->default('draft'); $t->text('admin_note')->nullable();
   $t->timestamp('submitted_at')->nullable(); $t->timestamp('approved_at')->nullable(); $t->timestamps();
   $t->unique(['student_id','academic_year_id','semester_id'],'report_card_student_period_unique');
  });
 }
 public function down(): void { Schema::dropIfExists('report_cards'); Schema::dropIfExists('teacher_assignments'); }
};
