<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Warning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarningController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        $warnings = $student->warnings()->with('creator')->latest()->get();

        return view('student.warnings.index', compact('warnings'));
    }

    public function markAsRead(Warning $warning)
    {
        if ($warning->student_id !== Auth::user()->student->id) {
            abort(403);
        }

        $warning->update(['is_read' => true]);

        return redirect()->back();
    }
}
