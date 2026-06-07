<?php

use App\Models\User;
use App\Models\Faculty;
use App\Models\StudyProgram;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

test('admin can view faculty list', function () {
    Faculty::factory()->count(3)->create();

    $response = $this->actingAs($this->admin)->get(route('admin.faculty.index'));
    
    $response->assertStatus(200);
});

test('admin can create faculty', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.faculty.store'), [
        'name' => 'Faculty of Engineering Test',
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('faculties', ['name' => 'Faculty of Engineering Test']);
});

test('admin can view study program list', function () {
    StudyProgram::factory()->count(3)->create();

    $response = $this->actingAs($this->admin)->get(route('admin.study-program.index'));
    
    $response->assertStatus(200);
});

test('admin can create study program', function () {
    $faculty = Faculty::factory()->create();

    $response = $this->actingAs($this->admin)->post(route('admin.study-program.store'), [
        'faculty_id' => $faculty->id,
        'name' => 'Computer Science Test',
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('study_programs', ['name' => 'Computer Science Test']);
});

test('non-admin cannot access admin routes', function () {
    $student = User::factory()->create(['role' => 'student']);
    $studyProgram = StudyProgram::factory()->create();
    \App\Models\Student::factory()->create([
        'user_id' => $student->id,
        'study_program_id' => $studyProgram->id,
    ]);

    $response = $this->actingAs($student)->get(route('admin.faculty.index'));
    
    $response->assertStatus(403);
});
