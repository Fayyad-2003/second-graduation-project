<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Performance indexes for frequently queried columns.
     * These indexes significantly improve query performance for common operations.
     */
    public function up(): void
    {
        // Study Plans table indexes
        Schema::table('study_plans', function (Blueprint $table) {
            // Index for status filtering (pending, approved, rejected, draft)
            $table->index('status', 'study_plans_status_index');
            
            // Composite index for student + academic year lookup
            $table->unique(['student_id', 'academic_year_id'], 'study_plans_student_year_unique');
        });

        // Study Plan Details table indexes
        Schema::table('study_plan_details', function (Blueprint $table) {
            // Prevent duplicate class in same study plan
            $table->unique(['study_plan_id', 'class_id'], 'study_plan_details_plan_class_unique');
        });

        // Grades table indexes
        Schema::table('grades', function (Blueprint $table) {
            // Prevent duplicate grade for same student + class
            $table->unique(['student_id', 'class_id'], 'grades_student_class_unique');
        });

        // Class table indexes
        Schema::table('classes', function (Blueprint $table) {
            // Index for course lookup
            $table->index('course_id', 'class_course_index');
            
            // Index for lecturer lookup
            $table->index('lecturer_id', 'class_lecturer_index');
        });

        // Student table indexes
         Schema::table('students', function (Blueprint $table) {
              // Index for batch filtering
              $table->index('batch', 'student_batch_index');
              
              // Index for study_program lookup (faculty scoping)
              $table->index('study_program_id', 'student_study_program_index');
          });

         // Lecturer table - add index for study_program if exists
         if (Schema::hasColumn('lecturers', 'study_program_id')) {
             Schema::table('lecturers', function (Blueprint $table) {
                 $table->index('study_program_id', 'lecturer_study_program_index');
             });
        }

        // Meeting table indexes
        Schema::table('meetings', function (Blueprint $table) {
            // Index for schedule lookup
            $table->index('course_schedule_id', 'meeting_schedule_index');
            
            // Index for date range queries
            $table->index('date', 'meeting_date_index');
        });

        // Course Schedule indexes
        if (Schema::hasTable('course_schedules')) {
            Schema::table('course_schedules', function (Blueprint $table) {
                $table->index('class_id', 'course_schedules_class_index');
                $table->index('day', 'course_schedules_day_index');
            });
        }

        // Activity Log indexes for better log querying
        if (Schema::hasTable('activity_logs')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->index('user_id', 'activity_logs_user_index');
                $table->index('created_at', 'activity_logs_created_at_index');
            });
        }

        // AI Conversation Logs indexes
        if (Schema::hasTable('ai_conversation_logs')) {
            Schema::table('ai_conversation_logs', function (Blueprint $table) {
                $table->index('student_id', 'ai_logs_student_index');
                $table->index('created_at', 'ai_logs_created_at_index');
            });
        }
    }

    public function down(): void
    {
        Schema::table('study_plans', function (Blueprint $table) {
            $table->dropIndex('study_plans_status_index');
            $table->dropUnique('study_plans_student_year_unique');
        });

        Schema::table('study_plan_details', function (Blueprint $table) {
            $table->dropUnique('study_plan_details_plan_class_unique');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropUnique('grades_student_class_unique');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropIndex('class_course_index');
            $table->dropIndex('class_lecturer_index');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('student_batch_index');
            $table->dropIndex('student_study_program_index');
        });

if (Schema::hasColumn('lecturers', 'study_program_id')) {
             Schema::table('lecturers', function (Blueprint $table) {
                 $table->dropIndex('lecturer_study_program_index');
             });
         }

        Schema::table('meetings', function (Blueprint $table) {
            $table->dropIndex('meeting_schedule_index');
            $table->dropIndex('meeting_date_index');
        });

        if (Schema::hasTable('course_schedules')) {
            Schema::table('course_schedules', function (Blueprint $table) {
                $table->dropIndex('course_schedules_class_index');
                $table->dropIndex('course_schedules_day_index');
            });
        }

        if (Schema::hasTable('activity_logs')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->dropIndex('activity_logs_user_index');
                $table->dropIndex('activity_logs_created_at_index');
            });
        }

        if (Schema::hasTable('ai_conversation_logs')) {
            Schema::table('ai_conversation_logs', function (Blueprint $table) {
                $table->dropIndex('ai_logs_student_index');
                $table->dropIndex('ai_logs_created_at_index');
            });
        }
    }
};
