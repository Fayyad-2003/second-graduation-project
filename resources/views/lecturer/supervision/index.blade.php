<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Supervised Students') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('List of students under your academic supervision.') }}
        </p>
    </div>

    <!-- Toolbar: Filter, Search -->
    <div class="mb-8 card-saas p-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[300px]">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-primary-400 group-focus-within:text-primary-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="{{ __('Search by Name/Student ID...') }}"
                        class="input-saas pl-11 pr-4 py-2.5 text-sm w-full">
                </div>
            </div>

            <select id="batchFilter" class="input-saas px-4 py-2.5 text-sm w-full sm:w-48">
                <option value="">{{ __('All Batches') }}</option>
                @foreach($batchList as $batch)
                <option value="{{ $batch }}">{{ $batch }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-saas overflow-hidden">
        <div id="tableContainer" class="overflow-x-auto">
            @include('lecturer.supervision._table')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const batchFilter = document.getElementById('batchFilter');
            const tableContainer = document.getElementById('tableContainer');
            let searchTimeout;
            let currentSort = '{{ request("sort", "batch") }}';
            let currentDir = '{{ request("dir", "desc") }}';

            function fetchData(page = 1) {
                const search = searchInput.value;
                const batch = batchFilter.value;
                const url = `{{ route('lecturers.supervision.index') }}?page=${page}&search=${encodeURIComponent(search)}&batch=${encodeURIComponent(batch)}&sort=${currentSort}&dir=${currentDir}`;

                tableContainer.style.opacity = '0.5';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        tableContainer.style.opacity = '1';
                        bindPaginationLinks();
                        bindSortableHeaders();
                    })
                    .catch(err => {
                        console.error(err);
                        tableContainer.style.opacity = '1';
                    });
            }

            function bindPaginationLinks() {
                tableContainer.querySelectorAll('.pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page') || 1;
                        fetchData(page);
                    });
                });
            }

            function bindSortableHeaders() {
                tableContainer.querySelectorAll('.sortable-header').forEach(header => {
                    header.addEventListener('click', function() {
                        const sortField = this.dataset.sort;

                        // Toggle direction if same field, otherwise default to asc
                        if (currentSort === sortField) {
                            currentDir = currentDir === 'asc' ? 'desc' : 'asc';
                        } else {
                            currentSort = sortField;
                            currentDir = 'asc';
                        }

                        fetchData(1);
                    });
                });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => fetchData(), 300);
            });

            batchFilter.addEventListener('change', function() {
                fetchData();
            });

            bindPaginationLinks();
            bindSortableHeaders();
        });
    </script>
</x-app-layout>