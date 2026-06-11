<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Semester Calendar') }}</h1>
            <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
                <span class="w-8 h-px bg-primary-200"></span>
                {{ __('Manage weekly academic activities and important semester events.') }}
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <form action="{{ route('admin.semester-calendar.index') }}" method="GET" id="filterForm" class="flex items-center">
                <select name="academic_year_id" onchange="this.form.submit()" class="input-saas text-sm py-2.5 px-4 rounded-xl min-w-[240px]">
                    @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->year }} — {{ ucfirst(__($year->semester)) }}
                    </option>
                    @endforeach
                </select>
            </form>
            <button onclick="openModal('createModal')"
                class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-primary-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Add Event') }}
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-soft dark:bg-emerald-950/20 dark:border-emerald-900/50 dark:text-emerald-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start w-32">{{ __('Week') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start w-40">{{ __('Date') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Event Details') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-32">{{ __('Type') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-end w-32">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($calendars as $calendar)
                    <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
                        <td class="py-5 px-8 text-start">
                            <span class="inline-flex px-3 py-1 text-xs font-black bg-primary-50 dark:bg-primary-900 text-primary-primary rounded-lg border border-primary-100 dark:border-primary-800 tracking-wider">
                                {{ $calendar->week_number ? __('Week') . ' ' . $calendar->week_number : '—' }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-xl bg-primary-50 dark:bg-primary-900 text-primary-400 group-hover:text-primary-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-black text-primary-900 dark:text-white font-mono uppercase tracking-widest">
                                    {{ $calendar->date ? $calendar->date->format('d M Y') : '—' }}
                                </span>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <div class="min-w-0">
                                <p class="text-sm font-black text-primary-900 dark:text-white leading-tight">{{ $calendar->title }}</p>
                                @if($calendar->description)
                                <p class="text-[10px] text-primary-400 font-bold mt-1 line-clamp-1">{{ $calendar->description }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-5 px-6 text-center">
                            @php
                            $typeClasses = [
                            'academic' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 border-blue-100 dark:border-blue-900/50',
                            'holiday' => 'bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400 border-red-100 dark:border-red-900/50',
                            'exam' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/30 dark:text-purple-400 border-purple-100 dark:border-purple-900/50',
                            'other' => 'bg-primary-50 text-primary-600 dark:bg-primary-950/30 dark:text-primary-400 border-primary-100 dark:border-primary-900/50',
                            ];
                            $typeClass = $typeClasses[$calendar->type] ?? $typeClasses['other'];
                            @endphp
                            <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $typeClass }}">
                                {{ __($calendar->type) }}
                            </span>
                        </td>
                        <td class="py-5 px-8 text-end">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal({{ json_encode($calendar) }})"
                                    class="p-2 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-lg transition" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('admin.semester-calendar.destroy', $calendar) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                    @csrf @method('DELETE')
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
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center mb-4 text-primary-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-primary-400 font-bold uppercase tracking-widest text-[10px]">{{ __('No calendar entries found') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('createModal')"></div>
            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden animate-fade-in flex flex-col">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>

                <h3 class="text-xl font-black text-primary-900 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    {{ __('Add Calendar Event') }}
                </h3>

                <form action="{{ route('admin.semester-calendar.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="academic_year_id" value="{{ $academicYearId }}">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Week Number') }}</label>
                            <input type="number" name="week_number" class="input-saas w-full" placeholder="e.g. 1">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Date') }}</label>
                            <input type="date" name="date" class="input-saas w-full">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Title') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="input-saas w-full" placeholder="{{ __('Event title') }}">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Description') }}</label>
                        <textarea name="description" rows="3" class="input-saas w-full" placeholder="{{ __('Optional description') }}"></textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Type') }} <span class="text-red-500">*</span></label>
                        <select name="type" required class="input-saas w-full">
                            <option value="academic">{{ __('Academic') }}</option>
                            <option value="holiday">{{ __('Holiday') }}</option>
                            <option value="exam">{{ __('Exam') }}</option>
                            <option value="other">{{ __('Other') }}</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-primary-50 dark:border-primary-800">
                        <button type="button" onclick="closeModal('createModal')" class="px-6 py-2.5 text-sm font-bold text-primary-400 hover:text-primary-600 transition">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-primary/20">{{ __('Save Event') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editModal')"></div>
            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden animate-fade-in flex flex-col">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>

                <h3 class="text-xl font-black text-primary-900 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    {{ __('Edit Calendar Event') }}
                </h3>

                <form id="editForm" method="POST" class="space-y-6">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Week Number') }}</label>
                            <input type="number" name="week_number" id="edit_week_number" class="input-saas w-full">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Date') }}</label>
                            <input type="date" name="date" id="edit_date" class="input-saas w-full">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Title') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="edit_title" required class="input-saas w-full">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Description') }}</label>
                        <textarea name="description" id="edit_description" rows="3" class="input-saas w-full"></textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Type') }} <span class="text-red-500">*</span></label>
                        <select name="type" id="edit_type" required class="input-saas w-full">
                            <option value="academic">{{ __('Academic') }}</option>
                            <option value="holiday">{{ __('Holiday') }}</option>
                            <option value="exam">{{ __('Exam') }}</option>
                            <option value="other">{{ __('Other') }}</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-primary-50 dark:border-primary-800">
                        <button type="button" onclick="closeModal('editModal')" class="px-6 py-2.5 text-sm font-bold text-primary-400 hover:text-primary-600 transition">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-primary/20">{{ __('Update Event') }}</button>
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

        function openEditModal(calendar) {
            const form = document.getElementById('editForm');
            form.action = `{{ url('admin/semester-calendar') }}/${calendar.id}`;

            document.getElementById('edit_week_number').value = calendar.week_number;
            document.getElementById('edit_date').value = calendar.date ? calendar.date.substring(0, 10) : '';
            document.getElementById('edit_title').value = calendar.title;
            document.getElementById('edit_description').value = calendar.description;
            document.getElementById('edit_type').value = calendar.type;

            openModal('editModal');
        }
    </script>
</x-app-layout>