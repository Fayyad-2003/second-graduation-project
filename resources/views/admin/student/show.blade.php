<x-app-layout>
    <x-slot name="header">
        {{ __('Student Details') }}
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.student.index') }}" class="inline-flex items-center gap-2 text-sm text-siakad-secondary hover:text-siakad-primary transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            {{ __('Back to List') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="card-saas p-6">
            <div class="text-center mb-6">
                <div class="w-20 h-20 mx-auto rounded-xl bg-siakad-primary flex items-center justify-center text-white text-3xl font-bold mb-4">
                    {{ strtoupper(substr($student->user->name ?? '-', 0, 1)) }}
                </div>
                <h2 class="text-xl font-semibold text-siakad-dark">{{ $student->user->name ?? '-' }}</h2>
                <p class="text-sm font-mono text-siakad-secondary">{{ $student->student_number }}</p>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-siakad-light/50">
                    <span class="text-sm text-siakad-secondary">{{ __('Study Program') }}</span>
                    <span class="text-sm font-medium text-siakad-dark">{{ $student->studyProgram->name ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-siakad-light/50">
                    <span class="text-sm text-siakad-secondary">{{ __('Entry Year') }}</span>
                    <span class="text-sm font-medium text-siakad-dark">{{ $student->batch }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-siakad-light/50">
                    <span class="text-sm text-siakad-secondary">{{ __('Academic Advisor') }}</span>
                    <span class="text-sm font-medium text-siakad-dark">{{ $student->academicAdvisor->user->name ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-siakad-secondary">{{ __('Status') }}</span>
                    @if($student->status === 'active')
                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">{{ __('Active') }}</span>
                    @else
                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold bg-slate-100 text-slate-600 rounded-full">{{ ucfirst($student->status ?? 'active') }}</span>
                    @endif
                </div>
            </div>

            <!-- GPA Card -->
            <div class="mt-6 bg-siakad-primary rounded-xl p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-80 uppercase tracking-wide">{{ __('GPA') }}</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($gpaData['gpa'] ?? 0, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs opacity-80">{{ __('Total Credits') }}</p>
                        <p class="text-xl font-semibold">{{ $gpaData['total_credits'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic History -->
        <div class="lg:col-span-2 space-y-6">
            <!-- GPA History Chart -->
            <div class="card-saas p-6">
                <h3 class="font-semibold text-siakad-dark mb-4">{{ __('GPA Progress') }}</h3>
                <div class="h-48">
                    <canvas id="semesterGpaChart"></canvas>
                </div>
            </div>

            <!-- Grade Distribution -->
            <div class="card-saas p-6">
                <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Grade Distribution') }}</h3>
                <div class="grid grid-cols-7 gap-3">
                    @foreach($gradeDistribution as $grade => $count)
                    <div class="text-center p-3 bg-siakad-light/30 rounded-lg">
                        <p class="text-lg font-bold text-siakad-dark">{{ $count }}</p>
                        <p class="text-xs text-siakad-secondary">{{ $grade }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Study Plan History -->
            <div class="card-saas overflow-hidden">
                <div class="px-6 py-4 border-b border-siakad-light">
                    <h3 class="font-semibold text-siakad-dark">{{ __('Study Plan History') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-saas">
                        <thead>
                            <tr class="bg-siakad-light/30">
                                <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary uppercase tracking-wider">{{ __('Academic Year') }}</th>
                                <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary uppercase tracking-wider">{{ __('Credits') }}</th>
                                <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary uppercase tracking-wider">{{ __('GPA') }}</th>
                                <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary uppercase tracking-wider">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gpaHistory as $semester)
                            <tr class="border-b border-siakad-light/50">
                                <td class="py-3 px-5 text-sm text-siakad-dark">{{ $semester['academic_year'] }}</td>
                                <td class="py-3 px-5 text-sm text-siakad-secondary">{{ $semester['total_credits'] }} {{ __('Credits') }}</td>
                                <td class="py-3 px-5 text-sm font-semibold text-siakad-primary">{{ number_format($semester['gpa'], 2) }}</td>
                                <td class="py-3 px-5">
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 rounded-full">{{ __('Passed') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-siakad-secondary text-sm">{{ __('No study plan history yet') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const siakadPrimary = '#234C6A';
        const siakadLight = '#E3E3E3';
        
        const gpaDataHistory = @json($gpaHistory);
        const ctx = document.getElementById('semesterGpaChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: gpaDataHistory.map(d => d.academic_year.substring(0, 9)),
                datasets: [{
                    label: 'GPA',
                    data: gpaDataHistory.map(d => d.gpa),
                    borderColor: siakadPrimary,
                    backgroundColor: 'rgba(35, 76, 106, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: siakadPrimary,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { min: 0, max: 4, ticks: { stepSize: 0.5 }, grid: { color: siakadLight } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
