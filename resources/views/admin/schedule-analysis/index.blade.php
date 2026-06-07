<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-siakad-900 dark:text-white">{{ __('Schedule Analysis') }}</h1>
        <p class="text-siakad-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-siakad-200"></span>
            {{ __('Analyze and resolve schedule conflicts for') }} {{ $activeYear->year ?? __('No Active Year') }}
        </p>
    </div>

    <div class="space-y-8">
        <!-- Room Conflicts -->
        <div class="card-saas overflow-hidden">
            <div class="p-6 border-b border-siakad-100/50 dark:border-siakad-800/50 flex items-center justify-between bg-siakad-50/30 dark:bg-siakad-900/20">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-950/30 flex items-center justify-center text-red-600 dark:text-red-400 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-siakad-900 dark:text-white">{{ __('Room Conflicts') }}</h3>
                        <p class="text-xs text-siakad-500 font-bold uppercase tracking-wider">{{ __('Overlapping classes in same room') }}</p>
                    </div>
                </div>
                <span class="px-4 py-1.5 text-xs font-black bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400 rounded-full border border-red-200 dark:border-red-800/50 shadow-sm">
                    {{ count($roomConflicts) }} {{ __('Conflicts') }}
                </span>
            </div>

            @if(empty($roomConflicts))
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center mx-auto mb-4 text-emerald-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-siakad-400 font-bold">{{ __('No room conflicts detected.') }}</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-start border-collapse">
                    <thead>
                        <tr class="bg-siakad-50/50 dark:bg-siakad-900/30 border-b border-siakad-100/50 dark:border-siakad-800/50">
                            <th class="py-4 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Room') }}</th>
                            <th class="py-4 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Day') }}</th>
                            <th class="py-4 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Conflicting Classes') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
                        @foreach($roomConflicts as $conflict)
                        <tr class="hover:bg-siakad-50/30 dark:hover:bg-siakad-900/20 transition-colors">
                            <td class="py-5 px-8 text-sm font-black text-siakad-900 dark:text-white text-start">{{ $conflict['room'] }}</td>
                            <td class="py-5 px-6 text-xs font-bold text-siakad-600 dark:text-siakad-400 capitalize text-start">{{ $conflict['day'] }}</td>
                            <td class="py-5 px-8 text-start">
                                <div class="space-y-3">
                                    @foreach($conflict['schedules'] as $sched)
                                    <div class="flex items-center gap-3">
                                        <div class="px-2 py-1 rounded-lg bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-[10px] font-black font-mono border border-red-100 dark:border-red-900/50">
                                            {{ $sched->start_time->format('H:i') }} - {{ $sched->end_time->format('H:i') }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-siakad-700 dark:text-siakad-300 truncate">{{ $sched->class->course->course_name }}</p>
                                            <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-wider">{{ $sched->class->class_name }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Lecturer Conflicts -->
        <div class="card-saas overflow-hidden">
            <div class="p-6 border-b border-siakad-100/50 dark:border-siakad-800/50 flex items-center justify-between bg-siakad-50/30 dark:bg-siakad-900/20">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-siakad-900 dark:text-white">{{ __('Lecturer Schedule Conflicts') }}</h3>
                        <p class="text-xs text-siakad-500 font-bold uppercase tracking-wider">{{ __('Overlapping classes for same lecturer') }}</p>
                    </div>
                </div>
                <span class="px-4 py-1.5 text-xs font-black bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 rounded-full border border-amber-200 dark:border-amber-900/50 shadow-sm">
                    {{ count($lecturerConflicts) }} {{ __('Conflicts') }}
                </span>
            </div>

            @if(empty($lecturerConflicts))
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center mx-auto mb-4 text-emerald-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-siakad-400 font-bold">{{ __('No lecturer conflicts detected.') }}</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-start border-collapse">
                    <thead>
                        <tr class="bg-siakad-50/50 dark:bg-siakad-900/30 border-b border-siakad-100/50 dark:border-siakad-800/50">
                            <th class="py-4 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Lecturer') }}</th>
                            <th class="py-4 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Day') }}</th>
                            <th class="py-4 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Overlapping Classes') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
                        @foreach($lecturerConflicts as $conflict)
                        <tr class="hover:bg-siakad-50/30 dark:hover:bg-siakad-900/20 transition-colors">
                            <td class="py-5 px-8 text-start">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-siakad-50 dark:bg-siakad-800 flex items-center justify-center text-siakad-600 dark:text-siakad-400 text-xs font-black">
                                        {{ strtoupper(substr($conflict['lecturer']->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-black text-siakad-900 dark:text-white">{{ $conflict['lecturer']->user->name }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-xs font-bold text-siakad-600 dark:text-siakad-400 capitalize text-start">{{ $conflict['day'] }}</td>
                            <td class="py-5 px-8 text-start">
                                <div class="space-y-3">
                                    @foreach($conflict['schedules'] as $sched)
                                    <div class="flex items-center gap-3">
                                        <div class="px-2 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 text-[10px] font-black font-mono border border-amber-100 dark:border-amber-900/50">
                                            {{ $sched->start_time->format('H:i') }} - {{ $sched->end_time->format('H:i') }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-siakad-700 dark:text-siakad-300 truncate">{{ $sched->class->course->course_name }}</p>
                                            <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-wider">{{ $sched->class->class_name }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Student Conflicts -->
        <div class="card-saas overflow-hidden">
            <div class="p-6 border-b border-siakad-100/50 dark:border-siakad-800/50 flex items-center justify-between bg-siakad-50/30 dark:bg-siakad-900/20">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/30 flex items-center justify-center text-purple-600 dark:text-purple-400 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-siakad-900 dark:text-white">{{ __('Student Schedule Conflicts') }}</h3>
                        <p class="text-xs text-siakad-500 font-bold uppercase tracking-wider">{{ __('Overlapping classes for same student') }}</p>
                    </div>
                </div>
                <span class="px-4 py-1.5 text-xs font-black bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400 rounded-full border border-purple-200 dark:border-purple-900/50 shadow-sm">
                    {{ count($studentConflicts) }} {{ __('Conflicts') }}
                </span>
            </div>

            @if(empty($studentConflicts))
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center mx-auto mb-4 text-emerald-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-siakad-400 font-bold">{{ __('No student conflicts detected.') }}</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-start border-collapse">
                    <thead>
                        <tr class="bg-siakad-50/50 dark:bg-siakad-900/30 border-b border-siakad-100/50 dark:border-siakad-800/50">
                            <th class="py-4 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Student') }}</th>
                            <th class="py-4 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Day') }}</th>
                            <th class="py-4 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Overlapping Classes') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
                        @foreach($studentConflicts as $conflict)
                        <tr class="hover:bg-siakad-50/30 dark:hover:bg-siakad-900/20 transition-colors">
                            <td class="py-5 px-8 text-start">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-siakad-50 dark:bg-siakad-800 flex items-center justify-center text-siakad-600 dark:text-siakad-400 text-xs font-black">
                                        {{ strtoupper(substr($conflict['student']->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-siakad-900 dark:text-white leading-tight">{{ $conflict['student']->user->name }}</p>
                                        <p class="text-[10px] text-siakad-400 font-mono font-bold">{{ $conflict['student']->nim }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-xs font-bold text-siakad-600 dark:text-siakad-400 capitalize text-start">{{ $conflict['day'] }}</td>
                            <td class="py-5 px-8 text-start">
                                <div class="space-y-3">
                                    @foreach($conflict['schedules'] as $sched)
                                    <div class="flex items-center gap-3">
                                        <div class="px-2 py-1 rounded-lg bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 text-[10px] font-black font-mono border border-purple-100 dark:border-purple-900/50">
                                            {{ $sched->start_time->format('H:i') }} - {{ $sched->end_time->format('H:i') }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-siakad-700 dark:text-siakad-300 truncate">{{ $sched->class->course->course_name }}</p>
                                            <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-wider">{{ $sched->class->class_name }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>