<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    Route::get('/ai-advisor', [\App\Http\Controllers\Student\AiAdvisorController::class, 'index'])->name('ai-advisor.index');
    Route::post('/ai-advisor/chat', [\App\Http\Controllers\Student\AiAdvisorController::class, 'chat'])->middleware('throttle:ai-chat')->name('ai-advisor.chat');

    // AI Study Plan
    Route::get('/study-plan-ai', [\App\Http\Controllers\Student\StudyPlanAiController::class, 'index'])->name('study-plan-ai.index');
    Route::post('/study-plan-ai/generate', [\App\Http\Controllers\Student\StudyPlanAiController::class, 'generate'])->name('study-plan-ai.generate');

    // AI Quiz Generator
    Route::get('/quiz-ai', [\App\Http\Controllers\Student\QuizAiController::class, 'index'])->name('quiz-ai.index');
    Route::post('/quiz-ai/generate', [\App\Http\Controllers\Student\QuizAiController::class, 'generate'])->name('quiz-ai.generate');

    // AI Career Roadmap
    Route::get('/career-roadmap', [\App\Http\Controllers\Student\CareerRoadmapAiController::class, 'index'])->name('career-roadmap.index');
    Route::post('/career-roadmap/generate', [\App\Http\Controllers\Student\CareerRoadmapAiController::class, 'generate'])->name('career-roadmap.generate');

    // AI Subject Search / FAQ
    Route::get('/subject-search', [\App\Http\Controllers\Student\SubjectSearchAiController::class, 'index'])->name('subject-search.index');
    Route::post('/subject-search/ask', [\App\Http\Controllers\Student\SubjectSearchAiController::class, 'ask'])->name('subject-search.ask');

    // AI Academic Skill Tree
    Route::get('/skill-tree', [\App\Http\Controllers\Student\SkillTreeAiController::class, 'index'])->name('skill-tree.index');
    Route::get('/skill-tree/data', [\App\Http\Controllers\Student\SkillTreeAiController::class, 'getTreeData'])->name('skill-tree.data');

    // AI Academic Source Finder
    Route::get('/source-finder', [\App\Http\Controllers\Student\SourceFinderAiController::class, 'index'])->name('source-finder.index');
    Route::post('/source-finder/search', [\App\Http\Controllers\Student\SourceFinderAiController::class, 'search'])->name('source-finder.search');
});






