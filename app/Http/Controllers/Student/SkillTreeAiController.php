<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\SkillTreeAiService;
use Illuminate\Support\Facades\Auth;

class SkillTreeAiController extends Controller
{
    protected SkillTreeAiService $aiService;

    public function __construct(SkillTreeAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        return view('student.skill-tree.index');
    }

    public function getTreeData()
    {
        $students = Auth::user()->student;
        $result = $this->aiService->generateTreeData($students);
        
        return response()->json($result);
    }
}
