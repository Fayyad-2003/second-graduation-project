<x-app-layout>
    <x-slot name="header">
        {{ __('Exams') }} - {{ $class->course->course_name }}
    </x-slot>

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
            <div class="flex items-center gap-3">
                <a href="{{ route('lecturers.exam-questions.index', $class->id) }}" class="btn-ghost-saas px-6 py-3 rounded-2xl text-sm font-black flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linecap="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Questions Bank') }}
                </a>
                <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="btn-primary-saas px-6 py-3 rounded-2xl text-sm font-black flex items-center gap-2 shadow-lg shadow-siakad-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Create Exam') }}
                </button>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($class->exams as $exam)
        <div class="card-saas hover:shadow-xl transition-all duration-300 group overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h3 class="text-lg font-black text-siakad-900 dark:text-white group-hover:text-siakad-primary transition-colors">{{ $exam->title }}</h3>
                            @if($exam->isUpcoming())
                            <span class="px-2.5 py-1 text-[10px] font-black bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg border border-emerald-100 dark:border-emerald-900/50 uppercase tracking-widest">{{ __('Upcoming') }}</span>
                            @else
                            <span class="px-2.5 py-1 text-[10px] font-black bg-siakad-50 dark:bg-siakad-900 text-siakad-400 rounded-lg border border-siakad-100 dark:border-siakad-800 uppercase tracking-widest">{{ __('Past') }}</span>
                            @endif
                            <span class="px-2.5 py-1 text-[10px] font-black bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-lg border border-blue-100 dark:border-blue-900/50 uppercase tracking-widest">{{ $exam->questions->count() }} {{ __('Questions') }}</span>
                        </div>
                        @if($exam->description)
                        <p class="text-sm text-siakad-500 font-medium mb-6 line-clamp-2">{{ $exam->description }}</p>
                        @endif
                        <div class="flex flex-wrap items-center gap-6">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-siakad-50 dark:bg-siakad-900/50 flex items-center justify-center text-siakad-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-siakad-400 font-black uppercase tracking-widest leading-none mb-1">{{ __('Exam Date') }}</p>
                                    <p class="text-xs font-bold text-siakad-700 dark:text-siakad-300">{{ $exam->formatted_exam_date }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-siakad-50 dark:bg-siakad-900/50 flex items-center justify-center text-siakad-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-siakad-400 font-black uppercase tracking-widest leading-none mb-1">{{ __('Duration') }}</p>
                                    <p class="text-xs font-bold text-siakad-700 dark:text-siakad-300">{{ $exam->duration }} {{ __('minutes') }}</p>
                                </div>
                            </div>
                            @if($exam->location)
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-siakad-50 dark:bg-siakad-900/50 flex items-center justify-center text-siakad-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-siakad-400 font-black uppercase tracking-widest leading-none mb-1">{{ __('Location') }}</p>
                                    <p class="text-xs font-bold text-siakad-700 dark:text-siakad-300">{{ $exam->location }}</p>
                                </div>
                            </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center text-amber-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-amber-400 font-black uppercase tracking-widest leading-none mb-1">{{ __('Max Score') }}</p>
                                    <p class="text-xs font-black text-amber-600 dark:text-amber-400">{{ $exam->max_score }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 lg:self-end">
                        <a href="{{ route('lecturers.exam.questions', [$class->id, $exam->id]) }}" class="px-4 py-2.5 text-sm font-black bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-xl hover:from-blue-600 hover:to-cyan-700 shadow-lg shadow-blue-500/20 transition-all">
                            {{ __('Questions') }}
                        </a>
                        <form action="{{ route('lecturers.exam.destroy', [$class->id, $exam->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition-all border border-transparent hover:border-red-100 dark:hover:border-red-900/50" title="{{ __('Delete Exam') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
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
            <p class="text-siakad-400 font-bold text-lg mb-2">{{ __('No exams for this class yet.') }}</p>
            <p class="text-siakad-300 text-sm mb-6">{{ __('Create your first exam to start scheduling.') }}</p>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="btn-primary-saas px-6 py-3 rounded-2xl text-sm font-black shadow-lg shadow-siakad-600/20">
                + {{ __('Create First Exam') }}
            </button>
        </div>
        @endforelse
    </div>

    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-siakad-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('createModal').classList.add('hidden')"></div>

            <div class="relative bg-white dark:bg-siakad-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden transform transition-all animate-fade-in">
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
                            {{ __('New Exam') }}
                        </h3>
                        <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-siakad-400 hover:text-siakad-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('lecturers.exam.store', $class->id) }}" method="POST">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Exam Title') }}</label>
                                <input type="text" name="title" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('e.g., Midterm Exam - Data Structures') }}" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Description') }}</label>
                                <textarea name="description" rows="3" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('Exam details or instructions...') }}"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Date & Time') }}</label>
                                    <input type="datetime-local" name="exam_date" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Duration (min)') }}</label>
                                    <input type="number" name="duration" class="input-saas w-full px-4 py-3 text-sm rounded-xl" value="60" required min="1">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Location') }}</label>
                                    <input type="text" name="location" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('e.g., Room 101') }}">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Max Score') }}</label>
                                    <input type="number" name="max_score" class="input-saas w-full px-4 py-3 text-sm rounded-xl" value="100" step="0.01" min="0" max="999.99">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-siakad-600/20">{{ __('Save Exam') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>