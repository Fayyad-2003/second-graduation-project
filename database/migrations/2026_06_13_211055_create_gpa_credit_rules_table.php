<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gpa_credit_rules', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_gpa', 3, 2);
            $table->decimal('max_gpa', 3, 2);
            $table->integer('max_credits');
            $table->timestamps();

            $table->index('min_gpa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gpa_credit_rules');
    }
};
