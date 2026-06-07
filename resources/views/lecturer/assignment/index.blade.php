<x-app-layout>
    <x-slot name="header">
        {{ __('Assignments') }} - {{ $class->course->course_name }}
    </x-slot>

    <!-- Class Info -->
    <div class="card-saas p-6 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-siakad-primary/5 rounded-full"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-siakad-900 dark:bg-siakad-950 rounded-[1.25rem] flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-siakad-900/20">
                    {{ $class->class_name }}
                </div>
                <div>
                    <h2 class="text-2xl font-black text-siakad-900 dark:text-white tracking-tight">{{ $class->course->course_name }}</h2>
                    <p class="text-siakad-500 font-medium mt-1 flex items-center gap-2">
                        <span class="w-8 h-px bg-siakad-200"></span>
                        {{ $class->course->course_code }} <span class="text-siakad-300">•</span> {{ $class->course->credits }} {{ __('Credits') }}
                    </p>
                </div>
            </div>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="btn-primary-saas px-6 py-3 rounded-2xl text-sm font-black flex items-center gap-2 shadow-lg shadow-siakad-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Create Assignment') }}
            </button>
        </div>
    </div>

    <!-- Assignment List -->
    <div class="space-y-6">
        @forelse($class->assignments as $assignment)
        <div class="card-saas hover:shadow-xl transition-all duration-300 group overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h3 class="text-lg font-black text-siakad-900 dark:text-white group-hover:text-siakad-primary transition-colors">{{ $assignment->title }}</h3>
                            @if(!$assignment->is_active)
                            <span class="px-2.5 py-1 text-[10px] font-black bg-siakad-50 dark:bg-siakad-900 text-siakad-400 rounded-lg border border-siakad-100 dark:border-siakad-800 uppercase tracking-widest">{{ __('Inactive') }}</span>
                            @elseif($assignment->isOverdue())
                            <span class="px-2.5 py-1 text-[10px] font-black bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded-lg border border-red-100 dark:border-red-900/50 uppercase tracking-widest">{{ __('Overdue') }}</span>
                            @else
                            <span class="px-2.5 py-1 text-[10px] font-black bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg border border-emerald-100 dark:border-emerald-900/50 uppercase tracking-widest">{{ __('Active') }}</span>
                            @endif
                        </div>
                        @if($assignment->description)
                        <p class="text-sm text-siakad-500 font-medium mb-6 line-clamp-2">{{ $assignment->description }}</p>
                        @endif
                        <div class="flex flex-wrap items-center gap-6">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-siakad-50 dark:bg-siakad-900/50 flex items-center justify-center text-siakad-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-siakad-400 font-black uppercase tracking-widest leading-none mb-1">{{ __('Deadline') }}</p>
                                    <p class="text-xs font-bold text-siakad-700 dark:text-siakad-300">{{ $assignment->deadline->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-emerald-400 font-black uppercase tracking-widest leading-none mb-1">{{ __('Collected') }}</p>
                                    <p class="text-xs font-black text-emerald-600 dark:text-emerald-400">{{ $assignment->submission_count }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center text-amber-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-amber-400 font-black uppercase tracking-widest leading-none mb-1">{{ __('Graded') }}</p>
                                    <p class="text-xs font-black text-amber-600 dark:text-amber-400">{{ $assignment->graded_count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 lg:self-end">
                        <a href="{{ route('lecturers.assignment.show', [$class->id, $assignment->id]) }}" class="px-5 py-2.5 text-xs font-black bg-siakad-900 dark:bg-siakad-800 text-white rounded-xl hover:bg-siakad-primary transition-all shadow-lg shadow-siakad-900/10">
                            {{ __('View Submissions') }}
                        </a>
                        <form action="{{ route('lecturers.assignment.toggle', [$class->id, $assignment->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2.5 text-siakad-400 hover:text-siakad-primary hover:bg-siakad-50 dark:hover:bg-siakad-800 rounded-xl transition-all border border-transparent hover:border-siakad-100 dark:hover:border-siakad-700" title="{{ $assignment->is_active ? __('Deactivate') : __('Activate') }}">
                                @if($assignment->is_active)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card-saas p-12 text-center">
            <div class="w-20 h-20 bg-siakad-50 dark:bg-siakad-900/50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-siakad-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <p class="text-siakad-400 font-bold text-lg mb-2">{{ __('No assignments for this class yet.') }}</p>
            <p class="text-siakad-300 text-sm mb-6">{{ __('Create your first assignment to start collecting submissions.') }}</p>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="btn-primary-saas px-6 py-3 rounded-2xl text-sm font-black shadow-lg shadow-siakad-600/20">
                + {{ __('Create First Assignment') }}
            </button>
        </div>
        @endforelse
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-siakad-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('createModal').classList.add('hidden')"></div>
            
            <div class="relative bg-white dark:bg-siakad-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden transform transition-all animate-fade-in">
                <!-- Decorative background -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-siakad-primary/5 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 bg-emerald-500/5 rounded-full"></div>

                <div class="relative">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-siakad-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-siakad-50 dark:bg-siakad-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            {{ __('New Assignment') }}
                        </h3>
                        <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-siakad-400 hover:text-siakad-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('lecturers.assignment.store', $class->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Assignment Title') }}</label>
                                <input type="text" name="title" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('e.g., Assignment 1 - Data Analysis') }}" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Description') }}</label>
                                <textarea name="description" rows="3" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('Assignment instructions...') }}"></textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Deadline') }}</label>
                                <input type="datetime-local" name="deadline" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Question File (Optional)') }}</label>
                                <input type="file" name="file_assignment" class="input-saas w-full px-4 py-2 text-sm rounded-xl file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-siakad-primary/10 file:text-siakad-primary">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Allowed Extensions') }}</label>
                                <input type="text" name="allowed_extensions" class="input-saas w-full px-4 py-3 text-sm rounded-xl" value="pdf,doc,docx,zip,rar">
                                <p class="text-[10px] text-siakad-400 mt-2 ml-1 italic">{{ __('Comma separated, no spaces') }}</p>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-siakad-600/20">{{ __('Save Assignment') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
