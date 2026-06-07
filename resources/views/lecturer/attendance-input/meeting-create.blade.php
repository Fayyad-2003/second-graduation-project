<x-app-layout>
    <x-slot name="header">
        {{ __('Create New Meeting') }}
    </x-slot>

    <!-- Breadcrumb -->
    <div class="mb-5">
        <a href="{{ route('lecturers.attendance-input.class', $class) }}" class="inline-flex items-center gap-2 text-siakad-secondary hover:text-siakad-primary transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            {{ __('Back to') }} {{ $class->course->course_name }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="card-saas overflow-hidden">
                <!-- Header -->
                <div class="p-5 bg-siakad-primary text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold">{{ $class->course->course_name }}</h2>
                            <p class="text-white/70 text-sm">{{ $class->course->course_code }} • {{ __('Class') }} {{ $class->class_name }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('lecturers.attendance-input.meeting.store', $class) }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <!-- Schedule Selection -->
                    <div>
                        <label class="block text-sm font-medium text-siakad-dark mb-2">{{ __('Academic Schedule') }}</label>
                        @if($scheduleList->isEmpty())
                        <div class="p-4 bg-siakad-light text-siakad-secondary rounded-lg text-sm">
                            {{ __('No schedule for this class. Contact administrator to add schedule.') }}
                        </div>
                        @else
                        <div class="space-y-2">
                            @foreach($scheduleList as $schedule)
                            <label class="flex items-center gap-3 p-4 border border-siakad-light rounded-lg cursor-pointer hover:border-siakad-primary/50 transition has-[:checked]:border-siakad-primary has-[:checked]:bg-siakad-primary/5">
                                <input type="radio" name="course_schedule_id" value="{{ $schedule->id }}" class="text-siakad-primary focus:ring-siakad-primary" {{ old('course_schedule_id') == $schedule->id ? 'checked' : ($loop->first && !old('course_schedule_id') ? 'checked' : '') }} required>
                                <div>
                                    <p class="font-medium text-siakad-dark">{{ __($schedule->day) }}</p>
                                    <p class="text-sm text-siakad-secondary">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} • {{ $schedule->room ?? __('Room') . ' TBA' }}</p>
                                    <p class="text-xs text-siakad-primary mt-1 font-medium">{{ __('Next Meeting') }}: {{ __('to-') }}{{ $nextMeetingNumber[$schedule->id] ?? 1 }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @endif
                        @error('course_schedule_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meeting Ke -->
                    <div>
                        <label for="meeting_number" class="block text-sm font-medium text-siakad-dark mb-2">{{ __('Meeting No.') }}</label>
                        <input type="number" name="meeting_number" id="meeting_number" min="1" max="16" 
                            value="{{ old('meeting_number', $nextMeetingNumber[$scheduleList->first()?->id] ?? 1) }}"
                            class="w-full px-4 py-3 rounded-lg border border-siakad-light bg-white text-siakad-dark focus:border-siakad-primary focus:ring-2 focus:ring-siakad-primary/20 transition" required>
                        @error('meeting_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-siakad-dark mb-2">{{ __('Date') }}</label>
                        <input type="date" name="date" id="date" 
                            value="{{ old('date', date('Y-m-d')) }}"
                            class="w-full px-4 py-3 rounded-lg border border-siakad-light bg-white text-siakad-dark focus:border-siakad-primary focus:ring-2 focus:ring-siakad-primary/20 transition" required>
                        @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Material -->
                    <div>
                        <label for="topic" class="block text-sm font-medium text-siakad-dark mb-2">{{ __('Topic (Optional)') }}</label>
                        <input type="text" name="topic" id="topic" 
                            value="{{ old('topic') }}"
                            placeholder="{{ __('Topic or material discussed') }}"
                            class="w-full px-4 py-3 rounded-lg border border-siakad-light bg-white text-siakad-dark placeholder:text-siakad-secondary focus:border-siakad-primary focus:ring-2 focus:ring-siakad-primary/20 transition">
                        @error('topic')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 px-6 py-3 bg-siakad-primary text-white rounded-lg font-medium hover:bg-siakad-primary/90 transition min-h-[44px]">
                            {{ __('Create & Input Presence') }}
                        </button>
                        <a href="{{ route('lecturers.attendance-input.class', $class) }}" class="px-6 py-3 border border-siakad-light text-siakad-secondary rounded-lg font-medium hover:bg-siakad-light/50 transition">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Info Class -->
            <div class="card-saas p-5">
                <h3 class="font-semibold text-siakad-dark mb-4 text-sm">{{ __('Class Information') }}</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-siakad-secondary">{{ __('Students') }}</span>
                        <span class="text-sm font-semibold text-siakad-dark">{{ $class->details->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-siakad-secondary">{{ __('Credits') }}</span>
                        <span class="text-sm font-semibold text-siakad-dark">{{ $class->course->credits }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-siakad-secondary">{{ __('Capacity') }}</span>
                        <span class="text-sm font-semibold text-siakad-dark">{{ $class->details->count() }}/{{ $class->capacity }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-siakad-secondary">{{ __('Semester') }}</span>
                        <span class="text-sm font-semibold text-siakad-dark">{{ $class->course->semester }}</span>
                    </div>
                </div>
            </div>

            <!-- Riwayat Meeting -->
            <div class="card-saas overflow-hidden">
                <div class="px-5 py-4 border-b border-siakad-light">
                    <h3 class="font-semibold text-siakad-dark text-sm">{{ __('Meeting History') }}</h3>
                </div>
                @php
                    $recentMeetings = \App\Models\Meeting::byClass($class->id)
                        ->orderBy('meeting_number', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                @if($recentMeetings->isEmpty())
                <div class="p-5 text-center">
                    <div class="w-12 h-12 rounded-full bg-siakad-light/50 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-siakad-secondary text-sm">{{ __('No meetings yet') }}</p>
                    <p class="text-xs text-siakad-secondary mt-1">{{ __('This will be the first meeting') }}</p>
                </div>
                @else
                <div class="divide-y divide-siakad-light max-h-64 overflow-y-auto">
                    @foreach($recentMeetings as $meeting)
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-siakad-primary/10 text-siakad-primary flex items-center justify-center font-bold text-sm flex-shrink-0">
                            {{ $meeting->meeting_number }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-siakad-dark truncate">{{ $meeting->topic ?? __('Meeting') . ' ' . $meeting->meeting_number }}</p>
                            <p class="text-xs text-siakad-secondary">{{ $meeting->date->format('d M Y') }}</p>
                        </div>
                        <span class="text-xs bg-siakad-primary/10 text-siakad-primary px-2 py-1 rounded-full">
                            {{ $meeting->attendance->count() }} {{ __('present') }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
