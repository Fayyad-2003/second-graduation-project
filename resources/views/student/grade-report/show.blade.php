<x-app-layout>
    <x-slot name="header">
        <span class="md:hidden">{{ $academicYear->year }} {{ __($academicYear->semester) }}</span>
        <span class="hidden md:inline">{{ $academicYear->year }} - {{ __('Semester') }} {{ __($academicYear->semester) }}</span>
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('students.grade-report.index') }}" class="inline-flex items-center gap-2 text-primary-secondary hover:text-primary-primary transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            {{ __('Back to Grade List') }}
        </a>
    </div>

    <!-- Header Card -->
    <div class="rounded-2xl p-6 bg-[#1B3C53] text-white mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-xl md:text-2xl font-bold">{{ __('Study Result Card') }}</h2>
                <p class="opacity-80 mt-1 text-sm md:text-base">
                    <span class="md:hidden">{{ $academicYear->year }} {{ __($academicYear->semester) }}</span>
                    <span class="hidden md:inline">{{ $academicYear->year }} - {{ __('Semester') }} {{ __($academicYear->semester) }}</span>
                </p>
                <div class="mt-4 flex flex-col md:flex-row gap-4 md:items-center md:gap-6">
                    <div>
                        <p class="text-[10px] md:text-xs text-primary-secondary uppercase tracking-wider mb-0.5 md:mb-1">{{ __('Name') }}</p>
                        <p class="font-semibold text-sm md:text-base">{{ $student->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-xs text-primary-secondary uppercase tracking-wider mb-0.5 md:mb-1">{{ __('Student ID') }}</p>
                        <p class="font-semibold text-sm md:text-base">{{ $student->student_number }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-xs text-primary-secondary uppercase tracking-wider mb-0.5 md:mb-1">{{ __('Study Program') }}</p>
                        <p class="font-semibold text-sm md:text-base">{{ $student->studyProgram->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-2 md:mt-0 pt-4 md:pt-0 border-t border-white/10 md:border-0 md:text-right flex flex-row md:flex-col items-center md:items-end justify-between md:justify-start">
                <div>
                    <p class="text-xs opacity-60">{{ __('GPA this semester') }}</p>
                    <p class="text-4xl md:text-5xl font-bold">{{ number_format($semesterGpaData['semester_gpa'], 2) }}</p>
                </div>
                <div class="md:mt-1">
                    <p class="text-sm opacity-80 bg-white/10 px-3 py-1 rounded-lg">{{ $semesterGpaData['total_credits'] }} {{ __('Credits') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-8">
        <!-- Stats Cards -->
        <div class="card-saas p-5 text-center">
            <div class="w-12 h-12 rounded-xl bg-primary-primary/10 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold text-primary-dark">{{ number_format($semesterGpaData['semester_gpa'], 2) }}</p>
            <p class="text-sm text-primary-secondary">{{ __('GPA') }}</p>
        </div>
        <div class="card-saas p-5 text-center">
            <div class="w-12 h-12 rounded-xl bg-[#234C6A]/10 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-[#234C6A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold text-primary-dark">{{ number_format($cgpaData['gpa'], 2) }}</p>
            <p class="text-sm text-primary-secondary">{{ __('Cumulative GPA') }}</p>
        </div>
        <div class="card-saas p-5 text-center">
            <div class="w-12 h-12 rounded-xl bg-[#456882]/10 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-[#456882]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold text-primary-dark">{{ $gradeList->count() }}</p>
            <p class="text-sm text-primary-secondary">{{ __('Courses') }}</p>
        </div>
        <div class="card-saas p-5 text-center">
            <div class="w-12 h-12 rounded-xl bg-[#1B3C53]/10 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-[#1B3C53] dark:text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold text-primary-dark">{{ $semesterGpaData['total_credits'] }}</p>
            <p class="text-sm text-primary-secondary">{{ __('Total Credits') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grade Table -->
        <div class="lg:col-span-2">
            <div class="card-saas overflow-hidden">
                <div class="px-6 py-4 border-b border-primary-light flex items-center justify-between">
                    <h3 class="font-semibold text-primary-dark">{{ __('Grade List') }}</h3>
                    <a href="{{ route('students.export.grades', $academicYear) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-primary/10 text-primary-primary rounded-lg text-sm font-medium hover:bg-primary-primary/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ __('Export PDF') }}
                    </a>
                </div>

                @if($gradeList->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-primary-secondary">{{ __('No grades for this semester yet') }}</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full table-saas">
                        <thead>
                            <tr class="bg-[#234C6A] text-white">
                                <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider">{{ __('Code') }}</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider">{{ __('Courses') }}</th>
                                <th class="text-center px-6 py-3 text-xs font-semibold uppercase tracking-wider">{{ __('Credits') }}</th>
                                <th class="text-center px-6 py-3 text-xs font-semibold uppercase tracking-wider">{{ __('Grade') }}</th>
                                <th class="text-center px-6 py-3 text-xs font-semibold uppercase tracking-wider">{{ __('Letter') }}</th>
                                <th class="text-center px-6 py-3 text-xs font-semibold uppercase tracking-wider">{{ __('Weight') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary-light">
                            @foreach($gradeList as $grade)
                            @php
                            $mk = $grade->academicClass->course;
                            $gradeColor = match($grade->letter_grade) {
                            'A' => 'emerald',
                            'B+', 'B' => 'blue',
                            'C+', 'C' => 'amber',
                            default => 'red'
                            };
                            $weight = match($grade->letter_grade) {
                            'A' => 4.0,
                            'B+' => 3.5,
                            'B' => 3.0,
                            'C+' => 2.5,
                            'C' => 2.0,
                            'D' => 1.0,
                            default => 0
                            };
                            @endphp
                            <tr class="hover:bg-primary-light/20 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm bg-primary-light px-2 py-1 rounded text-primary-dark">{{ $mk->course_code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-primary-dark">{{ $mk->course_name }}</p>
                                    <p class="text-xs text-primary-secondary">{{ $grade->academicClass->lecturer->user->name ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center text-primary-secondary">{{ $mk->credits }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-primary-dark font-medium">{{ $grade->numeric_grade ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($grade->letter_grade)
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl font-bold text-lg bg-{{ $gradeColor }}-100 text-{{ $gradeColor }}-700">
                                        {{ $grade->letter_grade }}
                                    </span>
                                    @else
                                    <span class="text-primary-light text-xl">•</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-primary-secondary">{{ number_format($weight * $mk->credits, 1) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-[#234C6A] text-white font-semibold">
                                <td colspan="2" class="px-6 py-4 text-right">{{ __('Total') }}</td>
                                <td class="px-6 py-4 text-center">{{ $semesterGpaData['total_credits'] }}</td>
                                <td colspan="2" class="px-6 py-4 text-center">{{ __('GPA') }}</td>
                                <td class="px-6 py-4 text-center text-lg">{{ number_format($semesterGpaData['semester_gpa'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <!-- Grade Distribution -->
        <div>
            <div class="card-saas overflow-hidden">
                <div class="px-6 py-4 border-b border-primary-light">
                    <h3 class="font-semibold text-primary-dark">{{ __('Grade Distribution') }}</h3>
                </div>
                <div class="p-6 space-y-4">
                    @foreach(['A', 'B+', 'B', 'C+', 'C', 'D', 'E'] as $grade)
                    @php
                    $count = $gradeDistribution[$grade] ?? 0;
                    $percentage = $gradeList->count() > 0 ? ($count / $gradeList->count() * 100) : 0;
                    $gradeColor = match($grade) {
                    'A' => 'emerald',
                    'B+', 'B' => 'blue',
                    'C+', 'C' => 'amber',
                    default => 'red'
                    };
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-{{ $gradeColor }}-100 text-{{ $gradeColor }}-700 flex items-center justify-center font-bold text-sm">{{ $grade }}</span>
                        <div class="flex-1">
                            <div class="h-3 bg-primary-light rounded-full overflow-hidden">
                                <div class="h-full bg-{{ $gradeColor }}-500 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm text-primary-secondary w-6 text-right">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-primary-dark rounded-2xl p-6 text-white">
                <h3 class="font-semibold mb-4">{{ __('Quick Actions') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('students.transcript.index') }}" class="flex items-center gap-3 p-3 bg-white/10 rounded-lg hover:bg-white/20 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm">{{ __('View Transcript') }}</span>
                    </a>
                    <a href="{{ route('students.attendance.index') }}" class="flex items-center gap-3 p-3 bg-white/10 rounded-lg hover:bg-white/20 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span class="text-sm">{{ __('View Attendance') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>