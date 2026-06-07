<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Academic Rules per Study Program
    |--------------------------------------------------------------------------
    |
    | This configuration contains academic rules used by AI Academic
    | Advisor to provide accurate and grounded answers.
    |
    */

    'study_programs' => [
        'information_systems_unri' => [
            'name' => 'Information Systems',
            'university' => 'Riau University',
            'graduation_total_credits' => 144,
            'thesis_min_credits' => 144,
            'internship' => [
                'credits' => 3,
                'min_credits_required' => 90,
                'name' => 'Internship',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Rules (fallback if study program is not specific)
    |--------------------------------------------------------------------------
    */

    'default' => [
        'graduation_total_credits' => 144,
        'thesis_min_credits' => 144,
        'internship' => [
            'credits' => 3,
            'min_credits_required' => 90,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Course Curriculum per Semester
    |--------------------------------------------------------------------------
    |
    | List of courses per semester for Information Systems Study Program UNRI.
    | Used to determine AVAILABLE_IN_CURRICULUM status.
    |
    */

    'curriculum' => [
        'information_systems_unri' => [
            6 => [
                ['code' => 'TIF601', 'name' => 'Software Engineering', 'credits' => 3],
                ['code' => 'TIF602', 'name' => 'Advanced Web Programming', 'credits' => 3],
                ['code' => 'TIF603', 'name' => 'Management Information Systems', 'credits' => 3],
                ['code' => 'TIF604', 'name' => 'Data Mining', 'credits' => 3],
                ['code' => 'TIF605', 'name' => 'Computer Networks', 'credits' => 3],
                ['code' => 'TIF606', 'name' => 'Information Systems Security', 'credits' => 3],
            ],
            7 => [
                ['code' => 'TIF701', 'name' => 'Big Data', 'credits' => 3],
                ['code' => 'TIF702', 'name' => 'Machine Learning', 'credits' => 3],
                ['code' => 'TIF703', 'name' => 'Cloud Computing', 'credits' => 3],
                ['code' => 'TIF704', 'name' => 'Enterprise Resource Planning', 'credits' => 3],
                ['code' => 'TIF705', 'name' => 'Internship', 'credits' => 3],
                ['code' => 'TIF706', 'name' => 'Research Methodology', 'credits' => 3],
            ],
            8 => [
                ['code' => 'TIF801', 'name' => 'Thesis', 'credits' => 6],
                ['code' => 'TIF802', 'name' => 'Seminar', 'credits' => 2],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Course Status
    |--------------------------------------------------------------------------
    |
    | Course status definitions for AI Advisor.
    |
    */

    'course_status' => [
        'PASSED' => 'Already passed with final grade in grade report',
        'IN_PROGRESS' => 'Currently enrolled in active study plan',
        'AVAILABLE_IN_CURRICULUM' => 'Available in upcoming semester curriculum',
        'NOT_AVAILABLE' => 'Not available in curriculum/system data',
    ],

    /*
    |--------------------------------------------------------------------------
    | Thresholds and Limits
    |--------------------------------------------------------------------------
    */

    'thresholds' => [
        'attendance_minimum_percentage' => 75,
        'attendance_warning_percentage' => 80,
    ],

    /*
    |--------------------------------------------------------------------------
    | Forbidden Assumption Phrases
    |--------------------------------------------------------------------------
    |
    | Words that are forbidden for AI to use in academic rules context.
    | If AI uses these words, it will trigger retry or sanitization.
    |
    */

    'forbidden_assumption_phrases' => [
        'usually',
        'generally',
        'depends',
        'in general',
        'commonly',
        'often',
        'maybe around',
        'approximately',
        'around',
        'more or less',
        'university average',
        'national standard',
    ],

];
