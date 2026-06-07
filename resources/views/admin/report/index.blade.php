<x-app-layout>
    <x-slot name="header">{{ __('Student Report Management') }}</x-slot>

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm text-siakad-secondary dark:text-gray-400">{{ __('Review and reply to reports sent by students') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search subject or name...') }}" class="input-saas px-4 py-2 text-sm w-48 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                <select name="status" class="input-saas px-4 py-2 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>{{ __('Replied') }}</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>{{ __('Closed') }}</option>
                </select>
                <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium">{{ __('Filter') }}</button>
            </form>
        </div>
    </div>

    <div class="card-saas dark:bg-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Student') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Subject') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($reports as $report)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-siakad-dark dark:text-white">{{ $report->user->name }}</div>
                                <div class="text-xs text-siakad-secondary dark:text-gray-500">{{ $report->user->student->student_number ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-siakad-dark dark:text-white">{{ Str::limit($report->subject, 40) }}</div>
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
                                {{ $report->created_at->format('d/m/y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.report.show', $report) }}" class="text-siakad-primary hover:text-siakad-dark transition">
                                    {{ __('Review') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-siakad-secondary dark:text-gray-400">
                                {{ __('No reports found.') }}
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
</x-app-layout>
