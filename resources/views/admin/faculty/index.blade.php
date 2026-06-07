<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-siakad-900 dark:text-white">{{ __('Faculty Management') }}</h1>
        <p class="text-siakad-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-siakad-200"></span>
            {{ __('Organize academic faculties and their graduation requirements.') }}
        </p>
    </div>

    <div class="mb-8 card-saas p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-siakad-400 group-focus-within:text-siakad-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="facultySearch" placeholder="{{ __('Search faculty...') }}"
                    class="input-saas pl-11 pr-4 py-2.5 text-sm w-full">
            </div>

            <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-siakad-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('Add Faculty') }}
            </button>
        </div>
    </div>

    <div class="card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-siakad-50/50 dark:bg-siakad-900/30 border-b border-siakad-100/50 dark:border-siakad-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start w-16">#</th>
                        <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Faculty Name') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center">{{ __('Programs') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center">{{ __('Total Students') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
                    @forelse($faculties as $index => $f)
                    <tr class="hover:bg-siakad-50/30 dark:hover:bg-siakad-900/20 transition-colors group">
                        <td class="py-5 px-8 text-xs font-bold text-siakad-400">{{ $index + 1 }}</td>
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-siakad-50 to-siakad-100 dark:from-siakad-900/50 dark:to-siakad-800/50 flex items-center justify-center text-siakad-600 dark:text-siakad-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($f->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-black text-siakad-900 dark:text-white">{{ $f->name }}</span>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-siakad-50 dark:bg-siakad-900 text-siakad-600 dark:text-siakad-400 rounded-lg border border-siakad-100 dark:border-siakad-800 uppercase">{{ $f->study_programs_count ?? $f->studyPrograms->count() }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg border border-emerald-100 dark:border-emerald-900/50 uppercase">{{ $f->studyPrograms->sum(fn($p) => $p->students->count()) }}</span>
                        </td>
                        <td class="py-5 px-8 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.faculty.requirements', $f) }}" class="p-2 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded-lg transition-colors" title="{{ __('Requirements') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </a>
                                <button onclick="editFaculty({{ $f->id }}, '{{ $f->name }}')" class="p-2 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.faculty.destroy', $f) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this faculty?') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-siakad-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-siakad-50 dark:bg-siakad-900/50 rounded-2xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-siakad-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <p class="text-siakad-400 text-sm font-bold">{{ __('No faculty data available yet.') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card List -->
    <div class="md:hidden space-y-4">
        @forelse($faculties as $f)
        <div class="card-saas p-4 dark:bg-gray-800">
            <div class="flex items-start justify-between mb-3">
                <h4 class="font-bold text-siakad-dark dark:text-white">{{ $f->name }}</h4>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg text-center">
                    <span class="block text-[10px] text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Study Programs') }}</span>
                    <span class="font-bold text-siakad-primary dark:text-blue-400">{{ $f->study_programs_count ?? $f->studyPrograms->count() }}</span>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg text-center">
                    <span class="block text-[10px] text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">{{ __('Students') }}</span>
                    <span class="font-bold text-siakad-dark dark:text-white">{{ $f->studyPrograms->sum(fn($p) => $p->students->count()) }}</span>
                </div>
            </div>

            <div class="flex items-center gap-2 pt-3 border-t border-siakad-light dark:border-gray-700">
                <a href="{{ route('admin.faculty.requirements', $f) }}" class="flex-1 py-2 text-sm font-medium text-siakad-primary bg-siakad-primary/10 rounded-lg hover:bg-siakad-primary hover:text-white transition text-center">
                    {{ __('Reqs') }}
                </a>
                <button onclick="editFaculty({{ $f->id }}, '{{ $f->name }}')" class="flex-1 py-2 text-sm font-medium text-siakad-secondary bg-siakad-light/50 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-siakad-light hover:text-siakad-primary dark:hover:bg-gray-600 transition text-center">
                    {{ __('Edit') }}
                </button>
                <form action="{{ route('admin.faculty.destroy', $f) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this faculty?') }}')" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-2 text-sm font-medium text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400 rounded-lg hover:bg-red-100 hover:text-red-700 dark:hover:bg-red-900/40 transition">
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="card-saas p-8 text-center">
            <p class="text-siakad-secondary dark:text-gray-400 mb-2">{{ __('No faculty data available yet') }}</p>
        </div>
        @endforelse
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-siakad-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('createModal').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-siakad-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden animate-fade-in flex flex-col max-h-[90vh]">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-siakad-primary/5 rounded-full"></div>

                <h3 class="text-xl font-black text-siakad-900 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-siakad-50 dark:bg-siakad-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    {{ __('Add New Faculty') }}
                </h3>

                <form action="{{ route('admin.faculty.store') }}" method="POST" class="flex flex-col min-h-0 space-y-6">
                    @csrf
                    <div class="overflow-y-auto pr-2 scrollbar-hide space-y-6 flex-1">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-siakad-400 uppercase tracking-widest ml-1">{{ __('Faculty Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="input-saas w-full" placeholder="{{ __('Enter faculty name') }}" required>
                        </div>

                        <div class="pt-4 border-t border-siakad-50 dark:border-siakad-800">
                            <p class="text-[10px] font-black text-siakad-primary uppercase tracking-widest mb-4">{{ __('Graduation Requirements') }}</p>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-siakad-400 uppercase tracking-widest ml-1">{{ __('Total Required Credits') }} <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" name="total_credits" min="0" value="0" class="input-saas w-full pr-16 font-black text-siakad-primary" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <span class="text-[10px] font-black text-siakad-400 uppercase tracking-widest">{{ __('Credits') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($classifications->isNotEmpty())
                        <div class="pt-4 border-t border-siakad-50 dark:border-siakad-800">
                            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-4">{{ __('Credits per Classification') }}</p>
                            <div class="grid grid-cols-1 gap-3">
                                @foreach($classifications as $classification)
                                <div class="flex items-center justify-between p-4 bg-siakad-50/50 dark:bg-siakad-800/50 rounded-2xl border border-siakad-100/50 dark:border-siakad-700/50">
                                    <span class="text-xs font-bold text-siakad-700 dark:text-siakad-300">{{ __($classification->name) }}</span>
                                    <div class="relative w-24">
                                        <input type="number" name="requirements[{{ $classification->id }}]" min="0" value="0" class="input-saas w-full pr-10 text-center font-black text-emerald-600">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <span class="text-[8px] font-black text-siakad-400 uppercase tracking-widest">SKS</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-siakad-50 dark:border-siakad-800 sticky bottom-0 bg-white dark:bg-siakad-900">
                        <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-6 py-2.5 text-sm font-bold text-siakad-400 hover:text-siakad-600 transition">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-siakad-primary/20">{{ __('Save Faculty') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-siakad-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editModal').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-siakad-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden animate-fade-in flex flex-col">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-siakad-primary/5 rounded-full"></div>

                <h3 class="text-xl font-black text-siakad-900 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-siakad-50 dark:bg-siakad-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    {{ __('Edit Faculty') }}
                </h3>

                <form id="editForm" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-siakad-400 uppercase tracking-widest ml-1">{{ __('Faculty Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="editName" class="input-saas w-full" required>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-siakad-50 dark:border-siakad-800">
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-6 py-2.5 text-sm font-bold text-siakad-400 hover:text-siakad-600 transition">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-siakad-primary/20">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editFaculty(id, name) {
            document.getElementById('editForm').action = `/admin/faculty/${id}`;
            document.getElementById('editName').value = name;
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>