@forelse($classGrouped as $semester => $classList)
<div class="mb-4">
    <!-- Semester Header -->
    <button type="button" onclick="toggleSemester('semester-{{ $semester }}')" id="btn-semester-{{ $semester }}"
        class="w-full flex items-center justify-between px-6 py-4 bg-white dark:bg-primary-900 border border-primary-100/50 dark:border-primary-800/50 rounded-2xl hover:bg-primary-50/50 dark:hover:bg-primary-900/40 transition-all duration-300 shadow-soft group relative text-start">
        <div class="flex items-center gap-4">
            <div id="badge-semester-{{ $semester }}" class="w-10 h-10 rounded-xl bg-white dark:bg-primary-800 text-primary-primary flex items-center justify-center font-black shadow-sm border border-primary-100/50 dark:border-primary-700/50 transition-all duration-300">
                <span id="badge-text-semester-{{ $semester }}" class="text-sm tracking-tighter">{{ $semester }}</span>
            </div>
            <div class="text-start">
                <h3 id="title-semester-{{ $semester }}" class="text-base font-black text-primary-900 dark:text-white transition-all duration-300">{{ __('Semester') }} {{ $semester }}</h3>
                <p id="subtitle-semester-{{ $semester }}" class="text-[10px] text-primary-400 font-bold uppercase tracking-widest mt-0.5">{{ $classList->count() }} {{ __('courses') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span id="count-semester-{{ $semester }}" class="hidden sm:inline-flex items-center px-3 py-1 text-[10px] font-black bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg border border-emerald-100 dark:border-emerald-900/50 uppercase tracking-widest">
                {{ $classList->sum(fn($k) => $k->studyPlanDetails->count()) }} {{ __('Students') }}
            </span>
            <div class="p-2 rounded-xl bg-primary-50 dark:bg-primary-900/50 group-hover:bg-primary-100 transition-colors">
                <svg id="icon-semester-{{ $semester }}" class="w-5 h-5 text-primary-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
    </button>

    <!-- Semester Content -->
    <div id="semester-{{ $semester }}" class="semester-content overflow-hidden transition-all duration-300" style="max-height: 2000px; opacity: 1; margin-top: 1.5rem;">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($classList as $class)
            <a href="{{ route('lecturers.grading.show', $class->id) }}" class="card-saas p-6 group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-transparent hover:border-primary-primary/20">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center border border-primary-100 dark:border-primary-800 group-hover:bg-primary-primary group-hover:scale-110 transition-all duration-300">
                        <span class="font-black text-lg text-primary-primary group-hover:text-white transition duration-300">{{ $class->class_name }}</span>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-primary-50 dark:bg-primary-900 text-primary-400 uppercase tracking-widest group-hover:bg-primary-primary/10 group-hover:text-primary-primary transition">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        {{ $class->studyPlanDetails->count() }}
                    </span>
                </div>

                <h4 class="text-base font-black text-primary-900 dark:text-white mb-1 group-hover:text-primary-primary transition line-clamp-2 leading-tight">{{ $class->course->course_name ?? 'Course Name' }}</h4>
                <p class="text-[10px] text-primary-400 font-bold uppercase tracking-widest mb-6">{{ $class->course->course_code ?? '-' }} • {{ $class->course->credits ?? 0 }} {{ __('Credits') }}</p>

                <div class="flex items-center justify-between pt-5 border-t border-primary-50 dark:border-primary-800">
                    <div class="flex items-center gap-2 text-xs font-black text-primary-500 group-hover:text-primary-primary transition uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('Input Grades') }}
                    </div>
                    <svg class="w-5 h-5 text-primary-200 group-hover:text-primary-primary group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@empty
<div class="card-saas p-16 text-center max-w-md mx-auto">
    <div class="w-16 h-16 rounded-3xl bg-primary-50 dark:bg-primary-900 flex items-center justify-center mx-auto mb-4 border border-primary-100 dark:border-primary-800">
        <svg class="w-8 h-8 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>
    <h3 class="text-lg font-black text-primary-900 dark:text-white mb-1">{{ __('No results') }}</h3>
    <p class="text-primary-500 font-medium text-sm">{{ __('No classes matching the search were found.') }}</p>
</div>
@endforelse