<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_schedule_id')->constrained('course_schedules')->onDelete('cascade');
            $table->integer('meeting_number');
            $table->date('date');
            $table->string('topic')->nullable();
            $table->enum('status', ['completed', 'holiday', 'rescheduled'])->default('completed');
            $table->timestamps();
            
            $table->unique(['course_schedule_id', 'meeting_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
