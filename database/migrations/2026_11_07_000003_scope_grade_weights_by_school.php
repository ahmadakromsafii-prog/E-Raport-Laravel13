<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('grade_weights', function (Blueprint $table) {
            // Foreign keys need indexes that remain available after the old
            // composite unique index is removed.
            $table->index('academic_year_id', 'grade_weights_academic_year_fk_idx');
            $table->index('semester_id', 'grade_weights_semester_fk_idx');
        });

        Schema::table('grade_weights', function (Blueprint $table) {
            $table->dropUnique('grade_weights_academic_year_id_semester_id_unique');
            $table->string('school_level', 10)->default('SD')->after('semester_id');
            $table->unique(
                ['academic_year_id', 'semester_id', 'school_level'],
                'grade_weights_school_period_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('grade_weights', function (Blueprint $table) {
            $table->dropUnique('grade_weights_school_period_unique');
            $table->dropColumn('school_level');
            $table->unique(['academic_year_id', 'semester_id']);
            // Keep the single-column indexes: the foreign keys may rely on them.
        });
    }
};
