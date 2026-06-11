<x-app-layout>
    <x-slot name="header">{{ __('Report Detail') }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Report Content -->
        <div class="card-saas dark:bg-gray-800 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-primary-dark dark:text-white">{{ $report->subject }}</h2>
                    <p class="text-sm text-primary-secondary dark:text-gray-400 mt-1">
                        {{ __('Sent at') }} {{ $report->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <div>
                    @if($report->status === 'pending')
                    <span class="px-3 py-1 text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-full font-medium">{{ __('Waiting for Reply') }}</span>
                    @elseif($report->status === 'replied')
                    <span class="px-3 py-1 text-xs bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full font-medium">{{ __('Already Replied') }}</span>
                    @else
                    <span class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full font-medium">{{ __('Closed') }}</span>
                    @endif
                </div>
            </div>

            <div class="prose dark:prose-invert max-w-none text-primary-dark dark:text-gray-300 whitespace-pre-wrap">{{ $report->message }}</div>
        </div>

        <!-- Admin Reply -->
        @if($report->admin_reply)
        <div class="card-saas bg-primary-primary/5 dark:bg-primary-primary/10 border-primary-primary/20 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-primary-primary flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-primary-dark dark:text-white">{{ __('Admin Reply') }}</h3>
                    <p class="text-xs text-primary-secondary dark:text-gray-400">
                        {{ $report->replied_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
            <div class="prose dark:prose-invert max-w-none text-primary-dark dark:text-gray-300 whitespace-pre-wrap">{{ $report->admin_reply }}</div>
        </div>
        @elseif($report->status === 'pending')
        <div class="text-center p-8 bg-gray-50 dark:bg-gray-900/30 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700">
            <p class="text-primary-secondary dark:text-gray-400">{{ __('Your report is waiting for review from admin.') }}</p>
        </div>
        @endif

        <div class="flex justify-start">
            <a href="{{ route('students.report.index') }}" class="flex items-center gap-2 text-sm text-primary-secondary dark:text-gray-400 hover:text-primary-primary transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Back to Report List') }}
            </a>
        </div>
    </div>
</x-app-layout>