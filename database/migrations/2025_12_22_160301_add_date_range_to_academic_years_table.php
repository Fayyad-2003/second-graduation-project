<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('is_active');
            $table->date('completion_date')->nullable()->after('start_date');
            $table->date('study_plan_start_date')->nullable()->after('completion_date');
            $table->date('study_plan_end_date')->nullable()->after('study_plan_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropColumn([
                'start_date',
                'completion_date',
                'study_plan_start_date',
                'study_plan_end_date',
            ]);
        });
    }
};
