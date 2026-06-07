<x-app-layout>
    <x-slot name="header">
        {{ __('Welcome, :name! 👋', ['name' => Auth::user()->name]) }}
    </x-slot>

    <!-- Stats Cards -->
    @if(Auth::user()->role === 'admin')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('Total Faculties') }}</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ \App\Models\Faculty::count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('Total Study Programs') }}</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ \App\Models\StudyProgram::count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('Courses') }}</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ \App\Models\Course::count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('Active Classes') }}</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ \App\Models\AcademicClass::count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <h3 class="text-lg font-semibold text-slate-800 mb-4">{{ __('Quick Actions') }}</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ url('admin/faculty') }}" class="group flex items-center gap-4 p-4 bg-white rounded-xl border border-slate-200 hover:border-indigo-300 hover:shadow-lg transition-all duration-300">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 group-hover:bg-indigo-500 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-800">{{ __('Add Faculty') }}</p>
                <p class="text-sm text-slate-500">{{ __('Manage faculty data') }}</p>
            </div>
        </a>
        <a href="{{ url('admin/study-program') }}" class="group flex items-center gap-4 p-4 bg-white rounded-xl border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 group-hover:bg-emerald-500 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-800">{{ __('Add Study Program') }}</p>
                <p class="text-sm text-slate-500">{{ __('Manage study programs') }}</p>
            </div>
        </a>
        <a href="{{ url('admin/course') }}" class="group flex items-center gap-4 p-4 bg-white rounded-xl border border-slate-200 hover:border-amber-300 hover:shadow-lg transition-all duration-300">
            <div class="w-10 h-10 rounded-lg bg-amber-100 group-hover:bg-amber-500 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-amber-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-800">{{ __('Add Course') }}</p>
                <p class="text-sm text-slate-500">{{ __('Manage courses') }}</p>
            </div>
        </a>
        <a href="{{ url('admin/class') }}" class="group flex items-center gap-4 p-4 bg-white rounded-xl border border-slate-200 hover:border-rose-300 hover:shadow-lg transition-all duration-300">
            <div class="w-10 h-10 rounded-lg bg-rose-100 group-hover:bg-rose-500 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-rose-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-800">{{ __('Create Class') }}</p>
                <p class="text-sm text-slate-500">{{ __('Lecture scheduling') }}</p>
            </div>
        </a>
    </div>
    @endif

    @if(Auth::user()->role === 'student')
    @php
    $student = Auth::user()->student;
    $studyPlan = $student ? \App\Models\StudyPlan::where('student_id', $student->id)->latest()->first() : null;
    $totalCredits = $studyPlan ? $studyPlan->details->sum(fn($d) => $d->academicClass->course->credits) : 0;
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-8 text-white">
                <h3 class="text-lg font-medium opacity-90">{{ __('Active Semester') }}</h3>
                <p class="text-3xl font-bold mt-2">{{ \App\Models\AcademicYear::where('is_active', true)->first()?->year ?? '-' }}</p>
                <p class="opacity-80 mt-1">{{ \App\Models\AcademicYear::where('is_active', true)->first()?->semester ?? '-' }}</p>

                <div class="mt-6 flex items-center gap-6">
                    <div>
                        <p class="text-sm opacity-80">{{ __('Total Credits') }}</p>
                        <p class="text-2xl font-bold">{{ $totalCredits }}</p>
                    </div>
                    <div>
                        <p class="text-sm opacity-80">{{ __('Study Plan Status') }}</p>
                        <p class="text-2xl font-bold capitalize">{{ $studyPlan?->status ?? __('None') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ url('student/study-plan') }}" class="block h-full bg-white rounded-2xl p-6 border border-slate-200 hover:border-indigo-300 hover:shadow-xl transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-indigo-100 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">{{ __('Study Plan') }}</h3>
                <p class="text-sm text-slate-500 mt-1">{{ __('Fill and manage the study plan for this semester') }}</p>
                <div class="mt-4 flex items-center text-indigo-600 font-medium">
                    <span>{{ __('Open Plan') }}</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>
    @endif

    @if(Auth::user()->role === 'lecturer')
    @php
    $lecturer = Auth::user()->lecturer;
    $classCount = $lecturer ? $lecturer->classes()->count() : 0;
    $studentCount = $lecturer ? \App\Models\StudyPlanDetail::whereIn('class_id', $lecturer->classes()->pluck('id'))->count() : 0;
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="stat-card bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('Taught Classes') }}</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{
$classCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('Total Students') }}</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $studentCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <a href="{{ url('lecturer/grading') }}" class="stat-card bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">{{ __('Grading') }}</p>
                    <p class="text-xl font-bold mt-1">{{ __('Click to open ←') }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>
        </a>
    </div>
    @endif

</x-app-layout>