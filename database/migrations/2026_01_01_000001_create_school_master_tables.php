<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('classrooms', function(Blueprint $t){$t->id();$t->string('name')->unique();$t->string('level')->nullable();$t->foreignId('homeroom_teacher_id')->nullable()->constrained('users')->nullOnDelete();$t->timestamps();});
  Schema::table('users', function(Blueprint $t){$t->foreign('classroom_id')->references('id')->on('classrooms')->nullOnDelete();});
  Schema::create('subjects', function(Blueprint $t){$t->id();$t->string('name');$t->string('code')->nullable()->unique();$t->text('description')->nullable();$t->timestamps();});
  Schema::create('academic_years', function(Blueprint $t){$t->id();$t->string('name')->unique();$t->boolean('is_active')->default(false);$t->timestamps();});
  Schema::create('semesters', function(Blueprint $t){$t->id();$t->string('name');$t->foreignId('academic_year_id')->constrained()->cascadeOnDelete();$t->boolean('is_active')->default(false);$t->timestamps();$t->unique(['name','academic_year_id']);});
 }
 public function down(): void {Schema::table('users', function(Blueprint $t){$t->dropForeign(['classroom_id']);});Schema::dropIfExists('semesters');Schema::dropIfExists('academic_years');Schema::dropIfExists('subjects');Schema::dropIfExists('classrooms');}
};