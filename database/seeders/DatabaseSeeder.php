<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Faculty;
use App\Models\Grade;
use App\Models\Lecturer;
use App\Models\LecturerAttendance;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Report;
use App\Models\Room;
use App\Models\SemesterCalendar;
use App\Models\Student;
use App\Models\StudyPlan;
use App\Models\StudyPlanDetail;
use App\Models\StudyProgram;
use App\Models\SubjectClassification;
use App\Models\User;
use App\Models\Assignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key constraints for safe truncation
        Schema::disableForeignKeyConstraints();

        $tables = [
            'users',
            'faculties',
            'study_programs',
            'academic_years',
            'courses',
            'lecturers',
            'students',
            'classes',
            'course_schedules',
            'study_plans',
            'study_plan_details',
            'grades',
            'rooms',
            'subject_classifications',
            'meetings',
            'attendances',
            'course_prerequisites',
            'assignments',
            'notifications',
            'reports',
            'lecturer_attendances',
            'semester_calendars',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                \DB::table($table)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();

        $this->command->info('تم مسح البيانات القديمة بنجاح.');

        // 1. Roles and Classifications
        $this->call(RolePermissionSeeder::class);
        $this->call(SubjectClassificationSeeder::class);

        $classifications = SubjectClassification::all()->keyBy('slug');

        // 2. Superadmin
        User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@system.test',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // 3. Faculties and Study Programs
        $facultyEng = Faculty::create(['name' => 'كلية الهندسة']);
        $spComputerEng = StudyProgram::create([
            'name' => 'هندسة الحاسوب',
            'faculty_id' => $facultyEng->id,
        ]);

        // 4. Rooms
        for ($i = 1; $i <= 20; $i++) {
            Room::create([
                'room_code' => 'قاعة-' . $i,
                'room_name' => 'قاعة المحاضرات ' . $i,
                'capacity' => 45,
                'building' => 'مبنى ' . ($i <= 10 ? 'أ' : 'ب'),
                'floor' => ($i % 4) + 1,
            ]);
        }

        // 5. Academic Years (Past 6 years to cover 5 levels + current)
        $academicYears = [];
        $years = [2019, 2020, 2021, 2022, 2023, 2024];
        foreach ($years as $year) {
            $academicYears["$year-Odd"] = AcademicYear::create([
                'year' => "$year/" . ($year + 1),
                'semester' => 'الفصل الأول',
                'is_active' => false,
                'start_date' => "$year-09-01",
                'completion_date' => ($year + 1) . "-01-31",
            ]);
            $academicYears["$year-Even"] = AcademicYear::create([
                'year' => "$year/" . ($year + 1),
                'semester' => 'الفصل الثاني',
                'is_active' => ($year == 2024),
                'start_date' => ($year + 1) . "-02-01",
                'completion_date' => ($year + 1) . "-06-30",
            ]);
        }
        $activeYear = $academicYears["2024-Even"];

        // 6. Arabic Curriculum (Computer Engineering - 5 Years / 10 Semesters)
        $curriculum = $this->getArabicCurriculum($spComputerEng->id, $classifications);
        foreach ($curriculum as $courseData) {
            Course::create($courseData);
        }

        // Add Prerequisites
        $this->seedArabicPrerequisites();

        // 7. Lecturers (30 Lecturers)
        $lecturers = Lecturer::factory(30)->create([
            'study_program_id' => $spComputerEng->id
        ]);

        // 8. Students (75 Students across 5 levels)
        $students = collect();
        $batches = [2020, 2021, 2022, 2023, 2024];
        foreach ($batches as $batch) {
            $batchStudents = Student::factory(15)->create([
                'study_program_id' => $spComputerEng->id,
                'batch' => $batch,
                'academic_advisor_id' => $lecturers->random()->id,
            ]);
            $students = $students->merge($batchStudents);
        }

        // 9. Process Data
        $this->command->info('جاري إنشاء السجلات الأكاديمية والمهام والتقارير...');

        foreach ($students as $student) {
            $currentSemester = $this->calculateCurrentSemester($student->batch, $activeYear);

            for ($sem = 1; $sem <= $currentSemester; $sem++) {
                $isCurrentSemester = ($sem == $currentSemester);
                $ay = $this->getAcademicYearForSemester($student->batch, $sem, $academicYears);

                if (!$ay)
                    continue;

                $studyPlan = StudyPlan::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $ay->id,
                    'status' => 'approved',
                ]);

                $semesterCourses = Course::where('semester', $sem)
                    ->where('study_program_id', $spComputerEng->id)
                    ->get();

                foreach ($semesterCourses as $course) {
                    $class = AcademicClass::firstOrCreate(
                        [
                            'course_id' => $course->id,
                            'academic_year_id' => $ay->id,
                            'class_name' => 'مجموعة 1',
                        ],
                        [
                            'lecturer_id' => $lecturers->random()->id,
                            'capacity' => 50,
                        ]
                    );

                    StudyPlanDetail::create([
                        'study_plan_id' => $studyPlan->id,
                        'class_id' => $class->id,
                    ]);

                    if (!$isCurrentSemester) {
                        $numericGrade = rand(60, 98);
                        Grade::create([
                            'student_id' => $student->id,
                            'class_id' => $class->id,
                            'numeric_grade' => $numericGrade,
                            'letter_grade' => $this->convertToLetter($numericGrade),
                        ]);
                        $this->seedAcademicDetails($class, $student, 16, true);
                    } else {
                        $this->seedAcademicDetails($class, $student, 8, false);
                    }
                }
            }

            // Seed Notifications for each student
            Notification::factory(5)->create(['user_id' => $student->user_id]);

            // Seed Reports for each student
            Report::factory(2)->create(['user_id' => $student->user_id]);
        }

        // Seed Lecturer Attendances and Assignments
        foreach ($lecturers as $lecturer) {
            $classes = AcademicClass::where('lecturer_id', $lecturer->id)->get();
            foreach ($classes as $class) {
                $schedule = CourseSchedule::where('class_id', $class->id)->first();
                if ($schedule) {
                    $meetings = Meeting::where('course_schedule_id', $schedule->id)->get();
                    foreach ($meetings as $meeting) {
                        LecturerAttendance::create([
                            'lecturer_id' => $lecturer->id,
                            'course_schedule_id' => $schedule->id,
                            'meeting_id' => $meeting->id,
                            'date' => $meeting->date,
                            'entry_time' => '08:30:00',
                            'exit_time' => '10:30:00',
                            'status' => 'present',
                            'description' => 'حضور اعتيادي',
                        ]);
                    }
                }
            }
            // Seed Notifications and Reports for lecturers
            Notification::factory(3)->create(['user_id' => $lecturer->user_id]);
            Report::factory(1)->create(['user_id' => $lecturer->user_id]);
        }

        // 10. Seed Semester Calendars
        $this->command->info('جاري إنشاء تقويم الشعب الدراسية...');
        $this->seedSemesterCalendars($academicYears);

        $this->command->info('✅ تم إنشاء اعداد البيانات الشاملة باللغة العربية بنجاح!');
    }

    private function getArabicCurriculum($spId, $classifications): array
    {
        $data = [
            ['code' => 'CE101', 'name' => 'مقدمة في هندسة الحاسوب', 'credits' => 2, 'sem' => 1, 'class' => 'university-requirements'],
            ['code' => 'CE102', 'name' => 'حساب التفاضل والتكامل 1', 'credits' => 3, 'sem' => 1, 'class' => 'college-requirements'],
            ['code' => 'CE103', 'name' => 'الفيزياء العامة 1', 'credits' => 3, 'sem' => 1, 'class' => 'college-requirements'],
            ['code' => 'CE104', 'name' => 'الرياضيات المتقطعة', 'credits' => 3, 'sem' => 1, 'class' => 'specialization-requirements'],
            ['code' => 'CE105', 'name' => 'منطق البرمجة', 'credits' => 3, 'sem' => 1, 'class' => 'specialization-requirements'],
            ['code' => 'CE106', 'name' => 'اللغة الإنجليزية 1', 'credits' => 2, 'sem' => 1, 'class' => 'university-requirements'],
            ['code' => 'CE201', 'name' => 'حساب التفاضل والتكامل 2', 'credits' => 3, 'sem' => 2, 'class' => 'college-requirements'],
            ['code' => 'CE202', 'name' => 'الفيزياء العامة 2', 'credits' => 3, 'sem' => 2, 'class' => 'college-requirements'],
            ['code' => 'CE203', 'name' => 'الخوارزميات وهياكل البيانات', 'credits' => 4, 'sem' => 2, 'class' => 'specialization-requirements'],
            ['code' => 'CE204', 'name' => 'تصميم المنطق الرقمي', 'credits' => 3, 'sem' => 2, 'class' => 'specialization-requirements'],
            ['code' => 'CE205', 'name' => 'الجبر الخطي', 'credits' => 3, 'sem' => 2, 'class' => 'specialization-requirements'],
            ['code' => 'CE301', 'name' => 'البرمجة كائنية التوجه', 'credits' => 3, 'sem' => 3, 'class' => 'specialization-requirements'],
            ['code' => 'CE302', 'name' => 'تنظيم الحاسوب', 'credits' => 3, 'sem' => 3, 'class' => 'specialization-requirements'],
            ['code' => 'CE303', 'name' => 'الدوائر الكهربائية', 'credits' => 3, 'sem' => 3, 'class' => 'specialization-requirements'],
            ['code' => 'CE304', 'name' => 'نظم قواعد البيانات', 'credits' => 3, 'sem' => 3, 'class' => 'specialization-requirements'],
            ['code' => 'CE305', 'name' => 'إحصاء هندسي', 'credits' => 3, 'sem' => 3, 'class' => 'specialization-requirements'],
            ['code' => 'CE401', 'name' => 'أنظمة التشغيل', 'credits' => 3, 'sem' => 4, 'class' => 'specialization-requirements'],
            ['code' => 'CE402', 'name' => 'المعالجات الدقيقة', 'credits' => 4, 'sem' => 4, 'class' => 'specialization-requirements'],
            ['code' => 'CE403', 'name' => 'شبكات الحاسوب', 'credits' => 3, 'sem' => 4, 'class' => 'specialization-requirements'],
            ['code' => 'CE404', 'name' => 'الإشارات والأنظمة', 'credits' => 3, 'sem' => 4, 'class' => 'specialization-requirements'],
            ['code' => 'CE405', 'name' => 'مبادئ هندسة البرمجيات', 'credits' => 3, 'sem' => 4, 'class' => 'specialization-requirements'],
            ['code' => 'CE501', 'name' => 'تصميم الأنظمة المدمجة', 'credits' => 4, 'sem' => 5, 'class' => 'specialization-requirements'],
            ['code' => 'CE502', 'name' => 'الذكاء الاصطناعي', 'credits' => 3, 'sem' => 5, 'class' => 'specialization-requirements'],
            ['code' => 'CE503', 'name' => 'معمارية الحاسوب', 'credits' => 3, 'sem' => 5, 'class' => 'specialization-requirements'],
            ['code' => 'CE504', 'name' => 'تقنيات الويب', 'credits' => 3, 'sem' => 5, 'class' => 'specialization-requirements'],
            ['code' => 'CE505', 'name' => 'أساسيات الأمن السيبراني', 'credits' => 3, 'sem' => 5, 'class' => 'specialization-requirements'],
            ['code' => 'CE601', 'name' => 'معالجة الإشارات الرقمية', 'credits' => 3, 'sem' => 6, 'class' => 'specialization-requirements'],
            ['code' => 'CE602', 'name' => 'أنظمة الزمن الحقيقي', 'credits' => 3, 'sem' => 6, 'class' => 'specialization-requirements'],
            ['code' => 'CE603', 'name' => 'تطوير تطبيقات الموبايل', 'credits' => 3, 'sem' => 6, 'class' => 'specialization-requirements'],
            ['code' => 'CE604', 'name' => 'الحوسبة السحابية', 'credits' => 3, 'sem' => 6, 'class' => 'specialization-optional', 'is_elective' => true],
            ['code' => 'CE605', 'name' => 'تعلم الآلة', 'credits' => 3, 'sem' => 6, 'class' => 'specialization-optional', 'is_elective' => true],
            ['code' => 'CE701', 'name' => 'إنترنت الأشياء', 'credits' => 3, 'sem' => 7, 'class' => 'specialization-requirements'],
            ['code' => 'CE702', 'name' => 'أنظمة التحكم', 'credits' => 3, 'sem' => 7, 'class' => 'specialization-requirements'],
            ['code' => 'CE703', 'name' => 'إدارة مشاريع تكنولوجيا المعلومات', 'credits' => 2, 'sem' => 7, 'class' => 'specialization-requirements'],
            ['code' => 'CE704', 'name' => 'تصميم VLSI', 'credits' => 3, 'sem' => 7, 'class' => 'specialization-optional', 'is_elective' => true],
            ['code' => 'CE705', 'name' => 'تحليل البيانات الضخمة', 'credits' => 3, 'sem' => 7, 'class' => 'specialization-optional', 'is_elective' => true],
            ['code' => 'CE801', 'name' => 'هندسة البروتوكولات', 'credits' => 3, 'sem' => 8, 'class' => 'specialization-requirements'],
            ['code' => 'CE802', 'name' => 'الرؤية الحاسوبية', 'credits' => 3, 'sem' => 8, 'class' => 'specialization-requirements'],
            ['code' => 'CE803', 'name' => 'أمن الشبكات المتقدم', 'credits' => 3, 'sem' => 8, 'class' => 'specialization-requirements'],
            ['code' => 'CE804', 'name' => 'الريادة والابتكار', 'credits' => 2, 'sem' => 8, 'class' => 'university-requirements'],
            ['code' => 'CE805', 'name' => 'اختياري تخصص 2', 'credits' => 3, 'sem' => 8, 'class' => 'specialization-optional', 'is_elective' => true],
            ['code' => 'CE901', 'name' => 'مشروع التخرج 1', 'credits' => 2, 'sem' => 9, 'class' => 'specialization-requirements'],
            ['code' => 'CE902', 'name' => 'التدريب العملي', 'credits' => 3, 'sem' => 9, 'class' => 'specialization-requirements'],
            ['code' => 'CE903', 'name' => 'النظم الموزعة', 'credits' => 3, 'sem' => 9, 'class' => 'specialization-requirements'],
            ['code' => 'CE904', 'name' => 'معالجة اللغات الطبيعية', 'credits' => 3, 'sem' => 9, 'class' => 'specialization-optional', 'is_elective' => true],
            ['code' => 'CE1001', 'name' => 'مشروع التخرج 2', 'credits' => 4, 'sem' => 10, 'class' => 'specialization-requirements'],
            ['code' => 'CE1002', 'name' => 'أخلاقيات المهنة', 'credits' => 2, 'sem' => 10, 'class' => 'university-requirements'],
            ['code' => 'CE1003', 'name' => 'موضوعات مختارة في هندسة الحاسوب', 'credits' => 3, 'sem' => 10, 'class' => 'specialization-optional', 'is_elective' => true],
        ];

        $curriculum = [];
        foreach ($data as $d) {
            $curriculum[] = [
                'course_code' => $d['code'],
                'course_name' => $d['name'],
                'credits' => $d['credits'],
                'theory_credits' => $d['credits'],
                'semester' => $d['sem'],
                'study_program_id' => $spId,
                'subject_classification_id' => $classifications[$d['class']]->id ?? null,
                'is_elective' => $d['is_elective'] ?? false,
            ];
        }
        return $curriculum;
    }

    private function seedArabicPrerequisites(): void
    {
        $pairs = [
            ['CE102', 'CE201'],
            ['CE103', 'CE202'],
            ['CE105', 'CE203'],
            ['CE203', 'CE301'],
            ['CE204', 'CE302'],
            ['CE302', 'CE402'],
            ['CE402', 'CE501'],
            ['CE301', 'CE504'],
            ['CE401', 'CE602'],
            ['CE901', 'CE1001'],
        ];

        foreach ($pairs as $pair) {
            $course = Course::where('course_code', $pair[1])->first();
            $prereq = Course::where('course_code', $pair[0])->first();
            if ($course && $prereq) {
                $course->prerequisites()->attach($prereq->id);
            }
        }
    }

    private function calculateCurrentSemester(int $batch, AcademicYear $activeYear): int
    {
        $activeStartYear = (int) explode('/', $activeYear->year)[0];
        $semestersPassed = ($activeStartYear - $batch) * 2;
        if (Str::contains($activeYear->semester, 'الثاني')) {
            $semestersPassed += 2;
        } else {
            $semestersPassed += 1;
        }
        return max(1, min(10, $semestersPassed));
    }

    private function getAcademicYearForSemester(int $batch, int $semester, array $academicYears): ?AcademicYear
    {
        $yearOffset = (int) floor(($semester - 1) / 2);
        $targetYear = $batch + $yearOffset;
        $isOdd = ($semester % 2 != 0);
        $semKey = $isOdd ? "$targetYear-Odd" : "$targetYear-Even";
        return $academicYears[$semKey] ?? null;
    }

    private function seedAcademicDetails(AcademicClass $class, Student $student, int $limit, bool $isPast): void
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $schedule = CourseSchedule::firstOrCreate(
            ['class_id' => $class->id],
            [
                'day' => $days[rand(0, 4)],
                'start_time' => '08:30',
                'end_time' => '10:30',
                'room' => Room::all()->random()->room_name,
            ]
        );

        if ($limit > 0) {
            Assignment::firstOrCreate(
                ['class_id' => $class->id, 'title' => 'الواجب الأول لـ ' . $class->course->course_name],
                [
                    'description' => 'يرجى تقديم الحل بصيغة PDF',
                    'deadline' => $isPast ? now()->subMonths(1) : now()->addWeeks(2),
                    'max_file_size' => 2048,
                    'allowed_extensions' => 'pdf,doc',
                    'is_active' => true,
                ]
            );
        }

        for ($i = 1; $i <= $limit; $i++) {
            $meeting = Meeting::firstOrCreate(
                [
                    'course_schedule_id' => $schedule->id,
                    'meeting_number' => $i,
                ],
                [
                    'date' => now()->subDays((16 - $i) * 7),
                    'topic' => "موضوع المحاضرة رقم $i لمساق " . $class->course->course_name,
                    'status' => 'completed',
                ]
            );

            Attendance::firstOrCreate(
                ['meeting_id' => $meeting->id, 'student_id' => $student->id],
                [
                    'status' => rand(0, 10) > 1 ? 'present' : (rand(0, 1) ? 'sick' : 'absent'),
                    'attendance_time' => '08:35',
                ]
            );
        }
    }

    private function seedSemesterCalendars(array $academicYears): void
    {
        $calendarEvents = [
            ['week' => 1, 'title' => 'بداية الفصل الدراسي', 'type' => 'academic', 'days_from_start' => 0],
            ['week' => 2, 'title' => 'آخر يوم لتسجيل الخطة', 'type' => 'academic', 'days_from_start' => 7],
            ['week' => 4, 'title' => 'إجازة عيد الفطر', 'type' => 'holiday', 'days_from_start' => 21],
            ['week' => 8, 'title' => 'امتحانات منتصف الفصل', 'type' => 'exam', 'days_from_start' => 50],
            ['week' => 12, 'title' => 'إجازة عيد الأضحى', 'type' => 'holiday', 'days_from_start' => 80],
            ['week' => 15, 'title' => 'بداية الامتحانات النهائية', 'type' => 'exam', 'days_from_start' => 100],
            ['week' => 16, 'title' => 'نهاية الفصل الدراسي', 'type' => 'academic', 'days_from_start' => 110],
            ['week' => 3, 'title' => 'يوم التأسيس', 'type' => 'national', 'days_from_start' => 14],
            ['week' => 10, 'title' => 'يوم استقلال', 'type' => 'national', 'days_from_start' => 63],
            ['week' => 6, 'title' => 'نشاط طلابي', 'type' => 'event', 'days_from_start' => 35],
        ];

        foreach ($academicYears as $key => $ay) {
            foreach ($calendarEvents as $event) {
                SemesterCalendar::create([
                    'academic_year_id' => $ay->id,
                    'week_number' => $event['week'],
                    'date' => \Carbon\Carbon::parse($ay->start_date)->addDays($event['days_from_start'])->format('Y-m-d'),
                    'title' => $event['title'],
                    'description' => 'تفاصيل عن الحدث في تقويم الفصل الدراسي',
                    'type' => $event['type'],
                    'is_active' => true,
                ]);
            }
        }
    }

    private function convertToLetter(int $grade): string
    {
        return match (true) {
            $grade >= 90 => 'A',
            $grade >= 85 => 'B+',
            $grade >= 80 => 'B',
            $grade >= 75 => 'C+',
            $grade >= 70 => 'C',
            $grade >= 65 => 'D+',
            $grade >= 60 => 'D',
            default => 'F',
        };
    }
}
