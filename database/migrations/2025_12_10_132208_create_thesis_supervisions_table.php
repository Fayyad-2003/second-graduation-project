<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis_supervisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_id')->constrained('theses')->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->date('supervision_date');
            $table->text('student_notes')->nullable(); // Progress from student
            $table->text('lecturer_notes')->nullable();     // Feedback from lecturer
            $table->string('document_file')->nullable();    // Upload dokumen
            $table->enum('status', ['waiting', 'approved', 'revision'])->default('waiting');
            $table->timestamps();
            
            $table->index(['thesis_id', 'supervision_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_supervisions');
    }
};
