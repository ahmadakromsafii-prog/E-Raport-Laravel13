<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
 public function up(): void { Schema::table('users', function(Blueprint $t){
  $t->string('phone',30)->nullable(); $t->text('residence')->nullable(); $t->string('previous_school',180)->nullable(); $t->string('parent_name',150)->nullable();
 });
  // Nilai yang sebelumnya sudah dikirim/disetujui dianggap siap tampil karena validasi nilai dihapus.
  if (Schema::hasTable('grades') && Schema::hasColumn('grades','published_at')) {
   DB::table('grades')->whereIn('status',['submitted','approved'])->update(['status'=>'published','published_at'=>now(),'updated_at'=>now()]);
  }
 }
 public function down(): void { Schema::table('users', function(Blueprint $t){ $t->dropColumn(['phone','residence','previous_school','parent_name']); }); }
};
