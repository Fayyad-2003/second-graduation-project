<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-siakad-900 dark:text-white">{{ __('Presence Management') }}</h1>
        <p class="text-siakad-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-siakad-200"></span>
            {{ __('Manage student attendance and session meetings.') }}
        </p>
    </div>

    @if($classList->isEmpty())
    <div class="card-saas p-16 text-center max-w-lg mx-auto">
        <div class="w-20 h-20 rounded-3xl bg-siakad-50 dark:bg-siakad-900/50 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-siakad-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
        </div>
        <h3 class="text-xl font-black text-siakad-900 dark:text-white mb-2">{{ __('No classes yet') }}</h3>
        <p class="text-siakad-500 font-medium">{{ __('You don\'t have any classes to teach this semester.') }}</p>
    </div>
    @else

    <!-- Search Bar -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-siakad-50 dark:bg-siakad-900/50 flex items-center justify-center text-siakad-primary font-black">
                {{ $classList->count() }}
            </div>
            <div>
                <p class="text-sm font-black text-siakad-900 dark:text-white">{{ __('Total Classes') }}</p>
                <p class="text-[10px] text-siakad-400 font-black uppercase tracking-widest">{{ __('Currently Taught') }}</p>
            </div>
        </div>

        <div class="relative w-full sm:w-80 group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors">
                <svg class="w-4 h-4 text-siakad-400 group-focus-within:text-siakad-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="searchInput" placeholder="{{ __('Search for courses...') }}" 
                class="input-saas pl-11 pr-4 py-3 text-sm w-full rounded-2xl">
        </div>
    </div>

    <!-- Semester Sections -->
    <div id="semesterContainer" class="space-y-6">
        @forelse($classesGrouped->sortKeys() as $semester => $classesSemester)
        <div class="semester-section" data-semester="{{ $semester }}">
            <!-- Semester Header -->
            <button type="button" onclick="toggleSemester('semester-{{ $semester }}')" id="btn-semester-{{ $semester }}" 
                class="semester-btn w-full flex items-center justify-between p-6 bg-siakad-900 dark:bg-siakad-950 rounded-[2rem] hover:bg-siakad-primary transition-all duration-300 group shadow-xl shadow-siakad-900/20 relative overflow-hidden" 
                data-expanded="true">
                <!-- Decorative background -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-white/5 rounded-full"></div>

                <div class="relative flex items-center gap-5">
                    <div id="badge-semester-{{ $semester }}" class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 transition-all duration-300 shadow-inner">
                        <span id="badge-text-semester-{{ $semester }}" class="font-black text-lg text-white transition-all duration-300">{{ $semester }}</span>
                    </div>
                    <div class="text-start">
                        <h3 id="title-semester-{{ $semester }}" class="text-xl font-black text-white transition-all duration-300 tracking-tight">{{ __('Semester') }} {{ $semester }}</h3>
                        <p id="subtitle-semester-{{ $semester }}" class="text-xs text-siakad-300 font-bold transition-all duration-300 flex items-center gap-2">
                            {{ $classesSemester->count() }} {{ __('Courses') }}
                            <span class="w-1 h-1 rounded-full bg-siakad-500"></span>
                            {{ $classesSemester->sum('student_count') }} {{ __('Students') }}
                        </p>
                    </div>
                </div>
                
                <div id="icon-container-semester-{{ $semester }}" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 transition-all duration-300">
                    <svg id="icon-semester-{{ $semester }}" class="w-5 h-5 text-white transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>

            <!-- Semester Content -->
            <div id="semester-{{ $semester }}" class="semester-content overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 2000px; opacity: 1; margin-top: 1.5rem;">
                <div class="card-saas overflow-hidden">
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-start border-collapse">
                            <thead>
                                <tr class="bg-siakad-50/50 dark:bg-siakad-900/30 border-b border-siakad-100/50 dark:border-siakad-800/50">
                                    <th class="py-5 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest w-16 text-start">#</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Code') }}</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Course') }}</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center w-24">{{ __('Class') }}</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center">{{ __('Students') }}</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center">{{ __('Schedule') }}</th>
                                    <th class="py-5 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
                                @foreach($classesSemester as $index => $class)
                                <tr class="class-row hover:bg-siakad-50/30 dark:hover:bg-siakad-900/20 transition-colors group"
                                    data-name="{{ strtolower($class->course->course_name ?? '') }}"
                                    data-code="{{ strtolower($class->course->course_code ?? '') }}">
                                    <td class="py-5 px-8 text-xs font-bold text-siakad-400 text-start">{{ $index + 1 }}</td>
                                    <td class="py-5 px-6 text-start">
                                        <span class="text-xs font-black text-siakad-700 dark:text-siakad-300 font-mono tracking-wider bg-siakad-50 dark:bg-siakad-900 px-2 py-1 rounded-lg border border-siakad-100 dark:border-siakad-800">{{ $class->course->course_code ?? '-' }}</span>
                                    </td>
                                    <td class="py-5 px-6 text-start">
                                        <span class="text-sm font-black text-siakad-900 dark:text-white group-hover:text-siakad-primary transition-colors">{{ $class->course->course_name ?? 'Course' }}</span>
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-siakad-50 dark:bg-siakad-900 text-xs font-black text-siakad-primary border border-siakad-100 dark:border-siakad-800 shadow-sm">{{ $class->class_name }}</span>
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        <span class="text-sm font-black text-siakad-700 dark:text-siakad-300">{{ $class->student_count ?? 0 }}</span>
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        @if($class->courseSchedules && $class->courseSchedules->isNotEmpty())
                                        <span class="inline-flex px-3 py-1 rounded-lg text-[10px] font-black bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50 uppercase tracking-widest">{{ __($class->courseSchedules->first()->day) }}</span>
                                        @else
                                        <span class="text-[10px] text-siakad-300 font-black uppercase tracking-widest">-</span>
                                        @endif
                                    </td>
                                    <td class="py-5 px-8 text-end">
                                        <a href="{{ route('lecturers.attendance-input.class', $class) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-siakad-900 dark:bg-siakad-800 text-white text-xs font-black rounded-xl hover:bg-siakad-primary transition-all shadow-lg shadow-siakad-900/10">
                                            {{ __('Manage') }}
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden grid grid-cols-1 gap-6 p-6">
                        @foreach($classesSemester as $index => $class)
                        <div class="class-row card-saas p-6 group"
                            data-name="{{ strtolower($class->course->course_name ?? '') }}"
                            data-code="{{ strtolower($class->course->course_code ?? '') }}">
                            <div class="flex items-start justify-between mb-4">
                                <div class="min-w-0">
                                    <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-siakad-50 dark:bg-siakad-900 text-siakad-primary rounded-lg border border-siakad-100 dark:border-siakad-800 font-mono mb-2 uppercase tracking-widest">{{ $class->course->course_code ?? '-' }}</span>
                                    <h4 class="font-black text-siakad-900 dark:text-white truncate group-hover:text-siakad-primary transition-colors">{{ $class->course->course_name ?? 'Course' }}</h4>
                                </div>
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-siakad-50 dark:bg-siakad-900 text-xs font-black text-siakad-primary border border-siakad-100 dark:border-siakad-800 shadow-sm">{{ $class->class_name }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-siakad-50/50 dark:bg-siakad-900/50 p-3 rounded-2xl border border-siakad-100/50 dark:border-siakad-800/50">
                                    <span class="block text-[10px] text-siakad-400 font-black uppercase tracking-widest mb-1">{{ __('Students') }}</span>
                                    <span class="font-black text-siakad-900 dark:text-white">{{ $class->student_count ?? 0 }}</span>
                                </div>
                                <div class="bg-siakad-50/50 dark:bg-siakad-900/50 p-3 rounded-2xl border border-siakad-100/50 dark:border-siakad-800/50">
                                    <span class="block text-[10px] text-siakad-400 font-black uppercase tracking-widest mb-1">{{ __('Day') }}</span>
                                    @if($class->courseSchedules && $class->courseSchedules->isNotEmpty())
                                    <span class="font-black text-siakad-900 dark:text-white">{{ __($class->courseSchedules->first()->day) }}</span>
                                    @else
                                    <span class="font-black text-siakad-300">-</span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('lecturers.attendance-input.class', $class) }}" class="flex items-center justify-center gap-2 w-full py-4 bg-siakad-900 dark:bg-siakad-800 text-white font-black text-sm rounded-2xl hover:bg-siakad-primary transition-all shadow-lg shadow-siakad-900/10">
                                {{ __('Manage Presence') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card-saas p-12 text-center max-w-md mx-auto">
            <div class="w-16 h-16 bg-siakad-50 dark:bg-siakad-900/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-siakad-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <h3 class="text-lg font-black text-siakad-900 dark:text-white mb-2">{{ __('No results') }}</h3>
            <p class="text-siakad-400 font-medium">{{ __('No classes matching the search were found.') }}</p>
        </div>
        @endforelse
    </div>

    <script>
        // Toggle semester expand/collapse with modern UI transitions
        function toggleSemester(id) {
            const content = document.getElementById(id);
            const btn = document.getElementById('btn-' + id);
            const icon = document.getElementById('icon-' + id);
            const badge = document.getElementById('badge-' + id);
            const iconContainer = document.getElementById('icon-container-' + id);

            const isExpanded = content.style.maxHeight !== '0px';

            if (!isExpanded) {
                // Expand
                content.style.maxHeight = '2000px';
                content.style.opacity = '1';
                content.style.marginTop = '1.5rem';
                icon.style.transform = 'rotate(0deg)';

                // Update styles to active (Primary/Navy)
                btn.classList.remove('bg-white', 'dark:bg-siakad-800', 'shadow-soft');
                btn.classList.add('bg-siakad-900', 'dark:bg-siakad-950', 'shadow-xl');
                
                badge.classList.remove('bg-siakad-50', 'dark:bg-siakad-900', 'border-siakad-100');
                badge.classList.add('bg-white/10', 'backdrop-blur-md', 'border-white/20');
                
                iconContainer.classList.remove('bg-siakad-50', 'dark:bg-siakad-900', 'border-siakad-100');
                iconContainer.classList.add('bg-white/5', 'border-white/10');
            } else {
                // Collapse
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
                content.style.marginTop = '0';
                icon.style.transform = 'rotate(-90deg)';

                // Revert styles to inactive
                btn.classList.add('bg-white', 'dark:bg-siakad-800', 'shadow-soft');
                btn.classList.remove('bg-siakad-900', 'dark:bg-siakad-950', 'shadow-xl');
                
                badge.classList.add('bg-siakad-50', 'dark:bg-siakad-900', 'border-siakad-100');
                badge.classList.remove('bg-white/10', 'backdrop-blur-md', 'border-white/20');
                
                iconContainer.classList.add('bg-siakad-50', 'dark:bg-siakad-900', 'border-siakad-100');
                iconContainer.classList.remove('bg-white/5', 'border-white/10');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();

                document.querySelectorAll('.class-row').forEach(row => {
                    const name = row.dataset.name || '';
                    const code = row.dataset.code || '';

                    if (name.includes(query) || code.includes(query)) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });
        });
    </script>

    <style>
        .semester-content {
            transition: max-height 0.3s ease-in-out, opacity 0.2s ease-in-out, margin-top 0.2s ease-in-out;
        }
    </style>
    @endif
</x-app-layout>
