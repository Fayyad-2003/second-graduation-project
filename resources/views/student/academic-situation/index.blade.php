<x-app-layout>
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-siakad-900 dark:text-white">{{ __('Academic Situation') }}</h1>
                <p class="text-siakad-500 font-medium mt-2 flex items-center gap-2">
                    <span class="w-8 h-px bg-siakad-200"></span>
                    {{ __('Comprehensive overview of your academic performance, attendance, and assignments.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Situation Report Card -->
    <div class="card-saas mb-10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-siakad-500/10 to-purple-500/10 rounded-full -mr-20 -mt-20 blur-3xl transition-all duration-500 group-hover:scale-125"></div>
        <div class="relative p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <span class="text-xs font-black uppercase tracking-widest text-siakad-400">{{ __('Your Situation') }}</span>
                    <h2 class="text-2xl font-black text-siakad-900 dark:text-white mt-1">{{ $reportSummary['status_text'] }}</h2>
                </div>
                @php
                $statusColors = [
                    'excellent' => 'bg-gradient-to-r from-emerald-500 to-emerald-600',
                    'good' => 'bg-gradient-to-r from-siakad-500 to-siakad-600',
                    'fair' => 'bg-gradient-to-r from-amber-500 to-amber-600',
                    'needs_attention' => 'bg-gradient-to-r from-red-500 to-red-600',
                ];
                @endphp
                <div class="w-20 h-20 {{ $statusColors[$reportSummary['status']] }} rounded-[26px] flex items-center justify-center shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($reportSummary['status'] === 'excellent')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @elseif($reportSummary['status'] === 'good')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @elseif($reportSummary['status'] === 'fair')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        @endif
                    </svg>
                </div>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-siakad-50/50 dark:bg-siakad-900/20 p-4 rounded-xl border border-siakad-100/50 dark:border-siakad-800/50">
                    <p class="text-xs text-siakad-400 font-bold uppercase tracking-widest mb-1">{{ __('GPA') }}</p>
                    <p class="text-2xl font-black text-siakad-900 dark:text-white">{{ number_format($reportSummary['gpa'], 2) }}</p>
                </div>
                <div class="bg-siakad-50/50 dark:bg-siakad-900/20 p-4 rounded-xl border border-siakad-100/50 dark:border-siakad-800/50">
                    <p class="text-xs text-siakad-400 font-bold uppercase tracking-widest mb-1">{{ __('Credits') }}</p>
                    <p class="text-2xl font-black text-siakad-900 dark:text-white">{{ $reportSummary['total_credits'] }}</p>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-100/50 dark:border-emerald-800/50">
                    <p class="text-xs text-emerald-500 font-bold uppercase tracking-widest mb-1">{{ __('Passed') }}</p>
                    <p class="text-2xl font-black text-emerald-700 dark:text-emerald-400">{{ $reportSummary['passed_subjects'] }}</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-xl border border-red-100/50 dark:border-red-800/50">
                    <p class="text-xs text-red-500 font-bold uppercase tracking-widest mb-1">{{ __('Failed') }}</p>
                    <p class="text-2xl font-black text-red-700 dark:text-red-400">{{ $reportSummary['failed_subjects'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <!-- Attendance Rate -->
        <div class="card-saas p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-siakad-50 dark:bg-siakad-900/50 rounded-lg">
                    <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-black text-siakad-900 dark:text-white">{{ __('Overall Attendance') }}</h3>
            </div>
            <div class="text-center">
                <p class="text-5xl font-black text-siakad-900 dark:text-white">{{ $reportSummary['attendance_rate'] }}%</p>
                <div class="w-full bg-siakad-50 dark:bg-siakad-900/30 rounded-full h-4 mt-4 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-siakad-500 to-siakad-600 rounded-full" style="width: {{ $reportSummary['attendance_rate'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Assignment Submission Rate -->
        <div class="card-saas p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-siakad-50 dark:bg-siakad-900/50 rounded-lg">
                    <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2zm-6 4h6m-6 4h6m-6 4h6"></path>
                    </svg>
                </div>
                <h3 class="text-base font-black text-siakad-900 dark:text-white">{{ __('Assignment Submission') }}</h3>
            </div>
            <div class="text-center">
                <p class="text-5xl font-black text-siakad-900 dark:text-white">{{ $reportSummary['submission_rate'] }}%</p>
                <div class="w-full bg-siakad-50 dark:bg-siakad-900/30 rounded-full h-4 mt-4 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full" style="width: {{ $reportSummary['submission_rate'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Semester Classes -->
    @if($currentClasses->count() > 0)
    <div class="card-saas p-8 mb-10">
        <div class="flex items-center gap-3 mb-8">
            <div class="p-2 bg-siakad-50 dark:bg-siakad-900/50 rounded-lg">
                <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-black text-siakad-900 dark:text-white">{{ __('Current Semester Classes') }}</h3>
                <p class="text-sm text-siakad-400 mt-1">{{ $activeAcademicYear ? $activeAcademicYear->year . ' ' . ucfirst(__($activeAcademicYear->semester)) : '' }}</p>
            </div>
        </div>
        <div class="space-y-4">
            @foreach($currentClasses as $class)
            @php
            $attendanceSummary = $classAttendanceSummaries->get($class->id);
            $assignmentSummary = $classAssignmentSummaries->get($class->id);
            @endphp
            <div class="bg-siakad-50/50 dark:bg-siakad-900/20 p-6 rounded-2xl border border-siakad-100/50 dark:border-siakad-800/50">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-siakad-100 dark:bg-siakad-900/50 rounded-xl flex items-center justify-center">
                            <span class="text-xs font-black text-siakad-700 dark:text-siakad-300">{{ $class->course->course_code }}</span>
                        </div>
                        <div>
                            <h4 class="font-black text-siakad-900 dark:text-white">{{ $class->course->course_name }}</h4>
                            <p class="text-sm text-siakad-500 dark:text-slate-400">{{ $class->lecturer->user->name ?? '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-black text-siakad-400 uppercase tracking-wider mb-2">{{ __('Attendance') }}</p>
                        <div class="flex items-center gap-3">
                            <p class="text-sm font-black text-siakad-900 dark:text-white">{{ $attendanceSummary['present'] }} <span class="text-siakad-400 font-medium">/{{ $attendanceSummary['total_meetings'] }}</span></p>
                            <div class="flex-1 bg-siakad-100 dark:bg-siakad-800 rounded-full h-2 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full" style="width: {{ $attendanceSummary['total_meetings'] > 0 ? ($attendanceSummary['present'] / $attendanceSummary['total_meetings']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-black text-siakad-400 uppercase tracking-wider mb-2">{{ __('Assignments') }}</p>
                        <div class="flex items-center gap-3">
                            <p class="text-sm font-black text-siakad-900 dark:text-white">{{ $assignmentSummary['submitted'] }} <span class="text-siakad-400 font-medium">/{{ $assignmentSummary['total'] }}</span></p>
                            <div class="flex-1 bg-siakad-100 dark:bg-siakad-800 rounded-full h-2 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full" style="width: {{ $assignmentSummary['total'] > 0 ? ($assignmentSummary['submitted'] / $assignmentSummary['total']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Passed and Failed Subjects -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Passed Subjects -->
        <div class="card-saas p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-black text-siakad-900 dark:text-white">{{ __('Passed Subjects') }} ({{ $passedSubjects->count() }})</h3>
            </div>
            @if($passedSubjects->count() > 0)
            <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                @foreach($passedSubjects as $grade)
                <div class="flex items-center justify-between bg-siakad-50/50 dark:bg-siakad-900/20 p-4 rounded-xl border border-siakad-100/50 dark:border-siakad-800/50">
                    <div>
                        <p class="font-black text-siakad-900 dark:text-white text-sm">{{ $grade->academicClass->course->course_name }}</p>
                        <p class="text-xs text-siakad-500 dark:text-slate-400">{{ $grade->academicClass->academicYear->year ?? '' }} {{ ucfirst(__($grade->academicClass->academicYear->semester ?? '')) }}</p>
                    </div>
                    <span class="inline-flex px-3 py-1 text-xs font-black uppercase tracking-widest rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50">{{ $grade->letter_grade }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-siakad-400 text-sm">{{ __('No passed subjects yet.') }}</p>
            @endif
        </div>

        <!-- Failed Subjects -->
        <div class="card-saas p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-red-50 dark:bg-red-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-black text-siakad-900 dark:text-white">{{ __('Failed Subjects') }} ({{ $failedSubjects->count() }})</h3>
            </div>
            @if($failedSubjects->count() > 0)
            <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                @foreach($failedSubjects as $grade)
                <div class="flex items-center justify-between bg-siakad-50/50 dark:bg-siakad-900/20 p-4 rounded-xl border border-siakad-100/50 dark:border-siakad-800/50">
                    <div>
                        <p class="font-black text-siakad-900 dark:text-white text-sm">{{ $grade->academicClass->course->course_name }}</p>
                        <p class="text-xs text-siakad-500 dark:text-slate-400">{{ $grade->academicClass->academicYear->year ?? '' }} {{ ucfirst(__($grade->academicClass->academicYear->semester ?? '')) }}</p>
                    </div>
                    <span class="inline-flex px-3 py-1 text-xs font-black uppercase tracking-widest rounded-full bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-100 dark:border-red-800/50">{{ $grade->letter_grade }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-siakad-400 text-sm">{{ __('Great! No failed subjects.') }}</p>
            @endif
        </div>
    </div>
</x-app-layout>
