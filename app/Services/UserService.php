<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Lecturer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createUser(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'        => $data['name'],
                'email'       => $data['email'],
                'password'    => Hash::make($data['password']),
                'role'        => $data['role'],
                'faculty_id' => $data['faculty_id'] ?? null,
            ]);


            if ($data['role'] === 'student') {
                Student::create([
                    'user_id'          => $user->id,
                    'student_number'   => $data['student_number'],
                    'study_program_id' => $data['study_program_id'],
                    'batch'            => $data['batch'],
                    'status'           => 'active',
                ]);
            } elseif ($data['role'] === 'lecturer') {
                Lecturer::create([
                    'user_id'          => $user->id,
                    'lecturer_number'  => $data['lecturer_number'],
                    'study_program_id' => $data['study_program_id'],
                ]);
            }

            return $user;
        });
    }
}
