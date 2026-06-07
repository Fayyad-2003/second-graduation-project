<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\AcademicYear;

/**
 * Centralized Cache Service for SIAKAD
 * 
 * Provides unified caching for frequently accessed data with automatic
 * invalidation and cache warming capabilities.
 */
class CacheService
{
    // Cache TTL constants (in seconds)
    public const TTL_SHORT = 300;      // 5 minutes - for volatile data
    public const TTL_MEDIUM = 3600;    // 1 hour - for master data
    public const TTL_LONG = 86400;     // 24 hours - for rarely changing data

    // Cache key prefixes
    private const PREFIX_MASTER = 'master';
    private const PREFIX_USER = 'user';
    private const PREFIX_STATS = 'stats';

    /**
     * ==========================================
     * MASTER DATA CACHING
     * ==========================================
     */

    /**
     * Get cached Active Academic Year
     */
    public function getActiveAcademicYear(): ?AcademicYear
    {
        return Cache::remember(
            self::PREFIX_MASTER . '.academic_year_active',
            self::TTL_MEDIUM,
            fn() => AcademicYear::where('is_active', true)->first()
        );
    }

    /**
     * Get cached Faculty list
     */
    public function getFacultyList()
    {
        return Cache::remember(
            self::PREFIX_MASTER . '.faculty',
            self::TTL_MEDIUM,
            fn() => Faculty::orderBy('name')->get()
        );
    }

    /**
     * Get cached Study Program list with faculty
     */
    public function getStudyProgramList()
    {
        return Cache::remember(
            self::PREFIX_MASTER . '.study_program',
            self::TTL_MEDIUM,
            fn() => StudyProgram::with('faculty')->orderBy('name')->get()
        );
    }

    /**
     * Get cached Study Program by Faculty
     */
    public function getStudyProgramByFaculty(int $facultyId)
    {
        return Cache::remember(
            self::PREFIX_MASTER . ".study_program.faculty.{$facultyId}",
            self::TTL_MEDIUM,
            fn() => StudyProgram::where('faculty_id', $facultyId)->orderBy('name')->get()
        );
    }

    /**
     * Get cached Course list
     */
    public function getCourseList()
    {
        return Cache::remember(
            self::PREFIX_MASTER . '.course',
            self::TTL_MEDIUM,
            fn() => Course::with('studyProgram')->orderBy('course_code')->get()
        );
    }

    /**
     * Get cached Lecturer list
     */
    public function getLecturerList()
    {
        return Cache::remember(
            self::PREFIX_MASTER . '.lecturer',
            self::TTL_MEDIUM,
            fn() => Lecturer::with(['user', 'studyProgram'])->get()
        );
    }

    /**
     * ==========================================
     * CACHE INVALIDATION
     * ==========================================
     */

    /**
     * Clear all master data cache
     */
    public function clearMasterCache(): void
    {
        Cache::forget(self::PREFIX_MASTER . '.academic_year_active');
        Cache::forget(self::PREFIX_MASTER . '.faculty');
        Cache::forget(self::PREFIX_MASTER . '.study_program');
        Cache::forget(self::PREFIX_MASTER . '.course');
        Cache::forget(self::PREFIX_MASTER . '.lecturer');
        
        // Clear faculty-specific study program lists
        $faculties = Faculty::all();
        foreach ($faculties as $faculty) {
            Cache::forget(self::PREFIX_MASTER . ".study_program.faculty.{$faculty->id}");
        }
    }
}
