<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('study_plans', 'notes')) {
            Schema::table('study_plans', function (Blueprint $table) {
                $table->text('notes')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('study_plans', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
