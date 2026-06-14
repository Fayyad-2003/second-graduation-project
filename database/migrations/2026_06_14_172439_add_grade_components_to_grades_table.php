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
        Schema::table('grades', function (Blueprint $table) {
            // For all courses
            $table->decimal('attendance', 5, 2)->nullable();
            $table->decimal('midterm', 5, 2)->nullable();
            $table->decimal('final_exam', 5, 2)->nullable();
            
            // For courses with practical
            $table->decimal('practical_attendance', 5, 2)->nullable();
            $table->decimal('practical_exam', 5, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn([
                'attendance',
                'midterm',
                'final_exam',
                'practical_attendance',
                'practical_exam',
            ]);
        });
    }
};
