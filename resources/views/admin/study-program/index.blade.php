<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Program Management') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('Manage individual study programs and departments within each faculty.') }}
        </p>
    </div>

    <div class="mb-8 card-saas p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-primary-400 group-focus-within:text-primary-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="programSearch" placeholder="{{ __('Search study program...') }}"
                    class="input-saas pl-11 pr-4 py-2.5 text-sm w-full">
            </div>

            <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-primary-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('Add Program') }}
            </button>
        </div>
    </div>


    <!-- Study Programs Grouped by Faculty (Collapsible) -->
    @foreach($faculties as $index => $f)
    <div class="card-saas overflow-hidden mb-8" x-data="{ open: false }">
        <!-- Faculty Header (Clickable) -->
        <button @click="open = !open" type="button" class="w-full px-8 py-6 bg-primary-50/50 dark:bg-primary-900/20 border-b border-primary-100/50 dark:border-primary-800/50 flex items-center justify-between hover:bg-primary-50 dark:hover:bg-primary-900/40 transition-all duration-300 cursor-pointer text-start group">
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 rounded-2xl bg-white dark:bg-primary-800 flex items-center justify-center text-primary-600 dark:text-primary-400 text-lg font-black shadow-soft border border-primary-100 dark:border-primary-700 group-hover:scale-110 transition-transform">
                    {{ strtoupper(substr($f->name, 0, 1)) }}
                </div>
                <div class="text-start">
                    <h3 class="font-black text-primary-900 dark:text-white text-base tracking-tight group-hover:text-primary-primary transition-colors">{{ $f->name }}</h3>
                    <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-0.5">{{ $f->studyPrograms->count() }} {{ __('Programs') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <span class="hidden sm:inline-flex items-center px-3 py-1 text-[10px] font-black bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg border border-emerald-100 dark:border-emerald-900/50 uppercase tracking-widest">
                    {{ $f->studyPrograms->sum(fn($p) => $p->students_count ?? 0) }} {{ __('Students') }}
                </span>
                <!-- Chevron Icon -->
                <div class="p-2 rounded-xl bg-primary-50 dark:bg-primary-900/50 group-hover:bg-primary-100 dark:group-hover:bg-primary-900 transition-colors">
                    <svg class="w-5 h-5 text-primary-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </button>

        <!-- Study Program Table (Collapsible Content) -->
        <div x-show="open" x-collapse>
            <div class="overflow-x-auto">
                <table class="w-full text-start border-collapse">
                    <thead>
                        <tr class="bg-primary-50/30 dark:bg-primary-900/10 border-b border-primary-100/30 dark:border-primary-800/30">
                            <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start w-16">#</th>
                            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Program Name') }}</th>
                            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-32">{{ __('Students') }}</th>
                            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-32">{{ __('Lecturers') }}</th>
                            <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-end w-32">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary-50/50 dark:divide-primary-800/30">
                        @forelse($f->studyPrograms as $idx => $p)
                        <tr class="hover:bg-primary-50/20 dark:hover:bg-primary-900/10 transition-colors group/row">
                            <td class="py-5 px-8 text-xs font-bold text-primary-400">{{ $idx + 1 }}</td>
                            <td class="py-5 px-6">
                                <span class="text-sm font-black text-primary-900 dark:text-white group-hover/row:text-primary-primary transition-colors">{{ $p->name }}</span>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-primary-50 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-lg border border-primary-100 dark:border-primary-800 uppercase">{{ $p->students_count ?? 0 }}</span>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-primary-50 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-lg border border-primary-100 dark:border-primary-800 uppercase">{{ $p->lecturers_count ?? 0 }}</span>
                            </td>
                            <td class="py-5 px-8 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="editStudyProgram({{ $p->id }}, '{{ $p->name }}', {{ $f->id }})"
                                        class="p-2 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.study-program.destroy', $p) }}" method="POST" onsubmit="return confirm('{{ __('Delete this study program?') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-primary-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Delete') }}">
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
                                <p class="text-primary-400 text-xs font-bold uppercase tracking-widest">{{ __('No study programs in this faculty yet') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    @if($faculties->isEmpty())
    <div class="card-saas p-20 text-center flex flex-col items-center justify-center">
        <div class="w-20 h-20 bg-primary-50 dark:bg-primary-900/50 rounded-3xl flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
        <h3 class="text-xl font-black text-primary-900 dark:text-white mb-2">{{ __('No faculties found') }}</h3>
        <p class="text-primary-500 font-medium max-w-xs mx-auto mb-8">{{ __('Please add at least one faculty before creating study programs.') }}</p>
        <a href="{{ route('admin.faculty.index') }}" class="btn-primary-saas px-8 py-3 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">
            {{ __('Go to Faculty Management') }}
        </a>
    </div>
    @endif

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('createModal').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden animate-fade-in flex flex-col max-h-[90vh]">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>

                <h3 class="text-xl font-black text-primary-900 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    {{ __('Add New Program') }}
                </h3>

                <form action="{{ route('admin.study-program.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Faculty') }}</label>
                        <select name="faculty_id" class="input-saas w-full" required>
                            <option value="">{{ __('Select Faculty') }}</option>
                            @foreach($faculties as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Program Name') }}</label>
                        <input type="text" name="name" class="input-saas w-full" placeholder="{{ __('Enter study program name') }}" required>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-primary-50 dark:border-primary-800">
                        <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-6 py-2.5 text-sm font-bold text-primary-400 hover:text-primary-600 transition">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-primary/20">{{ __('Save Program') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editModal').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden animate-fade-in flex flex-col">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>

                <h3 class="text-xl font-black text-primary-900 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    {{ __('Edit Program') }}
                </h3>

                <form id="editForm" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Faculty') }}</label>
                        <select name="faculty_id" id="editFaculty" class="input-saas w-full" required>
                            @foreach($faculties as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Program Name') }}</label>
                        <input type="text" name="name" id="editName" class="input-saas w-full" required>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-primary-50 dark:border-primary-800">
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="btn-ghost-saas px-4 py-2 rounded-lg text-sm font-medium dark:text-white">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-primary/20">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editStudyProgram(id, name, facultyId) {
            document.getElementById('editForm').action = `/admin/study-program/${id}`;
            document.getElementById('editName').value = name;
            document.getElementById('editFaculty').value = facultyId;
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>