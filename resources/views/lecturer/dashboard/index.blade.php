<x-app-layout>
    <x-slot name="header">
        {{ __('Lecturer Dashboard') }}
    </x-slot>

    <!-- Greeting -->
    <div class="mb-10 hidden md:block relative overflow-hidden">
        <div class="flex items-center justify-between">
            <div>
                @php
                $hour = now()->hour;
                if ($hour < 11) $greeting=__('Good Morning');
                    elseif ($hour < 15) $greeting=__('Good Afternoon');
                    elseif ($hour < 18) $greeting=__('Good Evening');
                    else $greeting=__('Good Night');

                    if ($hour < 11) $emoji='🌅' ;
                    elseif ($hour < 15) $emoji='☀️' ;
                    elseif ($hour < 18) $emoji='🌤️' ;
                    else $emoji='🌙' ;
                    @endphp
                    <h1 class="text-3xl font-black tracking-tight text-siakad-900 dark:text-white">
                    {{ $greeting }}, <span class="text-transparent bg-clip-text bg-gradient-to-r from-siakad-600 to-siakad-400">{{ explode(' ', $lecturer->user->name)[0] }}</span>!
                    <span class="inline-block animate-bounce-subtle">{{ $emoji }}</span>
                    </h1>
                    <p class="text-siakad-500 font-medium mt-2 flex items-center gap-2">
                        <span class="w-8 h-px bg-siakad-200"></span>
                        {{ __('Ready to manage your academic activities today?') }}
                    </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden xl:block">
                    <p class="text-xs font-bold text-siakad-400 uppercase tracking-widest">{{ now()->translatedFormat('l') }}</p>
                    <p class="text-sm font-black text-siakad-900 dark:text-white">{{ now()->translatedFormat('d F Y') }}</p>
                </div>
                <div class="p-3 bg-white dark:bg-siakad-800 rounded-2xl shadow-soft border border-siakad-50 dark:border-siakad-700">
                    <svg class="w-6 h-6 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="card-saas p-6 group hover:shadow-soft-lg transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-siakad-50 dark:bg-siakad-900/50 rounded-2xl flex items-center justify-center group-hover:bg-siakad-primary group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6 text-siakad-primary group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-siakad-900 dark:text-white">{{ $totalClass }}</p>
                    <p class="text-xs text-siakad-500 font-bold uppercase tracking-wider">{{ __('Classes') }}</p>
                </div>
            </div>
        </div>
        <div class="card-saas p-6 group hover:shadow-soft-lg transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-siakad-50 dark:bg-siakad-900/50 rounded-2xl flex items-center justify-center group-hover:bg-siakad-600 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6 text-siakad-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-siakad-900 dark:text-white">{{ $totalStudents }}</p>
                    <p class="text-xs text-siakad-500 font-bold uppercase tracking-wider">{{ __('Students') }}</p>
                </div>
            </div>
        </div>
        <div class="card-saas p-6 group hover:shadow-soft-lg transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-siakad-50 dark:bg-siakad-900/50 rounded-2xl flex items-center justify-center group-hover:bg-siakad-500 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6 text-siakad-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-siakad-900 dark:text-white">{{ $totalMeetings }}</p>
                    <p class="text-xs text-siakad-500 font-bold uppercase tracking-wider">{{ __('Meetings') }}</p>
                </div>
            </div>
        </div>
        <div class="card-saas p-6 group hover:shadow-soft-lg transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-siakad-50 dark:bg-siakad-900/50 rounded-2xl flex items-center justify-center group-hover:bg-siakad-700 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6 text-siakad-700 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-siakad-900 dark:text-white">{{ $advisedStudents->count() }}</p>
                    <p class="text-xs text-siakad-500 font-bold uppercase tracking-wider">{{ __('PA Students') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Schedule Hari Ini (Context Aware Hero Card) -->
            @if($todayClasses->isNotEmpty())
            @php
            $upcomingClass = $todayClasses->first();
            $schedule = $upcomingClass->courseSchedules->firstWhere('day', $today);
            @endphp
            <div class="card-saas p-8 bg-gradient-to-br from-siakad-primary to-siakad-700 text-white relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-lg bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest border border-white/10">{{ __('Today\'s Class') }}</span>
                            <div class="flex items-center gap-1.5 text-siakad-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs font-bold">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                            </div>
                        </div>
                        <h2 class="text-3xl font-black mb-2 tracking-tight">{{ $upcomingClass->course->course_name }}</h2>
                        <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-siakad-100/80">
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-bold px-2 py-0.5 bg-white/10 rounded uppercase tracking-wider text-white">{{ __('Class') }} {{ $upcomingClass->class_name }}</span>
                            </div>
                            <span class="w-1 h-1 rounded-full bg-white/30"></span>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-xs font-bold">{{ $upcomingClass->studyPlanDetails->count() }} {{ __('Students') }}</span>
                            </div>
                            <span class="w-1 h-1 rounded-full bg-white/30"></span>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-xs font-bold">{{ __('Room') }} {{ $schedule->room ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('lecturers.attendance-input.class', $upcomingClass) }}" class="flex-shrink-0 group/btn px-8 py-4 bg-white text-siakad-primary font-black rounded-2xl shadow-xl shadow-siakad-900/20 hover:shadow-siakad-900/40 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                        <svg class="w-6 h-6 transform group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        {{ __('Open Presence') }}
                    </a>
                </div>
            </div>

            <!-- List of other classes if any -->
            @if($todayClasses->count() > 1)
            <div class="card-saas overflow-hidden">
                <div class="px-8 py-6 bg-siakad-50/50 dark:bg-siakad-900/20 border-b border-siakad-100/50 dark:border-siakad-800/50">
                    <h3 class="font-bold text-siakad-900 dark:text-white text-base">{{ __('Other Schedules Today') }}</h3>
                </div>
                <div class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
                    @foreach($todayClasses->skip(1) as $class)
                    @php $schedule = $class->courseSchedules->firstWhere('day', $today); @endphp
                    <div class="p-6 flex items-center gap-6 hover:bg-siakad-50/50 dark:hover:bg-siakad-900/30 transition-all duration-200 group">
                        <div class="flex flex-col items-center justify-center bg-siakad-100/50 dark:bg-siakad-800/50 p-3 rounded-2xl w-20 h-20 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <p class="text-sm font-black text-siakad-primary mb-0.5">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</p>
                            <p class="text-[10px] text-siakad-400 font-bold tracking-tighter">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-siakad-900 dark:text-white truncate group-hover:text-siakad-600 transition-colors">{{ $class->course->course_name }}</h4>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="text-[10px] font-black bg-siakad-50 dark:bg-siakad-900 text-siakad-600 dark:text-siakad-400 px-2 py-0.5 rounded uppercase tracking-wider border border-siakad-100 dark:border-siakad-800">{{ $class->class_name }}</span>
                                <span class="w-1 h-1 rounded-full bg-siakad-200 dark:bg-siakad-700"></span>
                                <span class="text-xs text-siakad-500 font-medium">{{ $class->studyPlanDetails->count() }} {{ __('Students') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('lecturers.attendance-input.class', $class) }}" class="p-3 bg-siakad-50 dark:bg-siakad-900/50 text-siakad-400 hover:text-siakad-600 hover:bg-siakad-100 dark:hover:bg-siakad-900 rounded-xl transition-all duration-200" title="{{ __('Open Presence') }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @else
            <!-- Empty State for Today -->
            <div class="card-saas p-12 text-center flex flex-col items-center justify-center min-h-[300px] relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-siakad-50 dark:bg-siakad-900/20 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-500"></div>
                <div class="w-24 h-24 rounded-3xl bg-siakad-50 dark:bg-siakad-900/50 flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform duration-300">
                    <svg class="w-12 h-12 text-siakad-200 dark:text-siakad-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-siakad-900 dark:text-white mb-2">{{ __('No schedule today') }}</h3>
                <p class="text-siakad-500 font-medium max-w-xs mx-auto">{{ __('Enjoy your day, Lecturer! Use this time to prepare for your next sessions.') }} ☕</p>
                <a href="{{ route('lecturers.attendance-input.index') }}" class="mt-8 px-6 py-3 bg-white dark:bg-siakad-800 border border-siakad-100 dark:border-siakad-700 text-siakad-600 dark:text-siakad-400 rounded-xl text-sm font-bold shadow-soft hover:shadow-soft-lg transition-all duration-300">{{ __('See All Schedules') }}</a>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Pending Study Plans -->
            @if($pendingStudyPlans > 0)
            <div class="card-saas p-8 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/20 dark:to-orange-950/20 border-amber-100 dark:border-amber-900/50 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/10 rounded-full -mr-12 -mt-12 transition-transform duration-500 group-hover:scale-125"></div>
                <div class="relative">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center border border-amber-200/50">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-amber-600 uppercase tracking-widest">{{ __('Attention') }}</p>
                            <p class="text-lg font-black text-amber-900 dark:text-amber-100">{{ $pendingStudyPlans }} {{ __('Pending Plans') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('lecturers.supervision.study-plan-approval') }}" class="flex items-center justify-center gap-2 w-full py-3 bg-amber-600 text-white rounded-xl text-sm font-black shadow-lg shadow-amber-600/20 hover:bg-amber-700 hover:shadow-amber-600/40 transition-all duration-300">
                        {{ __('Review Now') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @endif

            <!-- Class List -->
            <div class="card-saas overflow-hidden">
                <div class="px-8 py-6 bg-siakad-50/50 dark:bg-siakad-900/20 border-b border-siakad-100/50 dark:border-siakad-800/50 flex items-center justify-between">
                    <h3 class="font-bold text-siakad-900 dark:text-white text-base">{{ __('My Classes') }}</h3>
                    <span class="px-2.5 py-0.5 rounded-lg bg-siakad-100 dark:bg-siakad-800 text-siakad-600 dark:text-siakad-400 text-[10px] font-black uppercase">{{ $classList->count() }}</span>
                </div>
                @if($classList->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-siakad-400 text-xs font-medium">{{ __('No classes assigned yet.') }}</p>
                </div>
                @else
                <div class="divide-y divide-siakad-50 dark:divide-siakad-800/50 max-h-80 overflow-y-auto">
                    @foreach($classList as $class)
                    <div class="p-6 flex items-center justify-between hover:bg-siakad-50/50 dark:hover:bg-siakad-900/30 transition-all duration-200 group">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-siakad-900 dark:text-white truncate group-hover:text-siakad-600 transition-colors">{{ $class->course->course_name }}</h4>
                            <p class="text-[10px] text-siakad-500 font-bold uppercase tracking-wider mt-1">{{ $class->class_name }} • {{ $class->studyPlanDetails->count() }} Students</p>
                        </div>
                        <a href="{{ route('lecturers.attendance-input.class', $class) }}" class="p-2 text-siakad-300 hover:text-siakad-600 hover:bg-siakad-50 dark:hover:bg-siakad-900 rounded-lg transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Progress Grade -->
            <div class="card-saas overflow-hidden">
                <div class="px-8 py-6 bg-siakad-50/50 dark:bg-siakad-900/20 border-b border-siakad-100/50 dark:border-siakad-800/50 flex items-center justify-between">
                    <h3 class="font-bold text-siakad-900 dark:text-white text-base">{{ __('Grading') }}</h3>
                    <a href="{{ route('lecturers.grading.index') }}" class="text-[10px] font-black text-siakad-600 uppercase tracking-widest hover:underline">{{ __('View All') }}</a>
                </div>
                <div class="p-8">
                    <div class="flex items-end justify-between mb-4">
                        <div>
                            <p class="text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-1">{{ __('Completion') }}</p>
                            <p class="text-2xl font-black text-siakad-900 dark:text-white">{{ $gradePercentage }}%</p>
                        </div>
                        <p class="text-xs text-siakad-500 font-bold">{{ $gradesInputted }}/{{ $totalGrade }}</p>
                    </div>
                    <div class="h-3 bg-siakad-50 dark:bg-siakad-900 rounded-full overflow-hidden shadow-inner-soft">
                        <div class="h-full bg-gradient-to-r from-siakad-500 to-siakad-600 rounded-full transition-all duration-1000 ease-out" style="width: {{ $gradePercentage }}%"></div>
                    </div>

                    @if($recentGrades->isNotEmpty())
                    <div class="mt-8">
                        <p class="text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-4">{{ __('Recent Entries') }}</p>
                        <div class="space-y-4">
                            @foreach($recentGrades as $grade)
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-siakad-50 dark:bg-siakad-900/50 text-siakad-600 dark:text-siakad-400 flex items-center justify-center text-xs font-black border border-siakad-100 dark:border-siakad-800">
                                        {{ strtoupper(substr($grade->student->user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-siakad-900 dark:text-white truncate max-w-[100px]">{{ $grade->student->user->name }}</p>
                                        <p class="text-[10px] text-siakad-400 font-medium">{{ $grade->academicClass->course->course_code }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 rounded-lg bg-siakad-50 dark:bg-siakad-900 text-siakad-600 dark:text-siakad-400 text-xs font-black">{{ $grade->letter_grade }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions (Full Width Horizontal) -->
    <div class="bg-gradient-to-br from-siakad-900 to-siakad-950 rounded-3xl p-10 text-white relative overflow-hidden shadow-soft-xl mb-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-siakad-500/5 rounded-full -mr-48 -mt-48 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-siakad-400/5 rounded-full -ml-32 -mb-32 blur-2xl"></div>

        <h3 class="text-lg font-black mb-8 relative flex items-center gap-3">
            <span class="w-8 h-1 bg-siakad-500 rounded-full"></span>
            {{ __('Quick Management Actions') }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
            <a href="{{ route('lecturers.attendance-input.index') }}" class="group p-6 bg-white/5 hover:bg-white/10 rounded-2xl border border-white/5 transition-all duration-300">
                <div class="w-12 h-12 bg-siakad-500/20 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-siakad-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="font-black text-base mb-1">{{ __('Input Attendance') }}</h4>
                <p class="text-xs text-white/50 font-medium leading-relaxed">{{ __('Record student presence for today\'s sessions.') }}</p>
            </a>
            <a href="{{ route('lecturers.grading.index') }}" class="group p-6 bg-white/5 hover:bg-white/10 rounded-2xl border border-white/5 transition-all duration-300">
                <div class="w-12 h-12 bg-siakad-400/20 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-siakad-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h4 class="font-black text-base mb-1">{{ __('Course Grading') }}</h4>
                <p class="text-xs text-white/50 font-medium leading-relaxed">{{ __('Submit and update final grades for your students.') }}</p>
            </a>
            <a href="{{ route('lecturers.supervision.study-plan-approval') }}" class="group p-6 bg-white/5 hover:bg-white/10 rounded-2xl border border-white/5 transition-all duration-300">
                <div class="w-12 h-12 bg-siakad-600/20 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-siakad-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h4 class="font-black text-base mb-1">{{ __('PA Supervision') }}</h4>
                <p class="text-xs text-white/50 font-medium leading-relaxed">{{ __('Review and approve student study plan proposals.') }}</p>
            </a>
        </div>
    </div>
</x-app-layout>