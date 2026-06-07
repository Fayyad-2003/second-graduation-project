<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-siakad-900 dark:text-white">{{ __('GPA Warning') }}</h1>
            <p class="text-siakad-500 font-medium mt-2 flex items-center gap-2">
                <span class="w-8 h-px bg-siakad-200"></span>
                {{ __('Monitor and notify students with low cumulative GPA.') }}
            </p>
        </div>
        <a href="{{ route('admin.student.index') }}"
            class="btn-ghost-saas px-5 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 border border-siakad-100 dark:border-siakad-800 text-siakad-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
            </svg>
            {{ __('Back to Students') }}
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-soft dark:bg-emerald-950/20 dark:border-emerald-900/50 dark:text-emerald-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Total At Risk -->
        <div class="card-saas p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150"></div>
            <div class="flex items-center gap-5 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-sm border border-amber-100/50 dark:border-amber-900/50">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black text-siakad-900 dark:text-white leading-tight">{{ $totalAtRisk }}</p>
                    <p class="text-[10px] text-siakad-400 font-black uppercase tracking-widest mt-1">{{ __('Total At-Risk') }}</p>
                </div>
            </div>
        </div>

        <!-- Danger -->
        <div class="card-saas p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150"></div>
            <div class="flex items-center gap-5 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/30 flex items-center justify-center text-red-600 dark:text-red-400 shadow-sm border border-red-100/50 dark:border-red-900/50">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black text-red-600 dark:text-red-400 leading-tight">{{ $dangerCount }}</p>
                    <p class="text-[10px] text-siakad-400 font-black uppercase tracking-widest mt-1">{{ __('Academic Probation') }}</p>
                </div>
            </div>
        </div>

        <!-- Caution -->
        <div class="card-saas p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150"></div>
            <div class="flex items-center gap-5 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-yellow-50 dark:bg-yellow-950/30 flex items-center justify-center text-yellow-600 dark:text-yellow-400 shadow-sm border border-yellow-100/50 dark:border-yellow-900/50">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black text-yellow-600 dark:text-yellow-400 leading-tight">{{ $cautionCount }}</p>
                    <p class="text-[10px] text-siakad-400 font-black uppercase tracking-widest mt-1">{{ __('Low GPA Warning') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-8 card-saas p-6">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <select name="study_program_id" class="input-saas px-4 py-2.5 text-sm w-full sm:w-64">
                <option value="">{{ __('All Study Programs') }}</option>
                @foreach($studyProgramList as $p)
                <option value="{{ $p->id }}" {{ request('study_program_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>

            <select name="batch" class="input-saas px-4 py-2.5 text-sm w-full sm:w-32">
                <option value="">{{ __('Batch') }}</option>
                @foreach($batchList as $batch)
                <option value="{{ $batch }}" {{ request('batch') == $batch ? 'selected' : '' }}>{{ $batch }}</option>
                @endforeach
            </select>

            <select name="level" class="input-saas px-4 py-2.5 text-sm w-full sm:w-48">
                <option value="">{{ __('All Risk Levels') }}</option>
                <option value="danger" {{ request('level') == 'danger' ? 'selected' : '' }}>🚨 {{ __('Danger') }}</option>
                <option value="caution" {{ request('level') == 'caution' ? 'selected' : '' }}>⚠️ {{ __('Caution') }}</option>
            </select>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex-1 sm:flex-none">
                    {{ __('Filter') }}
                </button>
                @if(request()->anyFilled(['study_program_id', 'batch', 'level']))
                <a href="{{ route('admin.gpa-warning.index') }}" class="btn-ghost-saas px-4 py-2.5 rounded-xl text-sm font-bold text-siakad-400 hover:text-siakad-600">
                    {{ __('Reset') }}
                </a>
                @endif
            </div>
        </form>
    </div>

    @if($atRiskStudents->isEmpty())
    <div class="card-saas p-16 text-center">
        <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-950/30 rounded-3xl flex items-center justify-center mx-auto mb-6 text-emerald-500 shadow-sm border border-emerald-100/50 dark:border-emerald-900/50">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-black text-siakad-900 dark:text-white mb-2">{{ __('All Clear!') }}</h3>
        <p class="text-siakad-400 font-bold max-w-sm mx-auto leading-relaxed">{{ __('No students are currently at risk based on the defined GPA thresholds.') }}</p>
    </div>
    @else
    <form id="notifyForm" action="{{ route('admin.gpa-warning.notify') }}" method="POST">
        @csrf

        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4 bg-siakad-50/50 dark:bg-siakad-900/30 px-5 py-2.5 rounded-2xl border border-siakad-100/50 dark:border-siakad-800/50">
                <label class="flex items-center gap-3 text-sm font-black text-siakad-700 dark:text-siakad-300 cursor-pointer">
                    <input type="checkbox" id="selectAll" class="w-5 h-5 rounded-lg border-siakad-200 text-siakad-primary focus:ring-siakad-primary transition-all cursor-pointer">
                    {{ __('Select All') }}
                </label>
                <div id="selectedCount" class="hidden h-5 w-px bg-siakad-200 dark:bg-siakad-800"></div>
                <span id="selectedCountLabel" class="text-xs font-black text-siakad-primary uppercase tracking-widest hidden">
                    <span id="selectedNumber">0</span> {{ __('Selected') }}
                </span>
            </div>
            
            <button type="submit" id="notifyBtn" disabled
                class="btn-primary-saas px-6 py-3 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-siakad-600/20 disabled:opacity-50 disabled:shadow-none transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                {{ __('Send Warning Notifications') }}
            </button>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block card-saas overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-start border-collapse">
                    <thead>
                        <tr class="bg-siakad-50/50 dark:bg-siakad-900/30 border-b border-siakad-100/50 dark:border-siakad-800/50">
                            <th class="py-5 px-8 w-16 text-center"></th>
                            <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Student') }}</th>
                            <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Student ID') }}</th>
                            <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Program') }}</th>
                            <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center">{{ __('Batch') }}</th>
                            <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center">{{ __('GPA') }}</th>
                            <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center">{{ __('Status') }}</th>
                            <th class="py-5 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
                        @foreach($atRiskStudents as $item)
                        @php $s = $item['student']; @endphp
                        <tr class="hover:bg-siakad-50/30 dark:hover:bg-siakad-900/20 transition-colors group">
                            <td class="py-5 px-8 text-center">
                                <input type="checkbox" name="student_ids[]" value="{{ $s->id }}"
                                    class="student-checkbox w-5 h-5 rounded-lg border-siakad-200 text-siakad-primary focus:ring-siakad-primary transition-all cursor-pointer">
                            </td>
                            <td class="py-5 px-6 text-start">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-siakad-50 to-siakad-100 dark:from-siakad-900/50 dark:to-siakad-800/50 flex items-center justify-center text-siakad-600 dark:text-siakad-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr($s->user->name ?? '-', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-black text-siakad-900 dark:text-white truncate leading-tight">{{ $s->user->name ?? '-' }}</p>
                                        @if($s->academicAdvisor)
                                        <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-wider mt-0.5">{{ __('Advisor') }}: {{ $s->academicAdvisor->user->name ?? '-' }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-start">
                                <span class="text-xs font-black text-siakad-700 dark:text-siakad-300 font-mono tracking-wider">{{ $s->student_number }}</span>
                            </td>
                            <td class="py-5 px-6 text-start">
                                <p class="text-xs font-bold text-siakad-600 dark:text-siakad-400 leading-tight">{{ $s->studyProgram->name ?? '-' }}</p>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-siakad-50 dark:bg-siakad-900 text-siakad-500 dark:text-siakad-400 rounded-lg border border-siakad-100 dark:border-siakad-800 uppercase tracking-widest">{{ $s->batch }}</span>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-black {{ $item['level'] === 'danger' ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}">
                                        {{ number_format($item['gpa'], 2) }}
                                    </span>
                                    <span class="text-[9px] font-black text-siakad-400 uppercase tracking-widest">{{ $item['total_credits'] }} {{ __('Credits') }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-center">
                                @if($item['level'] === 'danger')
                                <span class="inline-flex px-3 py-1 text-[10px] font-black bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400 rounded-full border border-red-100 dark:border-red-900/50 uppercase tracking-widest">
                                    🚨 {{ __('Danger') }}
                                </span>
                                @else
                                <span class="inline-flex px-3 py-1 text-[10px] font-black bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 rounded-full border border-amber-100 dark:border-amber-900/50 uppercase tracking-widest">
                                    ⚠️ {{ __('Caution') }}
                                </span>
                                @endif
                            </td>
                            <td class="py-5 px-8 text-end">
                                <a href="{{ route('admin.student.show', $s) }}"
                                    class="p-2 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded-lg transition"
                                    title="{{ __('Details') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden space-y-4">
            @foreach($atRiskStudents as $item)
            @php $s = $item['student']; @endphp
            <div class="card-saas p-5 relative overflow-hidden group">
                @if($item['level'] === 'danger')
                <div class="absolute top-0 right-0 w-1 h-full bg-red-500"></div>
                @else
                <div class="absolute top-0 right-0 w-1 h-full bg-amber-500"></div>
                @endif
                
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex items-center gap-4">
                        <input type="checkbox" name="student_ids[]" value="{{ $s->id }}"
                            class="student-checkbox w-6 h-6 rounded-lg border-siakad-200 text-siakad-primary focus:ring-siakad-primary transition-all cursor-pointer">
                        <div class="w-10 h-10 rounded-2xl bg-siakad-50 dark:bg-siakad-900 flex items-center justify-center text-siakad-600 dark:text-siakad-400 text-sm font-black shadow-sm">
                            {{ strtoupper(substr($s->user->name ?? '-', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm font-black text-siakad-900 dark:text-white truncate leading-tight">{{ $s->user->name ?? '-' }}</h4>
                            <p class="text-[10px] text-siakad-400 font-mono font-bold mt-0.5 tracking-wider">{{ $s->student_number }}</p>
                        </div>
                    </div>
                    @if($item['level'] === 'danger')
                    <span class="text-xs">🚨</span>
                    @else
                    <span class="text-xs">⚠️</span>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-4 bg-siakad-50/50 dark:bg-siakad-900/30 p-3 rounded-2xl border border-siakad-100/50 dark:border-siakad-800/50 mb-4">
                    <div class="text-center">
                        <span class="block text-[8px] font-black text-siakad-400 uppercase tracking-widest mb-1">{{ __('GPA') }}</span>
                        <span class="text-sm font-black {{ $item['level'] === 'danger' ? 'text-red-600' : 'text-amber-600' }}">{{ number_format($item['gpa'], 2) }}</span>
                    </div>
                    <div class="text-center border-x border-siakad-100/50 dark:border-siakad-800/50">
                        <span class="block text-[8px] font-black text-siakad-400 uppercase tracking-widest mb-1">{{ __('Credits') }}</span>
                        <span class="text-sm font-black text-siakad-900 dark:text-white">{{ $item['total_credits'] }}</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-[8px] font-black text-siakad-400 uppercase tracking-widest mb-1">{{ __('Batch') }}</span>
                        <span class="text-sm font-black text-siakad-900 dark:text-white">{{ $s->batch }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.student.show', $s) }}"
                    class="btn-ghost-saas w-full py-3 rounded-xl text-xs font-black flex items-center justify-center gap-2 border border-siakad-100 dark:border-siakad-800">
                    {{ __('View Full Details') }}
                </a>
            </div>
            @endforeach
        </div>
    </form>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.student-checkbox');
            const notifyBtn = document.getElementById('notifyBtn');
            const selectedCount = document.getElementById('selectedCount');
            const selectedCountLabel = document.getElementById('selectedCountLabel');
            const selectedNumber = document.getElementById('selectedNumber');

            function updateState() {
                const checked = document.querySelectorAll('.student-checkbox:checked').length;
                if (notifyBtn) notifyBtn.disabled = checked === 0;
                if (selectedCount) {
                    selectedCount.classList.toggle('hidden', checked === 0);
                    selectedCountLabel.classList.toggle('hidden', checked === 0);
                    selectedNumber.textContent = checked;
                }
                if (selectAll) {
                    selectAll.checked = checked > 0 && checked === checkboxes.length;
                    selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateState();
                });
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateState));

            // Confirm before sending
            const form = document.getElementById('notifyForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const checked = document.querySelectorAll('.student-checkbox:checked').length;
                    if (!confirm(`{{ __('Send GPA warning notifications to') }} ${checked} {{ __('students') }}?`)) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</x-app-layout>
