<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('assignment_file', 500)->nullable(); // Optional: file soal/instruksi
            $table->dateTime('deadline');
            $table->integer('max_file_size')->default(10485760); // 10MB default
            $table->string('allowed_extensions')->default('pdf,doc,docx,zip,rar');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
