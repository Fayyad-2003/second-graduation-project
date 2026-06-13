<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('has_practical')->default(false)->after('credits');
            $table->integer('practical_hours')->default(0)->after('has_practical');
            $table->integer('theory_credits')->default(0)->after('practical_hours');
        });

        // Set theory_credits equal to credits for existing courses
        DB::table('courses')->update([
            'theory_credits' => DB::raw('credits')
        ]);
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['has_practical', 'practical_hours', 'theory_credits']);
        });
    }
};
