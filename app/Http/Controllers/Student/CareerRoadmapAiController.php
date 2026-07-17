<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\CareerRoadmapAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CareerRoadmapAiController extends Controller
{
    protected CareerRoadmapAiService $roadmapService;

    public function __construct(CareerRoadmapAiService $roadmapService)
    {
        $this->roadmapService = $roadmapService;
    }

    public function index()
    {
        $student = Auth::user()->student;
        $studyProgram = $student?->studyProgram?->name ?? '';
        $faculty = $student?->studyProgram?->faculty?->name ?? '';
        return view('student.career-roadmap.index', compact('studyProgram', 'faculty'));
    }

    public function getMainFields()
    {
        $student = Auth::user()->student;
        $result = $this->roadmapService->getMainFields($student);
        return response()->json($result);
    }

    public function getOptions(Request $request)
    {
        $request->validate([
            'level' => 'required|in:subFields,specificFields,technologies',
            'field' => 'required|string',
            'subField' => 'nullable|string',
            'specificField' => 'nullable|string',
        ]);

        $result = $this->roadmapService->getOptions($request->all());
        return response()->json($result);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'field' => 'required|string',
            'subField' => 'required|string',
            'specificField' => 'required|string',
            'technology' => 'required|string',
        ]);

        $students = Auth::user()->student;
        
        $result = $this->roadmapService->generateRoadmap($students, $request->all());

        return response()->json($result);
    }
}
