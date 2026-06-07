<?php

use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\AcademicYear;
use App\Models\User;

beforeEach(function () {
    AcademicYear::factory()->create(['is_active' => true]);
    $this->user = User::factory()->create(['role' => 'student']);
    $this->studyProgram = StudyProgram::factory()->create();
    $this->student = Student::factory()->create([
        'user_id' => $this->user->id,
        'study_program_id' => $this->studyProgram->id,
    ]);
});

test('student can view their dashboard', function () {
    $response = $this->actingAs($this->user)->get(route('students.dashboard'));

    $response->assertStatus(200);
});

test('student can view schedule', function () {
    $response = $this->actingAs($this->user)->get(route('students.schedule.index'));

    $response->assertStatus(200);
});

test('student can view attendance', function () {
    $response = $this->actingAs($this->user)->get(route('students.attendance.index'));

    $response->assertStatus(200);
});

test('student can view transcript', function () {
    $response = $this->actingAs($this->user)->get(route('students.transcript.index'));

    $response->assertStatus(200);
});

test('student can view graduation checker', function () {
    $response = $this->actingAs($this->user)->get(route('students.graduation-checker.index'));

    $response->assertStatus(200);
});

test('student can view grade report', function () {
    $response = $this->actingAs($this->user)->get(route('students.grade-report.index'));

    $response->assertStatus(200);
});

test('student can view profile', function () {
    $response = $this->actingAs($this->user)->get(route('students.profile.index'));

    $response->assertStatus(200);
});

test('student can view thesis page', function () {
    $response = $this->actingAs($this->user)->get(route('students.thesis.index'));

    $response->assertStatus(200);
});

test('student can view internship page', function () {
    $response = $this->actingAs($this->user)->get(route('students.internship.index'));

    $response->assertStatus(200);
});
