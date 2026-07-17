<x-app-layout>
    <x-slot name="header">{{ __('E-Learning') }}</x-slot>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-primary-dark dark:text-white">{{ __('Select Class') }}</h2>
        <p class="text-sm text-primary-secondary dark:text-gray-400">{{ __('Manage materials and assignments for the classes you teach') }}</p>
    </div>

    <!-- Search Filter -->
    <div class="mb-6">
        <div class="relative">
            <input type="text" id="searchClass" placeholder="{{ __('Search for courses or codes...') }}"
                class="input-saas w-full md:w-80 pl-10 pr-4 py-2.5 text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                onkeyup="filterClass()">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-primary-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    @if($classList->isEmpty())
    <div class="card-saas p-12 text-center dark:bg-gray-800">
        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-primary-light dark:bg-gray-700 flex items-center justify-center">
            <svg class="w-10 h-10 text-primary-secondary/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
            </svg>
        </div>
        <p class="font-medium text-primary-dark dark:text-white">{{ __('You are not teaching any classes.') }}</p>
        <p class="text-sm text-primary-secondary dark:text-gray-400 mt-1">{{ __('Classes assigned to you will appear here.') }}</p>
    </div>
    @else
    <div id="classGrid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($classList as $index => $class)
        @php
            $palettes = [
                ['from' => 'from-blue-500',   'to' => 'to-indigo-600',  'badge' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'],
                ['from' => 'from-violet-500', 'to' => 'to-purple-600',  'badge' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300'],
                ['from' => 'from-emerald-500','to' => 'to-teal-600',    'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'],
                ['from' => 'from-orange-500', 'to' => 'to-rose-500',    'badge' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300'],
                ['from' => 'from-cyan-500',   'to' => 'to-blue-500',    'badge' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300'],
                ['from' => 'from-pink-500',   'to' => 'to-rose-600',    'badge' => 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300'],
            ];
            $p = $palettes[$index % count($palettes)];
        @endphp
        <div class="class-card flex flex-col rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700"
            data-search="{{ strtolower($class->course->course_name . ' ' . $class->course->course_code . ' ' . $class->class_name) }}">

            <!-- Colored Banner -->
            <div class="bg-gradient-to-r {{ $p['from'] }} {{ $p['to'] }} px-5 pt-5 pb-8 relative">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center text-white font-bold text-lg">
                        {{ $class->class_name }}
                    </div>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-white/20 text-white">
                        {{ $class->course->credits }} {{ __('Credits') }}
                    </span>
                </div>
                <h3 class="mt-3 font-bold text-white text-base leading-snug line-clamp-2">
                    {{ $class->course->course_name }}
                </h3>
                <p class="text-xs text-white/70 mt-0.5">{{ $class->course->course_code }}</p>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-3 divide-x divide-gray-100 dark:divide-gray-700 -mt-4 mx-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 z-10 relative">
                <div class="flex flex-col items-center py-3">
                    <span class="text-lg font-bold text-primary-dark dark:text-white">{{ $class->material_count }}</span>
                    <span class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Materials') }}</span>
                </div>
                <div class="flex flex-col items-center py-3">
                    <span class="text-lg font-bold text-primary-dark dark:text-white">{{ $class->assignment_count }}</span>
                    <span class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Assignments') }}</span>
                </div>
                <div class="flex flex-col items-center py-3">
                    <span class="text-lg font-bold text-primary-dark dark:text-white">{{ $class->exam_count }}</span>
                    <span class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Exams') }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-4 mt-2 flex flex-col gap-2 flex-1 justify-end">
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('lecturers.material.index', $class->id) }}"
                        class="flex items-center justify-center gap-1.5 px-3 py-2.5 text-sm font-medium bg-gray-50 dark:bg-gray-700 text-primary-dark dark:text-white rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition border border-gray-100 dark:border-gray-600">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ __('Materials') }}
                    </a>
                    <a href="{{ route('lecturers.assignment.index', $class->id) }}"
                        class="flex items-center justify-center gap-1.5 px-3 py-2.5 text-sm font-medium bg-primary-primary text-white rounded-xl hover:bg-primary-primary/90 transition">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        {{ __('Assignments') }}
                    </a>
                </div>
                <a href="{{ route('lecturers.exam.index', $class->id) }}"
                    class="flex items-center justify-center gap-1.5 px-3 py-2.5 text-sm font-medium bg-gradient-to-r from-violet-500 to-indigo-600 text-white rounded-xl hover:from-violet-600 hover:to-indigo-700 transition">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('Exams') }}
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="hidden card-saas p-8 text-center dark:bg-gray-800">
        <svg class="w-12 h-12 mx-auto mb-3 text-primary-secondary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        <p class="text-primary-secondary dark:text-gray-400">{{ __('No classes found.') }}</p>
    </div>
    @endif

    <script>
        function filterClass() {
            const query = document.getElementById('searchClass').value.toLowerCase();
            const cards = document.querySelectorAll('.class-card');
            const noResults = document.getElementById('noResults');
            let visibleCount = 0;

            cards.forEach(card => {
                const searchText = card.dataset.search;
                if (searchText.includes(query)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0);
            }
        }
    </script>
</x-app-layout>
