<x-app-layout>
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Academic Transcript') }}</h1>
                <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
                    <span class="w-8 h-px bg-primary-200"></span>
                    {{ __('Complete history of your academic achievements and grades.') }}
                </p>
            </div>
            <a href="{{ route('students.export.transcript') }}" target="_blank"
                class="hidden md:flex items-center gap-3 px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-sm font-black rounded-xl shadow-lg shadow-primary-600/20 hover:shadow-primary-600/40 hover:-translate-y-0.5 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6a2 2 0 012-2zm9-2V3a1 1 0 00-1-1H6a1 1 0 00-1 1v10m3-4h8a2 2 0 012 2v4H9v-4a2 2 0 012-2z"></path>
                </svg>
                {{ __('Download PDF') }}
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    @php
    $totalCreditsPassed = $transcript['total_credits_passed'] ?? 0;
    $gpa = $transcript['gpa'] ?? 0;
    @endphp

    <!-- Mobile Download Button -->
    <div class="mb-8 md:hidden">
        <a href="{{ route('students.export.transcript') }}" target="_blank"
            class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-primary-primary text-white font-black rounded-2xl shadow-xl shadow-primary-900/20 active:scale-[0.98] transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            {{ __('Download PDF Version') }}
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="card-saas p-6 group hover:shadow-soft-lg transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center group-hover:bg-primary-primary group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6 text-primary-primary group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-primary-900 dark:text-white">{{ number_format($gpa, 2) }}</p>
                    <p class="text-xs text-primary-500 font-bold uppercase tracking-wider">{{ __('GPA') }}</p>
                </div>
            </div>
        </div>
        <div class="card-saas p-6 group hover:shadow-soft-lg transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6 text-primary-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-primary-900 dark:text-white">{{ $totalCreditsPassed }}</p>
                    <p class="text-xs text-primary-500 font-bold uppercase tracking-wider">{{ __('Credits') }}</p>
                </div>
            </div>
        </div>
        <div class="card-saas p-6 group hover:shadow-soft-lg transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6 text-primary-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-primary-900 dark:text-white">{{ $maxCredits }}</p>
                    <p class="text-xs text-primary-500 font-bold uppercase tracking-wider">{{ __('Max SKS') }}</p>
                </div>
            </div>
        </div>
        <div class="card-saas p-6 group hover:shadow-soft-lg transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6 text-emerald-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-primary-900 dark:text-white">{{ count($transcript['semesters'] ?? []) }}</p>
                    <p class="text-xs text-primary-500 font-bold uppercase tracking-wider">{{ __('Semesters') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div class="card-saas p-8">
            <h3 class="text-base font-black text-primary-900 dark:text-white mb-6">{{ __('GPA Progress') }}</h3>
            <div class="h-64">
                <canvas id="semesterGpaChart"></canvas>
            </div>
        </div>

        <div class="card-saas p-8">
            <h3 class="text-base font-black text-primary-900 dark:text-white mb-6">{{ __('Grade Distribution') }}</h3>
            <div class="h-64">
                <canvas id="gradeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Classification Progress -->
    <div class="card-saas p-8 mb-10">
        <div class="flex items-center gap-3 mb-8">
            <div class="p-2 bg-primary-50 dark:bg-primary-900/50 rounded-lg">
                <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="text-base font-black text-primary-900 dark:text-white">{{ __('Graduation Requirements Progress') }}</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($classificationProgress as $progress)
            <div class="group">
                <div class="flex justify-between items-end mb-2.5">
                    <span class="text-xs font-bold text-primary-700 dark:text-primary-300 uppercase tracking-wider">{{ __($progress['name']) }}</span>
                    <span class="text-sm font-black text-primary-900 dark:text-white">{{ $progress['total'] }} <span class="text-[10px] text-primary-400 font-medium">/</span> {{ $progress['required'] }}</span>
                </div>
                <div class="w-full bg-primary-50 dark:bg-primary-900/30 rounded-full h-2.5 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-primary-500 to-primary-600 rounded-full transition-all duration-1000 ease-out shadow-sm" style="width: {{ $progress['percentage'] }}%"></div>
                </div>
                @if($progress['enrolled'] > 0)
                <p class="text-[9px] text-primary-400 font-bold uppercase tracking-tight mt-1.5 flex items-center gap-1">
                    <span class="w-1 h-1 rounded-full bg-primary-400"></span>
                    {{ $progress['enrolled'] }} {{ __('Credits currently enrolled') }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Transcript by Semester -->
    @foreach($transcript['semesters'] ?? [] as $semester)
    <div x-data="{ open: false }" class="card-saas overflow-hidden mb-8">
        <button @click="open = !open" type="button" class="w-full px-8 py-6 bg-primary-50/50 dark:bg-primary-900/20 border-b border-primary-100/50 dark:border-primary-800/50 flex items-center justify-between hover:bg-primary-50 dark:hover:bg-primary-900/40 transition-all duration-300 cursor-pointer text-left group">
            <div class="flex items-center gap-5">
                <div class="p-2 rounded-xl bg-white dark:bg-primary-800 shadow-soft border border-primary-100 dark:border-primary-700 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-primary-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <h3 class="text-base font-black text-primary-900 dark:text-white tracking-tight group-hover:text-primary-primary transition-colors">{{ $semester['semester'] }}</h3>
            </div>
            <div class="flex items-center gap-6">
                <div class="hidden sm:flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest">{{ __('GPA') }}</p>
                        <p class="text-sm font-black text-primary-primary">{{ number_format($semester['gpa'], 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest">{{ __('Credits') }}</p>
                        <p class="text-sm font-black text-primary-900 dark:text-white">{{ $semester['total_credits'] }}</p>
                    </div>
                </div>
            </div>
        </button>
        <div x-show="open" x-collapse>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-primary-50/30 dark:bg-primary-900/10 border-b border-primary-100/30 dark:border-primary-800/30">
                            <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest w-32">{{ __('Code') }}</th>
                            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest">{{ __('Course Name') }}</th>
                            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-24">{{ __('SKS') }}</th>
                            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-24">{{ __('Grade') }}</th>
                            <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-32">{{ __('Symbol') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary-50/50 dark:divide-primary-800/30">
                        @foreach($semester['courses'] as $course)
                        <tr class="hover:bg-primary-50/20 dark:hover:bg-primary-900/10 transition-colors group/row">
                            <td class="py-5 px-8">
                                <span class="text-xs font-black text-primary-700 dark:text-primary-300 font-mono tracking-wider bg-primary-50 dark:bg-primary-900 px-2 py-1 rounded-lg border border-primary-100 dark:border-primary-800">{{ $course['course_code'] }}</span>
                            </td>
                            <td class="py-5 px-6">
                                <span class="text-sm font-black text-primary-900 dark:text-white group-hover/row:text-primary-primary transition-colors">{{ $course['name'] }}</span>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <span class="text-sm font-black text-primary-900 dark:text-white">{{ $course['credits'] }}</span>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <span class="text-sm font-black text-primary-500">{{ $course['numeric_grade'] }}</span>
                            </td>
                            <td class="py-5 px-8 text-center">
                                @php
                                $gradeColors = [
                                'A' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/50',
                                'B+' => 'bg-primary-50 text-primary-600 border-primary-100 dark:bg-primary-950/30 dark:text-primary-400 dark:border-primary-900/50',
                                'B' => 'bg-primary-50 text-primary-600 border-primary-100 dark:bg-primary-950/30 dark:text-primary-400 dark:border-primary-900/50',
                                'C+' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50',
                                'C' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50',
                                'D' => 'bg-red-50 text-red-600 border-red-100 dark:bg-red-950/30 dark:text-red-400 dark:border-red-900/50',
                                'E' => 'bg-red-50 text-red-600 border-red-100 dark:bg-red-950/30 dark:text-red-400 dark:border-red-900/50',
                                ];
                                @endphp
                                <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $gradeColors[$course['letter_grade']] ?? 'bg-primary-50 text-primary-400 border-primary-100' }}">
                                    {{ $course['letter_grade'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const systemPrimary = '#234C6A';
        const systemSecondary = '#456882';
        const systemDark = '#1B3C53';
        const systemLight = '#E3E3E3';

        // GPA Chart
        const gpaDataHistory = @json($gpaHistory->filter(fn($s) => $s['gpa'] > 0)->values());
        const ipsCtx = document.getElementById('semesterGpaChart').getContext('2d');
        new Chart(ipsCtx, {
            type: 'line',
            data: {
                labels: gpaDataHistory.map(d => d.academic_year.substring(0, 9)),
                datasets: [{
                    label: 'GPA',
                    data: gpaDataHistory.map(d => d.gpa),
                    borderColor: systemPrimary,
                    backgroundColor: 'rgba(35, 76, 106, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: systemPrimary,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 4,
                        ticks: {
                            stepSize: 0.5
                        },
                        grid: {
                            color: systemLight
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Grade Chart
        const gradeData = @json($gradeDistribution);
        const gradeCtx = document.getElementById('gradeChart').getContext('2d');
        new Chart(gradeCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(gradeData),
                datasets: [{
                    data: Object.values(gradeData),
                    backgroundColor: [systemPrimary, systemSecondary, systemDark, '#86c5e0', '#b9dded', '#dceef6', systemLight],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    </script>
    @endpush
</x-app-layout>