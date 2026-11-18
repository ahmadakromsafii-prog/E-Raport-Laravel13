<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('grades', function (Blueprint $t) {
   $t->decimal('tugas_score',5,2)->nullable()->after('semester_id');
   $t->decimal('uts_score',5,2)->nullable()->after('tugas_score');
   $t->decimal('uas_score',5,2)->nullable()->after('uts_score');
   $t->decimal('tugas_weight',5,2)->default(30)->after('uas_score');
   $t->decimal('uts_weight',5,2)->default(30)->after('tugas_weight');
   $t->decimal('uas_weight',5,2)->default(40)->after('uts_weight');
  });
  Schema::create('grade_weights', function (Blueprint $t) {
   $t->id(); $t->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
   $t->foreignId('semester_id')->constrained()->cascadeOnDelete();
   $t->decimal('tugas_weight',5,2)->default(30); $t->decimal('uts_weight',5,2)->default(30); $t->decimal('uas_weight',5,2)->default(40);
   $t->timestamps(); $t->unique(['academic_year_id','semester_id']);
  });
 }
 public function down(): void { Schema::dropIfExists('grade_weights'); Schema::table('grades', function(Blueprint $t){$t->dropColumn(['tugas_score','uts_score','uas_score','tugas_weight','uts_weight','uas_weight']);}); }
};
