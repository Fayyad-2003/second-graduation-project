<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Lecturer Attendance Monitoring') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('Track and monitor daily attendance patterns of academic staff.') }}
        </p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-10">
        @php
        $attendanceStats = [
        ['label' => __('Present'), 'count' => $stats['present'] ?? 0, 'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => __('Permission'), 'count' => $stats['excused'] ?? 0, 'color' => 'blue', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['label' => __('Sick'), 'count' => $stats['sick'] ?? 0, 'color' => 'amber', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => __('On Duty'), 'count' => $stats['assignment'] ?? 0, 'color' => 'purple', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ['label' => __('Absent'), 'count' => $stats['absent'] ?? 0, 'color' => 'red', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z']
        ];
        @endphp
        @foreach($attendanceStats as $s)
        <div class="card-saas p-5 relative overflow-hidden group">
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="w-10 h-10 rounded-xl bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-950/30 flex items-center justify-center text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400 shadow-sm border border-{{ $s['color'] }}-100/50 dark:border-{{ $s['color'] }}-900/50 mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $s['icon'] }}"></path>
                    </svg>
                </div>
                <p class="text-2xl font-black text-primary-900 dark:text-white leading-tight">{{ $s['count'] }}</p>
                <p class="text-[9px] text-primary-400 font-black uppercase tracking-widest mt-1">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Filter -->
    <div class="card-saas p-6 mb-10">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1 mb-1.5 block">{{ __('Lecturer') }}</label>
                <select name="lecturer_id" class="input-saas w-full py-2.5 text-sm">
                    <option value="">{{ __('All Lecturers') }}</option>
                    @foreach($lecturerList as $d)
                    <option value="{{ $d->id }}" {{ $lecturerId == $d->id ? 'selected' : '' }}>{{ $d->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-48">
                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1 mb-1.5 block">{{ __('Month') }}</label>
                <select name="month" class="input-saas w-full py-2.5 text-sm">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->locale('ar')->monthName }}</option>
                        @endfor
                </select>
            </div>
            <div class="w-full sm:w-32">
                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1 mb-1.5 block">{{ __('Year') }}</label>
                <select name="year" class="input-saas w-full py-2.5 text-sm">
                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">
                {{ __('Filter') }}
            </button>
        </form>
    </div>

    <!-- Summary by Lecturer -->
    <div class="card-saas mb-10 overflow-hidden">
        <div class="px-8 py-6 border-b border-primary-100/50 dark:border-primary-800/50 bg-primary-50/30 dark:bg-primary-900/20 flex items-center justify-between">
            <h3 class="text-lg font-black text-primary-900 dark:text-white flex items-center gap-3">
                <div class="w-1.5 h-6 bg-primary-primary rounded-full"></div>
                {{ __('Summary per Lecturer') }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <!-- Table (Desktop) -->
            <table class="hidden md:table w-full text-start border-collapse">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Lecturer') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Present') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Permission') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Sick') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('On Duty') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Absent') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Attendance Rate') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @foreach($lecturerList as $d)
                    @php
                    $summary = $lecturerSummary[$d->id] ?? collect();
                    $presentCount = $summary->where('status', 'present')->first()?->count ?? 0;
                    $total = $summary->sum('count') ?: 1;
                    $percentage = round(($presentCount / $total) * 100);
                    @endphp
                    <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
                        <td class="py-5 px-8 text-start">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center text-primary-600 dark:text-primary-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($d->user->name ?? '-', 0, 1)) }}
                                </div>
                                <div class="min-w-0 text-start">
                                    <p class="text-sm font-black text-primary-900 dark:text-white truncate leading-tight">{{ $d->user->name }}</p>
                                    <p class="text-[10px] text-primary-400 font-mono font-bold uppercase tracking-wider mt-0.5">{{ $d->lecturer_number }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">{{ $summary->where('status', 'present')->first()?->count ?? 0 }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="text-sm font-black text-blue-600 dark:text-blue-400">{{ $summary->where('status', 'excused')->first()?->count ?? 0 }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="text-sm font-black text-amber-600 dark:text-amber-400">{{ $summary->where('status', 'sick')->first()?->count ?? 0 }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="text-sm font-black text-purple-600 dark:text-purple-400">{{ $summary->where('status', 'assignment')->first()?->count ?? 0 }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="text-sm font-black text-red-600 dark:text-red-400">{{ $summary->where('status', 'absent')->first()?->count ?? 0 }}</span>
                        </td>
                        <td class="py-5 px-8 text-center">
                            @php
                            $rateColor = $percentage >= 80 ? 'emerald' : ($percentage >= 60 ? 'amber' : 'red');
                            @endphp
                            <div class="flex flex-col items-center gap-1.5">
                                <span class="px-3 py-1 text-[10px] font-black rounded-full border border-{{ $rateColor }}-100 dark:border-{{ $rateColor }}-900/50 bg-{{ $rateColor }}-50 dark:bg-{{ $rateColor }}-950/30 text-{{ $rateColor }}-600 dark:text-{{ $rateColor }}-400 uppercase tracking-widest">{{ $percentage }}%</span>
                                <div class="w-20 h-1 bg-primary-50 dark:bg-primary-900 rounded-full overflow-hidden">
                                    <div class="h-full bg-{{ $rateColor }}-500 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Mobile Card List (Summary) -->
            <div class="md:hidden divide-y divide-primary-50 dark:divide-primary-800/50">
                @foreach($lecturerList as $d)
                @php
                $summary = $lecturerSummary[$d->id] ?? collect();
                $presentCount = $summary->where('status', 'present')->first()?->count ?? 0;
                $total = $summary->sum('count') ?: 1;
                $percentage = round(($presentCount / $total) * 100);
                $rateColor = $percentage >= 80 ? 'emerald' : ($percentage >= 60 ? 'amber' : 'red');
                @endphp
                <div class="p-5 hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-primary-50 dark:bg-primary-900 flex items-center justify-center text-primary-600 dark:text-primary-400 text-sm font-black shadow-sm">
                                {{ strtoupper(substr($d->user->name ?? '-', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-black text-primary-900 dark:text-white truncate leading-tight">{{ $d->user->name }}</h4>
                                <p class="text-[10px] text-primary-400 font-mono font-bold mt-0.5 tracking-wider">{{ $d->lecturer_number }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest rounded-full border border-{{ $rateColor }}-100 bg-{{ $rateColor }}-50 text-{{ $rateColor }}-600">
                            {{ $percentage }}%
                        </span>
                    </div>
                    <div class="grid grid-cols-5 gap-2 text-center">
                        @foreach([['emerald', 'present'], ['blue', 'excused'], ['amber', 'sick'], ['purple', 'assignment'], ['red', 'absent']] as $st)
                        <div class="bg-{{ $st[0] }}-50/50 dark:bg-{{ $st[0] }}-950/20 p-2 rounded-xl border border-{{ $st[0] }}-100/50 dark:border-{{ $st[0] }}-900/50">
                            <span class="block text-sm font-black text-{{ $st[0] }}-600 dark:text-{{ $st[0] }}-400">{{ $summary->where('status', $st[1])->first()?->count ?? 0 }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Detail -->
    <div class="card-saas overflow-hidden">
        <div class="px-8 py-6 border-b border-primary-100/50 dark:border-primary-800/50 bg-primary-50/30 dark:bg-primary-900/20 flex items-center justify-between">
            <h3 class="text-lg font-black text-primary-900 dark:text-white flex items-center gap-3">
                <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                {{ __('Attendance Details') }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <!-- Table (Desktop) -->
            <table class="hidden md:table w-full text-start border-collapse">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Date') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Lecturer') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Course') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Time') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($attendanceList as $k)
                    <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
                        <td class="py-5 px-8 text-start">
                            <span class="text-xs font-black text-primary-900 dark:text-white font-mono uppercase tracking-widest">{{ $k->date->format('d M Y') }}</span>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <span class="text-sm font-black text-primary-900 dark:text-white">{{ $k->lecturer->user->name }}</span>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <p class="text-xs font-bold text-primary-600 dark:text-primary-400 leading-tight">{{ $k->courseSchedule?->class?->course?->course_name ?? '-' }}</p>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="text-[10px] font-black text-primary-400 dark:text-primary-500 font-mono uppercase tracking-widest">{{ $k->check_in_time ? substr($k->check_in_time, 0, 5) : '--:--' }} — {{ $k->check_out_time ? substr($k->check_out_time, 0, 5) : '--:--' }}</span>
                        </td>
                        <td class="py-5 px-8 text-center">
                            <span class="inline-flex px-3 py-1 text-[10px] font-black rounded-full border border-{{ $k->status_color }}-100 dark:border-{{ $k->status_color }}-900/50 bg-{{ $k->status_color }}-50 dark:bg-{{ $k->status_color }}-950/30 text-{{ $k->status_color }}-600 dark:text-{{ $k->status_color }}-400 uppercase tracking-widest">
                                {{ $k->status_label }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <p class="text-primary-400 font-bold uppercase tracking-widest text-[10px]">{{ __('No detailed data available yet') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Mobile Card List (Detail) -->
            <div class="md:hidden divide-y divide-primary-50 dark:divide-primary-800/50">
                @forelse($attendanceList as $k)
                <div class="p-5 hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest">{{ $k->date->format('d M Y') }}</span>
                            <h4 class="font-black text-primary-900 dark:text-white text-sm mt-1 leading-tight">{{ $k->lecturer->user->name }}</h4>
                        </div>
                        <span class="px-2.5 py-1 text-[8px] font-black rounded-full border border-{{ $k->status_color }}-100 bg-{{ $k->status_color }}-50 text-{{ $k->status_color }}-600 uppercase tracking-widest">{{ $k->status_label }}</span>
                    </div>

                    @if($k->courseSchedule)
                    <div class="bg-primary-50/50 dark:bg-primary-900/30 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                        <p class="text-xs font-bold text-primary-700 dark:text-primary-300 mb-2 leading-tight">{{ $k->courseSchedule->class->course->course_name ?? '-' }}</p>
                        <div class="flex items-center gap-2 text-[10px] font-black text-primary-400 uppercase tracking-widest">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-mono">{{ $k->check_in_time ? substr($k->check_in_time, 0, 5) : '--:--' }} — {{ $k->check_out_time ? substr($k->check_out_time, 0, 5) : '--:--' }}</span>
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="p-16 text-center">
                    <p class="text-primary-400 font-bold uppercase tracking-widest text-[10px]">{{ __('No detailed data available yet') }}</p>
                </div>
                @endforelse
            </div>
        </div>
        @if($attendanceList->hasPages())
        <div class="px-8 py-6 border-t border-primary-50/50 dark:border-primary-800/50 bg-primary-50/20 dark:bg-primary-900/10">
            {{ $attendanceList->links() }}
        </div>
        @endif
    </div>
</x-app-layout>