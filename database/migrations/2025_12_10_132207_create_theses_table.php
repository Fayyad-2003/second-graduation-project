<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('supervisor1_id')->nullable()->constrained('lecturers')->nullOnDelete();
            $table->foreignId('supervisor2_id')->nullable()->constrained('lecturers')->nullOnDelete();
            $table->string('title', 500);
            $table->text('abstract')->nullable();
            $table->string('research_field', 100)->nullable();
            $table->enum('status', [
                'submission',      // Student submits title
                'review',         // In supervisor/admin review
                'rejected',        // Title rejected, needs revision
                'accepted',       // Title accepted, supervision starts
                'supervision',      // In supervision process
                'proposal_seminar', // Proposal seminar
                'research',     // In research
                'result_seminar',  // Result seminar
                'defense',         // Final defense
                'revision',         // Post-defense revision
                'completed',        // Thesis completed
            ])->default('submission');
            $table->date('submission_date')->nullable();
            $table->date('title_approval_date')->nullable();
            $table->date('proposal_seminar_date')->nullable();
            $table->date('result_seminar_date')->nullable();
            $table->date('defense_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->string('letter_grade', 5)->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theses');
    }
};
