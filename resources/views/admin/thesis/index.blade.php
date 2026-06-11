<x-app-layout>
    <x-slot name="header">
        {{ __('Thesis Management') }}
    </x-slot>

    <div class="mb-6">
        <p class="text-sm text-primary-secondary dark:text-gray-400">{{ __('Manage all student thesis submissions') }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card-saas p-4 flex items-center gap-3 dark:bg-gray-800">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-primary-dark dark:text-white">{{ $stats['total'] }}</p>
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Total Thesis') }}</p>
            </div>
        </div>
        <div class="card-saas p-4 flex items-center gap-3 dark:bg-gray-800">
            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-primary-dark dark:text-white">{{ $stats['active'] }}</p>
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Active') }}</p>
            </div>
        </div>
        <div class="card-saas p-4 flex items-center gap-3 dark:bg-gray-800">
            <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-primary-dark dark:text-white">{{ $stats['waiting_for_supervisor'] }}</p>
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Needs Advisor') }}</p>
            </div>
        </div>
        <div class="card-saas p-4 flex items-center gap-3 dark:bg-gray-800">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-primary-dark dark:text-white">{{ $stats['completed'] }}</p>
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Finished') }}</p>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card-saas p-4 mb-6 dark:bg-gray-800">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-primary-dark dark:text-gray-300 mb-1">{{ __('Search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}" class="input-saas w-full text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="{{ __('Name, Student ID, or Title...') }}">
            </div>
            <div class="w-48">
                <label class="block text-xs font-medium text-primary-dark dark:text-gray-300 mb-1">{{ __('Status') }}</label>
                <select name="status" class="input-saas w-full text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    <option value="">{{ __('All Statuses') }}</option>
                    @foreach($statusList as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Filter') }}</button>
        </form>
    </div>

    <!-- Table Card (Desktop) -->
    <div class="hidden md:block card-saas overflow-hidden dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead>
                    <tr class="bg-primary-light/30 dark:bg-gray-900">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase w-16">#</th>

                        <!-- Sortable: Student -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'student_name', 'order' => request('sort') == 'student_name' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="group flex items-center gap-1 hover:text-primary-primary transition">{{ __('Students') }}<span class="flex flex-col text-[10px] leading-none {{ in_array(request('sort'), ['student_name', 'student_name']) ? 'text-primary-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ in_array(request('sort'), ['student_name', 'student_name']) && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ in_array(request('sort'), ['student_name', 'student_name']) && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Title -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'order' => request('sort') == 'title' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="group flex items-center gap-1 hover:text-primary-primary transition">
                                {{ __('Title') }}
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') === 'title' ? 'text-primary-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') === 'title' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') === 'title' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Advisor -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'supervisor_name', 'order' => request('sort') == 'supervisor_name' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="group flex items-center gap-1 hover:text-primary-primary transition">
                                {{ __('Advisor') }}
                                <span class="flex flex-col text-[10px] leading-none {{ in_array(request('sort'), ['supervisor_name', 'supervisor_name']) ? 'text-primary-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ in_array(request('sort'), ['supervisor_name', 'supervisor_name']) && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ in_array(request('sort'), ['supervisor_name', 'supervisor_name']) && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Status -->
                        <th class="text-center py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'order' => request('sort') == 'status' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="group flex items-center gap-1 hover:text-primary-primary transition justify-center">
                                {{ __('Status') }}
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'status' ? 'text-primary-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'status' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'status' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <th class="text-right py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($thesisList as $index => $thesis)
                    <tr class="border-b border-primary-light/50 dark:border-gray-700/50">
                        <td class="py-4 px-5 text-sm text-primary-secondary dark:text-gray-400">{{ $thesisList->firstItem() + $index }}</td>
                        <td class="py-4 px-5">
                            <p class="font-medium text-primary-dark dark:text-white">{{ $thesis->student->user->name }}</p>
                            <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $thesis->student->student_number }}</p>
                        </td>
                        <td class="py-4 px-5">
                            <p class="text-sm text-primary-dark dark:text-white" title="{{ $thesis->title }}">{{ Str::limit($thesis->title, 50) }}</p>
                            @if($thesis->research_field)
                            <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $thesis->research_field }}</p>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-sm">
                            @if($thesis->supervisor1)
                            <p class="text-primary-dark dark:text-white">{{ $thesis->supervisor1->user->name }}</p>
                            @if($thesis->supervisor2)
                            <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $thesis->supervisor2->user->name }}</p>
                            @endif
                            @else
                            <span class="text-amber-600 dark:text-amber-400 text-xs">{{ __('Not specified') }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-{{ $thesis->status_color }}-100 text-{{ $thesis->status_color }}-700 dark:bg-{{ $thesis->status_color }}-900/50 dark:text-{{ $thesis->status_color }}-400">{{ $thesis->status_label }}</span>
                        </td>
                        <td class="py-4 px-5 text-right">
                            <a href="{{ route('admin.thesis.show', $thesis) }}" class="inline-flex items-center gap-1 text-sm text-primary-primary dark:text-blue-400 hover:underline">{{ __('Details') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr class="dark:bg-gray-800">
                        <td colspan="6" class="py-12 text-center text-primary-secondary dark:text-gray-400">{{ __('No thesis data available') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card List -->
    <div class="md:hidden space-y-4">
        @forelse($thesisList as $thesis)
        <div class="card-saas p-4 dark:bg-gray-800">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-primary dark:bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                        {{ strtoupper(substr($thesis->student->user->name ?? '-', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-primary-dark dark:text-white">{{ $thesis->student->user->name }}</h4>
                        <p class="text-xs text-primary-secondary dark:text-gray-400 font-mono">{{ $thesis->student->student_number }}</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-medium rounded-full bg-{{ $thesis->status_color }}-100 text-{{ $thesis->status_color }}-700 dark:bg-{{ $thesis->status_color }}-900/50 dark:text-{{ $thesis->status_color }}-400">{{ $thesis->status_label }}</span>
            </div>

            <div class="mb-4">
                <p class="text-sm font-medium text-primary-dark dark:text-white line-clamp-2 mb-1">{{ $thesis->title }}</p>
                @if($thesis->research_field)
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $thesis->research_field }}</p>
                @endif
            </div>

            <div class="bg-primary-light/30 dark:bg-gray-700/30 rounded-lg p-3 mb-4">
                <p class="text-xs text-primary-secondary dark:text-gray-400 mb-1">{{ __('Advisor') }}</p>
                @if($thesis->supervisor1)
                <p class="text-sm font-medium text-primary-dark dark:text-white">{{ $thesis->supervisor1->user->name }}</p>
                @if($thesis->supervisor2)
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $thesis->supervisor2->user->name }}</p>
                @endif
                @else
                <span class="text-amber-600 dark:text-amber-400 text-xs">{{ __('Not specified') }}</span>
                @endif
            </div>

            <a href="{{ route('admin.thesis.show', $thesis) }}" class="flex items-center justify-center w-full py-2 bg-primary-light dark:bg-gray-700 text-primary-dark dark:text-white font-medium rounded-lg hover:bg-gray-200 transition text-sm">
                {{ __('View Details') }}
            </a>
        </div>
        @empty
        <div class="card-saas p-8 text-center">
            <p class="text-primary-secondary dark:text-gray-400">{{ __('No thesis data available') }}</p>
        </div>
        @endforelse
    </div>

    @if($thesisList->hasPages())
    <div class="card-saas px-5 py-4 border-t border-primary-light dark:border-gray-700 dark:bg-gray-800 mt-4 md:mt-0">
        {{ $thesisList->links() }}
    </div>
    @endif
</x-app-layout>