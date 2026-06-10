<x-app-layout>
    <x-slot name="header">{{ __('Dashboard') }}</x-slot>

    <!-- Upcoming Events Section -->
    <div class="mb-8 card-saas p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-br from-siakad-500/10 to-purple-500/10 rounded-full -mr-24 -mt-24 blur-3xl transition-all duration-500 group-hover:scale-125"></div>
        <div class="relative flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-siakad-100 to-purple-100 dark:from-siakad-900/30 dark:to-purple-900/30 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-siakad-600 dark:text-siakad-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-gray-900 dark:text-white text-xl">{{ __('Upcoming Events') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 font-medium mt-1 text-sm">{{ __('Important dates and campus activities') }}</p>
                </div>
            </div>
            <a href="{{ route('student.semester-calendar.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-siakad-500 to-purple-600 text-white hover:shadow-lg hover:scale-105 transition-all duration-300">
                {{ __('View Calendar') }}
            </a>
        </div>
        @if($upcomingEvents->isEmpty())
            <div class="text-center py-10">
                <p class="text-gray-500 dark:text-gray-400 font-medium">{{ __('No upcoming events') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($upcomingEvents as $event)
                    <div class="p-5 bg-white dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700/50 hover:shadow-soft-lg hover:-translate-y-1 transition-all duration-300 group/event">
                        <div class="flex items-start justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-siakad-500">
                                {{ $event->type }}
                            </span>
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold 
                                {{ $event->type === 'holiday' ? 'bg-amber-100 text-amber-700' : 
                                   ($event->type === 'exam' ? 'bg-red-100 text-red-700' : 'bg-siakad-100 text-siakad-700') }}">
                                {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y') }}
                            </span>
                        </div>
                        <h4 class="font-black text-gray-900 dark:text-white">{{ $event->title }}</h4>
                        @if($event->description)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $event->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- GPA Warning Banner -->
    @if($student && isset($gpaWarningLevel) && $gpaWarningLevel)
    <div class="mb-8 rounded-2xl p-6 flex items-start gap-4 shadow-soft-lg {{ $gpaWarningLevel === 'danger' ? 'bg-gradient-to-r from-red-50 to-rose-50 border border-red-100 dark:from-red-950/30 dark:to-rose-950/30 dark:border-red-900/50' : 'bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 dark:from-amber-950/30 dark:to-orange-950/30 dark:border-amber-900/50' }}">
        <div class="flex-shrink-0 p-3 rounded-xl {{ $gpaWarningLevel === 'danger' ? 'bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400' : 'bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400' }}">
            @if($gpaWarningLevel === 'danger')
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            @else
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            @endif
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-base {{ $gpaWarningLevel === 'danger' ? 'text-red-900 dark:text-red-200' : 'text-amber-900 dark:text-amber-200' }}">
                {{ $gpaWarningLevel === 'danger' ? __('Academic Probation Warning') : __('Academic Performance Notice') }}
            </h4>
            <p class="text-sm mt-1 leading-relaxed {{ $gpaWarningLevel === 'danger' ? 'text-red-700 dark:text-red-400/90' : 'text-amber-700 dark:text-amber-400/90' }}">
                @if($gpaWarningLevel === 'danger')
                {{ __('Your cumulative GPA is :gpa, which is below :threshold. You are at risk of academic probation. Please consult your academic advisor immediately.', ['gpa' => number_format($gpaData['gpa'], 2), 'threshold' => number_format(config('siakad.gpa_warning.danger'), 2)]) }}
                @else
                {{ __('Your cumulative GPA is :gpa, which is below :threshold. Please take steps to improve your academic performance.', ['gpa' => number_format($gpaData['gpa'], 2), 'threshold' => number_format(config('siakad.gpa_warning.caution'), 2)]) }}
                @endif
            </p>
            <div class="flex items-center gap-4 mt-4">
                <a href="{{ route('students.grade-report.index') }}" class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 {{ $gpaWarningLevel === 'danger' ? 'bg-red-600 text-white hover:bg-red-700 shadow-soft shadow-red-600/20' : 'bg-amber-600 text-white hover:bg-amber-700 shadow-soft shadow-amber-600/20' }}">
                    {{ __('View Grade Report') }}
                </a>
                <a href="{{ route('students.profile.index') }}" class="text-sm font-bold {{ $gpaWarningLevel === 'danger' ? 'text-red-600 dark:text-red-400 hover:underline' : 'text-amber-600 dark:text-amber-400 hover:underline' }}">
                    {{ __('Contact Advisor') }}
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Greeting -->
    <div class="mb-10 hidden md:block relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-siakad-500/5 via-purple-500/5 to-pink-500/5 rounded-3xl -z-10"></div>
        <div class="flex items-center justify-between p-6">
            <div>
                <h1 class="text-4xl font-black tracking-tight text-gray-900 dark:text-white">
                    {{ $greeting }}, <span class="text-transparent bg-clip-text bg-gradient-to-r from-siakad-600 via-purple-600 to-pink-600 animate-gradient">{{ explode(' ', $user->name)[0] }}</span>!
                    @php
                    $hour = now()->hour;
                    if ($hour < 11) $emoji='🌅' ;
                        elseif ($hour < 15) $emoji='☀️' ;
                        elseif ($hour < 18) $emoji='🌤️' ;
                        else $emoji='🌙' ;
                    @endphp
                    <span class="inline-block animate-bounce-subtle">{{ $emoji }}</span>
                </h1>
                <p class="text-gray-600 dark:text-gray-400 font-medium mt-3 flex items-center gap-3">
                    <span class="w-12 h-0.5 bg-gradient-to-r from-siakad-500 to-purple-500 rounded-full"></span>
                    {{ __('Welcome back to your academic portal.') }}
                </p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden xl:block">
                    <p class="text-xs font-bold text-siakad-500 uppercase tracking-widest">{{ now()->translatedFormat('l') }}</p>
                    <p class="text-lg font-black bg-gradient-to-r from-siakad-600 to-purple-600 bg-clip-text text-transparent">{{ now()->translatedFormat('d F Y') }}</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-siakad-500 to-purple-600 rounded-[20px] shadow-lg shadow-siakad-500/30">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Profile & IPK Card -->
        <div class="card-saas p-8 relative overflow-hidden group">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-siakad-500/10 to-purple-500/10 rounded-full -mr-20 -mt-20 blur-2xl transition-all duration-500 group-hover:scale-125"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-pink-500/10 to-siakad-500/10 rounded-full -ml-16 -mb-16 blur-2xl transition-all duration-500 group-hover:scale-125"></div>

            <div class="relative flex items-center gap-5 mb-8">
                <div class="relative group/avatar">
                    <div class="w-20 h-20 rounded-[22px] bg-gradient-to-br from-siakad-500 via-purple-600 to-pink-600 flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-siakad-500/40 transition-all duration-300 group-hover/avatar:scale-105 group-hover/avatar:rotate-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-gradient-to-br from-emerald-400 to-emerald-600 border-4 border-white dark:border-gray-800 rounded-full shadow-lg"></div>
                </div>
                <div>
                    <h3 class="font-black text-gray-900 dark:text-white text-xl tracking-tight">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold font-mono tracking-wider mt-1 px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full inline-block">{{ $student->student_number ?? '-' }}</p>
                </div>
            </div>

            <div class="relative grid grid-cols-2 gap-5">
                <!-- IPK Card -->
                <div class="bg-gradient-to-br from-siakad-600 via-siakad-700 to-purple-700 rounded-[20px] p-6 text-white shadow-2xl shadow-siakad-600/30 transform transition-all duration-300 hover:scale-105 hover:-rotate-1 relative overflow-hidden group/ipk">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover/ipk:opacity-100 transition-opacity"></div>
                    <p class="text-[10px] font-black opacity-80 uppercase tracking-widest mb-2 relative z-10">{{ __('GPA') }}</p>
                    <div class="flex items-baseline gap-2 relative z-10">
                        <p class="text-4xl font-black">{{ $gpaData ? number_format($gpaData['gpa'], 2) : '-' }}</p>
                        <p class="text-xs font-bold opacity-70">/ 4.00</p>
                    </div>
                </div>

                <!-- IPS Card -->
                <div class="bg-white dark:bg-gray-800/50 rounded-[20px] p-6 border-2 border-gray-200 dark:border-gray-700 shadow-lg transform transition-all duration-300 hover:scale-105 hover:rotate-1 hover:border-siakad-500 relative overflow-hidden group/ips">
                    <div class="absolute inset-0 bg-gradient-to-br from-siakad-500/5 to-purple-500/5 opacity-0 group-hover/ips:opacity-100 transition-opacity"></div>
                    <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 relative z-10">{{ __('Semester IP') }}</p>
                    <p class="text-4xl font-black bg-gradient-to-r from-siakad-600 to-purple-600 bg-clip-text text-transparent relative z-10">{{ $currentSemesterGpa ? number_format($currentSemesterGpa['gpa'], 2) : '-' }}</p>
                </div>
            </div>

            <div class="relative mt-8 pt-6 border-t border-gray-200 dark:border-gray-700/50 space-y-4">
                <div class="flex items-center justify-between group/stat">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-gradient-to-r from-siakad-500 to-purple-500"></div>
                        <span class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('Credits Passed') }}</span>
                    </div>
                    <span class="text-base font-black text-gray-900 dark:text-white px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full">{{ $gpaData['total_credits'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between group/stat">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-gradient-to-r from-purple-500 to-pink-500"></div>
                        <span class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('Max SKS') }}</span>
                    </div>
                    <span class="text-base font-black bg-gradient-to-r from-siakad-600 to-purple-600 bg-clip-text text-transparent px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full">{{ $maxCredits }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Action Cards -->
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Pengisian KRS -->
            <a href="{{ route('students.study-plan.index') }}"
                class="card-saas p-8 group relative overflow-hidden transition-all duration-500 hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute inset-0 bg-gradient-to-br from-siakad-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="absolute top-0 right-0 p-4">
                    @if($currentStudyPlan && $currentStudyPlan->status === 'draft')
                    <span class="px-3 py-1.5 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">{{ __('Draft') }}</span>
                    @elseif(!$currentStudyPlan)
                    <span class="px-3 py-1.5 bg-gradient-to-r from-siakad-500 to-purple-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">{{ __('Open') }}</span>
                    @endif
                </div>
                <div class="relative w-16 h-16 bg-gradient-to-br from-siakad-100 to-purple-100 dark:from-siakad-900/30 dark:to-purple-900/30 rounded-[20px] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                    <svg class="w-8 h-8 text-siakad-600 dark:text-siakad-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="relative font-black text-gray-900 dark:text-white text-xl mb-2">{{ __('Fill Study Plan') }}</h3>
                <p class="relative text-sm text-gray-600 dark:text-gray-400 font-semibold">{{ $activeAcademicYear?->year ?? '-' }} <span class="opacity-40 mx-2">•</span> {{ $activeAcademicYear?->semester ?? '' }}</p>
            </a>

            <!-- Perkuliahan -->
            <a href="{{ route('students.schedule.index') }}"
                class="card-saas p-8 group relative overflow-hidden transition-all duration-500 hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative w-16 h-16 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-[20px] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="relative font-black text-gray-900 dark:text-white text-xl mb-2">{{ __('My Schedule') }}</h3>
                <p class="relative text-sm text-gray-600 dark:text-gray-400 font-semibold">{{ __('View daily lecture routines') }}</p>
            </a>

            <!-- Riwayat Kuliah -->
            <a href="{{ route('students.transcript.index') }}"
                class="card-saas p-8 group relative overflow-hidden transition-all duration-500 hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative w-16 h-16 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 rounded-[20px] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="relative font-black text-gray-900 dark:text-white text-xl mb-2">{{ __('Academic Transcript') }}</h3>
                <p class="relative text-sm text-gray-600 dark:text-gray-400 font-semibold">{{ __('Complete grade history') }}</p>
            </a>

            <!-- Biodata -->
            <a href="{{ route('students.profile.index') }}"
                class="card-saas p-8 group relative overflow-hidden transition-all duration-500 hover:shadow-2xl hover:scale-[1.02]">
                <div class="absolute inset-0 bg-gradient-to-br from-pink-500/5 to-rose-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative w-16 h-16 bg-gradient-to-br from-pink-100 to-rose-100 dark:from-pink-900/30 dark:to-rose-900/30 rounded-[20px] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500">
                    <svg class="w-8 h-8 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="relative font-black text-gray-900 dark:text-white text-xl mb-2">{{ __('Student Profile') }}</h3>
                <p class="relative text-sm text-gray-600 dark:text-gray-400 font-semibold">{{ __('Update personal information') }}</p>
            </a>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Credits per Semester Chart -->
        <div class="card-saas p-8 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-siakad-500/10 to-purple-500/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-black text-gray-900 dark:text-white text-lg">{{ __('Credits per Semester') }}</h3>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-siakad-100 to-purple-100 dark:from-siakad-900/30 dark:to-purple-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-siakad-600 dark:text-siakad-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="h-56 relative">
                <canvas id="creditsChart"></canvas>
            </div>
        </div>

        <!-- IPS Progression Chart -->
        <div class="card-saas p-8 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-black text-gray-900 dark:text-white text-lg">{{ __('Academic Progress - IP') }}</h3>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="h-56 relative">
                <canvas id="semesterGpaChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Lecturer PA Info -->
    @if($student->academicAdvisor)
    <div class="mt-8 card-saas p-8 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
        <div class="flex items-start gap-5">
            <div class="relative">
                <div class="w-16 h-16 rounded-[18px] bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-emerald-500/30">
                    {{ strtoupper(substr($student->academicAdvisor->user->name ?? 'D', 0, 1)) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-gradient-to-br from-blue-400 to-blue-600 border-4 border-white dark:border-gray-800 rounded-full"></div>
            </div>
            <div class="flex-1">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('Academic Advisor') }}</p>
                <p class="font-black text-gray-900 dark:text-white text-lg">{{ $student->academicAdvisor->user->name ?? '-' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold mt-1">{{ $student->academicAdvisor->lecturer_number ?? '-' }}</p>
            </div>
            <a href="{{ route('students.profile.index') }}" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:scale-105 transition-all duration-300">
                {{ __('Contact') }}
            </a>
        </div>
    </div>
    @endif

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Modern SIAKAD Colors
        const siakadPrimary = '#6366f1';
        const siakadSecondary = '#8b5cf6';
        const purpleGradient = ['#6366f1', '#8b5cf6', '#a855f7', '#c084fc'];

        // Detect dark mode
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#312e81' : '#e5e7eb';
        const textColor = isDark ? '#9ca3af' : '#6b7280';

        // Credits Chart
        const creditsDataHistory = @json($gpaHistory);
        const creditsCtx = document.getElementById('creditsChart').getContext('2d');
        new Chart(creditsCtx, {
            type: 'bar',
            data: {
                labels: creditsDataHistory.map(d => d.academic_year.substring(0, 9)),
                datasets: [{
                    label: 'Credits',
                    data: creditsDataHistory.map(d => d.total_credits),
                    backgroundColor: purpleGradient,
                    borderRadius: 10,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#1e1b3e' : '#ffffff',
                        titleColor: isDark ? '#f3f4f6' : '#111827',
                        bodyColor: isDark ? '#9ca3af' : '#6b7280',
                        borderColor: isDark ? '#312e81' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        cornerRadius: 12,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 24,
                        grid: {
                            color: gridColor,
                            drawBorder: false,
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                weight: '600'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });

        // IPS Chart with Gradient
        const gpaDataHistory = @json($gpaHistory);
        const ipsCtx = document.getElementById('semesterGpaChart').getContext('2d');
        
        const gradient = ipsCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, isDark ? 'rgba(139, 92, 246, 0.3)' : 'rgba(99, 102, 241, 0.2)');
        gradient.addColorStop(1, isDark ? 'rgba(139, 92, 246, 0)' : 'rgba(99, 102, 241, 0)');

        new Chart(ipsCtx, {
            type: 'line',
            data: {
                labels: gpaDataHistory.map(d => d.academic_year.substring(0, 9)),
                datasets: [{
                    label: 'GPA',
                    data: gpaDataHistory.map(d => d.gpa),
                    borderColor: isDark ? '#a855f7' : siakadPrimary,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: isDark ? '#a855f7' : siakadPrimary,
                    pointBorderWidth: 3,
                    borderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#1e1b3e' : '#ffffff',
                        titleColor: isDark ? '#f3f4f6' : '#111827',
                        bodyColor: isDark ? '#9ca3af' : '#6b7280',
                        borderColor: isDark ? '#312e81' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        cornerRadius: 12,
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 4,
                        ticks: {
                            stepSize: 0.5,
                            color: textColor,
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            color: gridColor,
                            drawBorder: false,
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>