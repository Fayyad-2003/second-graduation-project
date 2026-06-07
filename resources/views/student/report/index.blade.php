<x-app-layout>
    <x-slot name="header">{{ __('Student Grade Report') }}</x-slot>

    <div class="w-full px-0">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 px-4 md:px-6">
            <div>
                <p class="text-sm text-siakad-secondary dark:text-gray-400">{{ __('Send and monitor your reports to admin') }}</p>
            </div>
            <div>
                <a href="{{ route('students.report.create') }}" class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium">
                    {{ __('Send New Report') }}
                </a>
            </div>
        </div>

        <div class="card-saas dark:bg-gray-800 overflow-hidden !w-full !max-w-none">
            <div class="overflow-x-auto w-full">
                <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Subject') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Date') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-siakad-dark dark:text-white">{{ $report->subject }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($report->status === 'pending')
                                <span class="px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-full">{{ __('Pending') }}</span>
                                @elseif($report->status === 'replied')
                                <span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full">{{ __('Replied') }}</span>
                                @else
                                <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full">{{ __('Closed') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-siakad-secondary dark:text-gray-400">
                                {{ $report->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('students.report.show', $report) }}" class="text-siakad-primary hover:text-siakad-dark transition">
                                    {{ __('View Details') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-siakad-secondary dark:text-gray-400">
                                {{ __('No reports sent yet.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>