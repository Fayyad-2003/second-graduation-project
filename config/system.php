<?php

return [

    /*
    |--------------------------------------------------------------------------
    | System System Roles
    |--------------------------------------------------------------------------
    |
    | Roles used for system access. Do not change arbitrarily as it affects
    | middleware and policies.
    |
    */

    'roles' => [
        'admin',
        'lecturer',
        'student',
    ],


    /*
    |--------------------------------------------------------------------------
    | Semester Labels
    |--------------------------------------------------------------------------
    */

    'semester_labels' => [
        1 => 'Odd',
        2 => 'Even',
    ],


    /*
    |--------------------------------------------------------------------------
    | Study Plan Status
    |--------------------------------------------------------------------------
    |
    | draft     → student is still editing
    | pending   → waiting for admin/academic advisor approval
    | approved  → approved & final
    | rejected  → rejected, student must revise
    |
    */

    'study_plan_status' => [
        'draft',
        'pending',
        'approved',
        'rejected',
    ],


    /*
    |--------------------------------------------------------------------------
    | Grade Conversion
    |--------------------------------------------------------------------------
    |
    | Letter grade conversion based on numeric grade range.
    | You can call from service:
    | config('system.grade_conversion')
    |
    */

    'grade_conversion' => [
        ['min' => 85, 'max' => 100, 'letter' => 'A',  'weight' => 4.00],
        ['min' => 80, 'max' => 84,  'letter' => 'A-', 'weight' => 3.75],
        ['min' => 75, 'max' => 79,  'letter' => 'B+', 'weight' => 3.50],
        ['min' => 70, 'max' => 74,  'letter' => 'B',  'weight' => 3.00],
        ['min' => 65, 'max' => 69,  'letter' => 'C+', 'weight' => 2.50],
        ['min' => 60, 'max' => 64,  'letter' => 'C',  'weight' => 2.00],
        ['min' => 55, 'max' => 59,  'letter' => 'D',  'weight' => 1.00],
        ['min' => 0,  'max' => 54,  'letter' => 'E',  'weight' => 0.00],
    ],

    /*
    |--------------------------------------------------------------------------
    | Grade Components Breakdown
    |--------------------------------------------------------------------------
    |
    | Defines how the total grade is calculated based on whether the course
    | has a practical component or not.
    |
    */
    'grade_components' => [
        'with_practical' => [
            'attendance' => 10,
            'midterm' => 20,
            'final_exam' => 50,
            'practical_attendance' => 5,
            'practical_exam' => 20,
        ],
        'without_practical' => [
            'attendance' => 10,
            'midterm' => 30,
            'final_exam' => 60,
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Grade Status
    |--------------------------------------------------------------------------
    */

    'grade_status' => [
        'draft',        // grade can be edited by lecturer
        'submitted',    // waiting for verification
        'final',        // locked, cannot be changed
    ],


    /*
    |--------------------------------------------------------------------------
    | Student Credit Limit Rules
    |--------------------------------------------------------------------------
    |
    | Used if you want the study plan system to automatically calculate credits
    | based on the previous semester GPA.
    |
    */

    'max_credits' => [
        'default' => 24,
        'gpa_rules' => [
            ['min' => 3.51, 'max' => 4.00, 'credits' => 24],
            ['min' => 3.01, 'max' => 3.50, 'credits' => 22],
            ['min' => 2.51, 'max' => 3.00, 'credits' => 20],
            ['min' => 2.00, 'max' => 2.50, 'credits' => 18],
            ['min' => 0.00, 'max' => 1.99, 'credits' => 14],
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Default Class Capacity
    |--------------------------------------------------------------------------
    */

    'default_class_capacity' => 40,


    /*
    |--------------------------------------------------------------------------
    | Default Pagination
    |--------------------------------------------------------------------------
    */

    'pagination' => 15,


    /*
    |--------------------------------------------------------------------------
    | Academic Date Format
    |--------------------------------------------------------------------------
    */

    'date_format' => 'd-m-Y',


    /*
    |--------------------------------------------------------------------------
    | GPA Warning Thresholds
    |--------------------------------------------------------------------------
    |
    | Students whose cumulative GPA falls below these thresholds will
    | receive warnings.  "danger" = academic probation, "caution" = low GPA.
    |
    */

    'gpa_warning' => [
        'danger'  => 2.00,
        'caution' => 2.50,
    ],


    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */

    'app_name' => 'System Universitas',
    'version'  => '1.0.0',

];
