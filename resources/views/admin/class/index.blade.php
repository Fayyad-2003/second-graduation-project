<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Class Management') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('Manage class data, lecture schedules, and student capacity in the system.') }}
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
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search for classes / courses / lecturers...') }}"
                        class="input-saas pl-11 pr-4 py-2.5 text-sm w-full">
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex-1 sm:flex-none">
                    {{ __('Filter') }}
                </button>
                @if(request('search'))
                <a href="{{ route('admin.class.index') }}" class="btn-ghost-saas px-4 py-2.5 rounded-xl text-sm font-bold text-primary-400 hover:text-primary-600">
                    {{ __('Reset') }}
                </a>
                @endif
            </div>

            <div class="w-px h-8 bg-primary-100 hidden lg:block mx-2"></div>

            <button type="button" onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-primary-600/20 ml-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('Add Class') }}
            </button>
        </form>
    </div>

    <!-- Table Card (Desktop) -->
    <div class="hidden md:block card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start w-16">#</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">
                            <a href="{{ route('admin.class.index', array_merge(request()->all(), ['sort' => 'class_name', 'order' => request('sort') == 'class_name' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1.5 hover:text-primary-primary transition-colors">
                                {{ __('Class') }}
                                @if(request('sort') == 'class_name')
                                <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">
                            <a href="{{ route('admin.class.index', array_merge(request()->all(), ['sort' => 'course_name', 'order' => request('sort') == 'course_name' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1.5 hover:text-primary-primary transition-colors">
                                {{ __('Course') }}
                                @if(request('sort') == 'course_name')
                                <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">
                            <a href="{{ route('admin.class.index', array_merge(request()->all(), ['sort' => 'Lecturer', 'order' => request('sort') == 'Lecturer' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1.5 hover:text-primary-primary transition-colors">
                                {{ __('Lecturer') }}
                                @if(request('sort') == 'Lecturer')
                                <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Schedule') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">
                            <a href="{{ route('admin.class.index', array_merge(request()->all(), ['sort' => 'capacity', 'order' => request('sort') == 'capacity' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1.5 hover:text-primary-primary transition-colors">
                                {{ __('Capacity') }}
                                @if(request('sort') == 'capacity')
                                <svg class="w-3 h-3 {{ request('order') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                </svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-end w-32">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($classes as $index => $k)
                    @php
                    $schedule = $k->courseSchedules->first();
                    @endphp
                    <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
                        <td class="py-5 px-6 text-xs font-bold text-primary-400">{{ $classes->firstItem() + $index }}</td>
                        <td class="py-5 px-6">
                            <span class="inline-flex px-3 py-1 text-xs font-black bg-primary-50 dark:bg-primary-900 text-primary-primary rounded-lg border border-primary-100 dark:border-primary-800 tracking-wider group-hover:bg-primary-primary group-hover:text-white transition-all">{{ $k->class_name }}</span>
                        </td>
                        <td class="py-5 px-6">
                            <div>
                                <p class="text-sm font-black text-primary-900 dark:text-white">{{ $k->course->course_name ?? '-' }}</p>
                                <p class="text-[10px] text-primary-400 font-bold uppercase tracking-wider mt-0.5">{{ $k->course->course_code ?? '' }}</p>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center text-primary-600 dark:text-primary-400 text-xs font-black">
                                    {{ strtoupper(substr($k->lecturer->user->name ?? '-', 0, 1)) }}
                                </div>
                                <span class="text-sm font-bold text-primary-700 dark:text-primary-300">{{ $k->lecturer->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            @if($schedule)
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1.5 text-xs font-black text-primary-900 dark:text-white">
                                    <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ __($schedule->day) }}
                                </div>
                                <div class="flex items-center gap-1.5 text-[10px] font-bold text-primary-400 uppercase">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </div>
                                @if($schedule->room)
                                <div class="flex items-center gap-1.5 text-[10px] font-black text-emerald-500 uppercase mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    {{ $schedule->room }}
                                </div>
                                @endif
                            </div>
                            @else
                            <span class="inline-flex px-2 py-1 text-[10px] font-black bg-amber-50 text-amber-600 rounded-lg uppercase tracking-widest border border-amber-100">{{ __('Not set') }}</span>
                            @endif
                        </td>
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-1.5 bg-primary-50 dark:bg-primary-900 rounded-full overflow-hidden min-w-[60px]">
                                    <div class="h-full bg-primary-primary rounded-full" style="width: 100%"></div>
                                </div>
                                <span class="text-xs font-black text-primary-700 dark:text-primary-300">{{ $k->capacity }}</span>
                            </div>
                        </td>
                        <td class="py-5 px-8 text-end">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="editClass({{ json_encode([
                                    'id' => $k->id,
                                    'class_name' => $k->class_name,
                                    'course_id' => $k->course_id,
                                    'lecturer_id' => $k->lecturer_id,
                                    'capacity' => $k->capacity,
                                    'day' => $schedule?->day,
                                    'start_time' => $schedule ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : null,
                                    'end_time' => $schedule ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : null,
                                    'room' => $schedule?->room,
                                ]) }})" class="p-2 text-primary-primary hover:bg-primary-primary/10 rounded-lg transition" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.class.destroy', $k) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="{{ __('Delete') }}">
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
                        <td colspan="7" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <p class="text-primary-400 text-sm font-medium">{{ __('No class data available') }}</p>
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
        @forelse($classes as $k)
        @php
        $schedule = $k->courseSchedules->first();
        @endphp
        <div class="card-saas p-6 group">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center text-primary-600 dark:text-primary-400 text-sm font-black shadow-sm">
                        {{ strtoupper(substr($k->class_name ?? '-', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-black text-primary-900 dark:text-white truncate group-hover:text-primary-primary transition-colors">{{ $k->course->course_name ?? '-' }}</h4>
                        <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">{{ $k->course->course_code ?? '' }} <span class="mx-1">•</span> {{ __('Class') }} {{ $k->class_name }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50 col-span-2">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Lecturer') }}</span>
                    <span class="text-xs font-black text-primary-900 dark:text-white line-clamp-1">{{ $k->lecturer->user->name ?? '-' }}</span>
                </div>
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Schedule') }}</span>
                    @if($schedule)
                    <span class="text-[10px] font-black text-primary-900 dark:text-white">{{ __($schedule->day) }}, {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</span>
                    @else
                    <span class="text-[10px] font-black text-primary-300">{{ __('Not set') }}</span>
                    @endif
                </div>
                <div class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50">
                    <span class="block text-[10px] text-primary-400 font-black uppercase tracking-widest mb-1">{{ __('Capacity') }}</span>
                    <span class="text-xs font-black text-primary-900 dark:text-white">{{ $k->capacity }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-primary-50 dark:border-primary-800">
                <button onclick="editClass({{ json_encode([
                    'id' => $k->id,
                    'class_name' => $k->class_name,
                    'course_id' => $k->course_id,
                    'lecturer_id' => $k->lecturer_id,
                    'capacity' => $k->capacity,
                    'day' => $schedule?->day,
                    'start_time' => $schedule ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : null,
                    'end_time' => $schedule ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : null,
                    'room' => $schedule?->room,
                ]) }})" class="flex-1 py-3 text-xs font-black text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-800 rounded-xl hover:bg-primary-primary hover:text-white transition-all text-center">
                    {{ __('Edit Class') }}
                </button>
                <form action="{{ route('admin.class.destroy', $k) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')" class="flex-none">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-3 text-red-600 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="card-saas p-10 text-center">
            <p class="text-primary-400 font-bold">{{ __('No class data available') }}</p>
        </div>
        @endforelse
    </div>

    @if($classes->hasPages())
    <div class="md:hidden card-saas px-6 py-4 mb-6">
        {{ $classes->links() }}
    </div>
    @endif

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 modal-overlay" onclick="document.getElementById('createModal').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden animate-fade-in">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>

                <h3 class="text-xl font-black text-primary-900 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    {{ __('Add New Class') }}
                </h3>

                <form action="{{ route('admin.class.store') }}" method="POST" class="space-y-6 overflow-y-auto max-h-[70vh] pr-2 scrollbar-hide">
                    @csrf
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Class Name') }}</label>
                            <input type="text" name="class_name" placeholder="e.g., A" required class="input-saas w-full">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Capacity') }}</label>
                            <input type="number" name="capacity" min="1" placeholder="40" required class="input-saas w-full">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Course') }}</label>
                        <select name="course_id" required class="input-saas w-full">
                            <option value="">{{ __('Select Course') }}</option>
                            @foreach($courses as $mk)
                            <option value="{{ $mk->id }}">{{ $mk->course_code }} - {{ $mk->course_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Lecturer') }}</label>
                        <select name="lecturer_id" required class="input-saas w-full">
                            <option value="">{{ __('Select Lecturer') }}</option>
                            @foreach($lecturers as $d)
                            <option value="{{ $d->id }}">{{ $d->user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-4 border-t border-primary-50 dark:border-primary-800">
                        <p class="text-[10px] font-black text-primary-primary uppercase tracking-widest mb-4">{{ __('Academic Schedule') }}</p>
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Day') }}</label>
                                <select name="day" class="input-saas w-full">
                                    <option value="">{{ __('Select Day') }}</option>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    <option value="{{ $day }}">{{ __($day) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Room') }}</label>
                                <input type="text" name="room" placeholder="e.g., LT-101" class="input-saas w-full">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-5 mt-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Start Time') }}</label>
                                <input type="time" name="start_time" class="input-saas w-full">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('End Time') }}</label>
                                <input type="time" name="end_time" class="input-saas w-full">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 sticky bottom-0 bg-white dark:bg-primary-900 py-2 border-t border-primary-50 dark:border-primary-800">
                        <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-6 py-2.5 text-sm font-bold text-primary-400 hover:text-primary-600 transition">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-primary/20">{{ __('Create Class') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 modal-overlay" onclick="document.getElementById('editModal').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-primary-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden animate-fade-in">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>

                <h3 class="text-xl font-black text-primary-900 dark:text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    {{ __('Edit Class') }}
                </h3>

                <form id="editForm" method="POST" class="space-y-6 overflow-y-auto max-h-[70vh] pr-2 scrollbar-hide">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Class Name') }}</label>
                            <input type="text" name="class_name" id="editName" required class="input-saas w-full">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Capacity') }}</label>
                            <input type="number" name="capacity" id="editCapacity" min="1" required class="input-saas w-full">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Course') }}</label>
                        <select name="course_id" id="editMK" required class="input-saas w-full">
                            @foreach($courses as $mk)
                            <option value="{{ $mk->id }}">{{ $mk->course_code }} - {{ $mk->course_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Lecturer') }}</label>
                        <select name="lecturer_id" id="editLecturer" required class="input-saas w-full">
                            @foreach($lecturers as $d)
                            <option value="{{ $d->id }}">{{ $d->user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-4 border-t border-primary-50 dark:border-primary-800">
                        <p class="text-[10px] font-black text-primary-primary uppercase tracking-widest mb-4">{{ __('Academic Schedule') }}</p>
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Day') }}</label>
                                <select name="day" id="editDay" class="input-saas w-full">
                                    <option value="">{{ __('Select Day') }}</option>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    <option value="{{ $day }}">{{ __($day) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Room') }}</label>
                                <input type="text" name="room" id="editRoom" class="input-saas w-full">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-5 mt-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Start Time') }}</label>
                                <input type="time" name="start_time" id="editStartTime" class="input-saas w-full">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('End Time') }}</label>
                                <input type="time" name="end_time" id="editEndTime" class="input-saas w-full">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 sticky bottom-0 bg-white dark:bg-primary-900 py-2 border-t border-primary-50 dark:border-primary-800">
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-6 py-2.5 text-sm font-bold text-primary-400 hover:text-primary-600 transition">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-primary/20">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editClass(data) {
            document.getElementById('editForm').action = `/admin/class/${data.id}`;
            document.getElementById('editName').value = data.class_name;
            document.getElementById('editMK').value = data.course_id;
            document.getElementById('editLecturer').value = data.lecturer_id;
            document.getElementById('editCapacity').value = data.capacity;
            document.getElementById('editDay').value = data.day || '';
            document.getElementById('editStartTime').value = data.start_time || '';
            document.getElementById('editEndTime').value = data.end_time || '';
            document.getElementById('editRoom').value = data.room || '';
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>