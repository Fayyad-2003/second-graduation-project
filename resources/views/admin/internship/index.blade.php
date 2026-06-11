<x-app-layout>
    <x-slot name="header">{{ __('Internship Management') }}</x-slot>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @foreach([[__('Total'), $stats['total'], 'indigo'], [__('Active'), $stats['active'], 'blue'], [__('Needs Advisor'), $stats['needs_supervisor'], 'amber'], [__('Completed'), $stats['completed'], 'emerald']] as $s)
        <div class="card-saas p-4 flex items-center gap-3 dark:bg-gray-800">
            <div class="w-10 h-10 rounded-lg bg-{{ $s[2] }}-100 dark:bg-{{ $s[2] }}-900/50 flex items-center justify-center"><svg class="w-5 h-5 text-{{ $s[2] }}-600 dark:text-{{ $s[2] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z"></path>
                </svg></div>
            <div>
                <p class="text-2xl font-bold text-primary-dark dark:text-white">{{ $s[1] }}</p>
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $s[0] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <div class="card-saas p-4 mb-6 dark:bg-gray-800">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]"><label class="block text-xs font-medium text-primary-dark dark:text-gray-300 mb-1">{{ __('Search') }}</label><input type="text" name="search" value="{{ request('search') }}" class="input-saas w-full text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="{{ __('Name, Student ID, Company...') }}"></div>
            <div class="w-48"><label class="block text-xs font-medium text-primary-dark dark:text-gray-300 mb-1">{{ __('Status') }}</label><select name="status" class="input-saas w-full text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                    <option value="">{{ __('All') }}</option>
                    @foreach($statusList as $k => $v)<option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
                </select></div>
            <button type="submit" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Filter') }}</button>
        </form>
    </div>
    <!-- Table Card (Desktop) -->
    <div class="hidden md:block card-saas overflow-hidden dark:bg-gray-800">
        <table class="w-full table-saas">
            <thead>
                <tr class="bg-primary-light/30 dark:bg-gray-900">
                    <!-- Sortable: Student -->
                    <th class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'student_name', 'order' => request('sort') == 'student_name' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="group flex items-center gap-1 hover:text-primary-primary transition">{{ __('Students') }}<span class="flex flex-col text-[10px] leading-none {{ in_array(request('sort'), ['student_name', 'student_name']) ? 'text-primary-primary' : 'text-gray-300' }}">
                                <i class="opacity-{{ in_array(request('sort'), ['student_name', 'student_name']) && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                <i class="opacity-{{ in_array(request('sort'), ['student_name', 'student_name']) && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                            </span>
                        </a>
                    </th>

                    <!-- Sortable: Company -->
                    <th class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'company_name', 'order' => request('sort') == 'company_name' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="group flex items-center gap-1 hover:text-primary-primary transition">
                            {{ __('Company') }}
                            <span class="flex flex-col text-[10px] leading-none {{ request('sort') === 'company_name' ? 'text-primary-primary' : 'text-gray-300' }}">
                                <i class="opacity-{{ request('sort') === 'company_name' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                <i class="opacity-{{ request('sort') === 'company_name' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
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
                @forelse($internshipList as $internship)
                <tr class="border-b border-primary-light/50 dark:border-gray-700/50">
                    <td class="py-4 px-5">
                        <p class="font-medium text-primary-dark dark:text-white">{{ $internship->student->user->name }}</p>
                        <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $internship->student->student_number }}</p>
                    </td>
                    <td class="py-4 px-5 text-sm text-primary-dark dark:text-white">{{ $internship->company_name }}</td>
                    <td class="py-4 px-5 text-sm">@if($internship->supervisor)<span class="text-primary-dark dark:text-white">{{ $internship->supervisor->user->name }}</span>@else<span class="text-amber-600 dark:text-amber-400 text-xs">{{ __('Not Yet') }}</span>@endif</td>
                    <td class="py-4 px-5 text-center"><span class="px-2.5 py-1 text-xs font-medium rounded-full bg-{{ $internship->status_color }}-100 text-{{ $internship->status_color }}-700 dark:bg-{{ $internship->status_color }}-900/50 dark:text-{{ $internship->status_color }}-400">{{ $internship->status_label }}</span></td>
                    <td class="py-4 px-5 text-right"><a href="{{ route('admin.internship.show', $internship) }}" class="text-sm text-primary-primary dark:text-blue-400 hover:underline">{{ __('Details') }}</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-primary-secondary dark:text-gray-400">{{ __('No data available') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card List -->
    <div class="md:hidden space-y-4">
        @forelse($internshipList as $internship)
        <div class="card-saas p-4 dark:bg-gray-800">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-primary dark:bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                        {{ strtoupper(substr($internship->student->user->name ?? '-', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-primary-dark dark:text-white">{{ $internship->student->user->name }}</h4>
                        <p class="text-xs text-primary-secondary dark:text-gray-400 font-mono">{{ $internship->student->student_number }}</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-medium rounded-full bg-{{ $internship->status_color }}-100 text-{{ $internship->status_color }}-700 dark:bg-{{ $internship->status_color }}-900/50 dark:text-{{ $internship->status_color }}-400">{{ $internship->status_label }}</span>
            </div>

            <div class="mb-4">
                <p class="text-xs text-primary-secondary dark:text-gray-400 mb-1">{{ __('Company') }}</p>
                <p class="text-sm font-medium text-primary-dark dark:text-white">{{ $internship->company_name }}</p>
            </div>

            <div class="bg-primary-light/30 dark:bg-gray-700/30 rounded-lg p-3 mb-4">
                <p class="text-xs text-primary-secondary dark:text-gray-400 mb-1">{{ __('Advisor') }}</p>
                @if($internship->supervisor)
                <p class="text-sm font-medium text-primary-dark dark:text-white">{{ $internship->supervisor->user->name }}</p>
                @else
                <span class="text-amber-600 dark:text-amber-400 text-xs">{{ __('Not Yet') }}</span>
                @endif
            </div>

            <a href="{{ route('admin.internship.show', $internship) }}" class="flex items-center justify-center w-full py-2 bg-primary-light dark:bg-gray-700 text-primary-dark dark:text-white font-medium rounded-lg hover:bg-gray-200 transition text-sm">
                {{ __('View Details') }}
            </a>
        </div>
        @empty
        <div class="card-saas p-8 text-center">
            <p class="text-primary-secondary dark:text-gray-400">{{ __('No data available') }}</p>
        </div>
        @endforelse
    </div>

    @if($internshipList->hasPages())
    <div class="card-saas px-5 py-4 border-t border-primary-light dark:border-gray-700 dark:bg-gray-800 mt-4 md:mt-0">
        {{ $internshipList->links() }}
    </div>
    @endif
</x-app-layout>