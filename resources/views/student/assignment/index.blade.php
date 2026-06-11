<x-app-layout>
    <x-slot name="header">
        {{ __('Assignments') }} - {{ $class->course->course_name }}
    </x-slot>

    @if($isArchived)
    <!-- Archive Notice -->
    <div class="mb-4 p-3 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
            <span>{{ __('This is material from a previous semester') }} ({{ $class->academicYear->display_name ?? __('Archive') }}). {{ __('Assignment submission is not available.') }}</span>
        </div>
    </div>
    @endif

    <!-- Class Info -->
    <div class="card-saas p-4 mb-6 dark:bg-gray-800">
        <div class="flex items-center gap-4">
            <div
                class="w-12 h-12 bg-gradient-to-br from-primary-primary to-primary-dark rounded-xl flex items-center justify-center text-white font-bold text-lg">
                {{ $class->class_name }}
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-primary-dark dark:text-white">{{ $class->course->course_name }}
                    </h2>
                    @if($isArchived)
                    <span
                        class="px-2 py-0.5 text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full">{{ __('Archive') }}</span>
                    @endif
                </div>
                <p class="text-sm text-primary-secondary dark:text-gray-400">{{ $class->course->course_code }} • {{ __('Lecturer') }}:
                    {{ $class->lecturer->user->name ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Assignment List -->
    <div class="space-y-4">
        @forelse($assignments as $assignment)

        @php
        $submission = $assignment->submissions->first();
        @endphp
        <div class="card-saas dark:bg-gray-800 overflow-hidden">
            <div class="p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold text-primary-dark dark:text-white mb-1">{{ $assignment->title }}</h3>
                        @if($assignment->description)
                        <p class="text-sm text-primary-secondary dark:text-gray-400 mb-3 line-clamp-2">
                            {{ $assignment->description }}
                        </p>
                        @endif
                        <div class="flex items-center gap-4 text-xs text-primary-secondary dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @if($assignment->isOverdue())
                                <span class="text-red-500">{{ __('Deadline has passed') }}</span>
                                @else
                                {{ $assignment->remaining_time }}
                                @endif
                            </span>
                            <span>{{ __('Deadline') }}: {{ $assignment->deadline->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        @if($submission)
                        @if($submission->isGraded())
                        <div class="text-center">
                            <p class="text-2xl font-bold text-primary-primary">{{ $submission->grade }}</p>
                            <p class="text-xs text-primary-secondary">{{ __('Grade') }} ({{ $submission->grade_letter }})</p>
                        </div>
                        @else
                        <span
                            class="px-3 py-1 text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-lg">{{ __('Waiting for grading') }}</span>
                        @endif
                        @elseif($isArchived)
                        <span
                            class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-lg">{{ __('Semester Archive') }}</span>
                        @elseif($assignment->isOpen())
                        <a href="{{ route('students.assignment.show', [$class->id, $assignment->id]) }}"
                            class="px-4 py-2 text-sm font-medium bg-primary-primary text-white rounded-lg hover:bg-primary-primary/90 transition">
                            {{ __('Submit Assignment') }}
                        </a>
                        @else
                        <span
                            class="px-3 py-1 text-xs bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg">{{ __('Unable to submit') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @if($submission)
            <div
                class="px-5 py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-primary-light dark:border-gray-700 flex items-center justify-between">
                <p class="text-xs text-primary-secondary dark:text-gray-400">
                    {{ __('Submitted') }}: {{ $submission->submitted_at->format('d M Y, H:i') }}
                    @if(!$submission->isOnTime())
                    <span class="text-yellow-500">({{ __('Late') }})</span>
                    @endif
                </p>
                @if($submission->feedback)
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Feedback') }}:
                    {{ Str::limit($submission->feedback, 50) }}
                </p>
                @endif
            </div>
            @endif
        </div>
        @empty
        <div class="card-saas p-8 text-center dark:bg-gray-800">
            <svg class="w-12 h-12 mx-auto mb-3 text-primary-secondary/50" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                </path>
            </svg>
            <p class="text-primary-secondary dark:text-gray-400">{{ __('No assignments for this class yet.') }}</p>
        </div>
        @endforelse
    </div>

    <!-- Back Link -->
    <div class="mt-6">
        <a href="{{ route('students.lms.index') }}"
            class="text-sm text-primary-secondary hover:text-primary-primary transition">
            &larr; {{ __('Back to Materials & Assignments Page') }}
        </a>
    </div>
</x-app-layout>