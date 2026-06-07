<x-app-layout>
    <x-slot name="header">
        @if($isSuperAdmin)
        {{ __('Admin Dashboard') }}
        @else
        {{ __('Faculty Dashboard') }} {{ $faculty?->name ?? __('Faculty') }}
        @endif
    </x-slot>

    <!-- Faculty Info Banner for admin_faculty -->
    @if(!$isSuperAdmin && $faculty)
    <div class="mb-8 p-8 bg-gradient-to-r from-siakad-600 via-purple-600 to-pink-600 rounded-[24px] shadow-2xl relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 transition-transform duration-700 group-hover:scale-150 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24 transition-transform duration-700 group-hover:scale-150 blur-3xl"></div>
        <div class="relative flex items-center gap-6">
            <div class="w-20 h-20 bg-white/15 backdrop-blur-xl rounded-[20px] flex items-center justify-center border-2 border-white/30 shadow-2xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-3xl font-black text-white tracking-tight drop-shadow-lg">{{ $faculty->name }}</h2>
                <div class="flex items-center gap-4 mt-2">
                    <p class="text-base text-white/90 font-semibold">{{ __('Academic Year') }}: {{ $activeYear?->year ?? '-' }}</p>
                    <span class="w-1.5 h-1.5 rounded-full bg-white/50"></span>
                    <p class="text-base text-white/90 font-bold uppercase tracking-wider">{{ ucfirst($activeYear?->semester ?? '') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-{{ $isSuperAdmin ? '6' : '5' }} gap-6 mb-10">
        @if($isSuperAdmin)
        <div class="card-saas p-6 group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-siakad-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-siakad-100 to-purple-100 dark:from-siakad-900/30 dark:to-purple-900/30 rounded-[18px] flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-siakad-600 dark:text-siakad-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black bg-gradient-to-r from-siakad-600 to-purple-600 bg-clip-text text-transparent">{{ $stats['faculty'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mt-0.5">{{ __('Faculties') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="card-saas p-6 group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-[18px] flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">{{ $stats['study_program'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mt-0.5">{{ __('Programs') }}</p>
                </div>
            </div>
        </div>

        <div class="card-saas p-6 group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 rounded-[18px] flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">{{ $stats['student'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mt-0.5">{{ __('Students') }}</p>
                </div>
            </div>
        </div>

        <div class="card-saas p-6 group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 rounded-[18px] flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">{{ $stats['lecturer'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mt-0.5">{{ __('Lecturers') }}</p>
                </div>
            </div>
        </div>

        <div class="card-saas p-6 group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-orange-500/5 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 rounded-[18px] flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">{{ $stats['course'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mt-0.5">{{ __('Courses') }}</p>
                </div>
            </div>
        </div>

        <div class="card-saas p-6 group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-pink-500/5 to-rose-500/5 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-pink-100 to-rose-100 dark:from-pink-900/30 dark:to-rose-900/30 rounded-[18px] flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">{{ $stats['academic_class'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mt-0.5">{{ __('Classes') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Grade Distribution -->
        <div class="card-saas p-6 dark:bg-gray-800">
            <h3 class="font-semibold text-siakad-dark dark:text-white mb-4">{{ __('Grade Distribution') }}</h3>
            @if(count($gradeDistribution) > 0)
            <div class="h-48">
                <canvas id="gradeChart"></canvas>
            </div>
            @else
            <div class="h-48 flex items-center justify-center text-siakad-secondary dark:text-gray-500 text-sm">
                {{ __('No grade data available yet') }}
            </div>
            @endif
        </div>

        <!-- Students per Study Program -->
        <div class="card-saas p-6 dark:bg-gray-800">
            <h3 class="font-semibold text-siakad-dark dark:text-white mb-4">{{ __('Students per Study Program') }}</h3>
            <div class="space-y-3 max-h-48 overflow-y-auto">
                @forelse($studyProgramStats as $studyProgram)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-siakad-secondary dark:text-gray-400 truncate flex-1 mr-2">{{ $studyProgram->name }}</span>
                    <span class="text-sm font-semibold text-siakad-dark dark:text-white">{{ $studyProgram->students_count }}</span>
                </div>
                @empty
                <div class="text-center text-siakad-secondary dark:text-gray-500 text-sm py-4">
                    {{ __('No data available yet') }}
                </div>
                @endforelse
            </div>
        </div>

        <!-- Lecturers per Study Program -->
        <div class="card-saas p-6 dark:bg-gray-800">
            <h3 class="font-semibold text-siakad-dark dark:text-white mb-4">{{ __('Lecturers per Study Program') }}</h3>
            <div class="space-y-3 max-h-48 overflow-y-auto">
                @forelse($lecturersByStudyProgram as $studyProgram)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-siakad-secondary dark:text-gray-400 truncate flex-1 mr-2">{{ $studyProgram->name }}</span>
                    <span class="text-sm font-semibold text-siakad-dark dark:text-white">{{ $studyProgram->lecturers_count }}</span>
                </div>
                @empty
                <div class="text-center text-siakad-secondary dark:text-gray-500 text-sm py-4">
                    {{ __('No data available yet') }}
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @if($isSuperAdmin)
        <a href="{{ route('admin.faculty.index') }}" class="card-saas p-4 hover:border-siakad-primary/30 dark:hover:border-blue-500/30 group flex items-center gap-3 dark:bg-gray-800">
            <div class="w-9 h-9 bg-siakad-primary/10 dark:bg-blue-500/20 rounded-lg flex items-center justify-center group-hover:bg-siakad-primary/20 dark:group-hover:bg-blue-500/30 transition">
                <svg class="w-4 h-4 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <span class="text-sm font-medium text-siakad-dark dark:text-white">{{ __('Faculty Management') }}</span>
        </a>
        @endif
        <a href="{{ route('admin.study-program.index') }}" class="card-saas p-4 hover:border-siakad-primary/30 dark:hover:border-blue-500/30 group flex items-center gap-3 dark:bg-gray-800">
            <div class="w-9 h-9 bg-siakad-secondary/10 dark:bg-gray-700/50 rounded-lg flex items-center justify-center group-hover:bg-siakad-secondary/20 dark:group-hover:bg-gray-600 transition">
                <svg class="w-4 h-4 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <span class="text-sm font-medium text-siakad-dark dark:text-white">{{ __('Study Program Management') }}</span>
        </a>
        <a href="{{ route('admin.student.index') }}" class="card-saas p-4 hover:border-siakad-primary/30 dark:hover:border-blue-500/30 group flex items-center gap-3 dark:bg-gray-800">
            <div class="w-9 h-9 bg-siakad-primary/10 dark:bg-blue-500/20 rounded-lg flex items-center justify-center group-hover:bg-siakad-primary/20 dark:group-hover:bg-blue-500/30 transition">
                <svg class="w-4 h-4 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <span class="text-sm font-medium text-siakad-dark dark:text-white">{{ __('Student Management') }}</span>
        </a>
        <a href="{{ route('admin.lecturer.index') }}" class="card-saas p-4 hover:border-siakad-primary/30 dark:hover:border-blue-500/30 group flex items-center gap-3 dark:bg-gray-800">
            <div class="w-9 h-9 bg-siakad-dark/10 dark:bg-gray-700/50 rounded-lg flex items-center justify-center group-hover:bg-siakad-dark/20 dark:group-hover:bg-gray-600 transition">
                <svg class="w-4 h-4 text-siakad-dark dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <span class="text-sm font-medium text-siakad-dark dark:text-white">{{ __('Lecturer Management') }}</span>
        </a>
    </div>

    @push('scripts')
    @if(count($gradeDistribution) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const siakadPrimary = '#234C6A';
        const siakadSecondary = '#456882';
        const siakadDark = '#1B3C53';

        // Grade Chart
        const gradeData = @json($gradeDistribution);
        const gradeCtx = document.getElementById('gradeChart').getContext('2d');
        new Chart(gradeCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(gradeData),
                datasets: [{
                    data: Object.values(gradeData),
                    backgroundColor: [
                        siakadPrimary,
                        siakadSecondary,
                        siakadDark,
                        '#86c5e0',
                        '#b9dded',
                        '#dceef6',
                        '#E3E3E3'
                    ],
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
    @endif
    @endpush
</x-app-layout>
