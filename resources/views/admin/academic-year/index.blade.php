<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Academic Year') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('Manage and configure academic cycles, lecture periods, and study plan windows.') }}
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

    <div class="mb-8 flex justify-end">
        @if(auth()->user()->isSuperAdmin())
        <button onclick="openModal('createModal')"
            class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-primary-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ __('Add Academic Year') }}
        </button>
        @endif
    </div>

    <div class="card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Year') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Semester') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Lecture Period') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Study Plan Period') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Status') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($academicYears as $ta)
                    <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
                        <td class="py-5 px-6">
                            <span class="text-sm font-black text-primary-900 dark:text-white">{{ $ta->year }}</span>
                        </td>
                        <td class="py-5 px-6">
                            <span class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider">{{ __($ta->semester) }}</span>
                        </td>
                        <td class="py-5 px-6">
                            @if($ta->start_date && $ta->completion_date)
                            <div class="flex items-center gap-2 text-xs font-medium text-primary-500">
                                <svg class="w-3.5 h-3.5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $ta->start_date->format('d M Y') }} - {{ $ta->completion_date->format('d M Y') }}
                            </div>
                            @else
                            <span class="inline-flex px-2 py-1 text-[10px] font-black bg-amber-50 text-amber-600 rounded-lg uppercase tracking-widest border border-amber-100">{{ __('Not set') }}</span>
                            @endif
                        </td>
                        <td class="py-5 px-6">
                            @if($ta->study_plan_start_date && $ta->study_plan_end_date)
                            <div class="flex items-center gap-2 text-xs font-medium text-primary-500">
                                <svg class="w-3.5 h-3.5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                {{ $ta->study_plan_start_date->format('d M Y') }} - {{ $ta->study_plan_end_date->format('d M Y') }}
                            </div>
                            @else
                            <span class="inline-flex px-2 py-1 text-[10px] font-black bg-amber-50 text-amber-600 rounded-lg uppercase tracking-widest border border-amber-100">{{ __('Not set') }}</span>
                            @endif
                        </td>
                        <td class="py-5 px-6">
                            @if($ta->is_active)
                            <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/50">
                                {{ __('Active') }}
                            </span>
                            @else
                            <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border bg-primary-50 text-primary-400 border-primary-100 dark:bg-primary-900/30 dark:border-primary-800/50">
                                {{ __('Inactive') }}
                            </span>
                            @endif
                        </td>
                        <td class="py-5 px-8 text-end">
                            @if(auth()->user()->isSuperAdmin())
                            <div class="flex items-center justify-end gap-2">
                                @if(!$ta->is_active)
                                <form action="{{ route('admin.academic-year.activate', $ta) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="{{ __('Activate') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                                <button onclick="openEditModal({{ json_encode($ta) }})" class="p-2 text-primary-primary hover:bg-primary-primary/10 rounded-lg transition" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                @if(!$ta->is_active)
                                <form action="{{ route('admin.academic-year.destroy', $ta) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="{{ __('Delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                            @else
                            <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest">{{ __('View Only') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-primary-400 text-sm font-medium">{{ __('No academic year data available') }}</p>
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
        @forelse($academicYears as $ta)
        <div class="card-saas p-6 group">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h4 class="font-black text-primary-900 dark:text-white group-hover:text-primary-primary transition-colors">{{ $ta->year }}</h4>
                    <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">{{ __($ta->semester) }}</p>
                </div>
                @if($ta->is_active)
                <span class="inline-flex px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/50">
                    {{ __('Active') }}
                </span>
                @else
                <span class="inline-flex px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border bg-primary-50 text-primary-400 border-primary-100 dark:bg-primary-900/30 dark:border-primary-800/50">
                    {{ __('Inactive') }}
                </span>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-3 mb-6">
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Lecture Period') }}</span>
                    @if($ta->start_date && $ta->completion_date)
                    <span class="text-[10px] font-black text-primary-900 dark:text-white">{{ $ta->start_date->format('d/m/y') }} - {{ $ta->completion_date->format('d/m/y') }}</span>
                    @else
                    <span class="text-[10px] font-black text-primary-300">{{ __('Not set') }}</span>
                    @endif
                </div>
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Study Plan Period') }}</span>
                    @if($ta->study_plan_start_date && $ta->study_plan_end_date)
                    <span class="text-[10px] font-black text-primary-900 dark:text-white">{{ $ta->study_plan_start_date->format('d/m/y') }} - {{ $ta->study_plan_end_date->format('d/m/y') }}</span>
                    @else
                    <span class="text-[10px] font-black text-primary-300">{{ __('Not set') }}</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-primary-50 dark:border-primary-800">
                @if(auth()->user()->isSuperAdmin())
                @if(!$ta->is_active)
                <form action="{{ route('admin.academic-year.activate', $ta) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-3 text-xs font-black text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl hover:bg-emerald-600 hover:text-white transition-all">
                        {{ __('Activate') }}
                    </button>
                </form>
                @endif
                <button onclick="openEditModal({{ json_encode($ta) }})" class="p-3 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-xl transition-all border border-primary-100 dark:border-primary-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
                @if(!$ta->is_active)
                <form action="{{ route('admin.academic-year.destroy', $ta) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')" class="flex-none">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-3 text-red-600 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </form>
                @endif
                @else
                <span class="w-full py-3 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('View Only') }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="card-saas p-10 text-center">
            <p class="text-primary-400 font-bold">{{ __('No data available') }}</p>
        </div>
        @endforelse
    </div>

    @if($academicYears->hasPages())
    <div class="md:hidden card-saas px-6 py-4 mb-6">
        {{ $academicYears->links() }}
    </div>
    @endif
    </div> <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('createModal')"></div>
            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>

                <h3 class="text-xl font-black text-primary-900 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    {{ __('Add Academic Year') }}
                </h3>

                <form action="{{ route('admin.academic-year.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Year') }}</label>
                            <input type="text" name="year" placeholder="e.g., 2024/2025" required class="input-saas w-full">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Semester') }}</label>
                            <select name="semester" required class="input-saas w-full">
                                <option value="odd">{{ __('Odd') }}</option>
                                <option value="even">{{ __('Even') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-primary-50 dark:border-primary-800">
                        <p class="text-[10px] font-black text-primary-primary uppercase tracking-widest mb-4">{{ __('Lecture Period') }}</p>
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Start Date') }}</label>
                                <input type="date" name="start_date" class="input-saas w-full">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('End Date') }}</label>
                                <input type="date" name="completion_date" class="input-saas w-full">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-primary-50 dark:border-primary-800">
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-4">{{ __('Study Plan Period') }}</p>
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Start Date') }}</label>
                                <input type="date" name="study_plan_start_date" class="input-saas w-full">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('End Date') }}</label>
                                <input type="date" name="study_plan_end_date" class="input-saas w-full">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeModal('createModal')" class="px-6 py-2.5 text-sm font-bold text-primary-400 hover:text-primary-600 transition">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-primary/20">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editModal')"></div>
            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>

                <h3 class="text-xl font-black text-primary-900 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    {{ __('Edit Academic Year') }}
                </h3>

                <form id="editForm" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Year') }}</label>
                            <input type="text" name="year" id="editYear" required class="input-saas w-full">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Semester') }}</label>
                            <select name="semester" id="editSemester" required class="input-saas w-full">
                                <option value="odd">{{ __('Odd') }}</option>
                                <option value="even">{{ __('Even') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-primary-50 dark:border-primary-800">
                        <p class="text-[10px] font-black text-primary-primary uppercase tracking-widest mb-4">{{ __('Lecture Period') }}</p>
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Start Date') }}</label>
                                <input type="date" name="start_date" id="editStartDate" class="input-saas w-full">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('End Date') }}</label>
                                <input type="date" name="completion_date" id="editCompletionDate" class="input-saas w-full">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-primary-50 dark:border-primary-800">
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-4">{{ __('Study Plan Period') }}</p>
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Start Date') }}</label>
                                <input type="date" name="study_plan_start_date" id="editStudyPlanStartDate" class="input-saas w-full">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('End Date') }}</label>
                                <input type="date" name="study_plan_end_date" id="editStudyPlanEndDate" class="input-saas w-full">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeModal('editModal')" class="px-6 py-2.5 text-sm font-bold text-primary-400 hover:text-primary-600 transition">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-primary/20">{{ __('Save Changes') }}</button>
                    </div>
                </form>
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

        function openEditModal(ta) {
            document.getElementById('editForm').action = `/admin/academic-year/${ta.id}`;
            document.getElementById('editYear').value = ta.year;
            document.getElementById('editSemester').value = ta.semester;
            document.getElementById('editStartDate').value = ta.start_date ? ta.start_date.split('T')[0] : '';
            document.getElementById('editCompletionDate').value = ta.completion_date ? ta.completion_date.split('T')[0] : '';
            document.getElementById('editStudyPlanStartDate').value = ta.study_plan_start_date ? ta.study_plan_start_date.split('T')[0] : '';
            document.getElementById('editStudyPlanEndDate').value = ta.study_plan_end_date ? ta.study_plan_end_date.split('T')[0] : '';
            openModal('editModal');
        }
    </script>
</x-app-layout>