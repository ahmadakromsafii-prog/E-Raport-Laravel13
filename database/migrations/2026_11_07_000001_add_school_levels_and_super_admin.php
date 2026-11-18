<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
 public function up(): void {
  Schema::table('users', function(Blueprint $t) { $t->string('school_level',10)->nullable()->index(); });
  Schema::table('classrooms', function(Blueprint $t) { $t->string('school_level',10)->default('SD')->index(); });
  Schema::table('users', function(Blueprint $t) { $t->string('role')->default('siswa')->change(); });
  DB::table('users')->whereNull('school_level')->update(['school_level'=>'SD']);
 }
 public function down(): void {
  Schema::table('users', function(Blueprint $t) { $t->dropIndex(['school_level']); $t->dropColumn('school_level'); });
  Schema::table('classrooms', function(Blueprint $t) { $t->dropIndex(['school_level']); $t->dropColumn('school_level'); });
 }
};
