<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ScheduleAnalysisService;
use Illuminate\Http\Request;

class ScheduleAnalysisController extends Controller
{
    protected $analysisService;

    public function __construct(ScheduleAnalysisService $analysisService)
    {
        $this->analysisService = $analysisService;
    }

    public function index()
    {
        $results = $this->analysisService->analyzeActiveSemester();

        return view('admin.schedule-analysis.index', [
            'roomConflicts' => $results['room_conflicts'],
            'lecturerConflicts' => $results['lecturer_conflicts'],
            'studentConflicts' => $results['student_conflicts'],
            'activeYear' => $results['active_year']
        ]);
    }
}
