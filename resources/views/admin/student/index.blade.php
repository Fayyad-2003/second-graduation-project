<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Student Management') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('View and manage all registered students across various programs.') }}
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
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[300px]">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-primary-400 group-focus-within:text-primary-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search students...') }}"
                        class="input-saas pl-11 pr-4 py-2.5 text-sm w-full">
                </div>
            </div>

            <select name="faculty_id" id="filterFaculty" class="input-saas px-4 py-2.5 text-sm w-full sm:w-48">
                <option value="">{{ __('All Faculties') }}</option>
                @foreach($facultyList as $f)
                <option value="{{ $f->id }}" {{ request('faculty_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                @endforeach
            </select>

            <select name="study_program_id" id="filterStudyProgram" class="input-saas px-4 py-2.5 text-sm w-full sm:w-48">
                <option value="">{{ __('All Programs') }}</option>
                @foreach($studyProgramList as $p)
                <option value="{{ $p->id }}" data-faculty-id="{{ $p->faculty_id }}" {{ request('study_program_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex-1 sm:flex-none">
                    {{ __('Filter') }}
                </button>
                @if(request()->anyFilled(['search', 'faculty_id', 'study_program_id', 'batch']))
                <a href="{{ route('admin.student.index') }}" class="btn-ghost-saas px-4 py-2.5 rounded-xl text-sm font-bold text-primary-400 hover:text-primary-600">
                    {{ __('Reset') }}
                </a>
                @endif
            </div>

            <div class="w-px h-8 bg-primary-100 hidden lg:block mx-2"></div>

            <div class="flex items-center gap-2 ml-auto">
                <a href="{{ route('admin.student.export', request()->all()) }}" target="_blank"
                    class="btn-ghost-saas px-5 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 border border-primary-100 dark:border-primary-800">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Export') }}
                </a>
                <button onclick="openModal('createModal')"
                    class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-primary-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('Add Student') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Script for Dynamic Dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const facultySelect = document.getElementById('filterFaculty');
            const study_programSelect = document.getElementById('filterStudyProgram');
            const study_programOptions = Array.from(study_programSelect.options);

            function updateStudyProgramOptions() {
                const selectedFacultyId = facultySelect.value;
                const currentStudyProgramValue = study_programSelect.value;
                let isCurrentStudyProgramValid = false;

                study_programSelect.innerHTML = '';
                study_programSelect.appendChild(study_programOptions[0]);

                study_programOptions.slice(1).forEach(option => {
                    if (!selectedFacultyId || option.dataset.facultyId === selectedFacultyId) {
                        study_programSelect.appendChild(option);
                        if (option.value === currentStudyProgramValue) {
                            isCurrentStudyProgramValid = true;
                        }
                    }
                });

                if (currentStudyProgramValue && !isCurrentStudyProgramValid) {
                    study_programSelect.value = '';
                } else {
                    study_programSelect.value = currentStudyProgramValue;
                }
            }

            facultySelect.addEventListener('change', updateStudyProgramOptions);
            updateStudyProgramOptions();
        });
    </script>

    <!-- Table Card -->
    <div class="card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest w-16 text-start">#</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">
                            <a href="{{ route('admin.student.index', array_merge(request()->all(), ['sort' => 'name', 'order' => request('sort') == 'name' && request('order') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex items-center gap-1.5 hover:text-primary-primary transition-colors">
                                {{ __('Student') }}
                                @if(request('sort') == 'name')
                                <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">
                            <a href="{{ route('admin.student.index', array_merge(request()->all(), ['sort' => 'student_number', 'order' => request('sort') == 'student_number' && request('order') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex items-center gap-1.5 hover:text-primary-primary transition-colors">
                                {{ __('ID Number') }}
                                @if(request('sort') == 'student_number')
                                <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Program') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Batch') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('GPA') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Status') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($students as $index => $m)
                    <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
                        <td class="py-5 px-8 text-xs font-bold text-primary-400 text-start">{{ $students->firstItem() + $index }}</td>
                        <td class="py-5 px-6 text-start">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center text-primary-600 dark:text-primary-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($m->user->name ?? '-', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-primary-900 dark:text-white truncate">{{ $m->user->name ?? '-' }}</p>
                                    <p class="text-[10px] text-primary-400 font-bold uppercase tracking-wider text-start">{{ $m->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <span class="text-xs font-black text-primary-700 dark:text-primary-300 font-mono tracking-wider">{{ $m->student_number }}</span>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <p class="text-xs font-bold text-primary-600 dark:text-primary-400">{{ $m->studyProgram->name ?? '-' }}</p>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-primary-50 dark:bg-primary-900 text-primary-500 dark:text-primary-400 rounded-lg border border-primary-100 dark:border-primary-800 uppercase">{{ $m->batch }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full {{ $m->ipk >= 3.0 ? 'bg-emerald-500' : ($m->ipk >= 2.0 ? 'bg-amber-500' : 'bg-red-500') }}"></div>
                                <span class="text-sm font-black text-primary-900 dark:text-white">{{ number_format($m->ipk ?? 0, 2) }}</span>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-center">
                            @php
                            $statusClasses = [
                            'active' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/50',
                            'cuti' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 border-amber-100 dark:border-amber-900/50',
                            'default' => 'bg-primary-50 text-primary-600 dark:bg-primary-950/30 dark:text-primary-400 border-primary-100 dark:border-primary-900/50'
                            ];
                            $statusClass = $statusClasses[$m->status] ?? $statusClasses['default'];
                            @endphp
                            <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $statusClass }}">
                                {{ $m->status === 'active' ? __('Active') : ($m->status === 'cuti' ? __('Leave') : ucfirst($m->status)) }}
                            </span>
                        </td>
                        <td class="py-5 px-8 text-end">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.student.show', $m) }}"
                                    class="p-2 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-lg transition"
                                    title="{{ __('Details') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </a>
                                <button
                                    onclick="openEditModal({{ json_encode(['id' => $m->id, 'name' => $m->user->name, 'email' => $m->user->email, 'student_number' => $m->student_number, 'study_program_id' => $m->study_program_id, 'batch' => $m->batch, 'academic_advisor_id' => $m->academic_advisor_id, 'status' => $m->status]) }})"
                                    class="p-2 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-lg transition" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('admin.student.destroy', $m) }}" method="POST" class="inline"
                                    onsubmit="return confirm('{{ __('Are you sure?') }}')">@csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-primary-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="{{ __('Delete') }}"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-12 h-12 bg-primary-light/50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-primary-secondary dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-primary-secondary dark:text-gray-400 text-sm">{{ __('No student data available') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card List -->
    <div class="md:hidden space-y-4 mb-6">
        @forelse($students as $m)
        <div class="card-saas p-6 group">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center text-primary-600 dark:text-primary-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                        {{ strtoupper(substr($m->user->name ?? '-', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-black text-primary-900 dark:text-white truncate group-hover:text-primary-primary transition-colors">{{ $m->user->name ?? '-' }}</h4>
                        <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">{{ $m->student_number }}</p>
                    </div>
                </div>
                @php
                $statusClasses = [
                'active' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/50',
                'cuti' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 border-amber-100 dark:border-amber-900/50',
                'default' => 'bg-primary-50 text-primary-600 dark:bg-primary-950/30 dark:text-primary-400 border-primary-100 dark:border-primary-900/50'
                ];
                $statusClass = $statusClasses[$m->status] ?? $statusClasses['default'];
                @endphp
                <span class="inline-flex px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border {{ $statusClass }}">
                    {{ $m->status === 'active' ? __('Active') : ($m->status === 'cuti' ? __('Leave') : ucfirst($m->status)) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Program') }}</span>
                    <span class="text-xs font-black text-primary-900 dark:text-white line-clamp-1">{{ $m->studyProgram->name ?? '-' }}</span>
                </div>
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Batch') }}</span>
                    <span class="text-xs font-black text-primary-900 dark:text-white">{{ $m->batch }}</span>
                </div>
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50 col-span-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-primary-400 font-black uppercase tracking-widest">{{ __('GPA Status') }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full {{ $m->ipk >= 3.0 ? 'bg-emerald-500' : ($m->ipk >= 2.0 ? 'bg-amber-500' : 'bg-red-500') }}"></div>
                            <span class="text-sm font-black text-primary-900 dark:text-white">{{ number_format($m->ipk ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-primary-50 dark:border-primary-800">
                <a href="{{ route('admin.student.show', $m) }}" class="flex-1 py-3 text-xs font-black text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-800 rounded-xl hover:bg-primary-primary hover:text-white transition-all text-center">
                    {{ __('View Details') }}
                </a>
                <button onclick="openEditModal({{ json_encode(['id' => $m->id, 'name' => $m->user->name, 'email' => $m->user->email, 'student_number' => $m->student_number, 'study_program_id' => $m->study_program_id, 'batch' => $m->batch, 'academic_advisor_id' => $m->academic_advisor_id, 'status' => $m->status]) }})"
                    class="p-3 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-xl transition-all border border-primary-100 dark:border-primary-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
            </div>
        </div>
        @empty
        <div class="card-saas p-10 text-center">
            <p class="text-primary-400 font-bold">{{ __('No student data available') }}</p>
        </div>
        @endforelse
    </div>

    @if($students->hasPages())
    <div class="md:hidden card-saas px-6 py-4 mb-6">
        {{ $students->links() }}
    </div>
    @endif
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('createModal')"></div>

            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden transform transition-all animate-fade-in">
                <!-- Decorative background -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 bg-emerald-500/5 rounded-full"></div>

                <div class="relative">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-primary-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            {{ __('Add Student') }}
                        </h3>
                        <button onclick="closeModal('createModal')" class="text-primary-400 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.student.store') }}" method="POST">
                        @csrf
                        <div class="space-y-5 max-h-[60vh] overflow-y-auto pr-2 scrollbar-hide">
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Full Name') }}</label>
                                <input type="text" name="name" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('Enter student name') }}" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Email') }}</label>
                                    <input type="email" name="email" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="student@system.com" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Password') }}</label>
                                    <input type="password" name="password" minlength="8" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Student ID Number') }}</label>
                                <input type="text" name="student_number" class="input-saas w-full px-4 py-3 text-sm rounded-xl font-mono" placeholder="20210001" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Study Program') }}</label>
                                <select name="study_program_id" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                    <option value="">{{ __('-- Select Program --') }}</option>
                                    @foreach($studyProgramList as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Batch Year') }}</label>
                                    <input type="number" name="batch" value="{{ date('Y') }}" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Academic Advisor') }}</label>
                                    <select name="academic_advisor_id" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                        <option value="">{{ __('-- Select Advisor --') }}</option>
                                        @foreach($lecturerList as $d)
                                        <option value="{{ $d->id }}">{{ $d->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="closeModal('createModal')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">{{ __('Save Student') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editModal')"></div>

            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden transform transition-all animate-fade-in">
                <!-- Decorative background -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 bg-amber-500/5 rounded-full"></div>

                <div class="relative">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-primary-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            {{ __('Edit Student') }}
                        </h3>
                        <button onclick="closeModal('editModal')" class="text-primary-400 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="editForm" method="POST">
                        @csrf @method('PUT')
                        <div class="space-y-5 max-h-[60vh] overflow-y-auto pr-2 scrollbar-hide">
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Full Name') }}</label>
                                <input type="text" name="name" id="editName" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Email') }}</label>
                                    <input type="email" name="email" id="editEmail" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('New Password (Optional)') }}</label>
                                    <input type="password" name="password" minlength="8" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Student ID Number') }}</label>
                                <input type="text" name="student_number" id="editStudentNumber" class="input-saas w-full px-4 py-3 text-sm rounded-xl font-mono" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Study Program') }}</label>
                                <select name="study_program_id" id="editStudyProgramId" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                    @foreach($studyProgramList as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Batch Year') }}</label>
                                    <input type="number" name="batch" id="editBatch" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Academic Advisor') }}</label>
                                    <select name="academic_advisor_id" id="editAcademicAdvisorId" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                        <option value="">{{ __('-- Select Advisor --') }}</option>
                                        @foreach($lecturerList as $d)
                                        <option value="{{ $d->id }}">{{ $d->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Academic Status') }}</label>
                                <select name="status" id="editStatus" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                    <option value="active">{{ __('Active') }}</option>
                                    <option value="cuti">{{ __('Leave') }}</option>
                                    <option value="passed">{{ __('Passed/Graduated') }}</option>
                                    <option value="do">{{ __('DO (Drop Out)') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="closeModal('editModal')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">{{ __('Update Student') }}</button>
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

        function openEditModal(m) {
            document.getElementById('editForm').action = `/admin/student/${m.id}`;
            document.getElementById('editName').value = m.name;
            document.getElementById('editEmail').value = m.email;
            document.getElementById('editStudentNumber').value = m.student_number;
            document.getElementById('editStudyProgramId').value = m.study_program_id;
            document.getElementById('editBatch').value = m.batch;
            document.getElementById('editAcademicAdvisorId').value = m.academic_advisor_id || '';
            document.getElementById('editStatus').value = m.status || 'active';
            openModal('editModal');
        }
    </script>
</x-app-layout>