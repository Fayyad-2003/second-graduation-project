<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Lecturer Management') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('Manage academic staff and faculty members.') }}
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
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search lecturers...') }}"
                        class="input-saas pl-11 pr-4 py-2.5 text-sm w-full">
                </div>
            </div>

            <select name="study_program" class="input-saas px-4 py-2.5 text-sm w-full sm:w-48">
                <option value="">{{ __('All Programs') }}</option>
                @foreach($studyProgramList as $p)
                <option value="{{ $p->id }}" {{ request('study_program') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex-1 sm:flex-none">
                    {{ __('Filter') }}
                </button>
            </div>

            <div class="w-px h-8 bg-primary-100 hidden lg:block mx-2"></div>

            <div class="flex items-center gap-2 ml-auto">
                <button onclick="openModal('createModal')"
                    class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-primary-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('Add Lecturer') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start w-16">#</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">
                            <a href="{{ route('admin.lecturer.index', array_merge(request()->all(), ['sort' => 'name', 'order' => request('sort') == 'name' && request('order') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex items-center gap-1.5 hover:text-primary-primary transition-colors">
                                {{ __('Lecturer') }}
                                @if(request('sort') == 'name')
                                <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">
                            <a href="{{ route('admin.lecturer.index', array_merge(request()->all(), ['sort' => 'nidn', 'order' => request('sort') == 'nidn' && request('order') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex items-center gap-1.5 hover:text-primary-primary transition-colors">
                                {{ __('NIDN') }}
                                @if(request('sort') == 'nidn')
                                <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Program') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Classes') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Mentoring') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($lecturers as $index => $d)
                    <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
                        <td class="py-5 px-8 text-xs font-bold text-primary-400 text-start">{{ $lecturers->firstItem() + $index }}</td>
                        <td class="py-5 px-6 text-start">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center text-primary-600 dark:text-primary-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($d->user->name ?? '-', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-primary-900 dark:text-white truncate">{{ $d->user->name ?? '-' }}</p>
                                    <p class="text-[10px] text-primary-400 font-bold uppercase tracking-wider text-start">{{ $d->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <span class="text-xs font-black text-primary-700 dark:text-primary-300 font-mono tracking-wider bg-primary-50 dark:bg-primary-900 px-2 py-1 rounded-lg border border-primary-100 dark:border-primary-800">{{ $d->lecturer_number }}</span>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <p class="text-xs font-bold text-primary-600 dark:text-primary-400">{{ $d->studyProgram->name ?? '-' }}</p>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-primary-50 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-lg border border-primary-100 dark:border-primary-800 uppercase tracking-widest">{{ $d->classes_count ?? $d->classes->count() }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg border border-emerald-100 dark:border-emerald-900/50 uppercase tracking-widest">{{ $d->advised_students_count ?? $d->advisedStudents->count() }}</span>
                        </td>
                        <td class="py-5 px-8 text-end">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.lecturer.show', $d) }}" class="p-2 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-lg transition" title="{{ __('Details') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <button onclick="openEditModal({{ json_encode(['id'=>$d->id,'name'=>$d->user->name,'email'=>$d->user->email,'lecturer_number'=>$d->lecturer_number,'study_program_id'=>$d->study_program_id]) }})" class="p-2 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-lg transition" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('admin.lecturer.destroy', $d) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">@csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-primary-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="{{ __('Delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-primary-light/50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-primary-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <p class="text-primary-secondary dark:text-gray-400 text-sm">{{ __('No lecturer data available') }}</p>
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
        @forelse($lecturers as $d)
        <div class="card-saas p-6 group">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center text-primary-600 dark:text-primary-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                        {{ strtoupper(substr($d->user->name ?? '-', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-black text-primary-900 dark:text-white truncate group-hover:text-primary-primary transition-colors">{{ $d->user->name ?? '-' }}</h4>
                        <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">{{ $d->lecturer_number }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50 col-span-2">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Program') }}</span>
                    <span class="text-xs font-black text-primary-900 dark:text-white line-clamp-1">{{ $d->studyProgram->name ?? '-' }}</span>
                </div>
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Teaching Classes') }}</span>
                    <span class="text-xs font-black text-primary-900 dark:text-white">{{ $d->classes_count ?? $d->classes->count() }}</span>
                </div>
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Mentoring') }}</span>
                    <span class="text-xs font-black text-primary-900 dark:text-white">{{ $d->advised_students_count ?? $d->advisedStudents->count() }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-primary-50 dark:border-primary-800">
                <a href="{{ route('admin.lecturer.show', $d) }}" class="flex-1 py-3 text-xs font-black text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-800 rounded-xl hover:bg-primary-primary hover:text-white transition-all text-center">
                    {{ __('Lecturer Details') }}
                </a>
                <button onclick="openEditModal({{ json_encode(['id'=>$d->id,'name'=>$d->user->name,'email'=>$d->user->email,'lecturer_number'=>$d->lecturer_number,'study_program_id'=>$d->study_program_id]) }})"
                    class="p-3 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-xl transition-all border border-primary-100 dark:border-primary-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
            </div>
        </div>
        @empty
        <div class="card-saas p-10 text-center">
            <p class="text-primary-400 font-bold">{{ __('No lecturer data available') }}</p>
        </div>
        @endforelse
    </div>

    @if($lecturers->hasPages())
    <div class="md:hidden card-saas px-6 py-4 mb-6">
        {{ $lecturers->links() }}
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
                            {{ __('Add Lecturer') }}
                        </h3>
                        <button onclick="closeModal('createModal')" class="text-primary-400 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.lecturer.store') }}" method="POST">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Full Name') }}</label>
                                <input type="text" name="name" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('Enter lecturer name') }}" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Email Address') }}</label>
                                    <input type="email" name="email" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="lecturer@siakad.com" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Password') }}</label>
                                    <input type="password" name="password" minlength="8" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('NIDN (ID Number)') }}</label>
                                <input type="text" name="lecturer_number" class="input-saas w-full px-4 py-3 text-sm rounded-xl font-mono" placeholder="1010101010" required>
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
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="closeModal('createModal')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">{{ __('Save Lecturer') }}</button>
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
                            {{ __('Edit Lecturer') }}
                        </h3>
                        <button onclick="closeModal('editModal')" class="text-primary-400 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="editForm" method="POST">
                        @csrf @method('PUT')
                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Full Name') }}</label>
                                <input type="text" name="name" id="editName" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Email Address') }}</label>
                                    <input type="email" name="email" id="editEmail" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('New Password (Optional)') }}</label>
                                    <input type="password" name="password" minlength="8" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('NIDN (ID Number)') }}</label>
                                <input type="text" name="lecturer_number" id="editLecturerNumber" class="input-saas w-full px-4 py-3 text-sm rounded-xl font-mono" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Study Program') }}</label>
                                <select name="study_program_id" id="editStudyProgramId" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                    @foreach($studyProgramList as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="closeModal('editModal')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">{{ __('Update Lecturer') }}</button>
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

        function openEditModal(d) {
            document.getElementById('editForm').action = `/admin/lecturer/${d.id}`;
            document.getElementById('editName').value = d.name;
            document.getElementById('editEmail').value = d.email;
            document.getElementById('editLecturerNumber').value = d.lecturer_number;
            document.getElementById('editStudyProgramId').value = d.study_program_id;
            openModal('editModal');
        }
    </script>
</x-app-layout>