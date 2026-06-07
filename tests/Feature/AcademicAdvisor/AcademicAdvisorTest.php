<?php

namespace Tests\Feature\AcademicAdvisor;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\Course;
use App\Models\AcademicClass;
use App\Models\StudyPlan;
use App\Models\StudyPlanDetail;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Services\AcademicAdvisor\AdvisorContextBuilder;
use App\Services\AcademicAdvisor\AdvisorGuards;
use App\Services\AiAdvisorService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcademicAdvisorTest extends TestCase
{
    use RefreshDatabase;

    protected AdvisorContextBuilder $contextBuilder;
    protected AdvisorGuards $guards;
    protected Student $student;
    protected AcademicYear $activeAcademicYear;
    protected Lecturer $lecturer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contextBuilder = app(AdvisorContextBuilder::class);
        $this->guards = app(AdvisorGuards::class);

        // Create test data
        $this->setupTestData();
    }

    protected function setupTestData(): void
    {
        // Create faculty and study program
        $faculty = Faculty::create(['name' => 'Faculty of Engineering']);
        $studyProgram = StudyProgram::create([
            'faculty_id' => $faculty->id,
            'name' => 'Information Systems',
        ]);

        // Create lecturer
        $lecturerUser = User::create([
            'name' => 'Test Lecturer',
            'email' => 'lecturer@test.com',
            'password' => bcrypt('password'),
            'role' => 'lecturer',
        ]);

        $this->lecturer = Lecturer::create([
            'user_id' => $lecturerUser->id,
            'lecturer_number' => '1234567890',
            'study_program_id' => $studyProgram->id,
        ]);

        // Create user and student
        $user = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        $this->student = Student::create([
            'user_id' => $user->id,
            'student_number' => '2303113649',
            'study_program_id' => $studyProgram->id,
            'batch' => 2023,
            'status' => 'active',
        ]);

        // Create active academic year
        $this->activeAcademicYear = AcademicYear::create([
            'year' => '2024/2025',
            'semester' => 'Ganjil',
            'is_active' => true,
        ]);

        // Create some completed courses (LULUS) - total 87 credits
        $this->createCompletedCourses(87);
    }

    protected function createCompletedCourses(int $targetCredits): void
    {
        $creditsCreated = 0;
        $semesterNum = 1;
        $courseNum = 1;

        while ($creditsCreated < $targetCredits) {
            $credits = min(3, $targetCredits - $creditsCreated);

            $course = Course::create([
                'course_code' => 'MK' . str_pad($courseNum, 3, '0', STR_PAD_LEFT),
                'course_name' => 'Course ' . $courseNum,
                'credits' => $credits,
                'semester' => $semesterNum,
            ]);

            $academicYear = AcademicYear::create([
                'year' => '2023/2024',
                'semester' => $semesterNum % 2 == 1 ? 'Ganjil' : 'Genap',
                'is_active' => false,
            ]);

            $studyPlan = StudyPlan::create([
                'student_id' => $this->student->id,
                'academic_year_id' => $academicYear->id,
                'status' => 'approved',
            ]);

            $academicClass = AcademicClass::create([
                'course_id' => $course->id,
                'lecturer_id' => $this->lecturer->id,
                'class_name' => 'Class A',
                'capacity' => 40,
            ]);

            StudyPlanDetail::create([
                'study_plan_id' => $studyPlan->id,
                'class_id' => $academicClass->id,
            ]);

            Grade::create([
                'student_id' => $this->student->id,
                'class_id' => $academicClass->id,
                'numeric_grade' => 85,
                'letter_grade' => 'A',
            ]);

            $creditsCreated += $credits;
            $courseNum++;

            if ($courseNum % 6 == 0) {
                $semesterNum++;
            }
        }
    }

    /**
     * Test 1: it_returns_total_credits_and_progress_using_144_rule
     * Input: total completed 87, rule 144
     * Expected: progress ≈ 60% (57 credits remaining)
     */
    public function test_it_returns_total_credits_and_progress_using_144_rule(): void
    {
        $context = $this->contextBuilder->build($this->student);
        $progress = $this->contextBuilder->calculateGraduationProgress($context);

        // Expected: 87 credits completed, 144 target, 57 remaining, ~60.4% progress
        $this->assertEquals(87, $progress['credits_completed']);
        $this->assertEquals(144, $progress['credits_target']);
        $this->assertEquals(57, $progress['credits_remaining']);
        $this->assertEqualsWithDelta(60.4, $progress['progress_percent'], 0.5);

        // Verify study program rules loaded correctly
        $this->assertEquals(144, $context['prodi_rules']['graduation_total_credits']);
    }

    /**
     * Test 2: it_denies_thesis_if_credits_less_than_144
     * Expected: false + "57 credits remaining"
     */
    public function test_it_denies_thesis_if_credits_less_than_144(): void
    {
        $context = $this->contextBuilder->build($this->student);
        $progress = $this->contextBuilder->calculateGraduationProgress($context);

        // With 87 credits, should NOT be eligible for thesis (requires 144)
        $this->assertFalse($progress['eligible_thesis']);
        $this->assertEquals(57, $progress['credits_remaining']);

        // Verify thesis min credits rule
        $this->assertEquals(144, $context['prodi_rules']['thesis_min_credits']);
    }

    /**
     * Test 3: it_finds_big_data_from_curriculum_semester_7
     * Expected: Big Data semester 7, status "AVAILABLE_IN_CURRICULUM"
     */
    public function test_it_finds_big_data_from_curriculum_semester_7(): void
    {
        $context = $this->contextBuilder->build($this->student);

        // Search for Big Data in course statuses
        $bigDataCourse = $this->contextBuilder->findCourseByName($context, 'Big Data');

        $this->assertNotNull($bigDataCourse);
        $this->assertEquals('Big Data', $bigDataCourse['name']);
        $this->assertEquals(7, $bigDataCourse['semester']);
        $this->assertEquals('AVAILABLE_IN_CURRICULUM', $bigDataCourse['status']);

        // Also verify it's in curriculum
        $semester7 = collect($context['curriculum'])->firstWhere('semester', 7);
        $this->assertNotNull($semester7);

        $bigDataInCurriculum = collect($semester7['courses'])->firstWhere('name', 'Big Data');
        $this->assertNotNull($bigDataInCurriculum);
        $this->assertEquals(3, $bigDataInCurriculum['credits']);
    }

    /**
     * Test 4: it_does_not_flag_attendance_low_if_attendance_data_missing
     * When attendance_data_available=false
         * Expected: mention "attendance data not available", not low attendance
     */
    public function test_it_does_not_flag_attendance_low_if_attendance_data_missing(): void
    {
        // Create an enrolled course in active semester (no meeting data)
        $enrolledCourse = Course::create([
            'course_code' => 'ATTEND01',
            'course_name' => 'Course Without Attendance',
            'credits' => 3,
            'semester' => 5,
        ]);

        $enrolledClass = AcademicClass::create([
            'course_id' => $enrolledCourse->id,
            'lecturer_id' => $this->lecturer->id,
            'class_name' => 'Class No Attendance',
            'capacity' => 40,
        ]);

        $activeStudyPlan = StudyPlan::create([
            'student_id' => $this->student->id,
            'academic_year_id' => $this->activeAcademicYear->id,
            'status' => 'approved',
        ]);

        StudyPlanDetail::create([
            'study_plan_id' => $activeStudyPlan->id,
            'class_id' => $enrolledClass->id,
        ]);

        $context = $this->contextBuilder->build($this->student);

        // Since we haven't created any meetings, attendance should be unavailable
        $this->assertFalse($context['attendance']['data_available']);
        $this->assertTrue($context['attendance']['all_zero_or_null']);
        $this->assertNotNull($context['attendance']['warning']);

        // Test guard with output that mentions low attendance
        $badOutput = 'Based on data, attendance is low and needs improvement.';
        $guardResult = $this->guards->attendanceGuard($context, $badOutput);

        $this->assertEquals(AdvisorGuards::GUARD_FAIL, $guardResult['status']);
        $this->assertNotNull($guardResult['issue']);
        $this->assertStringContainsString('not available', $guardResult['recommended_response']);
    }

    /**
     * Test 5: it_rejects_generic_assumption_phrases
     * If output contains "usually/generally/depends"
     * Expected: retry or sanitized output
     */
    public function test_it_rejects_generic_assumption_phrases(): void
    {
        // Test each forbidden phrase
        $forbiddenOutputs = [
            'Usually graduation credits are 120 credits.',
            'Generally students in semester 5 can take thesis.',
            'This depends on each study program policy.',
            'In general, Big Data course is in upper semesters.',
        ];

        foreach ($forbiddenOutputs as $output) {
            $result = $this->guards->preventGenericAssumptions($output);

            $this->assertEquals(
                AdvisorGuards::GUARD_RETRY,
                $result['status'],
                "Expected GUARD_RETRY for output: {$output}"
            );
            $this->assertNotEmpty($result['violations']);
            $this->assertNotNull($result['retry_prompt']);
            $this->assertNotNull($result['sanitized_output']);
        }

        // Test good output (should pass)
        $goodOutput = 'Based on Information Systems study program rules, total graduation credits is 144 credits.';
        $result = $this->guards->preventGenericAssumptions($goodOutput);

        $this->assertEquals(AdvisorGuards::GUARD_PASS, $result['status']);
        $this->assertEmpty($result['violations']);
    }

    /**
     * Test 6: it_distinguishes_completed_vs_enrolled_vs_available
     * Completed from grades, enrolled from study plans, available from curriculum.
     */
    public function test_it_distinguishes_completed_vs_enrolled_vs_available(): void
    {
        // Create an enrolled course (in active study plan, no grade yet)
        $enrolledCourse = Course::create([
            'course_code' => 'ENROLLED01',
            'course_name' => 'Enrolled Course',
            'credits' => 3,
            'semester' => 5,
        ]);

        $enrolledClass = AcademicClass::create([
            'course_id' => $enrolledCourse->id,
            'lecturer_id' => $this->lecturer->id,
            'class_name' => 'Class Enrolled',
            'capacity' => 40,
        ]);

        $activeStudyPlan = StudyPlan::create([
            'student_id' => $this->student->id,
            'academic_year_id' => $this->activeAcademicYear->id,
            'status' => 'approved',
        ]);

        StudyPlanDetail::create([
            'study_plan_id' => $activeStudyPlan->id,
            'class_id' => $enrolledClass->id,
        ]);

        // Build context and check statuses
        $context = $this->contextBuilder->build($this->student);

        $statuses = collect($context['course_statuses']);

        // Check completed courses (from our setUp)
        $completedCourses = $statuses->where('status', 'COMPLETED');
        $this->assertGreaterThan(0, $completedCourses->count());

        // Check enrolled course
        $enrolledCourseStatus = $statuses->firstWhere('code', 'ENROLLED01');
        $this->assertNotNull($enrolledCourseStatus, 'Enrolled course should exist in statuses');
        $this->assertEquals('CURRENTLY_ENROLLED', $enrolledCourseStatus['status']);

        // Check available in curriculum (from config)
        $availableCourses = $statuses->where('status', 'AVAILABLE_IN_CURRICULUM');
        $this->assertGreaterThan(0, $availableCourses->count());

        // Specifically check Big Data is available
        $bigData = $statuses->first(fn($c) => str_contains($c['name'], 'Big Data'));
        $this->assertNotNull($bigData);
        $this->assertEquals('AVAILABLE_IN_CURRICULUM', $bigData['status']);
    }

    /**
     * Additional test: Guards validation works correctly
     */
    public function test_guards_assert_rules_present(): void
    {
        $context = $this->contextBuilder->build($this->student);

        // Should not throw exception with valid context
        $this->guards->assertRulesPresent($context);
        $this->guards->validateContext($context);

        // Should throw with invalid context
        $this->expectException(\InvalidArgumentException::class);
        $this->guards->assertRulesPresent([
            'prodi_rules' => ['graduation_total_credits' => 0]
        ]);
    }

    /**
     * Additional test: Full guard pipeline works
     */
    public function test_full_guard_pipeline(): void
    {
        $context = $this->contextBuilder->build($this->student);

        // Good output should pass
        $goodOutput = 'You have completed 87 of 144 credits (60.4%). 57 credits remaining to graduate.';
        $result = $this->guards->runPostGuards($context, $goodOutput);

        $this->assertTrue($result['passed']);
        $this->assertEmpty($result['issues']);

        // Bad output with assumption should fail
        $badOutput = 'Usually students need around 120-140 credits to graduate.';
        $result = $this->guards->runPostGuards($context, $badOutput);

        $this->assertFalse($result['passed']);
        $this->assertNotEmpty($result['issues']);
        $this->assertTrue($result['should_retry']);
    }
}
