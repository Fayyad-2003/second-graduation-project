<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Thesis Supervision') }}</h1>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-2">
            <p class="text-primary-500 font-medium flex items-center gap-2">
                <span class="w-8 h-px bg-primary-200"></span>
                {{ __('List of students for your thesis supervision.') }}
            </p>
            @if($pendingSupervision > 0)
            <span class="px-4 py-1.5 text-[10px] font-black bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-full border border-blue-100 dark:border-blue-900/50 uppercase tracking-widest shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                {{ $pendingSupervision }} {{ __('awaiting review') }}
            </span>
            @endif
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card-saas p-6 flex items-center gap-4 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-primary-primary/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="w-14 h-14 rounded-2xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center text-primary-primary shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-primary-900 dark:text-white leading-none">{{ $thesisList->count() }}</p>
                <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-2">{{ __('Total Supervised') }}</p>
            </div>
        </div>
        <div class="card-saas p-6 flex items-center gap-4 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-emerald-500/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-500 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-primary-900 dark:text-white leading-none">{{ $thesisList->where('status', 'supervision')->count() }}</p>
                <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-2">{{ __('Active Supervision') }}</p>
            </div>
        </div>
        <div class="card-saas p-6 flex items-center gap-4 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-blue-500/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center text-blue-500 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-primary-900 dark:text-white leading-none">{{ $pendingSupervision }}</p>
                <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-2">{{ __('Awaiting Feedback') }}</p>
            </div>
        </div>
        <div class="card-saas p-6 flex items-center gap-4 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-amber-500/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center text-amber-500 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-primary-900 dark:text-white leading-none">{{ $thesisList->where('status', 'completed')->count() }}</p>
                <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-2">{{ __('Completed') }}</p>
            </div>
        </div>
    </div>

    <!-- Toolbar: Search -->
    <div class="mb-8 card-saas p-6">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors">
                <svg class="w-4 h-4 text-primary-400 group-focus-within:text-primary-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="searchInput" placeholder="{{ __('Search student name, student number or title...') }}"
                class="input-saas pl-11 pr-4 py-3 text-sm w-full rounded-2xl">
        </div>
    </div>

    <!-- Data Table -->
    <div class="card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse" id="thesisTable">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest w-16 text-start">#</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start sortable-header cursor-pointer hover:text-primary-primary transition-colors" data-sort="name" onclick="sortTable('name')">
                            <div class="flex items-center gap-2">
                                {{ __('Student') }}
                                <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start sortable-header cursor-pointer hover:text-primary-primary transition-colors" data-sort="title" onclick="sortTable('title')">
                            <div class="flex items-center gap-2">
                                {{ __('Title') }}
                                <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center sortable-header cursor-pointer hover:text-primary-primary transition-colors" data-sort="progress" onclick="sortTable('progress')">
                            <div class="flex items-center justify-center gap-2">
                                {{ __('Progress') }}
                                <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center sortable-header cursor-pointer hover:text-primary-primary transition-colors" data-sort="status" onclick="sortTable('status')">
                            <div class="flex items-center justify-center gap-2">
                                {{ __('Status') }}
                                <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($thesisList as $index => $thesis)
                    <tr class="thesis-row hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group"
                        data-name="{{ strtolower($thesis->student->user->name) }}"
                        data-student-number="{{ strtolower($thesis->student->student_number) }}"
                        data-title="{{ strtolower($thesis->title) }}"
                        data-progress="{{ $thesis->progress_percent }}"
                        data-status="{{ $thesis->status }}">
                        <td class="py-5 px-8 text-xs font-bold text-primary-400 text-start">{{ $index + 1 }}</td>
                        <td class="py-5 px-6 text-start">
                            <p class="text-sm font-black text-primary-900 dark:text-white group-hover:text-primary-primary transition-colors">{{ $thesis->student->user->name }}</p>
                            <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">{{ $thesis->student->student_number }}</p>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <p class="text-sm font-black text-primary-700 dark:text-primary-300 line-clamp-2" title="{{ $thesis->title }}">{{ $thesis->title }}</p>
                        </td>
                        <td class="py-5 px-6">
                            <div class="w-32 mx-auto">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest">{{ __('Completion') }}</span>
                                    <span class="text-[10px] font-black text-primary-900 dark:text-white">{{ $thesis->progress_percent }}%</span>
                                </div>
                                <div class="h-1.5 bg-primary-50 dark:bg-primary-900/50 rounded-full overflow-hidden p-0.5 border border-primary-100 dark:border-primary-800">
                                    <div class="h-full bg-primary-primary rounded-full shadow-[0_0_8px_rgba(37,99,235,0.4)] transition-all duration-500" style="width: {{ $thesis->progress_percent }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-center">
                            @php
                            $statusColor = match($thesis->status) {
                            'supervision' => 'emerald',
                            'completed' => 'amber',
                            'pending' => 'blue',
                            default => 'system'
                            };
                            @endphp
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-950/30 text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 rounded-lg border border-{{ $statusColor }}-100 dark:border-{{ $statusColor }}-900/50 uppercase tracking-widest shadow-sm">{{ $thesis->status_label }}</span>
                        </td>
                        <td class="py-5 px-8 text-end">
                            <a href="{{ route('lecturers.thesis.show', $thesis) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-900 dark:bg-primary-800 text-white text-xs font-black rounded-xl hover:bg-primary-primary transition-all shadow-lg shadow-primary-900/10">
                                {{ __('Details') }}
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-primary-400 font-bold text-sm">{{ __('No students with thesis supervision yet.') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let currentSort = '';
        let sortDir = 'asc';

        function sortTable(column) {
            const tbody = document.getElementById('tableBody');
            const rows = Array.from(tbody.querySelectorAll('.thesis-row'));

            if (currentSort === column) {
                sortDir = sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = column;
                sortDir = 'asc';
            }

            rows.sort((a, b) => {
                let aVal = a.dataset[column];
                let bVal = b.dataset[column];

                if (column === 'progress') {
                    aVal = parseInt(aVal) || 0;
                    bVal = parseInt(bVal) || 0;
                    return sortDir === 'asc' ? aVal - bVal : bVal - aVal;
                }

                if (sortDir === 'asc') {
                    return aVal.localeCompare(bVal);
                } else {
                    return bVal.localeCompare(aVal);
                }
            });

            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
                tbody.appendChild(row);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.thesis-row');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();

                rows.forEach(row => {
                    const name = row.dataset.name || '';
                    const student_number = row.dataset.student_number || '';
                    const title = row.dataset.title || '';

                    if (name.includes(query) || student_number.includes(query) || title.includes(query)) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</x-app-layout>