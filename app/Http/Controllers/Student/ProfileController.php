<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        $student->load(['studyProgram.faculty', 'academicAdvisor.user', 'studyPlans']);

        return view('student.profile.index', compact('user', 'student'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update student (if phone/address fields exist)
        // Note: You may need to add these columns to students table
        // $student->update([
        //     'phone' => $validated['phone'],
        //     'address' => $validated['address'],
        // ]);

        return redirect()->back()->with('success', __('Profile successfully updated'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', __('Old password is incorrect'));
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', __('Password successfully updated'));
    }
}
