<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Student Grading') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('Evaluate student performance and manage grade records for your courses.') }}
        </p>
    </div>

    <!-- Search & Filter Bar -->
    <div class="mb-8 card-saas p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center text-primary-primary border border-primary-100 dark:border-primary-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-black text-primary-900 dark:text-white">{{ __('Teaching Load') }}</p>
                    <p class="text-xs font-bold text-primary-400 uppercase tracking-widest mt-0.5">
                        <span id="totalClass" class="text-primary-primary">{{ $teachingClasses->count() }}</span> {{ __('Active Classes') }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 flex-1 max-w-2xl">
                <!-- Search -->
                <div class="relative flex-1 w-full group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-primary-400 group-focus-within:text-primary-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="{{ __('Search course name or code...') }}"
                        class="input-saas pl-11 pr-4 py-2.5 text-sm w-full">
                </div>
                <!-- Filter Semester -->
                <select id="semesterFilter" class="input-saas px-4 py-2.5 text-sm w-full sm:w-48">
                    <option value="">{{ __('All Semesters') }}</option>
                    @foreach($semesterList as $sem)
                    <option value="{{ $sem }}">{{ __('Semester') }} {{ $sem }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if($teachingClasses->isEmpty() && !request('search') && !request('semester'))
    <div class="card-saas p-16 text-center max-w-lg mx-auto">
        <div class="w-20 h-20 rounded-3xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center mx-auto mb-6 border border-primary-100 dark:border-primary-800">
            <svg class="w-10 h-10 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-black text-primary-900 dark:text-white mb-2">{{ __('No classes yet') }}</h3>
        <p class="text-primary-500 font-medium">{{ __('You don\'t have any classes to teach this semester.') }}</p>
    </div>
    @else
    <div id="cardsContainer" class="space-y-6">
        @include('lecturer.grading._cards')
    </div>
    @endif

    <script>
        // Toggle semester expand/collapse with color change
        function toggleSemester(id) {
            const content = document.getElementById(id);
            const btn = document.getElementById('btn-' + id);
            const icon = document.getElementById('icon-' + id);
            const badge = document.getElementById('badge-' + id);
            const badgeText = document.getElementById('badge-text-' + id);
            const title = document.getElementById('title-' + id);
            const subtitle = document.getElementById('subtitle-' + id);
            const count = document.getElementById('count-' + id);

            const isExpanded = content.style.maxHeight !== '0px';

            if (!isExpanded) {
                // Expand - change to active theme
                content.style.maxHeight = '2000px';
                content.style.opacity = '1';
                content.style.marginTop = '1.5rem';
                icon.classList.remove('-rotate-90');

                btn.classList.add('ring-2', 'ring-primary-primary', 'dark:ring-primary-600', 'bg-primary-50/50', 'dark:bg-primary-900/30');
                badge.classList.add('bg-primary-primary', 'text-white');
                badge.classList.remove('bg-white', 'dark:bg-primary-800', 'text-primary-primary');
                title.classList.add('text-primary-primary');
            } else {
                // Collapse
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
                content.style.marginTop = '0';
                icon.classList.add('-rotate-90');

                btn.classList.remove('ring-2', 'ring-primary-primary', 'dark:ring-primary-600', 'bg-primary-50/50', 'dark:bg-primary-900/30');
                badge.classList.remove('bg-primary-primary', 'text-white');
                badge.classList.add('bg-white', 'dark:bg-primary-800', 'text-primary-primary');
                title.classList.remove('text-primary-primary');
            }
        }

        // Expand all semesters
        function expandAll() {
            document.querySelectorAll('.semester-content').forEach(el => {
                el.style.maxHeight = '2000px';
                el.style.opacity = '1';
                el.style.marginTop = '0.75rem';
            });
            document.querySelectorAll('[id^="icon-semester"]').forEach(icon => {
                icon.classList.remove('-rotate-90');
            });
        }

        // Collapse all semesters
        function collapseAll() {
            document.querySelectorAll('.semester-content').forEach(el => {
                el.style.maxHeight = '0px';
                el.style.opacity = '0';
                el.style.marginTop = '0';
            });
            document.querySelectorAll('[id^="icon-semester"]').forEach(icon => {
                icon.classList.add('-rotate-90');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const semesterFilter = document.getElementById('semesterFilter');
            const cardsContainer = document.getElementById('cardsContainer');
            let searchTimeout;

            function fetchData() {
                const search = searchInput.value;
                const semester = semesterFilter.value;
                const url = `{{ route('lecturers.grading.index') }}?search=${encodeURIComponent(search)}&semester=${encodeURIComponent(semester)}`;

                cardsContainer.style.opacity = '0.5';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        cardsContainer.innerHTML = html;
                        cardsContainer.style.opacity = '1';
                    })
                    .catch(err => {
                        console.error(err);
                        cardsContainer.style.opacity = '1';
                    });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => fetchData(), 300);
            });

            semesterFilter.addEventListener('change', function() {
                fetchData();
            });
        });
    </script>

    <style>
        #cardsContainer {
            transition: opacity 0.2s ease-in-out;
        }

        .semester-content {
            transition: max-height 0.3s ease-in-out, opacity 0.2s ease-in-out, margin-top 0.2s ease-in-out;
        }
    </style>
</x-app-layout>