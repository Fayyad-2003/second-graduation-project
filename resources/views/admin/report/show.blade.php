<x-app-layout>
    <x-slot name="header">{{ __('Review Report') }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Report Content -->
        <div class="card-saas dark:bg-gray-800 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-siakad-dark dark:text-white">{{ $report->subject }}</h2>
                    <div class="flex items-center gap-2 mt-1">
                            <p class="text-sm text-siakad-secondary dark:text-gray-400">
                            {{ __('From:') }} <span class="font-medium text-siakad-dark dark:text-gray-300">{{ $report->user->name }}</span> ({{ $report->user->student->student_number ?? '-' }})
                        </p>
                        <span class="text-gray-300 dark:text-gray-600">•</span>
                        <p class="text-sm text-siakad-secondary dark:text-gray-400">
                            {{ $report->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
                <div>
                        @if($report->status === 'pending')
                        <span class="px-3 py-1 text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-full font-medium">{{ __('Pending') }}</span>
                    @elseif($report->status === 'replied')
                        <span class="px-3 py-1 text-xs bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full font-medium">{{ __('Replied') }}</span>
                    @else
                        <span class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full font-medium">{{ __('Closed') }}</span>
                    @endif
                </div>
            </div>

            <div class="prose dark:prose-invert max-w-none text-siakad-dark dark:text-gray-300 whitespace-pre-wrap mb-6">{{ $report->message }}</div>

            @if($report->status !== 'closed')
                <div class="flex justify-end border-t border-gray-100 dark:border-gray-700 pt-4">
                    <form action="{{ route('admin.report.close', $report) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to close this report?') }}')">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium transition">
                            {{ __('Close Report') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Reply Form / Previous Reply -->
        <div class="card-saas dark:bg-gray-800 p-6">
            <h3 class="font-bold text-siakad-dark dark:text-white mb-4">{{ $report->admin_reply ? __('Previous Reply') : __('Leave a Reply') }}</h3>
            
            @if($report->admin_reply)
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 mb-6">
                    <div class="prose dark:prose-invert max-w-none text-siakad-dark dark:text-gray-300 whitespace-pre-wrap">{{ $report->admin_reply }}</div>
                    <p class="text-xs text-siakad-secondary dark:text-gray-500 mt-4">
                        {{ __('Replied on') }} {{ $report->replied_at->format('d M Y, H:i') }}
                    </p>
                </div>
            @endif

            @if($report->status !== 'closed')
                <form action="{{ route('admin.report.reply', $report) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <textarea name="admin_reply" rows="4" class="input-saas w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" placeholder="{{ __('Write your reply here...') }}" required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary-saas px-6 py-2 rounded-lg text-sm font-medium">
                            {{ $report->admin_reply ? __('Update Reply') : __('Send Reply') }}
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <div class="flex justify-start">
            <a href="{{ route('admin.report.index') }}" class="flex items-center gap-2 text-sm text-siakad-secondary dark:text-gray-400 hover:text-siakad-primary transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Back to Reports List') }}
            </a>
        </div>
    </div>
</x-app-layout>
