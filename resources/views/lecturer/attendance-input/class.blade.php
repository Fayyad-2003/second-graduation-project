<x-app-layout>
    <x-slot name="header">
        {{ __('Class Presence') }}
    </x-slot>

    <!-- Breadcrumb -->
    <div class="mb-5">
        <a href="{{ route('lecturers.attendance-input.index') }}" class="inline-flex items-center gap-2 text-primary-secondary hover:text-primary-primary transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            {{ __('Back to Class List') }}
        </a>
    </div>

    <!-- Compact Header Card -->
    <div class="card-saas p-5 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl bg-primary-primary/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-primary-dark">{{ $class->course->course_name }}</h1>
                    <p class="text-sm text-primary-secondary mt-0.5">{{ $class->course->course_code }} • {{ $class->course->credits }} {{ __('Credits') }} • {{ __('Class') }} {{ $class->class_name }}</p>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="inline-flex items-center gap-1.5 text-xs text-primary-secondary">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $class->details->count() }} {{ __('Students') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs text-primary-secondary">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $meetingList->count() }} {{ __('Meetings') }}
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('lecturers.attendance-input.meeting.create', $class) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-primary text-white rounded-lg font-medium hover:bg-primary-primary/90 transition text-sm min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('Create Meeting') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Meeting List -->
        <div class="lg:col-span-2">
            <div class="card-saas overflow-hidden">
                <div class="px-5 py-4 border-b border-primary-light flex items-center justify-between">
                    <h3 class="font-semibold text-primary-dark">{{ __('Meeting List') }}</h3>
                    <span class="text-xs text-primary-secondary bg-primary-light px-2 py-1 rounded-full">{{ $meetingList->count() }} {{ __('Total') }}</span>
                </div>

                @if($meetingList->isEmpty())
                <div class="p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-primary-light/50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-primary-secondary mb-4">{{ __('No meetings for this class yet') }}</p>
                    <a href="{{ route('lecturers.attendance-input.meeting.create', $class) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-primary text-white rounded-lg hover:bg-primary-primary/90 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('Create First Meeting') }}
                    </a>
                </div>
                @else
                <div class="divide-y divide-primary-light">
                    @foreach($meetingList as $meeting)
                    <div class="p-4 flex items-center justify-between hover:bg-primary-light/30 transition group">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-lg bg-primary-primary/10 text-primary-primary flex items-center justify-center font-bold text-sm">
                                {{ $meeting->meeting_number }}
                            </div>
                            <div>
                                <p class="font-medium text-primary-dark">{{ __('Meeting') }} {{ $meeting->meeting_number }}</p>
                                <p class="text-sm text-primary-secondary">{{ $meeting->date->format('d M Y') }} • {{ $meeting->topic ?? __('No material yet') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @php
                            $count = $meeting->attendances->count();
                            $total = $class->studyPlanDetails->count();
                            @endphp
                            <span class="text-xs px-2.5 py-1 rounded-full {{ $count > 0 ? 'bg-primary-primary/10 text-primary-primary' : 'bg-primary-light text-primary-secondary' }}">
                                {{ $count }}/{{ $total }} {{ __('present') }}
                            </span>
                            <a href="{{ route('lecturers.attendance-input.input', $meeting) }}" class="p-2 text-primary-primary hover:bg-primary-primary/10 rounded-lg transition opacity-0 group-hover:opacity-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Rekap Student -->
        <div>
            <div class="card-saas overflow-hidden">
                <div class="px-5 py-4 border-b border-primary-light">
                    <h3 class="font-semibold text-primary-dark">{{ __('Presence Summary') }}</h3>
                </div>

                @if($studentSummary->isEmpty())
                <div class="p-8 text-center text-primary-secondary text-sm">
                    {{ __('No students registered yet') }}
                </div>
                @else
                <div class="divide-y divide-primary-light max-h-[480px] overflow-y-auto">
                    @foreach($studentSummary as $item)
                    <div class="p-4 hover:bg-primary-light/20 transition">
                        <div class="flex items-center gap-3 mb-2.5">
                            <div class="w-9 h-9 rounded-full bg-primary-primary text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($item['student']->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-primary-dark truncate">{{ $item['student']->user->name }}</p>
                                <p class="text-xs text-primary-secondary">{{ $item['student']->student_number }}</p>
                            </div>
                            <span class="text-sm font-bold {{ $item['summary']['percentage'] >= 75 ? 'text-primary-primary' : 'text-primary-secondary' }}">
                                {{ $item['summary']['percentage'] }}%
                            </span>
                        </div>
                        <!-- Progress bar -->
                        <div class="h-1.5 bg-primary-light rounded-full overflow-hidden mb-2">
                            <div class="h-full bg-primary-primary rounded-full transition-all" style="width: {{ $item['summary']['percentage'] }}%"></div>
                        </div>
                        <div class="flex gap-2 text-xs">
                            <span class="px-2 py-0.5 bg-primary-primary/10 text-primary-primary rounded">P:{{ $item['summary']['present'] }}</span>
                            <span class="px-2 py-0.5 bg-primary-primary/5 text-primary-secondary rounded">S:{{ $item['summary']['sick'] }}</span>
                            <span class="px-2 py-0.5 bg-primary-primary/5 text-primary-secondary rounded">E:{{ $item['summary']['excused'] }}</span>
                            <span class="px-2 py-0.5 bg-primary-light text-primary-secondary rounded">A:{{ $item['summary']['absent'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>