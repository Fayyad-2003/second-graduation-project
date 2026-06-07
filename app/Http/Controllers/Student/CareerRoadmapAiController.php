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
        return view('student.career-roadmap.index');
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
