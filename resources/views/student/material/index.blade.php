<x-app-layout>
    <x-slot name="header">
        {{ __('Learning Materials') }} - {{ $class->course->course_name }}
    </x-slot>

    @if($isArchived)
    <!-- Archive Notice -->
    <div class="mb-4 p-3 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
            <span>{{ __('This is material from a previous semester') }} ({{ $class->academicYear->display_name ?? __('Archive') }})</span>
        </div>
    </div>
    @endif

    <!-- Class Info Card -->
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
                <p class="text-sm text-primary-secondary dark:text-gray-400">{{ $class->course->course_code }} •
                    {{ $class->course->credits }} {{ __('Credits') }} • {{ __('Lecturer') }}: {{ $class->lecturer->user->name ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Meeting List with Material -->
    <div class="space-y-4">
        @forelse($meetingList as $meeting)
        <div class="card-saas dark:bg-gray-800 overflow-hidden">
            <!-- Meeting Header -->
            <div class="px-5 py-4 border-b border-primary-light dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-primary-primary/10 dark:bg-blue-500/20 rounded-full flex items-center justify-center">
                        <span
                            class="text-sm font-bold text-primary-primary dark:text-blue-400">{{ $meeting->meeting_number }}</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-primary-dark dark:text-white">{{ __('Meeting') }} {{ $meeting->meeting_number }}
                        </h3>
                        <p class="text-xs text-primary-secondary dark:text-gray-400">
                            {{ $meeting->date?->format('d M Y') ?? __('Not scheduled yet') }}
                            @if($meeting->topic)
                            <span class="mx-1">•</span>
                            {{ $meeting->topic }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Material List -->
            <div class="p-4">
                @if($meeting->materials->count() > 0)
                <div class="space-y-2">
                    @foreach($meeting->materials as $material)
                    <div
                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <div class="flex items-center gap-3">
                            @if($material->isFile())
                            <div
                                class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            @else
                            <div
                                class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                    </path>
                                </svg>
                            </div>
                            @endif
                            <div>
                                <h4 class="font-medium text-primary-dark dark:text-white text-sm">{{ $material->title }}
                                </h4>
                                @if($material->description)
                                <p class="text-xs text-primary-secondary dark:text-gray-400 line-clamp-1">
                                    {{ $material->description }}
                                </p>
                                @endif
                                @if($material->isFile())
                                <p class="text-xs text-primary-secondary dark:text-gray-500">{{ $material->file_name }} •
                                    {{ $material->formatted_file_size }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($material->isFile())
                            <a href="{{ route('students.material.download', [$class->id, $material->id]) }}"
                                class="px-3 py-1.5 text-xs font-medium bg-primary-primary text-white rounded-lg hover:bg-primary-primary/90 transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                {{ __('Download') }}
                            </a>
                            @elseif($material->isExternalLink())
                            <a href="{{ $material->link_external }}" target="_blank"
                                class="px-3 py-1.5 text-xs font-medium bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                                {{ __('Open Link') }}
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-primary-secondary dark:text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <p class="text-sm">{{ __('No materials for this meeting yet') }}</p>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="card-saas p-8 text-center dark:bg-gray-800">
            <p class="text-primary-secondary dark:text-gray-400">{{ __('No meetings scheduled for this semester.') }}</p>
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