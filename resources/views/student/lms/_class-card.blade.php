{{-- Class Card Partial - reusable card component for class list --}}
<div class="class-card card-saas dark:bg-gray-800 overflow-hidden hover:shadow-lg transition-shadow"
    data-search="{{ strtolower($class->course->course_name . ' ' . $class->course->course_code . ' ' . ($class->lecturer->user->name ?? '')) }}">
    <div class="p-5">
        <div class="flex items-start gap-3 mb-3">
            <div
                class="w-12 h-12 bg-gradient-to-br from-primary-primary to-primary-dark rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                {{ $class->class_name }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-2">
                    <h3 class="font-semibold text-primary-dark dark:text-white truncate">
                        {{ $class->course->course_name }}
                    </h3>
                    @if($class->is_archived ?? false)
                    <span
                        class="flex-shrink-0 px-2 py-0.5 text-xs font-medium bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full">
                        {{ __('Archive') }}
                    </span>
                    @endif
                </div>
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $class->course->course_code }} •
                    {{ $class->course->credits }} {{ __('Credits') }}
                </p>
            </div>
        </div>

        <p class="text-xs text-primary-secondary dark:text-gray-400 mb-4">
            {{ __('Lecturer') }}: {{ $class->lecturer->user->name ?? '-' }}
        </p>

        @if(($class->pending_assignments ?? 0) > 0 && !($class->is_archived ?? false))
        <div
            class="mb-4 px-3 py-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
            <p class="text-xs text-amber-700 dark:text-amber-400 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                {{ $class->pending_assignments }} {{ __('assignments not submitted yet') }}
            </p>
        </div>
        @endif

        <div class="flex flex-col gap-2">
            <div class="flex gap-2">
                <a href="{{ route('students.material.index', $class->id) }}"
                    class="flex-1 text-center px-3 py-2 text-sm font-medium bg-primary-light dark:bg-gray-700 text-primary-dark dark:text-white rounded-lg hover:bg-primary-light/80 dark:hover:bg-gray-600 transition">
                    {{ __('Learning Material') }}
                </a>
                <a href="{{ route('students.assignment.index', $class->id) }}"
                    class="flex-1 text-center px-3 py-2 text-sm font-medium {{ $class->is_archived ?? false ? 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300' : 'bg-primary-primary text-white hover:bg-primary-primary/90' }} rounded-lg transition">
                    {{ __('Assignment') }}
                </a>
            </div>

            @php
            $chatRequest = $class->chatRequests->first();
            @endphp

            @if(!$chatRequest)
            <form action="{{ route('students.lms.chat-request.store', $class->id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full text-center px-3 py-2 text-sm font-medium bg-green-500 text-white rounded-lg hover:bg-green-600 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    {{ __('Request Chat with Lecturer') }}
                </button>
            </form>
            @elseif($chatRequest->status === 'pending')
            <div class="w-full text-center px-3 py-2 text-sm font-medium bg-amber-100 text-amber-700 rounded-lg flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('Chat Request Pending') }}
            </div>
            @elseif($chatRequest->status === 'approved')
            <a href="{{ route('chat.show', $chatRequest) }}"
                class="w-full text-center px-3 py-2 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                {{ __('Start Chat') }}
            </a>
            @elseif($chatRequest->status === 'rejected')
            <form action="{{ route('students.lms.chat-request.store', $class->id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full text-center px-3 py-2 text-sm font-medium bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Request Again (Rejected)') }}
                </button>
            </form>
            @endif
        </div>
    </div>
</div>