<x-app-layout>
    <x-slot name="header">
        {{ __('Study Plan (KRS) Overview') }}
    </x-slot>

    <!-- Status Tabs -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-1 border-b border-siakad-light dark:border-gray-700 overflow-x-auto">
            <a href="{{ route('admin.study-plan-approval.index') }}" class="px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap {{ !request('status') || request('status') === 'pending' ? 'text-siakad-primary dark:text-blue-400 border-siakad-primary dark:border-blue-400' : 'text-siakad-secondary dark:text-gray-400 border-transparent hover:text-siakad-dark dark:hover:text-gray-300' }}">
                {{ __('Pending') }}
                @if($statusCounts['pending'] > 0)
                <span class="ml-1 px-2 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 rounded-full">{{ $statusCounts['pending'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.study-plan-approval.index', ['status' => 'approved']) }}" class="px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap {{ request('status') === 'approved' ? 'text-siakad-primary dark:text-blue-400 border-siakad-primary dark:border-blue-400' : 'text-siakad-secondary dark:text-gray-400 border-transparent hover:text-siakad-dark dark:hover:text-gray-300' }}">
                {{ __('Approved') }}
            </a>
            <a href="{{ route('admin.study-plan-approval.index', ['status' => 'rejected']) }}" class="px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap {{ request('status') === 'rejected' ? 'text-siakad-primary dark:text-blue-400 border-siakad-primary dark:border-blue-400' : 'text-siakad-secondary dark:text-gray-400 border-transparent hover:text-siakad-dark dark:hover:text-gray-300' }}">
                {{ __('Rejected') }}
            </a>
            <a href="{{ route('admin.study-plan-approval.index', ['status' => 'all']) }}" class="px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap {{ request('status') === 'all' ? 'text-siakad-primary dark:text-blue-400 border-siakad-primary dark:border-blue-400' : 'text-siakad-secondary dark:text-gray-400 border-transparent hover:text-siakad-dark dark:hover:text-gray-300' }}">
                {{ __('All') }}
            </a>
        </div>

        <!-- Search Form -->
        <form action="{{ route('admin.study-plan-approval.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search for Name / Student ID...') }}" class="input-saas pl-9 pr-4 py-2 text-sm w-full sm:w-64">
                <svg class="w-4 h-4 text-siakad-secondary absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <button type="submit" class="hidden sm:block px-4 py-2 bg-siakad-primary text-white text-sm font-medium rounded-lg hover:bg-siakad-primary/90 transition">
                {{ __('Search') }}
            </button>
        </form>
    </div>

    <!-- Table Card (Desktop) -->
    <div class="hidden md:block card-saas overflow-hidden dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead>
                    <tr class="bg-siakad-light/30 dark:bg-gray-900 border-b border-siakad-light dark:border-gray-700">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider w-16">#</th>

                        <!-- Sortable: Student -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['sort' => 'name', 'order' => request('sort') == 'name' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">{{ __('Students') }}<span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'name' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'name' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'name' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Student Number -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['sort' => 'student_number', 'order' => request('sort') == 'student_number' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">
                                {{ __('Student ID') }}
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'student_number' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'student_number' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'student_number' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Study Program -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['sort' => 'study_program', 'order' => request('sort') == 'study_program' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">
                                {{ __('Study Program') }}
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'study_program' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'study_program' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'study_program' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Total Credits') }}</th>

                        <!-- Sortable: Status -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['sort' => 'status', 'order' => request('sort') == 'status' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">
                                {{ __('Status') }}
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'status' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'status' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'status' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <th class="text-right py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studyPlanList as $index => $studyPlan)
                    <tr class="border-b border-siakad-light/50 dark:border-gray-700/50 hover:bg-siakad-light/10 transition">
                        <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">{{ $studyPlanList->firstItem() + $index }}</td>
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-siakad-primary dark:bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr($studyPlan->student->user->name ?? '-', 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-siakad-dark dark:text-white">{{ $studyPlan->student->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-5">
                            <span class="text-sm font-mono text-siakad-secondary dark:text-gray-400">{{ $studyPlan->student->student_number ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="text-sm text-siakad-secondary dark:text-gray-400">{{ $studyPlan->student->studyProgram->study_program_name ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-siakad-primary/10 text-siakad-primary dark:bg-blue-500/10 dark:text-blue-400 rounded-full">{{ $studyPlan->details?->sum(fn($d) => $d->academicClass->course->credits ?? 0) ?? 0 }} {{ __('Credits') }}</span>
                        </td>
                        <td class="py-4 px-5">
                            @if($studyPlan->status === 'approved')
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 rounded-full border dark:border-emerald-500/20">{{ __('Approved') }}</span>
                            @elseif($studyPlan->status === 'pending')
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 rounded-full border dark:border-amber-500/20">{{ __('Pending') }}</span>
                            @elseif($studyPlan->status === 'rejected')
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 rounded-full border dark:border-red-500/20">{{ __('Rejected') }}</span>
                            @else
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-gray-700 dark:text-gray-300 rounded-full border dark:border-gray-500/20">{{ __('Drafts') }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-right">
                            <a href="{{ route('admin.study-plan-approval.show', $studyPlan) }}" class="inline-flex p-2 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded-lg transition" title="{{ __('View Details') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-siakad-light/50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                </div>
                                <p class="text-siakad-secondary dark:text-gray-400 text-sm">{{ __('No Study Plan data available') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($studyPlanList->hasPages())
        <div class="px-5 py-4 border-t border-siakad-light dark:border-gray-700">
            {{ $studyPlanList->links() }}
        </div>
        @endif
    </div>

    <!-- Mobile Card List -->
    <div class="md:hidden space-y-4">
        @forelse($studyPlanList as $studyPlan)
        <div class="card-saas p-4 dark:bg-gray-800">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h4 class="font-bold text-siakad-dark dark:text-white">{{ $studyPlan->student->user->name ?? '-' }}</h4>
                    <p class="text-xs text-siakad-secondary dark:text-gray-400 font-mono">{{ $studyPlan->student->student_number ?? '-' }}</p>
                </div>
                @if($studyPlan->status === 'approved')
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 rounded-full">{{ __('Approved') }}</span>
                @elseif($studyPlan->status === 'pending')
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-700 rounded-full">{{ __('Pending') }}</span>
                @elseif($studyPlan->status === 'rejected')
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-red-100 text-red-700 rounded-full">{{ __('Rejected') }}</span>
                @else
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 rounded-full">{{ __('Drafts') }}</span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-2 text-sm text-siakad-secondary dark:text-gray-400 mb-4">
                <div>
                    <span class="block text-xs text-gray-400">{{ __('Study Program') }}</span>
                    <span class="font-medium text-siakad-dark dark:text-gray-200">{{ $studyPlan->student->studyProgram->study_program_name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400">{{ __('Total Credits') }}</span>
                    <span class="font-medium text-siakad-dark dark:text-gray-200">{{ $studyPlan->details?->sum(fn($d) => $d->academicClass->course->credits ?? 0) ?? 0 }} {{ __('Credits') }}</span>
                </div>
            </div>

            <div class="pt-3 border-t border-siakad-light dark:border-gray-700">
                <a href="{{ route('admin.study-plan-approval.show', $studyPlan) }}" class="block w-full py-2 text-center text-xs font-medium bg-siakad-light dark:bg-gray-700 text-siakad-dark dark:text-white rounded-lg hover:bg-gray-200 transition">
                    {{ __('View Details') }}
                </a>
            </div>
        </div>
        @empty
        <div class="card-saas p-8 text-center">
            <p class="text-siakad-secondary dark:text-gray-400">{{ __('No Study Plan data available') }}</p>
        </div>
        @endforelse
    </div>
</x-app-layout>
