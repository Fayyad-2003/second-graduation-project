<x-app-layout>
    <x-slot name="header">
        {{ $assignment->title }}
    </x-slot>

    <!-- Back Link -->
    <div class="mb-4">
        <a href="{{ route('students.assignment.index', $class->id) }}"
            class="text-sm text-primary-secondary hover:text-primary-primary transition flex items-center gap-1">


            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            {{ __('Back to Assignment List') }}
        </a>
    </div>

    @if($isArchived)
    <!-- Archive Notice -->
    <div class="mb-4 p-3 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
            {{ __('This assignment is from a previous semester (:semester).', ['semester' => $class->academicYear->display_name ?? __('Archive')]) }}
            {{ __('Assignment submissions are not available.') }}
        </div>
    </div>
    @endif

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Assignment Detail -->
        <div class="md:col-span-2 space-y-6">
            <div class="card-saas p-5 dark:bg-gray-800">
                <div class="flex items-center gap-2 mb-2">
                    <h2 class="text-xl font-bold text-primary-dark dark:text-white">{{ $assignment->title }}</h2>
                    @if($isArchived)
                    <span
                        class="px-2 py-0.5 text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full">{{ __('Archived') }}</span>
                    @endif
                </div>
                <p class="text-sm text-primary-secondary dark:text-gray-400 mb-4">{{ $class->course->course_name }} -
                    {{ $class->class_name }}
                </p>

                @if($assignment->description)
                <div class="prose dark:prose-invert max-w-none text-primary-dark dark:text-gray-300 mb-4">
                    {!! nl2br(e($assignment->description)) !!}
                </div>
                @endif

                @if($assignment->assignment_file)
                <div class="mt-4">
                    <a href="{{ route('students.assignment.download', [$class->id, $assignment->id]) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-primary-light dark:bg-gray-700 text-primary-dark dark:text-white rounded-lg hover:bg-primary-light/80 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        {{ __('Question file') }}
                    </a>
                </div>
                @endif
            </div>

            <!-- Submission Form or Status -->
            @if($submission)
            <div class="card-saas p-5 dark:bg-gray-800">
                <h3 class="font-semibold text-primary-dark dark:text-white mb-4">{{ __('Your Submission') }}</h3>
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">{{ __('Assignment Submitted') }}</span>
                    </div>
                    <p class="text-sm text-primary-secondary dark:text-gray-400">
                        {{ __('Submitted on') }} {{ $submission->submitted_at->format('d M Y, H:i') }}
                        @if(!$submission->isOnTime())
                        <span class="text-yellow-500">({{ __('Late') }})</span>
                        @endif
                    </p>
                    <p class="text-sm text-primary-secondary dark:text-gray-400">{{ __('File') }}: {{ $submission->file_name }}</p>
                </div>

                @if($submission->isGraded())
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium text-primary-dark dark:text-white">{{ __('Your Grade') }}</span>
                        <span class="text-2xl font-bold text-primary-primary">{{ $submission->grade }} <span
                                class="text-sm">({{ $submission->grade_letter }})</span></span>
                    </div>
                    @if($submission->feedback)
                    <div class="mt-3 pt-3 border-t border-blue-200 dark:border-blue-800">
                        <p class="text-sm font-medium text-primary-dark dark:text-white mb-1">{{ __('Lecturer Feedback') }}:</p>
                        <p class="text-sm text-primary-secondary dark:text-gray-400">{{ $submission->feedback }}</p>
                    </div>
                    @endif
                </div>
                @else
                <p class="text-sm text-primary-secondary dark:text-gray-400 italic">{{ __('Waiting for grading from lecturer...') }}</p>
                @endif
            </div>
            @elseif($isArchived)
            <div class="card-saas p-5 dark:bg-gray-800">
                <div class="text-center py-4">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                        </path>
                    </svg>
                    <p class="text-primary-dark dark:text-white font-medium">{{ __('Archived Semester') }}</p>
                    <p class="text-sm text-primary-secondary dark:text-gray-400">{{ __('This assignment is from a previous semester. Submission is not available.') }}</p>
                </div>
            </div>
            @elseif($assignment->isOpen())
            <div class="card-saas p-5 dark:bg-gray-800">
                <h3 class="font-semibold text-primary-dark dark:text-white mb-4">{{ __('Submit Assignment') }}</h3>
                <form action="{{ route('students.assignment.submit', [$class->id, $assignment->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Upload File') }}</label>
                            <input type="file" name="file"
                                class="input-saas w-full px-4 py-2 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-primary-primary/10 file:text-primary-primary"
                                required>
                            <p class="text-xs text-primary-secondary mt-1">{{ __('Extensions') }}: {{ $assignment->allowed_extensions }} •
                                {{ __('Max') }}: {{ $assignment->formatted_max_file_size }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Notes (Optional)') }}</label>
                            <textarea name="notes" rows="2"
                                class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                                placeholder="{{ __('Add notes if needed...') }}"></textarea>
                        </div>
                        <button type="submit" class="w-full btn-primary-saas py-3 rounded-lg text-sm font-medium">
                            {{ __('Submit Assignment') }}
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="card-saas p-5 dark:bg-gray-800">
                <div class="text-center py-4">
                    <svg class="w-12 h-12 mx-auto mb-3 text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-primary-dark dark:text-white font-medium">{{ __('Unable to Submit') }}</p>
                    <p class="text-sm text-primary-secondary dark:text-gray-400">{{ __('The deadline has passed or the assignment is not active.') }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="card-saas p-4 dark:bg-gray-800">
                <h4 class="font-medium text-primary-dark dark:text-white mb-3">{{ __('Assignment Information') }}</h4>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-primary-secondary dark:text-gray-400">{{ __('Deadline') }}</dt>
                        <dd class="text-primary-dark dark:text-white font-medium">{{ $assignment->deadline->format('d M Y') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-primary-secondary dark:text-gray-400">{{ __('Time') }}</dt>
                        <dd class="text-primary-dark dark:text-white font-medium">{{ $assignment->deadline->format('H:i') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-primary-secondary dark:text-gray-400">{{ __('Status') }}</dt>
                        <dd>
                            @if($assignment->isOverdue())
                            <span class="text-red-500 font-medium">{{ __('Past Deadline') }}</span>
                            @else
                            <span class="text-green-500 font-medium">{{ $assignment->remaining_time }}</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>