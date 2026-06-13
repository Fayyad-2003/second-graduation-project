<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Course Management') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('Define curriculum and manage individual course subjects.') }}
        </p>
    </div>

    <!-- Toolbar: Filter, Search, Action -->
    <div class="mb-8 card-saas p-6">
        <form action="{{ route('admin.course.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
            @if(request('order')) <input type="hidden" name="order" value="{{ request('order') }}"> @endif

            <div class="flex-1 min-w-[300px]">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-primary-400 group-focus-within:text-primary-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search course name or code...') }}"
                        class="input-saas pl-11 pr-4 py-2.5 text-sm w-full">
                </div>
            </div>

            <select name="category" onchange="this.form.submit()" class="input-saas px-4 py-2.5 text-sm w-full sm:w-48">
                <option value="">{{ __('All Categories') }}</option>
                @php
                $categories = [
                'TI' => __('Informatics'),
                'SI' => __('Info Systems'),
                'TE' => __('Electrical'),
                'MN' => __('Management'),
                'AK' => __('Accounting'),
                'MT' => __('Mathematics'),
                'MK' => __('General'),
                'UN' => __('University')
                ];
                @endphp
                @foreach($categories as $code => $name)
                <option value="{{ $code }}" {{ request('category') == $code ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>

            <select name="classification_id" onchange="this.form.submit()" class="input-saas px-4 py-2.5 text-sm w-full sm:w-48">
                <option value="">{{ __('All Classifications') }}</option>
                @foreach($classifications as $c)
                <option value="{{ $c->id }}" {{ request('classification_id') == $c->id ? 'selected' : '' }}>{{ __($c->name) }}</option>
                @endforeach
            </select>

            <div class="w-px h-8 bg-primary-100 hidden lg:block mx-2"></div>

            <div class="flex items-center gap-2 ml-auto">
                <a href="{{ route('admin.course.export', request()->all()) }}" target="_blank"
                    class="btn-ghost-saas px-5 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 border border-primary-100 dark:border-primary-800">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Export') }}
                </a>
                <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                    class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-primary-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('Add Course') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest w-16 text-start">#</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest w-32 text-start">
                            <a href="{{ route('admin.course.index', array_merge(request()->all(), ['sort' => 'course_code', 'order' => request('sort') == 'course_code' && request('order') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex items-center gap-1.5 hover:text-primary-primary transition-colors">
                                {{ __('Code') }}
                                @if(request('sort') == 'course_code')
                                <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">
                            <a href="{{ route('admin.course.index', array_merge(request()->all(), ['sort' => 'course_name', 'order' => request('sort') == 'course_name' && request('order') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="flex items-center gap-1.5 hover:text-primary-primary transition-colors">
                                {{ __('Course Name') }}
                                @if(request('sort') == 'course_name')
                                <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-20">{{ __('Credits') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-28">{{ __('Sem') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-32">{{ __('Classification') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Prerequisites') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($courses as $idx => $mk)
                    <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
                        <td class="py-5 px-8 text-xs font-bold text-primary-400 text-start">{{ $courses->firstItem() + $idx }}</td>
                        <td class="py-5 px-6 text-start">
                            <span class="text-xs font-black text-primary-700 dark:text-primary-300 font-mono tracking-wider bg-primary-50 dark:bg-primary-900 px-2 py-1 rounded-lg border border-primary-100 dark:border-primary-800">{{ $mk->course_code }}</span>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <div class="min-w-0">
                                <p class="text-sm font-black text-primary-900 dark:text-white truncate">{{ $mk->course_name }}</p>
                                @php $prefix = substr($mk->course_code, 0, 2); @endphp
                                <p class="text-[10px] text-primary-400 font-bold uppercase tracking-wider text-start">{{ $categories[$prefix] ?? '' }}</p>
                                @if($mk->description)
                                <p class="text-[10px] text-primary-500 dark:text-primary-400 mt-0.5 line-clamp-1 italic">{{ $mk->description }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-primary-50 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-lg border border-primary-100 dark:border-primary-800 uppercase" title="{{ $mk->theory_credits }} {{ __('Theory') }} + {{ $mk->practical_hours }} {{ __('Practical Hours') }}">
                                {{ $mk->credits }}
                                @if($mk->has_practical)
                                    <span class="text-primary-400 dark:text-primary-500 ml-1">({{ $mk->theory_credits }}+{{ $mk->practical_hours }})</span>
                                @endif
                            </span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg border border-emerald-100 dark:border-emerald-900/50 uppercase">{{ __('Sem') }} {{ $mk->semester }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            @if($mk->classification)
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded-lg border border-amber-100 dark:border-amber-900/50 uppercase">
                                {{ __($mk->classification->name) }}
                            </span>
                            @else
                            <span class="text-[10px] text-primary-300 font-black uppercase tracking-widest">{{ __('Unclassified') }}</span>
                            @endif
                        </td>
                        <td class="py-5 px-6 text-start">
                            @if($mk->prerequisites->count() > 0)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($mk->prerequisites as $prereq)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-black bg-sky-50 dark:bg-sky-950/30 text-sky-600 dark:text-sky-400 rounded-lg border border-sky-100 dark:border-sky-900/50">
                                    {{ $prereq->course_name }}
                                </span>
                                @endforeach
                            </div>
                            @else
                            <span class="text-[10px] text-primary-300 font-black uppercase tracking-widest">{{ __('None') }}</span>
                            @endif
                        </td>
                        <td class="py-5 px-8 text-end">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="editCourse({{ $mk->id }}, '{{ $mk->course_code }}', '{{ addslashes($mk->course_name) }}', {{ $mk->theory_credits }}, {{ $mk->semester }}, {{ $mk->study_program_id ?? 'null' }}, {{ $mk->studyProgram?->faculty_id ?? 'null' }}, {{ json_encode($mk->prerequisites->pluck('id')) }}, {{ $mk->subject_classification_id ?? 'null' }}, {{ json_encode($mk->description) }}, {{ $mk->has_practical ? 'true' : 'false' }}, {{ $mk->practical_hours }})"
                                    class="p-2 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.course.destroy', $mk) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this course?') }}')">
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
                        <td colspan="8" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <p class="text-primary-400 text-sm font-bold">{{ __('No courses found matching your criteria.') }}</p>
                                <a href="{{ route('admin.course.index') }}" class="mt-4 text-xs font-black text-primary-600 uppercase tracking-widest hover:underline">{{ __('Reset Filters') }}</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courses->hasPages())
        <div class="px-8 py-5 border-t border-primary-100/50 dark:border-primary-800/50 bg-primary-50/30 dark:bg-primary-900/20">
            {{ $courses->links() }}
        </div>
        @endif
    </div>

    <!-- Mobile Card List -->
    <div class="md:hidden space-y-4 mb-6">
        @forelse($courses as $mk)
        <div class="card-saas p-6 group">
            <div class="flex items-start justify-between mb-4">
                <div class="min-w-0">
                    <span class="inline-flex px-2.5 py-1 text-[10px] font-black bg-primary-50 dark:bg-primary-900 text-primary-primary rounded-lg border border-primary-100 dark:border-primary-800 font-mono mb-2 uppercase tracking-widest">{{ $mk->course_code }}</span>
                    <h4 class="font-black text-primary-900 dark:text-white truncate group-hover:text-primary-primary transition-colors">{{ $mk->course_name }}</h4>
                    @php $prefix = substr($mk->course_code, 0, 2); @endphp
                    <p class="text-[10px] text-primary-400 font-bold uppercase tracking-wider mt-1">{{ $categories[$prefix] ?? '' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Credits') }}</span>
                    <span class="font-black text-primary-900 dark:text-white">
                        {{ $mk->credits }}
                        @if($mk->has_practical)
                            <span class="text-xs text-primary-500 font-normal">({{ $mk->theory_credits }} T + {{ $mk->practical_hours }} P)</span>
                        @endif
                    </span>
                </div>
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Semester') }}</span>
                    <span class="font-black text-primary-900 dark:text-white">{{ $mk->semester }}</span>
                </div>
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50 col-span-2">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Classification') }}</span>
                    <span class="font-black text-primary-900 dark:text-white">{{ __($mk->classification?->name ?? 'Unclassified') }}</span>
                </div>
                @if($mk->prerequisites->count() > 0)
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50 col-span-2">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Prerequisites') }}</span>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($mk->prerequisites as $prereq)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-black bg-sky-50 dark:bg-sky-950/30 text-sky-600 dark:text-sky-400 rounded-lg border border-sky-100 dark:border-sky-900/50">
                            {{ $prereq->course_name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if($mk->description)
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50 col-span-2">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Description') }}</span>
                    <p class="text-xs text-primary-700 dark:text-primary-300 leading-relaxed">{{ $mk->description }}</p>
                </div>
                @endif
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-primary-50 dark:border-primary-800">
                <button onclick="editCourse({{ $mk->id }}, '{{ $mk->course_code }}', '{{ addslashes($mk->course_name) }}', {{ $mk->theory_credits }}, {{ $mk->semester }}, {{ $mk->study_program_id ?? 'null' }}, {{ $mk->studyProgram?->faculty_id ?? 'null' }}, {{ json_encode($mk->prerequisites->pluck('id')) }}, {{ $mk->subject_classification_id ?? 'null' }}, {{ json_encode($mk->description) }}, {{ $mk->has_practical ? 'true' : 'false' }}, {{ $mk->practical_hours }})"
                    class="flex-1 py-3 text-xs font-black text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-800 rounded-xl hover:bg-primary-primary hover:text-white transition-all text-center">
                    {{ __('Edit') }}
                </button>
                <form action="{{ route('admin.course.destroy', $mk) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this course?') }}')" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3 text-xs font-black text-red-600 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="card-saas p-10 text-center">
            <div class="w-16 h-16 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <p class="text-primary-400 font-bold mb-4">{{ __('No courses found matching your criteria.') }}</p>
            <a href="{{ route('admin.course.index') }}" class="text-xs font-black text-primary-primary uppercase tracking-widest hover:underline">{{ __('Reset Filters') }}</a>
        </div>
        @endforelse
    </div>

    @if($courses->hasPages())
    <div class="md:hidden card-saas px-6 py-4 mb-6">
        {{ $courses->links() }}
    </div>
    @endif
    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('createModal').classList.add('hidden')"></div>

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
                            {{ __('Add New Course') }}
                        </h3>
                        <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-primary-400 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.course.store') }}" method="POST">
                        @csrf
                        <div class="space-y-5 max-h-[60vh] overflow-y-auto pr-2 scrollbar-hide">
                            @if($isSuperAdmin)
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Faculties') }}</label>
                                <select id="createFacultySelect" onchange="filterStudyProgramCreate()" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                    <option value="">{{ __('Select Faculty') }}</option>
                                    @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Study Programs') }}</label>
                                <select name="study_program_id" id="createStudyProgramSelect" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                    <option value="">{{ __('Select Study Program') }}</option>
                                    @foreach($studyPrograms as $studyProgram)
                                    <option value="{{ $studyProgram->id }}" data-faculty="{{ $studyProgram->faculty_id }}">{{ $studyProgram->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Code') }}</label>
                                    <input type="text" name="course_code" class="input-saas w-full px-4 py-3 text-sm rounded-xl font-mono uppercase" placeholder="TI101" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Course Name') }}</label>
                                    <input type="text" name="course_name" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('Enter course name') }}" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Lecture/Theory Credits') }}</label>
                                    <input type="number" name="theory_credits" id="createTheoryCredits" min="1" max="6" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="3" required oninput="calculateTotalCreditsCreate()">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Semester') }}</label>
                                    <input type="number" name="semester" min="1" max="8" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="1" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 items-center">
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="has_practical" id="createHasPractical" value="1" class="rounded border-primary-300 text-primary-primary focus:ring-primary-primary h-4 w-4" onchange="togglePracticalInputCreate()">
                                    <label for="createHasPractical" class="ml-2 text-xs font-black text-primary-700 dark:text-primary-300 uppercase tracking-wider">{{ __('Has Practical Part?') }}</label>
                                </div>
                                <div id="createPracticalHoursGroup" class="hidden">
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Practical Hours') }}</label>
                                    <input type="number" name="practical_hours" id="createPracticalHours" min="0" step="2" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="0" value="0" oninput="calculateTotalCreditsCreate()">
                                    <p class="text-[9px] text-primary-400 mt-1 ml-1">{{ __('Must be even. 2 hours = 1 credit.') }}</p>
                                </div>
                            </div>

                            <div id="createTotalCreditsPreview" class="bg-primary-50/50 dark:bg-primary-900/50 p-4 rounded-2xl border border-primary-100/50 dark:border-primary-800/50 text-xs font-black text-primary-700 dark:text-primary-300">
                                {{ __('Total Computed Credits') }}: <span id="createTotalCreditsValue" class="text-primary-primary text-sm font-black ml-1">3</span> {{ __('Credits') }}
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Classification') }}</label>
                                <select name="subject_classification_id" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                    <option value="">{{ __('Select Classification') }}</option>
                                    @foreach($classifications as $classification)
                                    <option value="{{ $classification->id }}">{{ __($classification->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Prerequisites') }}</label>
                                <select name="prerequisites[]" multiple class="input-saas w-full px-4 py-3 text-sm rounded-xl h-32 scrollbar-hide">
                                    @foreach($allCourses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }} ({{ $course->course_code }})</option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-primary-400 mt-2 ml-1 italic">{{ __('Press Ctrl/Cmd to select multiple') }}</p>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Description') }}</label>
                                <textarea name="description" rows="3"
                                    class="input-saas w-full px-4 py-3 text-sm rounded-xl resize-none"
                                    placeholder="{{ __('Optional: describe the course content, goals, or notes...') }}"></textarea>
                                <p class="text-[10px] text-primary-400 mt-1.5 ml-1">{{ __('Max 2000 characters. Visible to students on their subject tree.') }}</p>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">{{ __('Save Course') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editModal').classList.add('hidden')"></div>

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
                            {{ __('Edit Course') }}
                        </h3>
                        <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-primary-400 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="editForm" method="POST">
                        @csrf @method('PUT')
                        <div class="space-y-5 max-h-[60vh] overflow-y-auto pr-2 scrollbar-hide">
                            @if($isSuperAdmin)
                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Faculties') }}</label>
                                <select id="editFacultySelect" onchange="filterStudyProgramEdit()" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                    <option value="">{{ __('Select Faculty') }}</option>
                                    @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Study Programs') }}</label>
                                <select name="study_program_id" id="editStudyProgramSelect" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                    <option value="">{{ __('Select Study Program') }}</option>
                                    @foreach($studyPrograms as $studyProgram)
                                    <option value="{{ $studyProgram->id }}" data-faculty="{{ $studyProgram->faculty_id }}">{{ $studyProgram->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Code') }}</label>
                                    <input type="text" name="course_code" id="editCode" class="input-saas w-full px-4 py-3 text-sm rounded-xl font-mono uppercase" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Course Name') }}</label>
                                    <input type="text" name="course_name" id="editName" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Lecture/Theory Credits') }}</label>
                                    <input type="number" name="theory_credits" id="editTheoryCredits" min="1" max="6" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required oninput="calculateTotalCreditsEdit()">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Semester') }}</label>
                                    <input type="number" name="semester" id="editSemester" min="1" max="8" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 items-center">
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="has_practical" id="editHasPractical" value="1" class="rounded border-primary-300 text-primary-primary focus:ring-primary-primary h-4 w-4" onchange="togglePracticalInputEdit()">
                                    <label for="editHasPractical" class="ml-2 text-xs font-black text-primary-700 dark:text-primary-300 uppercase tracking-wider">{{ __('Has Practical Part?') }}</label>
                                </div>
                                <div id="editPracticalHoursGroup" class="hidden">
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Practical Hours') }}</label>
                                    <input type="number" name="practical_hours" id="editPracticalHours" min="0" step="2" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="0" oninput="calculateTotalCreditsEdit()">
                                    <p class="text-[9px] text-primary-400 mt-1 ml-1">{{ __('Must be even. 2 hours = 1 credit.') }}</p>
                                </div>
                            </div>

                            <div id="editTotalCreditsPreview" class="bg-primary-50/50 dark:bg-primary-900/50 p-4 rounded-2xl border border-primary-100/50 dark:border-primary-800/50 text-xs font-black text-primary-700 dark:text-primary-300">
                                {{ __('Total Computed Credits') }}: <span id="editTotalCreditsValue" class="text-primary-primary text-sm font-black ml-1">3</span> {{ __('Credits') }}
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Classification') }}</label>
                                <select name="subject_classification_id" id="editClassification" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                    <option value="">{{ __('Select Classification') }}</option>
                                    @foreach($classifications as $classification)
                                    <option value="{{ $classification->id }}">{{ __($classification->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Prerequisites') }}</label>
                                <select name="prerequisites[]" id="editPrerequisites" multiple class="input-saas w-full px-4 py-3 text-sm rounded-xl h-32 scrollbar-hide">
                                    @foreach($allCourses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }} ({{ $course->course_code }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Description') }}</label>
                                <textarea name="description" id="editDescription" rows="3"
                                    class="input-saas w-full px-4 py-3 text-sm rounded-xl resize-none"
                                    placeholder="{{ __('Optional: describe the course content, goals, or notes...') }}"></textarea>
                                <p class="text-[10px] text-primary-400 mt-1.5 ml-1">{{ __('Max 2000 characters. Visible to students on their subject tree.') }}</p>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">{{ __('Update Course') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filter study program based on faculty for Create modal
        function filterStudyProgramCreate() {
            const facultyId = document.getElementById('createFacultySelect')?.value || '';
            const study_programSelect = document.getElementById('createStudyProgramSelect');
            const options = study_programSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === '') return; // Keep placeholder
                const optFacultyId = option.getAttribute('data-faculty');
                option.style.display = (facultyId === '' || optFacultyId === facultyId) ? '' : 'none';
            });

            // Reset selection
            study_programSelect.value = '';
        }

        // Filter study program based on faculty for Edit modal
        function filterStudyProgramEdit() {
            const facultyId = document.getElementById('editFacultySelect')?.value || '';
            const study_programSelect = document.getElementById('editStudyProgramSelect');
            const options = study_programSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === '') return;
                const optFacultyId = option.getAttribute('data-faculty');
                option.style.display = (facultyId === '' || optFacultyId === facultyId) ? '' : 'none';
            });

            study_programSelect.value = '';
        }

        function editCourse(id, code, name, theoryCredits, semester, study_programId, facultyId, prerequisites, classificationId, description, hasPractical, practicalHours) {
            document.getElementById('editForm').action = `/admin/course/${id}`;
            document.getElementById('editCode').value = code;
            document.getElementById('editName').value = name;
            document.getElementById('editTheoryCredits').value = theoryCredits;
            document.getElementById('editSemester').value = semester;
            document.getElementById('editClassification').value = classificationId || '';
            document.getElementById('editDescription').value = description || '';

            // Set practical part fields
            const hasPrCheck = document.getElementById('editHasPractical');
            hasPrCheck.checked = !!hasPractical;
            document.getElementById('editPracticalHours').value = practicalHours || 0;

            togglePracticalInputEdit();
            calculateTotalCreditsEdit();

            // Set faculty and study_program
            const facultySelect = document.getElementById('editFacultySelect');
            if (facultySelect) {
                facultySelect.value = facultyId || '';

                // Filter study program without resetting the value yet
                const study_programSelect = document.getElementById('editStudyProgramSelect');
                const options = study_programSelect.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value === '') return;
                    const optFacultyId = option.getAttribute('data-faculty');
                    option.style.display = (facultyId === '' || optFacultyId === facultyId) ? '' : 'none';
                });
            }
            document.getElementById('editStudyProgramSelect').value = study_programId || '';

            // Set prerequisites
            const prSelect = document.getElementById('editPrerequisites');
            const options = prSelect.options;
            for (let i = 0; i < options.length; i++) {
                options[i].selected = prerequisites.includes(parseInt(options[i].value));
            }

            document.getElementById('editModal').classList.remove('hidden');
        }

        // Live calculation and toggles for Create Modal
        function togglePracticalInputCreate() {
            const hasPractical = document.getElementById('createHasPractical').checked;
            const group = document.getElementById('createPracticalHoursGroup');
            if (hasPractical) {
                group.classList.remove('hidden');
            } else {
                group.classList.add('hidden');
                document.getElementById('createPracticalHours').value = 0;
            }
            calculateTotalCreditsCreate();
        }

        function calculateTotalCreditsCreate() {
            const theoryCredits = parseInt(document.getElementById('createTheoryCredits').value) || 0;
            const hasPractical = document.getElementById('createHasPractical').checked;
            const practicalHours = parseInt(document.getElementById('createPracticalHours').value) || 0;
            const total = theoryCredits + (hasPractical ? Math.floor(practicalHours / 2) : 0);
            document.getElementById('createTotalCreditsValue').innerText = total;
        }

        // Live calculation and toggles for Edit Modal
        function togglePracticalInputEdit() {
            const hasPractical = document.getElementById('editHasPractical').checked;
            const group = document.getElementById('editPracticalHoursGroup');
            if (hasPractical) {
                group.classList.remove('hidden');
            } else {
                group.classList.add('hidden');
                document.getElementById('editPracticalHours').value = 0;
            }
            calculateTotalCreditsEdit();
        }

        function calculateTotalCreditsEdit() {
            const theoryCredits = parseInt(document.getElementById('editTheoryCredits').value) || 0;
            const hasPractical = document.getElementById('editHasPractical').checked;
            const practicalHours = parseInt(document.getElementById('editPracticalHours').value) || 0;
            const total = theoryCredits + (hasPractical ? Math.floor(practicalHours / 2) : 0);
            document.getElementById('editTotalCreditsValue').innerText = total;
        }
    </script>
</x-app-layout>