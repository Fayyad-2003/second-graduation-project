<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->enum('status', ['active', 'leave', 'dropped', 'graduated'])->default('active')->after('batch');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->boolean('is_closed')->default(false)->after('capacity');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('is_closed');
        });
    }
};
