<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\SourceFinderAiService;
use Illuminate\Http\Request;

class SourceFinderAiController extends Controller
{
    protected SourceFinderAiService $aiService;

    public function __construct(SourceFinderAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        return view('student.source-finder.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $result = $this->aiService->searchSources($request->query('query'));

        return response()->json($result);
    }
}
