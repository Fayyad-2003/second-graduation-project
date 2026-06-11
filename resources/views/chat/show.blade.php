<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ url()->previous() }}" class="text-primary-secondary hover:text-primary-primary transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Chat with') }} {{ Auth::user()->role === 'student' ? $chatRequest->lecturer->user->name : $chatRequest->student->user->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-[600px]">
                <!-- Chat Header Info -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <p class="text-sm text-primary-secondary dark:text-gray-400">
                        {{ __('Subject') }}: <span class="font-semibold text-primary-dark dark:text-white">{{ $chatRequest->academicClass->course->course_name }} ({{ $chatRequest->academicClass->class_name }})</span>
                    </p>
                </div>

                <!-- Messages Container -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50/30 dark:bg-gray-900/10">
                    @forelse($messages as $message)
                    <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%] rounded-2xl px-4 py-2 shadow-sm {{ $message->sender_id === Auth::id() ? 'bg-primary-primary text-white rounded-tr-none' : 'bg-white dark:bg-gray-700 text-primary-dark dark:text-white rounded-tl-none border border-gray-100 dark:border-gray-600' }}">
                            <p class="text-sm">{{ $message->message }}</p>
                            <p class="text-[10px] mt-1 opacity-70 text-right">
                                {{ $message->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-primary-secondary dark:text-gray-500">{{ __('No messages yet. Start the conversation!') }}</p>
                    </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <form action="{{ route('chat.send', $chatRequest) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="message" placeholder="{{ __('Type your message here...') }}"
                            class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-primary focus:border-primary-primary"
                            required autocomplete="off">
                        <button type="submit" class="bg-primary-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition flex items-center gap-2">
                            <span>{{ __('Send') }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-2-9-18-9 18 9 2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Scroll to bottom of messages container
        const container = document.getElementById('messages-container');
        container.scrollTop = container.scrollHeight;
    </script>
</x-app-layout>