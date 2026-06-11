<x-app-layout>
    <x-slot name="header">{{ __('Academic Schedule') }}</x-slot>

    @php
    $today = \Carbon\Carbon::now()->locale('en')->isoFormat('dddd');
    $now = \Carbon\Carbon::now();
    $dayOrder = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    @endphp

    <div x-data="{ selectedDay: '{{ $today }}', viewMode: 'card' }">
        <div class="mb-10">
            <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Academic Schedule') }}</h1>
            <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
                <span class="w-8 h-px bg-primary-200"></span>
                {{ __('Your weekly class schedule for the current semester.') }}
            </p>
        </div>

        @if(!$activeAcademicYear)
        <div class="card-saas p-10 text-center max-w-md mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center mx-auto mb-4 border border-amber-100 dark:border-amber-900/50">
                <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-black text-primary-900 dark:text-white mb-2">{{ __('No active academic year') }}</h3>
            <p class="text-primary-500 text-sm">{{ __('Contact the administrator to activate the academic year.') }}</p>
        </div>
        @elseif($scheduleByDay->isEmpty())
        <div class="card-saas p-10 text-center max-w-md mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center mx-auto mb-4 border border-primary-100 dark:border-primary-800/50">
                <svg class="w-8 h-8 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-black text-primary-900 dark:text-white mb-2">{{ __('No schedule yet') }}</h3>
            <p class="text-primary-500 mb-6 text-sm">{{ __('Your study plan has not been approved or there is no schedule for this semester yet.') }}</p>
            <a href="{{ route('students.study-plan.index') }}" class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">
                {{ __('See Study Plan') }}
            </a>
        </div>
        @else

        <!-- Controls Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <!-- Day Tabs -->
            <div class="overflow-x-auto pb-1 sm:pb-0 scrollbar-hide">
                <div class="flex gap-3 min-w-max p-1 bg-primary-50/50 dark:bg-primary-900/30 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    @foreach($dayOrder as $day)
                    @php $hasClass = $scheduleByDay->has($day); $count = $hasClass ? $scheduleByDay[$day]->count() : 0; @endphp
                    <button
                        @click="selectedDay = '{{ $day }}'"
                        :class="selectedDay === '{{ $day }}' ? 'bg-white dark:bg-primary-800 text-primary-primary shadow-soft' : '{{ $hasClass ? 'text-primary-500 hover:text-primary-700 dark:hover:text-primary-300' : 'text-primary-300 cursor-not-allowed' }}'"
                        class="px-5 py-2.5 rounded-xl text-sm font-black transition-all duration-200 flex items-center gap-2"
                        {{ !$hasClass ? 'disabled' : '' }}>
                        <span>{{ __($day) }}</span>
                        @if($hasClass)
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-lg bg-primary-50 dark:bg-primary-900 text-[10px] font-black"
                            :class="selectedDay === '{{ $day }}' ? 'text-primary-primary' : 'text-primary-400'">
                            {{ $count }}
                        </span>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- View Toggle -->
            <div class="flex items-center gap-1 bg-primary-50/50 dark:bg-primary-900/30 p-1.5 rounded-2xl border border-primary-100/50 dark:border-primary-800/50 hidden md:flex">
                <button @click="viewMode = 'card'"
                    :class="viewMode === 'card' ? 'bg-white dark:bg-primary-800 shadow-soft text-primary-primary' : 'text-primary-400 hover:text-primary-600'"
                    class="p-2 rounded-xl transition-all duration-200" title="{{ __('Card View') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </button>
                <button @click="viewMode = 'compact'"
                    :class="viewMode === 'compact' ? 'bg-white dark:bg-primary-800 shadow-soft text-primary-primary' : 'text-primary-400 hover:text-primary-600'"
                    class="p-2 rounded-xl transition-all duration-200" title="{{ __('Compact View') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Schedule Content -->
        @foreach($dayOrder as $day)
        @if($scheduleByDay->has($day))
        <div x-show="selectedDay === '{{ $day }}'"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-cloak class="space-y-6">
            @foreach($scheduleByDay[$day] as $item)
            @php
            $class = $item['class'];
            $schedule = $item['schedule'];
            $startTime = \Carbon\Carbon::parse($schedule->start_time);
            $endTime = \Carbon\Carbon::parse($schedule->end_time);
            $isOngoing = $day === $today && $now->between($startTime, $endTime);
            @endphp

            <!-- Card View -->
            <div x-show="viewMode === 'card'" class="flex gap-6 items-start group">
                <!-- Time Column -->
                <div class="hidden sm:block w-24 flex-shrink-0 text-end pt-6">
                    <p class="text-xl font-black text-primary-900 dark:text-white">{{ $startTime->format('H:i') }}</p>
                    <p class="text-xs text-primary-400 font-bold uppercase tracking-widest mt-1">{{ $endTime->format('H:i') }}</p>
                </div>

                <!-- Card -->
                <div class="flex-1 card-saas p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden {{ $isOngoing ? 'ring-2 ring-primary-primary dark:ring-primary-600 ring-inset' : '' }}">
                    @if($isOngoing)
                    <div class="absolute top-0 right-0 p-1">
                        <div class="bg-emerald-500 h-2 w-2 rounded-full animate-ping"></div>
                    </div>
                    @endif

                    <!-- Mobile Time Header -->
                    <div class="sm:hidden flex justify-between items-center mb-4 pb-4 border-b border-primary-50 dark:border-primary-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-black text-primary-900 dark:text-white">{{ $startTime->format('H:i') }} — {{ $endTime->format('H:i') }}</p>
                                @if($isOngoing)
                                <p class="text-[10px] text-emerald-500 font-black uppercase tracking-widest">{{ __('Ongoing') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-primary-50 dark:bg-primary-900/50 text-primary-primary text-[10px] font-black rounded-lg border border-primary-100 dark:border-primary-800 uppercase tracking-widest">
                                {{ $class->course->course_code }}
                            </span>
                            <span class="text-xs text-primary-400 font-bold flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                {{ $class->course->credits }} {{ __('Credits') }}
                            </span>
                        </div>

                        @if($isOngoing && !$loop->parent->first) {{-- Hidden on mobile header, shown on larger screens --}}
                        <span class="hidden sm:flex px-3 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black rounded-full border border-emerald-100 dark:border-emerald-900/50 items-center gap-1.5 uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                            {{ __('Ongoing Now') }}
                        </span>
                        @endif
                    </div>

                    <h4 class="text-lg font-black text-primary-900 dark:text-white mb-2 group-hover:text-primary-primary transition-colors">{{ $class->course->course_name }}</h4>
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-1.5 h-1.5 rounded-full bg-primary-300"></div>
                        <p class="text-sm text-primary-500 font-medium">{{ __('Class') }} <span class="text-primary-900 dark:text-primary-100 font-black">{{ $class->class_name }}</span></p>
                    </div>

                    <div class="flex flex-wrap items-center gap-6 pt-5 border-t border-primary-50 dark:border-primary-800">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center text-primary-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest leading-none mb-1">{{ __('Lecturer') }}</p>
                                <p class="text-xs font-bold text-primary-700 dark:text-primary-300 truncate">{{ $class->lecturer->user->name ?? 'TBA' }}</p>
                            </div>
                        </div>

                        @if($schedule->room)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-emerald-400 font-black uppercase tracking-widest leading-none mb-1">{{ __('Location') }}</p>
                                <p class="text-xs font-black text-emerald-600 dark:text-emerald-400 truncate">{{ $schedule->room }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Compact View -->
            <div x-show="viewMode === 'compact'" class="card-saas px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors {{ $isOngoing ? 'ring-2 ring-primary-primary/20 ring-inset' : '' }}">
                <div class="sm:w-32 flex-shrink-0">
                    <span class="text-sm font-black text-primary-900 dark:text-white">{{ $startTime->format('H:i') }}</span>
                    <span class="text-primary-300 mx-2">—</span>
                    <span class="text-sm font-bold text-primary-400">{{ $endTime->format('H:i') }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-1">
                        <span class="text-[10px] font-black text-primary-400 bg-primary-50 dark:bg-primary-900/50 px-2 py-0.5 rounded-md border border-primary-100 dark:border-primary-800 uppercase tracking-widest">{{ $class->course->course_code }}</span>
                        <span class="text-sm font-black text-primary-900 dark:text-white truncate">{{ $class->course->course_name }}</span>
                        @if($isOngoing)
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-sm shadow-emerald-500/50"></span>
                        @endif
                    </div>
                    <p class="text-xs text-primary-500 font-medium">
                        {{ $class->lecturer->user->name ?? 'TBA' }}
                        <span class="mx-2 text-primary-200">•</span>
                        {{ __('Class') }} {{ $class->class_name }}
                    </p>
                </div>
                @if($schedule->room)
                <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-xl border border-emerald-100 dark:border-emerald-900/50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                    <span class="text-xs font-black">{{ $schedule->room }}</span>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
        @endforeach

        <!-- Summary -->
        <div class="mt-12 bg-primary-900 dark:bg-primary-950 rounded-[2rem] p-8 sm:p-10 relative overflow-hidden shadow-2xl shadow-primary-900/20">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-primary-primary/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white tracking-tight">{{ __('Schedule Summary') }}</h3>
                        <p class="text-primary-300 font-bold mt-1 flex items-center gap-2">
                            {{ $activeAcademicYear->year }}
                            <span class="w-1 h-1 rounded-full bg-primary-500"></span>
                            {{ __('Semester') }} {{ __($activeAcademicYear->semester) }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 sm:gap-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                            <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white leading-none">{{ $scheduleByDay->flatten(1)->count() }}</p>
                            <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">{{ __('Sessions') }}</p>
                        </div>
                    </div>

                    <div class="w-px h-12 bg-white/10 hidden sm:block"></div>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white leading-none">{{ $scheduleByDay->keys()->count() }}</p>
                            <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">{{ __('Active Days') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>