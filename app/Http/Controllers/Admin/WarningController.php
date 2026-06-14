<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Warning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarningController extends Controller
{
    public function index()
    {
        $warnings = Warning::with(['student.user', 'creator'])->latest()->paginate(10);
        $students = Student::with('user')->active()->get();

        return view('admin.warnings.index', compact('warnings', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'message' => 'required|string|min:5',
        ]);

        Warning::create([
            'student_id' => $validated['student_id'],
            'message' => $validated['message'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.warnings.index')->with('success', 'Warning sent successfully!');
    }

    public function destroy(Warning $warning)
    {
        $warning->delete();

        return redirect()->route('admin.warnings.index')->with('success', 'Warning deleted successfully!');
    }
}
