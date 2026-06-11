<?php

namespace App\Services;

use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Course;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Cache;

class AcademicService
{
    // Cache TTL in seconds (1 hour)
    protected const CACHE_TTL = 3600;

    // --- Faculty ---
    public function getAllFaculties()
    {
        return Cache::remember('master.faculties', self::CACHE_TTL, function () {
            return Faculty::with(['studyPrograms', 'studyPrograms.students'])->get();
        });
    }

    public function createFaculty($data)
    {
        Cache::forget('master.faculties');
        return Faculty::create($data);
    }

    // --- StudyProgram ---
    public function getAllStudyPrograms()
    {
        return Cache::remember('master.study_programs', self::CACHE_TTL, function () {
            return StudyProgram::with('faculties')->get();
        });
    }

    public function createStudyProgram($data)
    {
        Cache::forget('master.study_programs');
        return StudyProgram::create($data);
    }

    // --- Course ---
    public function getAllCourses()
    {
        return Cache::remember('master.courses', self::CACHE_TTL, function () {
            return Course::all();
        });
    }

    public function createCourse($data)
    {
        Cache::forget('master.courses');
        return Course::create($data);
    }

    // --- Academic Year ---
    public function getActiveAcademicYear()
    {
        return Cache::remember('master.active_academic_year', self::CACHE_TTL, function () {
            return AcademicYear::where('is_active', true)->first();
        });
    }

    public function activateAcademicYear($id)
    {
        AcademicYear::query()->update(['is_active' => false]); // Deactivate all
        $result = AcademicYear::where('id', $id)->update(['is_active' => true]);
        Cache::forget('master.active_academic_year');
        return $result;
    }

    // --- Class ---
    public function createClass($data)
    {
        $data['capacity'] = $data['capacity'] ?? config('system.default_class_capacity');

        // Auto-assign active academic year if not specified
        if (!isset($data['academic_year_id'])) {
            $activeAcademicYear = AcademicYear::where('is_active', true)->first();
            $data['academic_year_id'] = $activeAcademicYear?->id;
        }

        return AcademicClass::create($data);
    }

    /**
     * Clear all master data caches
     */
    public function clearAllCache(): void
    {
        Cache::forget('master.faculties');
        Cache::forget('master.study_programs');
        Cache::forget('master.courses');
        Cache::forget('master.active_academic_year');
    }
}
