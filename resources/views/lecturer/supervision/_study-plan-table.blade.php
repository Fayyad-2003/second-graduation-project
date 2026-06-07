<table class="w-full table-saas">
    <thead>
        <tr class="bg-siakad-light/30 dark:bg-gray-900">
            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary uppercase tracking-wider w-16">#</th>
            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary uppercase tracking-wider sortable-header cursor-pointer hover:bg-siakad-light/50 transition" data-sort="name">
                <div class="flex items-center gap-1">{{ __('Students') }}<span class="sort-icon">
                        @if(request('sort') === 'name')
                        @if(request('dir') === 'asc')
                        <svg class="w-3 h-3 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                        @else
                        <svg class="w-3 h-3 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        @endif
                        @else
                        <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        @endif
                    </span>
                </div>
            </th>
            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary uppercase tracking-wider sortable-header cursor-pointer hover:bg-siakad-light/50 transition" data-sort="student_number">
                <div class="flex items-center gap-1">
                    {{ __('Student ID') }}
                    <span class="sort-icon">
                            @if(request('sort') === 'student_number')
                            @if(request('dir') === 'asc')
                        <svg class="w-3 h-3 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                        @else
                        <svg class="w-3 h-3 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        @endif
                        @else
                        <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        @endif
                    </span>
                </div>
            </th>
            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary uppercase tracking-wider">{{ __('Total Credits') }}</th>
            <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary uppercase tracking-wider sortable-header cursor-pointer hover:bg-siakad-light/50 transition" data-sort="status">
                <div class="flex items-center gap-1">
                    {{ __('Status') }}
                    <span class="sort-icon">
                        @if(request('sort') === 'status')
                        @if(request('dir') === 'asc')
                        <svg class="w-3 h-3 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                        @else
                        <svg class="w-3 h-3 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        @endif
                        @else
                        <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        @endif
                    </span>
                </div>
            </th>
            <th class="text-right py-3 px-5 text-xs font-semibold text-siakad-secondary uppercase tracking-wider">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($studyPlanList as $index => $studyPlan)
        <tr class="border-b border-siakad-light/50">
            <td class="py-4 px-5 text-sm text-siakad-secondary">{{ $studyPlanList->firstItem() + $index }}</td>
            <td class="py-4 px-5">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-siakad-dark">{{ $studyPlan->student->user->name ?? '-' }}</span>
                </div>
            </td>
            <td class="py-4 px-5">
                <span class="text-sm font-mono text-siakad-secondary">{{ $studyPlan->student->student_number ?? '-' }}</span>
            </td>
            <td class="py-4 px-5">
                <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-siakad-primary/10 text-siakad-primary rounded-full">{{ $studyPlan->details?->sum(fn($d) => $d->academicClass->course->credits ?? 0) ?? 0 }} {{ __('Credits') }}</span>
            </td>
            <td class="py-4 px-5">
                @if($studyPlan->status === 'approved')
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-700/30 dark:text-emerald-400 rounded-full">{{ __('Approved') }}</span>
                @elseif($studyPlan->status === 'pending')
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-700/30 dark:text-amber-400 rounded-full">{{ __('Pending') }}</span>
                @elseif($studyPlan->status === 'rejected')
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-red-100 text-red-700 dark:bg-red-700/30 dark:text-red-400 rounded-full">{{ __('Rejected') }}</span>
                @else
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700/30 dark:text-slate-400 rounded-full">{{ __('Drafts') }}</span>
                @endif
            </td>
            <td class="py-4 px-5 text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('lecturers.supervision.study-plan-show', $studyPlan->id) }}" class="inline-flex p-2 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    @if($studyPlan->status === 'pending')
                    <form action="{{ route('lecturers.supervision.study-plan-approve', $studyPlan->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-siakad-secondary hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-700/30 rounded-lg transition" title="{{ __('Approve') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    </form>
                    <button type="button" onclick="openRejectModal('{{ route('lecturers.supervision.study-plan-reject', $studyPlan->id) }}')" class="p-2 text-siakad-secondary hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-700/30 rounded-lg transition" title="{{ __('Reject') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="py-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-siakad-light/50 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <p class="text-siakad-secondary text-sm">{{ __('No study plans waiting for approval') }}</p>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@if($studyPlanList->hasPages())
<div class="px-5 py-4 border-t border-siakad-light">
    {{ $studyPlanList->links() }}
</div>
@endif
