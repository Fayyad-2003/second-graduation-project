<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\CourseRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(CourseRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(403, __('You do not have access as a student.'));
        }

        $recommendations = $this->recommendationService->getRecommendations($student);

        return view('student.recommendations.index', compact('recommendations'));
    }
}
