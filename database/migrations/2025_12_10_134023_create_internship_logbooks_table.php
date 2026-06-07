<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained('internships')->onDelete('cascade');
            $table->date('date');
            $table->time('entry_time')->nullable();
            $table->time('exit_time')->nullable();
            $table->text('activity');
            $table->text('supervisor_notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'revision'])->default('pending');
            $table->timestamps();
            
            $table->index(['internship_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_logbooks');
    }
};
