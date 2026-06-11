<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Internship Supervision') }}</h1>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-2">
            <p class="text-primary-500 font-medium flex items-center gap-2">
                <span class="w-8 h-px bg-primary-200"></span>
                {{ __('Your guided internship students.') }}
            </p>
            @if($pendingLogbook > 0)
            <span class="px-4 py-1.5 text-[10px] font-black bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded-full border border-amber-100 dark:border-amber-900/50 uppercase tracking-widest shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                {{ $pendingLogbook }} {{ __('logbook pending') }}
            </span>
            @endif
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-saas p-6 flex items-center gap-4 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-primary-primary/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="w-14 h-14 rounded-2xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center text-primary-primary shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-primary-900 dark:text-white leading-none">{{ $internshipList->count() }}</p>
                <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-2">{{ __('Total Guided') }}</p>
            </div>
        </div>
        <div class="card-saas p-6 flex items-center gap-4 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-emerald-500/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-500 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-primary-900 dark:text-white leading-none">{{ $internshipList->where('status', 'ongoing')->count() }}</p>
                <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-2">{{ __('In Progress') }}</p>
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
                <p class="text-3xl font-black text-primary-900 dark:text-white leading-none">{{ $internshipList->where('status', 'completed')->count() }}</p>
                <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-2">{{ __('Finished') }}</p>
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
            <input type="text" id="searchInput" placeholder="{{ __('Search for student name, student ID, or company...') }}"
                class="input-saas pl-11 pr-4 py-3 text-sm w-full rounded-2xl">
        </div>
    </div>

    <!-- Data Table -->
    <div class="card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse" id="kpTable">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest w-16 text-start">#</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start sortable-header cursor-pointer hover:text-primary-primary transition-colors" data-sort="name" onclick="sortTable('name')">
                            <div class="flex items-center gap-2">
                                {{ __('Students') }}
                                <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start sortable-header cursor-pointer hover:text-primary-primary transition-colors" data-sort="perusahaan" onclick="sortTable('perusahaan')">
                            <div class="flex items-center gap-2">
                                {{ __('Company') }}
                                <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Period') }}</th>
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
                    @forelse($internshipList as $index => $internship)
                    <tr class="kp-row hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group"
                        data-name="{{ strtolower($internship->student->user->name) }}"
                        data-student-number="{{ strtolower($internship->student->student_number) }}"
                        data-company="{{ strtolower($internship->company_name) }}"
                        data-status="{{ $internship->status }}">
                        <td class="py-5 px-8 text-xs font-bold text-primary-400 text-start">{{ $index + 1 }}</td>
                        <td class="py-5 px-6 text-start">
                            <p class="text-sm font-black text-primary-900 dark:text-white group-hover:text-primary-primary transition-colors">{{ $internship->student->user->name }}</p>
                            <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">{{ $internship->student->student_number }}</p>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <span class="text-sm font-black text-primary-700 dark:text-primary-300">{{ $internship->company_name }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="inline-flex px-3 py-1 rounded-lg text-[10px] font-black bg-primary-50 dark:bg-primary-900 text-primary-400 border border-primary-100 dark:border-primary-800 uppercase tracking-widest">{{ $internship->start_date->format('d/m') }} - {{ $internship->completion_date->format('d/m/Y') }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            @php
                            $statusColor = match($internship->status) {
                            'ongoing' => 'emerald',
                            'completed' => 'amber',
                            'pending' => 'blue',
                            default => 'system'
                            };
                            @endphp
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-950/30 text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 rounded-lg border border-{{ $statusColor }}-100 dark:border-{{ $statusColor }}-900/50 uppercase tracking-widest shadow-sm">{{ __($internship->status_label) }}</span>
                        </td>
                        <td class="py-5 px-8 text-end">
                            <a href="{{ route('lecturers.internship.show', $internship) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-900 dark:bg-primary-800 text-white text-xs font-black rounded-xl hover:bg-primary-primary transition-all shadow-lg shadow-primary-900/10">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-primary-400 font-bold text-sm">{{ __('No data available') }}</p>
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
            const rows = Array.from(tbody.querySelectorAll('.kp-row'));

            if (currentSort === column) {
                sortDir = sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = column;
                sortDir = 'asc';
            }

            rows.sort((a, b) => {
                let aVal = a.dataset[column];
                let bVal = b.dataset[column];

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
            const rows = document.querySelectorAll('.kp-row');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();

                rows.forEach(row => {
                    const name = row.dataset.name || '';
                    const studentNumber = row.dataset.studentNumber || '';
                    const perusahaan = row.dataset.perusahaan || '';

                    if (name.includes(query) || studentNumber.includes(query) || perusahaan.includes(query)) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</x-app-layout>