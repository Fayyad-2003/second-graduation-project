<?php

use App\Models\Lecturer;
use App\Models\AcademicClass;
use App\Models\Course;
use App\Models\StudyProgram;
use App\Models\CourseSchedule;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'lecturer']);
    $this->studyProgram = StudyProgram::factory()->create();
    $this->lecturer = Lecturer::factory()->create([
        'user_id' => $this->user->id,
        'study_program_id' => $this->studyProgram->id,
    ]);
});

test('lecturer can view their dashboard', function () {
    $response = $this->actingAs($this->user)->get(route('lecturers.dashboard'));
    
    $response->assertStatus(200);
});

test('lecturer can view supervision list', function () {
    $response = $this->actingAs($this->user)->get(route('lecturers.supervision.index'));
    
    $response->assertStatus(200);
});

test('lecturer can view attendance list', function () {
    $response = $this->actingAs($this->user)->get(route('lecturers.attendance-input.index'));
    
    $response->assertStatus(200);
});

test('lecturer can view thesis supervision list', function () {
    $response = $this->actingAs($this->user)->get(route('lecturers.thesis.index'));
    
    $response->assertStatus(200);
});

test('lecturer can view internship supervision list', function () {
    $response = $this->actingAs($this->user)->get(route('lecturers.internship.index'));
    
    $response->assertStatus(200);
});

test('lecturer cannot access student routes', function () {
    $response = $this->actingAs($this->user)->get(route('students.dashboard'));
    
    $response->assertStatus(403);
});
