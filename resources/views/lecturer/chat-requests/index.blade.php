<x-app-layout>
    <x-slot name="header">
        {{ __('Chat Requests') }}
    </x-slot>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-siakad-dark dark:text-white">{{ __('Manage Chat Requests') }}</h2>
        <p class="text-sm text-siakad-secondary dark:text-gray-400">{{ __('Approve or reject chat requests from students') }}</p>
    </div>

    @if($requests->isEmpty())
    <div class="card-saas p-8 text-center dark:bg-gray-800">
        <svg class="w-16 h-16 mx-auto mb-4 text-siakad-secondary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <p class="text-siakad-secondary dark:text-gray-400">{{ __('No chat requests yet.') }}</p>
    </div>
    @else
    <div class="grid gap-4">
        @foreach($requests as $request)
        <div class="card-saas p-5 dark:bg-gray-800">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-siakad-primary/10 rounded-full flex items-center justify-center text-siakad-primary font-bold text-lg flex-shrink-0">
                        {{ strtoupper(substr($request->student->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-siakad-dark dark:text-white">{{ $request->student->user->name }}</h3>
                        <p class="text-sm text-siakad-secondary dark:text-gray-400">
                            {{ $request->academicClass->course->course_name }} ({{ $request->academicClass->class_name }})
                        </p>
                        <p class="text-xs text-siakad-secondary dark:text-gray-500 mt-1">
                            {{ $request->created_at->diffForHumans() }}
                        </p>
                        @if($request->message)
                        <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-sm text-siakad-dark dark:text-gray-300 italic">
                            "{{ $request->message }}"
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($request->status === 'pending')
                    <form action="{{ route('lecturers.chat-requests.update', $request) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Approve') }}
                        </button>
                    </form>
                    <form action="{{ route('lecturers.chat-requests.update', $request) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 text-sm font-semibold rounded-lg hover:bg-red-100 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ __('Reject') }}
                        </button>
                    </form>
                    @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $request->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ __($request->status) }}
                    </span>
                    @if($request->status === 'approved')
                    <a href="{{ route('chat.show', $request) }}" class="px-4 py-2 bg-siakad-primary text-white text-sm font-semibold rounded-lg hover:bg-siakad-primary/90 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        {{ __('Chat') }}
                    </a>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</x-app-layout>