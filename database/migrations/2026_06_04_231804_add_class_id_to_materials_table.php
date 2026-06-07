<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('id')->constrained('classes')->onDelete('cascade');
        });

        // Populate class_id from meeting relationship
        DB::table('materials')
            ->join('meetings', 'materials.meeting_id', '=', 'meetings.id')
            ->join('course_schedules', 'meetings.course_schedule_id', '=', 'course_schedules.id')
            ->update(['materials.class_id' => DB::raw('course_schedules.class_id')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }
};
