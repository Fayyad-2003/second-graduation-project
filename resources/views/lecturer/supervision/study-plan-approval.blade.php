<x-app-layout>
    <x-slot name="header">
        {{ __('Study Plan Approval for Supervised Students') }}
    </x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-primary-secondary">{{ __('List of study plans waiting for approval') }}</p>
        <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
            <!-- Search -->
            <div class="relative w-full md:w-auto">
                <input type="text" id="searchInput" placeholder="{{ __('Search by Name/Student ID...') }}" class="input-saas pl-9 pr-4 py-2 text-sm w-full md:w-48">
                <svg class="w-4 h-4 text-primary-secondary absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <!-- Filter Status -->
            <select id="statusFilter" class="input-saas text-sm py-2 w-full md:w-auto">
                <option value="pending" {{ request('status', 'pending') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>{{ __('All Statuses') }}</option>
            </select>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-saas overflow-hidden">
        <div id="tableContainer" class="overflow-x-auto">
            @include('lecturer.supervision._study-plan-table')
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-primary-dark rounded-xl w-full max-w-md shadow-xl">
            <div class="px-6 py-4 border-b border-primary-light dark:border-primary-light/20">
                <h3 class="text-lg font-semibold text-primary-dark dark:text-white">{{ __('Reject Study Plan') }}</h3>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-6">
                    <label class="block text-sm font-medium text-primary-dark dark:text-white mb-2">{{ __('Reason for Rejection') }}</label>
                    <textarea name="notes" rows="4" class="input-saas w-full resize-none" placeholder="{{ __('Enter reason for rejection... (optional)') }}"></textarea>
                    <p class="text-xs text-primary-secondary mt-2">{{ __('Students will see this note as the reason for rejection.') }}</p>
                </div>
                <div class="px-6 py-4 border-t border-primary-light dark:border-primary-light/20 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="btn-ghost-saas px-4 py-2 rounded-lg text-sm">{{ __('Cancel') }}</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">{{ __('Reject Study Plan') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tableContainer = document.getElementById('tableContainer');
            let searchTimeout;
            let currentSort = '{{ request("sort", "updated_at") }}';
            let currentDir = '{{ request("dir", "desc") }}';

            function fetchData(page = 1) {
                const search = searchInput.value;
                const status = statusFilter.value;
                const url = `{{ route('lecturers.supervision.study-plan-approval') }}?page=${page}&search=${encodeURIComponent(search)}&sort=${currentSort}&dir=${currentDir}&status=${status}`;


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

            statusFilter.addEventListener('change', function() {
                fetchData();
            });

            bindPaginationLinks();
            bindSortableHeaders();
        });

        function openRejectModal(url) {
            document.getElementById('rejectForm').action = url;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
</x-app-layout>