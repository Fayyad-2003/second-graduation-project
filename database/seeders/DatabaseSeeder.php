<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Course;
use App\Models\AcademicClass;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\StudyPlan;
use App\Models\StudyPlanDetail;
use App\Models\Grade;
use App\Models\CourseSchedule;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key constraints to allow safe truncation
        Schema::disableForeignKeyConstraints();
        
        // Truncate tables managed by this seeder to avoid unique constraint violations
        User::truncate();
        AcademicYear::truncate();
        Faculty::truncate();
        StudyProgram::truncate();
        Room::truncate();
        Course::truncate();
        Lecturer::truncate();
        AcademicClass::truncate();
        CourseSchedule::truncate();
        Student::truncate();
        StudyPlan::truncate();
        StudyPlanDetail::truncate();
        Grade::truncate();
        
        Schema::enableForeignKeyConstraints();

        $this->call(RolePermissionSeeder::class);

        // ==========================================
        // 1. SUPERADMIN
        // ==========================================
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@siakad.test',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // ==========================================
        // 2. ACADEMIC YEARS
        // ==========================================
        AcademicYear::create([
            'year' => '2023/2024',
            'semester' => 'Odd',
            'is_active' => false,
            'start_date' => '2023-09-01',
            'completion_date' => '2024-01-31',
        ]);
        
        $previousYear = AcademicYear::create([
            'year' => '2023/2024',
            'semester' => 'Even',
            'is_active' => false,
            'start_date' => '2024-02-01',
            'completion_date' => '2024-06-30',
        ]);
        
        $activeYear = AcademicYear::create([
            'year' => '2024/2025',
            'semester' => 'Odd',
            'is_active' => true,
            'start_date' => '2024-09-01',
            'completion_date' => '2025-01-31',
        ]);

        // ==========================================
        // 3. FACULTIES
        // ==========================================
        $faculty = Faculty::create(['name' => 'Faculty of Engineering and Computer Science']);

        // Faculty Admin
        $adminFaculty = User::create([
            'name' => 'Admin FTIK',
            'email' => 'admin.ftik@siakad.test',
            'password' => Hash::make('password'),
            'role' => 'admin_faculty',
            'faculty_id' => $faculty->id,
        ]);

        // ==========================================
        // 4. STUDY PROGRAMS
        // ==========================================
        $studyProgram = StudyProgram::create([
            'name' => 'Information Technology',
            'faculty_id' => $faculty->id,
        ]);

        // ==========================================
        // 5. ROOMS
        // ==========================================
        $room = Room::create([
            'room_code' => 'LC-01',
            'room_name' => 'Computer Lab 1',
            'capacity' => 40,
            'building' => 'Building A',
            'floor' => 1,
        ]);

        // ==========================================
        // 6. COURSES - 8 SEMESTER CURRICULUM (144 CREDITS)
        // ==========================================
        $curriculum = $this->getCurriculum($studyProgram->id);
        
        foreach ($curriculum as $course) {
            Course::create($course);
        }

        // ==========================================
        // 7. LECTURERS
        // ==========================================
        $lecturerUser = User::create([
            'name' => 'Dr. Ahmad Fauzi, M.Kom.',
            'email' => 'lecturer@siakad.test',
            'password' => Hash::make('password'),
            'role' => 'lecturer',
        ]);

        $lecturer = Lecturer::create([
            'user_id' => $lecturerUser->id,
            'lecturer_number' => '0012056701',
            'study_program_id' => $studyProgram->id,
        ]);

        // ==========================================
        // 8. CLASSES (for semester 1-2)
        // ==========================================
        $coursesSem1 = Course::where('semester', 1)->get();
        $coursesSem2 = Course::where('semester', 2)->get();
        
        $classList = [];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $startTimes = ['08:00', '10:00', '13:00', '15:00'];
        
        $dayIndex = 0;
        $timeIndex = 0;
        
        foreach ($coursesSem1->merge($coursesSem2) as $course) {
            $class = AcademicClass::create([
                'course_id' => $course->id,
                'lecturer_id' => $lecturer->id,
                'class_name' => 'A',
                'capacity' => 40,
                'academic_year_id' => $activeYear->id,
            ]);
            
            // Create schedule
            CourseSchedule::create([
                'class_id' => $class->id,
                'day' => $days[$dayIndex % 5],
                'start_time' => $startTimes[$timeIndex % 4],
                'end_time' => date('H:i', strtotime($startTimes[$timeIndex % 4]) + 5400), // +1.5 hours
                'room' => $room->room_name,
            ]);
            
            $classList[] = $class;
            $dayIndex++;
            $timeIndex++;
        }

        // ==========================================
        // 9. STUDENTS (Semester 5 - Batch 2022)
        // ==========================================
        $studentUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'student@siakad.test',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $student = Student::create([
            'user_id' => $studentUser->id,
            'student_number' => '2022101001',
            'study_program_id' => $studyProgram->id,
            'batch' => 2022,
            'academic_advisor_id' => $lecturer->id,
            'status' => 'active',
        ]);

        // ==========================================
        // 10. ACADEMIC HISTORY (4 Completed Semesters)
        // ==========================================
        
        // Create academic years for semester 1-4
        $ay2022Odd = AcademicYear::create([
            'year' => '2022/2023', 'semester' => 'Odd', 'is_active' => false,
            'start_date' => '2022-09-01', 'completion_date' => '2023-01-31',
        ]);
        $ay2022Even = AcademicYear::create([
            'year' => '2022/2023', 'semester' => 'Even', 'is_active' => false,
            'start_date' => '2023-02-01', 'completion_date' => '2023-06-30',
        ]);
        
        // Semester 1 (20 Credits - 7 Courses)
        $sp1 = StudyPlan::create(['student_id' => $student->id, 'academic_year_id' => $ay2022Odd->id, 'status' => 'approved']);
        foreach (Course::where('semester', 1)->get() as $course) {
            $class = AcademicClass::where('course_id', $course->id)->first();
            if ($class) {
                StudyPlanDetail::create(['study_plan_id' => $sp1->id, 'class_id' => $class->id]);
                $numericGrade = rand(75, 92);
                Grade::create(['student_id' => $student->id, 'class_id' => $class->id, 'numeric_grade' => $numericGrade, 'letter_grade' => $this->convertToLetter($numericGrade)]);
            }
        }

        // Semester 2 (20 Credits - 7 Courses)
        $sp2 = StudyPlan::create(['student_id' => $student->id, 'academic_year_id' => $ay2022Even->id, 'status' => 'approved']);
        foreach (Course::where('semester', 2)->get() as $course) {
            $class = AcademicClass::where('course_id', $course->id)->first();
            if ($class) {
                StudyPlanDetail::create(['study_plan_id' => $sp2->id, 'class_id' => $class->id]);
                $numericGrade = rand(73, 90);
                Grade::create(['student_id' => $student->id, 'class_id' => $class->id, 'numeric_grade' => $numericGrade, 'letter_grade' => $this->convertToLetter($numericGrade)]);
            }
        }

        // Semester 3 (21 Credits - 7 Courses) - 2023/2024 Odd (already exists above)
        $ay2023Odd = AcademicYear::where('year', '2023/2024')->where('semester', 'Odd')->first();
        $sp3 = StudyPlan::create(['student_id' => $student->id, 'academic_year_id' => $ay2023Odd->id, 'status' => 'approved']);
        foreach (Course::where('semester', 3)->get() as $course) {
            // Create class for semester 3
            $class3 = AcademicClass::create([
                'course_id' => $course->id, 'lecturer_id' => $lecturer->id, 'class_name' => 'A', 
                'capacity' => 40, 'academic_year_id' => $ay2023Odd->id,
            ]);
            StudyPlanDetail::create(['study_plan_id' => $sp3->id, 'class_id' => $class3->id]);
            $numericGrade = rand(72, 88);
            Grade::create(['student_id' => $student->id, 'class_id' => $class3->id, 'numeric_grade' => $numericGrade, 'letter_grade' => $this->convertToLetter($numericGrade)]);
        }

        // Semester 4 (21 Credits - 7 Courses) - 2023/2024 Even
        $sp4 = StudyPlan::create(['student_id' => $student->id, 'academic_year_id' => $previousYear->id, 'status' => 'approved']);
        foreach (Course::where('semester', 4)->get() as $course) {
            $class4 = AcademicClass::create([
                'course_id' => $course->id, 'lecturer_id' => $lecturer->id, 'class_name' => 'A',
                'capacity' => 40, 'academic_year_id' => $previousYear->id,
            ]);
            StudyPlanDetail::create(['study_plan_id' => $sp4->id, 'class_id' => $class4->id]);
            $numericGrade = rand(74, 90);
            Grade::create(['student_id' => $student->id, 'class_id' => $class4->id, 'numeric_grade' => $numericGrade, 'letter_grade' => $this->convertToLetter($numericGrade)]);
        }

        // ==========================================
        // 11. CURRENT SEMESTER 5 STUDY PLAN (Draft)
        // ==========================================
        $currentStudyPlan = StudyPlan::create([
            'student_id' => $student->id,
            'academic_year_id' => $activeYear->id,
            'status' => 'draft',
        ]);

        // Create classes for semester 5 and enroll in study plan
        foreach (Course::where('semester', 5)->get() as $course) {
            $class5 = AcademicClass::create([
                'course_id' => $course->id, 'lecturer_id' => $lecturer->id, 'class_name' => 'A',
                'capacity' => 40, 'academic_year_id' => $activeYear->id,
            ]);
            StudyPlanDetail::create(['study_plan_id' => $currentStudyPlan->id, 'class_id' => $class5->id]);
        }

        // ==========================================
        // OUTPUT
        // ==========================================
        $this->command->newLine();
        $this->command->info('✅ Database seeded successfully!');
        $this->command->newLine();
        $this->command->info('📋 Login Credentials:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Superadmin', 'superadmin@siakad.test', 'password'],
                ['Faculty Admin', 'admin.ftik@siakad.test', 'password'],
                ['Lecturer', 'lecturer@siakad.test', 'password'],
                ['Student', 'student@siakad.test', 'password'],
            ]
        );
        $this->command->newLine();
        $this->command->info("📚 Curriculum: {$studyProgram->name}");
        $this->command->info("   Total: 144 Credits | 8 Semesters | " . Course::count() . " Courses");;
    }

    /**
     * Computer Science Curriculum - 8 Semesters - 144 Credits
     */
    private function getCurriculum(int $studyProgramId): array
    {
        return [
            // ====== SEMESTER 1 (20 Credits) ======
            ['course_code' => 'TI101', 'course_name' => 'Algorithms and Programming I', 'credits' => 4, 'semester' => 1, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI102', 'course_name' => 'Discrete Mathematics', 'credits' => 3, 'semester' => 1, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI103', 'course_name' => 'Introduction to Information Technology', 'credits' => 3, 'semester' => 1, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI104', 'course_name' => 'Calculus I', 'credits' => 3, 'semester' => 1, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI105', 'course_name' => 'Basic Physics', 'credits' => 3, 'semester' => 1, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI106', 'course_name' => 'English I', 'credits' => 2, 'semester' => 1, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI107', 'course_name' => 'Pancasila Education', 'credits' => 2, 'semester' => 1, 'study_program_id' => $studyProgramId],

            // ====== SEMESTER 2 (20 Credits) ======
            ['course_code' => 'TI201', 'course_name' => 'Algorithms and Programming II', 'credits' => 4, 'semester' => 2, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI202', 'course_name' => 'Data Structures', 'credits' => 4, 'semester' => 2, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI203', 'course_name' => 'Calculus II', 'credits' => 3, 'semester' => 2, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI204', 'course_name' => 'Linear Algebra', 'credits' => 3, 'semester' => 2, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI205', 'course_name' => 'English II', 'credits' => 2, 'semester' => 2, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI206', 'course_name' => 'Civic Education', 'credits' => 2, 'semester' => 2, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI207', 'course_name' => 'Algorithm Practicum', 'credits' => 2, 'semester' => 2, 'study_program_id' => $studyProgramId],

            // ====== SEMESTER 3 (20 Credits) ======
            ['course_code' => 'TI301', 'course_name' => 'Object-Oriented Programming', 'credits' => 4, 'semester' => 3, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI302', 'course_name' => 'Database', 'credits' => 4, 'semester' => 3, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI303', 'course_name' => 'Operating Systems', 'credits' => 3, 'semester' => 3, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI304', 'course_name' => 'Statistics and Probability', 'credits' => 3, 'semester' => 3, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI305', 'course_name' => 'Computer Organization and Architecture', 'credits' => 3, 'semester' => 3, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI306', 'course_name' => 'Database Practicum', 'credits' => 2, 'semester' => 3, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI307', 'course_name' => 'Religion', 'credits' => 2, 'semester' => 3, 'study_program_id' => $studyProgramId],

            // ====== SEMESTER 4 (20 Credits) ======
            ['course_code' => 'TI401', 'course_name' => 'Web Programming', 'credits' => 4, 'semester' => 4, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI402', 'course_name' => 'Computer Networks', 'credits' => 4, 'semester' => 4, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI403', 'course_name' => 'Software Engineering', 'credits' => 3, 'semester' => 4, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI404', 'course_name' => 'Human-Computer Interaction', 'credits' => 3, 'semester' => 4, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI405', 'course_name' => 'System Analysis and Design', 'credits' => 3, 'semester' => 4, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI406', 'course_name' => 'Network Practicum', 'credits' => 2, 'semester' => 4, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI407', 'course_name' => 'Professional Ethics', 'credits' => 2, 'semester' => 4, 'study_program_id' => $studyProgramId],

            // ====== SEMESTER 5 (20 Credits) ======
            ['course_code' => 'TI501', 'course_name' => 'Mobile Programming', 'credits' => 4, 'semester' => 5, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI502', 'course_name' => 'Artificial Intelligence', 'credits' => 3, 'semester' => 5, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI503', 'course_name' => 'Information System Security', 'credits' => 3, 'semester' => 5, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI504', 'course_name' => 'Distributed Systems', 'credits' => 3, 'semester' => 5, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI505', 'course_name' => 'IT Project Management', 'credits' => 3, 'semester' => 5, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI506', 'course_name' => 'Mobile Practicum', 'credits' => 2, 'semester' => 5, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI507', 'course_name' => 'Entrepreneurship', 'credits' => 2, 'semester' => 5, 'study_program_id' => $studyProgramId],

            // ====== SEMESTER 6 (18 Credits) ======
            ['course_code' => 'TI601', 'course_name' => 'Machine Learning', 'credits' => 3, 'semester' => 6, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI602', 'course_name' => 'Data Mining', 'credits' => 3, 'semester' => 6, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI603', 'course_name' => 'Cloud Computing', 'credits' => 3, 'semester' => 6, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI604', 'course_name' => 'Digital Image Processing', 'credits' => 3, 'semester' => 6, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI605', 'course_name' => 'Research Methodology', 'credits' => 2, 'semester' => 6, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI606', 'course_name' => 'Internship', 'credits' => 4, 'semester' => 6, 'study_program_id' => $studyProgramId],

            // ====== SEMESTER 7 (14 Credits) ======
            ['course_code' => 'TI701', 'course_name' => 'Internet of Things', 'credits' => 3, 'semester' => 7, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI702', 'course_name' => 'Big Data Analytics', 'credits' => 3, 'semester' => 7, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI703', 'course_name' => 'Natural Language Processing', 'credits' => 3, 'semester' => 7, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI704', 'course_name' => 'Project 1 (Thesis Proposal)', 'credits' => 2, 'semester' => 7, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI705', 'course_name' => 'Elective 1', 'credits' => 3, 'semester' => 7, 'study_program_id' => $studyProgramId],

            // ====== SEMESTER 8 (12 Credits) ======
            ['course_code' => 'TI801', 'course_name' => 'Deep Learning', 'credits' => 3, 'semester' => 8, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI802', 'course_name' => 'Elective 2', 'credits' => 3, 'semester' => 8, 'study_program_id' => $studyProgramId],
            ['course_code' => 'TI803', 'course_name' => 'Thesis', 'credits' => 6, 'semester' => 8, 'study_program_id' => $studyProgramId],
        ];
    }

    private function convertToLetter(int $grade): string
    {
        return match (true) {
            $grade >= 85 => 'A',
            $grade >= 80 => 'A-',
            $grade >= 75 => 'B+',
            $grade >= 70 => 'B',
            $grade >= 65 => 'C+',
            $grade >= 60 => 'C',
            $grade >= 55 => 'D',
            default => 'E',
        };
    }
}
