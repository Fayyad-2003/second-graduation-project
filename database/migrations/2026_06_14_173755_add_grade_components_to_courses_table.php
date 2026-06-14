<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Grade components without practical
            $table->unsignedTinyInteger('attendance_weight')->default(10);
            $table->unsignedTinyInteger('midterm_weight')->default(30);
            $table->unsignedTinyInteger('final_exam_weight')->default(60);
            
            // Grade components with practical
            $table->unsignedTinyInteger('practical_attendance_weight')->default(5);
            $table->unsignedTinyInteger('practical_exam_weight')->default(20);
            // When has_practical is true, final_exam_weight defaults to 50 and midterm to 20
            // but we'll handle that logic in code
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'attendance_weight',
                'midterm_weight',
                'final_exam_weight',
                'practical_attendance_weight',
                'practical_exam_weight',
            ]);
        });
    }
};
