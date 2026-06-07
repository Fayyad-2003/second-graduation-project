<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-siakad-900 dark:text-white">{{ __('Lecturer Attendance') }}</h1>
        <p class="text-siakad-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-siakad-200"></span>
            {{ __('Monitor your teaching hours and record class attendance in real-time.') }}
        </p>
    </div>

    @php
    $now = now();

    // Find next/current class
    $nextClass = null;
    $ongoingClass = null;
    $classStatus = 'none';
    $minutesUntilStart = 0;

    foreach($todaySchedule as $schedule) {
        $scheduleStartTime = $schedule->start_time->format('H:i');
        $scheduleEndTime = $schedule->end_time->format('H:i');
        $currentTime = $now->format('H:i');

        // Check if ongoing
        if ($currentTime >= $scheduleStartTime && $currentTime <= $scheduleEndTime) {
            $ongoingClass = $schedule;
            $classStatus = 'ongoing';
            break;
        }
        // Check if upcoming
        if ($currentTime < $scheduleStartTime && !$nextClass) {
            $nextClass = $schedule;
            $classStatus = 'upcoming';
            $minutesUntilStart = $now->diffInMinutes($schedule->start_time->copy()->setDate($now->year, $now->month, $now->day), false);
        }
    }

    $featuredClass = $ongoingClass ?? $nextClass;

    // Stats calculation
    $totalAttendance = ($stats['present'] ?? 0) + ($stats['excused'] ?? 0) + ($stats['sick'] ?? 0) + ($stats['assignment'] ?? 0) + ($stats['absent'] ?? 0);
    $percentPresent = $totalAttendance > 0 ? round((($stats['present'] ?? 0) / $totalAttendance) * 100) : 0;
    @endphp

    <!-- Top Section: Featured Class & Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Card 1: Next/Ongoing Class -->
        <div class="lg:col-span-2">
            @if($featuredClass)
            @php
            $isCheckedIn = in_array($featuredClass->id, $todayAttendance);
            $attendanceData = \App\Models\LecturerAttendance::where('lecturer_id', $lecturer->id)
                ->where('course_schedule_id', $featuredClass->id)
                ->whereDate('date', now())
                ->first();
            $isCheckedOut = $attendanceData && $attendanceData->exit_time;

            $startTime = $featuredClass->start_time;
            $endTime = $featuredClass->end_time;
            $canAbsen = $classStatus === 'ongoing' || ($minutesUntilStart <= 10 && $minutesUntilStart >= -($startTime->diffInMinutes($endTime)));
            @endphp

            <div class="card-saas p-8 h-full relative overflow-hidden flex flex-col group">
                <!-- Status & Time Header -->
                <div class="flex items-center justify-between mb-8 relative z-10">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest {{ $classStatus === 'ongoing' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50' : 'bg-siakad-50 text-siakad-primary dark:bg-siakad-900/50 dark:text-siakad-400 border border-siakad-100 dark:border-siakad-800' }}">
                        @if($classStatus === 'ongoing')
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        {{ __('Ongoing Now') }}
                        @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('Upcoming') }}
                        @endif
                    </span>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-siakad-50 dark:bg-siakad-900/50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-lg font-black text-siakad-900 dark:text-white">{{ $startTime->format('H:i') }} — {{ $endTime->format('H:i') }}</span>
                    </div>
                </div>

                <!-- Course Info -->
                <div class="relative z-10">
                    <h2 class="text-3xl font-black text-siakad-900 dark:text-white mb-6 group-hover:text-siakad-primary transition-colors">{{ $featuredClass->class->course->course_name ?? '-' }}</h2>
                    
                    <div class="flex flex-wrap gap-4 mb-8">
                        <div class="flex items-center gap-3 px-4 py-2 bg-siakad-50/50 dark:bg-siakad-900/30 rounded-xl border border-siakad-100/50 dark:border-siakad-800/50">
                            <svg class="w-4 h-4 text-siakad-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-sm font-bold text-siakad-700 dark:text-siakad-300">{{ __('Class') }} {{ $featuredClass->class->class_name }}</span>
                        </div>
                        <div class="flex items-center gap-3 px-4 py-2 bg-emerald-50/50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100/50 dark:border-emerald-900/50">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400">{{ $featuredClass->room ?? __('TBA') }}</span>
                        </div>
                        <div class="flex items-center gap-3 px-4 py-2 bg-siakad-50/50 dark:bg-siakad-900/30 rounded-xl border border-siakad-100/50 dark:border-siakad-800/50">
                            <svg class="w-4 h-4 text-siakad-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-sm font-bold text-siakad-700 dark:text-siakad-300">{{ $featuredClass->class->studyPlanDetails()->count() }} {{ __('Students') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Area -->
                <div class="mt-auto pt-8 border-t border-siakad-50 dark:border-siakad-800 flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                    <div class="flex items-center gap-4">
                        @if($isCheckedOut)
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-500 border border-emerald-100 dark:border-emerald-900/50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-siakad-900 dark:text-white">{{ __('Attendance Recorded') }}</p>
                            <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-widest mt-0.5">In {{ substr($attendanceData->entry_time, 0, 5) }} • Out {{ substr($attendanceData->exit_time, 0, 5) }}</p>
                        </div>
                        @elseif($isCheckedIn)
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center text-blue-500 border border-blue-100 dark:border-blue-900/50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-siakad-900 dark:text-white">{{ __('Absence Recorded') }}</p>
                            <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-widest mt-0.5">{{ substr($attendanceData->entry_time, 0, 5) }} • {{ __('Pending Checkout') }}</p>
                        </div>
                        @else
                        <div class="w-12 h-12 rounded-2xl {{ $canAbsen ? 'bg-amber-50 text-amber-500 border-amber-100' : 'bg-siakad-50 text-siakad-300 border-siakad-100' }} flex items-center justify-center border">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-black {{ $canAbsen ? 'text-siakad-900 dark:text-white' : 'text-siakad-400' }}">{{ $canAbsen ? __('Not Checked In') : __('Wait for Session') }}</p>
                            <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-widest mt-0.5">{{ $canAbsen ? __('Session is active') : __('Available 10m before') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($isCheckedOut)
                    <span class="btn-ghost-saas px-8 py-3 rounded-xl text-sm font-black text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50">
                        {{ __('Completed') }}
                    </span>
                    @elseif($isCheckedIn)
                    <form action="{{ route('lecturers.attendance.checkout', $attendanceData) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary-saas px-8 py-3 rounded-xl text-sm font-black shadow-lg shadow-siakad-600/20">
                            {{ __('Absence Check-out') }}
                        </button>
                    </form>
                    @elseif($canAbsen)
                    <button onclick="showModal({{ $featuredClass->id }}, '{{ $featuredClass->class->course->course_name }}')" class="btn-primary-saas px-8 py-3 rounded-xl text-sm font-black shadow-lg shadow-siakad-600/20">
                        {{ __('Check In Now') }}
                    </button>
                    @endif
                </div>
            </div>
            @else
            <div class="card-saas p-16 h-full flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 rounded-3xl bg-siakad-50 dark:bg-siakad-900/50 flex items-center justify-center mb-6 border border-siakad-100 dark:border-siakad-800">
                    <svg class="w-10 h-10 text-siakad-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-siakad-900 dark:text-white mb-2">{{ __('No upcoming classes') }}</h3>
                <p class="text-siakad-500 font-medium">{{ __('All schedules for today have been completed.') }}</p>
            </div>
            @endif
        </div>

        <!-- Card 2: Stats Summary -->
        <div class="lg:col-span-1">
            <div class="card-saas p-8 h-full flex flex-col bg-siakad-900 dark:bg-siakad-950 text-white border-none shadow-2xl shadow-siakad-900/20 relative overflow-hidden">
                <!-- Decoration -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                
                <h3 class="text-lg font-black tracking-tight mb-8 relative z-10">{{ __('Monthly Summary') }}</h3>
                
                @if($totalAttendance > 0)
                <div class="flex-1 flex flex-col justify-between relative z-10">
                    <div class="space-y-6">
                        <div class="relative pt-2">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-siakad-300 uppercase tracking-widest">{{ __('Overall Presence') }}</span>
                                <span class="text-2xl font-black">{{ $percentPresent }}%</span>
                            </div>
                            <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full shadow-lg shadow-emerald-500/50" style="width: {{ $percentPresent }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                                <p class="text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-1">{{ __('Present') }}</p>
                                <p class="text-xl font-black">{{ $stats['present'] ?? 0 }}</p>
                            </div>
                            <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                                <p class="text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-1">{{ __('Excused') }}</p>
                                <p class="text-xl font-black">{{ ($stats['excused'] ?? 0) + ($stats['sick'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between">
                        <span class="text-sm font-bold text-siakad-300">{{ __('Total Sessions') }}</span>
                        <span class="text-3xl font-black text-siakad-primary">{{ $totalAttendance }}</span>
                    </div>
                </div>
                @else
                <div class="flex-1 flex flex-col items-center justify-center text-center opacity-50 relative z-10">
                    <svg class="w-12 h-12 mb-4 text-siakad-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-sm font-bold text-siakad-500 uppercase tracking-widest">{{ __('No data yet') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Section B: Today's Schedule -->
    <div class="card-saas mb-10 overflow-hidden">
        <div class="px-8 py-5 border-b border-siakad-50 dark:border-siakad-800 flex items-center justify-between bg-siakad-50/30 dark:bg-siakad-900/20">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-siakad-900 dark:bg-white flex items-center justify-center text-white dark:text-siakad-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-base font-black text-siakad-900 dark:text-white">{{ __('Today\'s Full Schedule') }}</h3>
            </div>
            <span class="text-xs font-black text-siakad-400 uppercase tracking-widest">{{ now()->locale(app()->getLocale())->isoFormat('dddd, D MMMM Y') }}</span>
        </div>

        @if($todaySchedule->isNotEmpty())
        <div class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
            @foreach($todaySchedule as $schedule)
            @php
            $scheduleStartTime = $schedule->start_time->format('H:i');
            $scheduleEndTime = $schedule->end_time->format('H:i');
            $isOngoing = $now->between($schedule->start_time->copy()->setDate($now->year, $now->month, $now->day), $schedule->end_time->copy()->setDate($now->year, $now->month, $now->day));
            $isDone = $now->gt($schedule->end_time->copy()->setDate($now->year, $now->month, $now->day));
            $isCheckedInSchedule = in_array($schedule->id, $todayAttendance);
            @endphp
            <div class="flex flex-col sm:flex-row sm:items-center gap-6 px-8 py-5 hover:bg-siakad-50/30 dark:hover:bg-siakad-900/10 transition-colors group">
                <!-- Time -->
                <div class="flex items-center sm:block gap-3 sm:w-20 flex-shrink-0 text-start sm:text-center">
                    <p class="text-lg font-black {{ $isOngoing ? 'text-emerald-500' : ($isDone ? 'text-siakad-300' : 'text-siakad-900 dark:text-white') }}">{{ $scheduleStartTime }}</p>
                    <span class="sm:hidden text-siakad-200">/</span>
                    <p class="text-[10px] font-bold text-siakad-400 uppercase tracking-wider">{{ $scheduleEndTime }}</p>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-siakad-900 dark:text-white truncate group-hover:text-siakad-primary transition-colors">{{ $schedule->class->course->course_name ?? '-' }}</p>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-[10px] font-bold text-siakad-400 uppercase tracking-widest">{{ __('Class') }} {{ $schedule->class->class_name }}</span>
                        <span class="w-1 h-1 rounded-full bg-siakad-200"></span>
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">{{ $schedule->room ?? '-' }}</span>
                    </div>
                </div>

                <!-- Status & Action -->
                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                    <div class="flex-shrink-0">
                        @if($isDone)
                        <span class="inline-flex px-3 py-1 text-[10px] font-black bg-siakad-50 text-siakad-400 rounded-lg uppercase tracking-widest border border-siakad-100">{{ __('Finished') }}</span>
                        @elseif($isOngoing)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-black bg-emerald-50 text-emerald-600 rounded-lg uppercase tracking-widest border border-emerald-100 animate-pulse">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ __('Ongoing') }}
                        </span>
                        @else
                        <span class="inline-flex px-3 py-1 text-[10px] font-black bg-siakad-50 text-siakad-primary rounded-lg uppercase tracking-widest border border-siakad-100">{{ __('Upcoming') }}</span>
                        @endif
                    </div>

                    <div class="flex-shrink-0 min-w-[100px] text-end">
                        @if($isCheckedInSchedule)
                        <span class="text-xs font-black text-emerald-600 flex items-center justify-end gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('Checked In') }}
                        </span>
                        @elseif($isOngoing || (!$isDone && $schedule->id === ($nextClass?->id ?? null)))
                        <button onclick="showModal({{ $schedule->id }}, '{{ $schedule->class->course->course_name }}')" class="btn-primary-saas px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-md shadow-siakad-primary/20">
                            {{ __('Check In') }}
                        </button>
                        @else
                        <span class="text-xs text-siakad-200">—</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <p class="text-siakad-400 text-sm font-medium">{{ __('No teaching schedule today') }}</p>
        </div>
        @endif
    </div>

    <!-- Section D: History -->
    <div id="history" class="card-saas overflow-hidden">
        <div class="px-8 py-6 border-b border-siakad-50 dark:border-siakad-800">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <h3 class="text-lg font-black text-siakad-900 dark:text-white tracking-tight">{{ __('Attendance History') }}</h3>
                    <p class="text-xs font-bold text-siakad-400 uppercase tracking-widest mt-1">{{ __('Past teaching records and session logs') }}</p>
                </div>
                <form method="GET" class="flex flex-col md:flex-row items-center gap-4 w-full lg:w-auto">
                    <div class="relative w-full md:w-64 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-siakad-400 group-focus-within:text-siakad-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search courses...') }}" class="input-saas pl-11 pr-4 py-2.5 text-sm w-full">
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <select name="month" class="input-saas px-4 py-2.5 text-sm flex-1">
                            @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->locale(app()->getLocale())->monthName }}</option>
                            @endfor
                        </select>
                        <select name="year" class="input-saas px-4 py-2.5 text-sm flex-1">
                            @for($y = now()->year; $y >= now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black w-full md:w-auto">{{ __('Filter') }}</button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-siakad-50/50 dark:bg-siakad-900/30 border-b border-siakad-100/50 dark:border-siakad-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Date') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Course Details') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center">{{ __('Entry') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center">{{ __('Exit') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-end">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
                    @forelse($history as $r)
                    <tr class="hover:bg-siakad-50/30 dark:hover:bg-siakad-900/20 transition-colors group">
                        <td class="py-5 px-8">
                            <span class="text-sm font-black text-siakad-900 dark:text-white">{{ $r->date->locale(app()->getLocale())->isoFormat('D MMM YYYY') }}</span>
                        </td>
                        <td class="py-5 px-6">
                            <p class="text-sm font-bold text-siakad-700 dark:text-siakad-300 group-hover:text-siakad-primary transition-colors">{{ $r->courseSchedule?->class?->course?->course_name ?? '-' }}</p>
                            <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-wider mt-0.5">{{ __('Class') }} {{ $r->courseSchedule?->class?->class_name ?? '-' }}</p>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="text-xs font-black text-siakad-600 dark:text-siakad-400 font-mono">{{ $r->entry_time ? substr($r->entry_time, 0, 5) : '-' }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="text-xs font-black text-siakad-600 dark:text-siakad-400 font-mono">{{ $r->exit_time ? substr($r->exit_time, 0, 5) : '-' }}</span>
                        </td>
                        <td class="py-5 px-8 text-end">
                            <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border bg-{{ $r->status_color === 'green' ? 'emerald' : ($r->status_color === 'red' ? 'rose' : $r->status_color) }}-50 text-{{ $r->status_color === 'green' ? 'emerald' : ($r->status_color === 'red' ? 'rose' : $r->status_color) }}-600 border-{{ $r->status_color === 'green' ? 'emerald' : ($r->status_color === 'red' ? 'rose' : $r->status_color) }}-100">
                                {{ __($r->status_label) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-siakad-50 dark:bg-siakad-900 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-siakad-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-siakad-400 text-sm font-bold">{{ __('No history found for this period') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Attendance Modal -->
    <div id="absenModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-siakad-900/60 backdrop-blur-sm transition-opacity" onclick="hideModal()"></div>
            <div class="relative bg-white dark:bg-siakad-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden animate-fade-in">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-siakad-primary/5 rounded-full"></div>
                
                <h3 class="text-xl font-black text-siakad-900 dark:text-white mb-1 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-siakad-50 dark:bg-siakad-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    {{ __('Attendance Entry') }}
                </h3>
                <p id="modalCourse" class="text-sm font-bold text-siakad-500 mb-8 ml-13"></p>

                <form action="{{ route('lecturers.attendance.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="course_schedule_id" id="modalScheduleId">
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-siakad-400 uppercase tracking-widest ml-1">{{ __('Entry Time') }}</label>
                            <input type="time" name="entry_time" value="{{ now()->format('H:i') }}" required class="input-saas w-full">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-siakad-400 uppercase tracking-widest ml-1">{{ __('Session Status') }}</label>
                            <select name="status" required class="input-saas w-full">
                                @foreach(\App\Models\LecturerAttendance::getStatusList() as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-siakad-400 uppercase tracking-widest ml-1">{{ __('Description / Notes') }}</label>
                        <textarea name="description" rows="3" class="input-saas w-full" placeholder="{{ __('Record topics covered or other notes...') }}"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-siakad-50 dark:border-siakad-800">
                        <button type="button" onclick="hideModal()" class="px-6 py-2.5 text-sm font-bold text-siakad-400 hover:text-siakad-600 transition">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-siakad-primary/20">{{ __('Confirm Attendance') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showModal(id, course) {
            document.getElementById('modalScheduleId').value = id;
            document.getElementById('modalCourse').textContent = course;
            const modal = document.getElementById('absenModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hideModal() {
            const modal = document.getElementById('absenModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>