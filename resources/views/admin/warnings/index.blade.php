<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Student Warnings') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('Send and manage warnings to students.') }}
        </p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-soft dark:bg-emerald-950/20 dark:border-emerald-900/50 dark:text-emerald-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="mb-8 card-saas p-6">
        <button onclick="openModal('createModal')"
            class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-primary-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ __('Send Warning') }}
        </button>
    </div>

    <!-- Table Card -->
    <div class="card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest w-16 text-start">#</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Student') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Message') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Sent By') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Status') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Date') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($warnings as $index => $warning)
                    <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
                        <td class="py-5 px-8 text-xs font-bold text-primary-400 text-start">{{ $warnings->firstItem() + $index }}</td>
                        <td class="py-5 px-6 text-start">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center text-primary-600 dark:text-primary-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($warning->student->user->name ?? '-', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-primary-900 dark:text-white truncate">{{ $warning->student->user->name ?? '-' }}</p>
                                    <p class="text-[10px] text-primary-400 font-bold uppercase tracking-wider text-start">{{ $warning->student->student_number ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <p class="text-xs text-primary-700 dark:text-primary-300 line-clamp-2">{{ $warning->message }}</p>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <p class="text-xs font-bold text-primary-600 dark:text-primary-400">{{ $warning->creator->name ?? '-' }}</p>
                        </td>
                        <td class="py-5 px-6 text-start">
                            @php
                            $statusClasses = [
                            true => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/50',
                            false => 'bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 border-amber-100 dark:border-amber-900/50'
                            ];
                            $statusClass = $statusClasses[$warning->is_read];
                            @endphp
                            <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $statusClass }}">
                                {{ $warning->is_read ? __('Read') : __('Unread') }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <span class="text-xs font-black text-primary-700 dark:text-primary-300">{{ $warning->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="py-5 px-8 text-end">
                            <form action="{{ route('admin.warnings.destroy', $warning) }}" method="POST" class="inline"
                                onsubmit="return confirm('{{ __('Are you sure?') }}')">@csrf @method('DELETE')
                                <button type="submit" class="p-2 text-primary-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                    title="{{ __('Delete') }}"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-12 h-12 bg-primary-light/50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-primary-secondary dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-primary-secondary dark:text-gray-400 text-sm">{{ __('No warnings available') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($warnings->hasPages())
    <div class="mt-6 card-saas px-6 py-4">
        {{ $warnings->links() }}
    </div>
    @endif

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 modal-overlay" onclick="closeModal('createModal')"></div>

            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden transform transition-all animate-fade-in">
                <!-- Decorative background -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 bg-amber-500/5 rounded-full"></div>

                <div class="relative">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-primary-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            {{ __('Send Warning') }}
                        </h3>
                        <button onclick="closeModal('createModal')" class="text-primary-400 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.warnings.store') }}" method="POST">
                        @csrf
                        <div class="space-y-5 max-h-[60vh] overflow-y-auto pr-2 scrollbar-hide">
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Select Student') }}</label>
                                <select name="student_id" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                    <option value="">{{ __('-- Select Student --') }}</option>
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->name }} ({{ $student->student_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Warning Message') }}</label>
                                <textarea name="message" rows="5" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('Enter warning message...') }}" required></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="closeModal('createModal')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">{{ __('Send Warning') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
</x-app-layout>