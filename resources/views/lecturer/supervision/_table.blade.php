<table class="w-full text-start border-collapse">
    <thead>
        <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
            <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest w-16 text-start">#</th>
            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start sortable-header cursor-pointer hover:text-primary-primary transition-colors" data-sort="name">
                <div class="flex items-center gap-2">
                    {{ __('Students') }}
                    <span class="sort-icon">
                        @if(request('sort') === 'name')
                        @if(request('dir') === 'asc')
                        <svg class="w-3.5 h-3.5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                        </svg>
                        @else
                        <svg class="w-3.5 h-3.5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        @endif
                        @else
                        <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        @endif
                    </span>
                </div>
            </th>
            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start sortable-header cursor-pointer hover:text-primary-primary transition-colors" data-sort="student_number">
                <div class="flex items-center gap-2">
                    {{ __('Student ID') }}
                    <span class="sort-icon">
                        @if(request('sort') === 'student_number')
                        @if(request('dir') === 'asc')
                        <svg class="w-3.5 h-3.5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                        </svg>
                        @else
                        <svg class="w-3.5 h-3.5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        @endif
                        @else
                        <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        @endif
                    </span>
                </div>
            </th>
            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start sortable-header cursor-pointer hover:text-primary-primary transition-colors" data-sort="batch">
                <div class="flex items-center gap-2">
                    {{ __('Batch') }}
                    <span class="sort-icon">
                        @if(request('sort') === 'batch' || !request('sort'))
                        @if(request('dir') === 'asc')
                        <svg class="w-3.5 h-3.5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                        </svg>
                        @else
                        <svg class="w-3.5 h-3.5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        @endif
                        @else
                        <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        @endif
                    </span>
                </div>
            </th>
            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start sortable-header cursor-pointer hover:text-primary-primary transition-colors" data-sort="status">
                <div class="flex items-center gap-2">
                    {{ __('Status') }}
                    <span class="sort-icon">
                        @if(request('sort') === 'status')
                        @if(request('dir') === 'asc')
                        <svg class="w-3.5 h-3.5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                        </svg>
                        @else
                        <svg class="w-3.5 h-3.5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        @endif
                        @else
                        <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        @endif
                    </span>
                </div>
            </th>
        </tr>
    </thead>
    <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
        @forelse($advisedStudents as $index => $m)
        <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
            <td class="py-5 px-8 text-xs font-bold text-primary-400 text-start">{{ $advisedStudents->firstItem() + $index }}</td>
            <td class="py-5 px-6 text-start">
                <span class="text-sm font-black text-primary-900 dark:text-white group-hover:text-primary-primary transition-colors">{{ $m->user->name ?? '-' }}</span>
            </td>
            <td class="py-5 px-6 text-start">
                <span class="text-xs font-black text-primary-700 dark:text-primary-300 font-mono tracking-wider bg-primary-50 dark:bg-primary-900 px-2 py-1 rounded-lg border border-primary-100 dark:border-primary-800">{{ $m->student_number }}</span>
            </td>
            <td class="py-5 px-6 text-start">
                <span class="inline-flex px-3 py-1 rounded-lg text-[10px] font-black bg-primary-50 dark:bg-primary-900 text-primary-primary border border-primary-100 dark:border-primary-800 uppercase tracking-widest shadow-sm">{{ $m->batch }}</span>
            </td>
            <td class="py-5 px-6 text-start">
                @php
                $statusClass = match($m->status ?? 'active') {
                'active' => 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/50',
                'cuti' => 'bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-900/50',
                'pass' => 'bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-900/50',
                'do' => 'bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 border-red-100 dark:border-red-900/50',
                default => 'bg-primary-50 dark:bg-primary-900/50 text-primary-400 border-primary-100 dark:border-primary-800',
                };
                $statusLabel = match($m->status ?? 'active') {
                'active' => __('Active'),
                'cuti' => __('Leave'),
                'pass' => __('Passed/Graduated'),
                'do' => __('Drop Out'),
                default => ucfirst($m->status ?? 'active'),
                };
                @endphp
                <span class="inline-flex px-2.5 py-1 text-[10px] font-black {{ $statusClass }} rounded-lg border uppercase tracking-widest shadow-sm">{{ $statusLabel }}</span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="py-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <p class="text-primary-400 font-bold text-sm">{{ __('No supervised students found.') }}</p>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@if($advisedStudents->hasPages())
<div class="px-8 py-5 border-t border-primary-100/50 dark:border-primary-800/50 bg-primary-50/30 dark:bg-primary-900/20">
    {{ $advisedStudents->links() }}
</div>
@endif