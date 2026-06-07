<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('supervisor_id')->nullable()->constrained('lecturers')->nullOnDelete();
            $table->string('company_name');
            $table->string('company_address')->nullable();
            $table->string('business_field')->nullable();
            $table->string('field_supervisor_name')->nullable();
            $table->string('field_supervisor_title')->nullable();
            $table->string('supervisor_phone')->nullable();
            $table->date('start_date');
            $table->date('completion_date');
            $table->string('report_title', 500)->nullable();
            $table->enum('status', [
                'submission',
                'approved',
                'rejected',
                'ongoing',
                'internship_completed',
                'report_drafting',
                'seminar',
                'revision',
                'completed',
            ])->default('submission');
            $table->date('seminar_date')->nullable();
            $table->decimal('company_grade', 5, 2)->nullable();
            $table->decimal('supervisor_grade', 5, 2)->nullable();
            $table->decimal('seminar_grade', 5, 2)->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->string('letter_grade', 5)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};
