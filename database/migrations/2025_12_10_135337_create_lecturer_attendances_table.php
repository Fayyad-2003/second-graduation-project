<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturer_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->foreignId('course_schedule_id')->nullable()->constrained('course_schedules')->nullOnDelete();
            $table->foreignId('meeting_id')->nullable()->constrained('meetings')->nullOnDelete();
            $table->date('date');
            $table->time('entry_time')->nullable();
            $table->time('exit_time')->nullable();
            $table->enum('status', ['present', 'excused', 'sick', 'assignment', 'absent'])->default('present');
            $table->text('description')->nullable();
            $table->string('proof_file')->nullable();
            $table->timestamps();
            
            $table->index(['lecturer_id', 'date']);
            $table->unique(['lecturer_id', 'course_schedule_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturer_attendances');
    }
};
