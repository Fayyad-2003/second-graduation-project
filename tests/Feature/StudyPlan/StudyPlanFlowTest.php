<?php

use App\Models\AcademicClass;
use App\Models\StudyPlan;
use App\Models\StudyPlanDetail;
use App\Models\Student;
use App\Models\Course;
use App\Models\StudyProgram;
use App\Models\AcademicYear;
use App\Models\User;

beforeEach(function () {
    // Create active academic year
    AcademicYear::factory()->create(['is_active' => true]);
});

test('student can view study plan page', function () {
    $user = User::factory()->create(['role' => 'student']);
    $studyProgram = StudyProgram::factory()->create();
    Student::factory()->create([
        'user_id' => $user->id,
        'study_program_id' => $studyProgram->id,
    ]);

    $response = $this->actingAs($user)->get(route('students.study-plan.index'));
    
    $response->assertStatus(200);
});

test('student cannot access admin routes', function () {
    $user = User::factory()->create(['role' => 'student']);
    $studyProgram = StudyProgram::factory()->create();
    Student::factory()->create([
        'user_id' => $user->id,
        'study_program_id' => $studyProgram->id,
    ]);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));
    
    $response->assertStatus(403);
});

test('unauthenticated user is redirected to login', function () {
    $response = $this->get(route('students.study-plan.index'));
    
    $response->assertRedirect(route('login'));
});
