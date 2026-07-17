<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\GraduationPredictorAiService;
use Illuminate\Support\Facades\Auth;

class GraduationPredictorAiController extends Controller
{
    protected GraduationPredictorAiService $predictor;

    public function __construct(GraduationPredictorAiService $predictor)
    {
        $this->predictor = $predictor;
    }

    public function index()
    {
        $student = Auth::user()->student;
        return view('student.graduation-predictor.index', compact('student'));
    }

    public function predict()
    {
        $student = Auth::user()->student;
        $result  = $this->predictor->predict($student);
        return response()->json($result);
    }
}
