<?php

use App\Models\Lecturer;
use App\Models\AcademicClass;
use App\Models\Course;
use App\Models\StudyProgram;
use App\Models\User;

test('lecturer can view grading index page', function () {
    $user = User::factory()->create(['role' => 'lecturer']);
    $studyProgram = StudyProgram::factory()->create();
    Lecturer::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('lecturers.grading.index'));
    
    $response->assertStatus(200);
});

test('lecturer cannot access admin routes', function () {
    $user = User::factory()->create(['role' => 'lecturer']);
    Lecturer::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));
    
    $response->assertStatus(403);
});

test('student cannot access grading routes', function () {
    $user = User::factory()->create(['role' => 'student']);
    $studyProgram = StudyProgram::factory()->create();
    \App\Models\Student::factory()->create([
        'user_id' => $user->id,
        'study_program_id' => $studyProgram->id,
    ]);

    $response = $this->actingAs($user)->get(route('lecturers.grading.index'));
    
    $response->assertStatus(403);
});
