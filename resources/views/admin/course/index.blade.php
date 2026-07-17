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
            <input type="hidden" name="view" value="{{ $viewMode }}">

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

            <select name="study_program_id" onchange="this.form.submit()" class="input-saas px-4 py-2.5 text-sm w-full sm:w-48">
                <option value="">{{ __('All Study Programs') }}</option>
                @foreach($studyPrograms as $sp)
                <option value="{{ $sp->id }}" {{ $selectedStudyProgramId == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
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
                <button type="button" onclick="document.getElementById('createModal').classList.remove('hidden')"
                    class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-primary-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('Add Course') }}
                </button>
            </div>
        </form>
    </div>

    <!-- View Switcher -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2 bg-primary-100/50 dark:bg-primary-900/50 p-1.5 rounded-2xl border border-primary-200/50 dark:border-primary-800/50">
            <a href="{{ route('admin.course.index', array_merge(request()->all(), ['view' => 'table'])) }}"
                class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ $viewMode !== 'tree' ? 'bg-white dark:bg-primary-800 text-primary-primary shadow-sm' : 'text-primary-secondary hover:text-primary-primary' }}">
                {{ __('Table List') }}
            </a>
            <a href="{{ route('admin.course.index', array_merge(request()->all(), ['view' => 'tree'])) }}"
                class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ $viewMode === 'tree' ? 'bg-white dark:bg-primary-800 text-primary-primary shadow-sm' : 'text-primary-secondary hover:text-primary-primary' }}">
                {{ __('Subject Tree') }}
            </a>
        </div>
    </div>

    @if($viewMode === 'tree')
    @php
    $totalCourses = $coursesBySemester->flatten()->count();
    $totalCredits = $coursesBySemester->flatten()->sum('credits');
    $totalTheoryCredits = $coursesBySemester->flatten()->sum('theory_credits');
    $totalPracticalHours = $coursesBySemester->flatten()->sum('practical_hours');
    @endphp

    <!-- Subject Tree View -->
    <div class="card-saas overflow-hidden sm:rounded-xl mb-10">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ __('Curriculum Tree') }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm">
                        {{ __('Curriculum structure grouped by semester. Lines connect prerequisite subjects.') }}
                    </p>
                </div>
            </div>

            <!-- Metrics Summary -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-2xl border border-blue-200 dark:border-blue-700">
                    <div class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1">{{ __('Total Subjects') }}</div>
                    <div class="text-3xl font-black text-blue-800 dark:text-blue-100">{{ $totalCourses }}</div>
                    <div class="text-xs text-blue-600/80 dark:text-blue-400/80 mt-1">{{ __('Courses registered') }}</div>
                </div>

                <div class="p-4 bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-2xl border border-green-200 dark:border-green-700">
                    <div class="text-[10px] font-bold text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">{{ __('Total Credits') }}</div>
                    <div class="text-3xl font-black text-green-800 dark:text-green-100">{{ $totalCredits }}</div>
                    <div class="text-xs text-green-600/80 dark:text-green-400/80 mt-1">{{ __('SKS credits') }}</div>
                </div>

                <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-100 dark:from-purple-900/30 dark:to-purple-900/30 rounded-2xl border border-purple-200 dark:border-purple-700">
                    <div class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">{{ __('Theory Credits') }}</div>
                    <div class="text-3xl font-black text-purple-800 dark:text-purple-100">{{ $totalTheoryCredits }}</div>
                    <div class="text-xs text-purple-600/80 dark:text-purple-400/80 mt-1">{{ __('Lecture SKS') }}</div>
                </div>

                <div class="p-4 bg-gradient-to-br from-amber-50 to-yellow-100 dark:from-amber-900/30 dark:to-yellow-900/30 rounded-2xl border border-amber-200 dark:border-amber-700">
                    <div class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">{{ __('Practical Hours') }}</div>
                    <div class="text-3xl font-black text-amber-800 dark:text-amber-100">{{ $totalPracticalHours }}</div>
                    <div class="text-xs text-amber-600/80 dark:text-amber-400/80 mt-1">{{ __('Total weekly hours') }}</div>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-8 overflow-x-auto relative">
            <svg id="connector-svg" class="absolute top-0 left-0 w-full h-full pointer-events-none" style="z-index: 0;"></svg>

            @forelse($coursesBySemester as $semester => $courses)
            <div class="relative z-10">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-1 border-b border-gray-200 dark:border-gray-700">
                    {{ __('Semester') }} {{ $semester }}
                </h3>
                <div class="flex gap-4 flex-wrap">
                    @foreach($courses as $course)
                    <div id="course-{{ $course->id }}" class="relative min-w-[200px] p-4 rounded-xl border bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 shadow-sm transition-all duration-300 hover:shadow-md hover:border-primary-primary group">

                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="font-black text-sm text-primary-900 dark:text-white font-mono bg-primary-50 dark:bg-primary-900 px-2 py-1 rounded border border-primary-100 dark:border-primary-800">
                                    {{ $course->course_code }}
                                </div>
                                <div class="text-xs font-bold text-primary-600 dark:text-primary-300 mt-2">
                                    {{ $course->course_name }}
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-1.5 mb-2">
                            <span class="inline-flex px-2 py-0.5 text-[9px] font-black bg-primary-50 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded border border-primary-100 dark:border-primary-850 uppercase">
                                {{ $course->credits }} SKS
                            </span>
                            @if($course->has_practical)
                            <span class="inline-flex px-2 py-0.5 text-[9px] font-black bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded border border-amber-100 dark:border-amber-900/50 uppercase">
                                {{ __('Practical') }}
                            </span>
                            @endif
                            @if($course->classification)
                            <span class="inline-flex px-2 py-0.5 text-[9px] font-black bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 rounded border border-purple-100 dark:border-purple-900/50 uppercase">
                                {{ __($course->classification->name) }}
                            </span>
                            @endif
                        </div>

                        @if(count($course->prerequisites) > 0)
                        <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                            <div class="text-[9px] font-bold text-gray-500 uppercase tracking-wider mb-1">{{ __('Prerequisites') }}:</div>
                            <div class="flex flex-wrap gap-1">
                                @foreach($course->prerequisites as $prereq)
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-sky-50 dark:bg-sky-950/30 text-sky-700 dark:text-sky-300 font-bold border border-sky-100 dark:border-sky-900/50">
                                    {{ $prereq->course_code }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons inside Tree Nodes -->
                        <div class="flex items-center gap-2 mt-3 pt-2 border-t border-gray-100 dark:border-gray-850/80">
                            <button onclick="editCourse({{ $course->id }}, '{{ $course->course_code }}', '{{ addslashes($course->course_name) }}', {{ $course->theory_credits }}, {{ $course->semester }}, {{ $course->study_program_id ?? 'null' }}, {{ $course->studyProgram?->faculty_id ?? 'null' }}, {{ json_encode($course->prerequisites->pluck('id')) }}, {{ $course->subject_classification_id ?? 'null' }}, {{ json_encode($course->description) }}, {{ $course->has_practical ? 'true' : 'false' }}, {{ $course->practical_hours }}, {{ $course->attendance_weight ?? 10 }}, {{ $course->midterm_weight ?? ($course->has_practical ? 20 : 30) }}, {{ $course->final_exam_weight ?? ($course->has_practical ? 50 : 60) }}, {{ $course->practical_attendance_weight ?? 5 }}, {{ $course->practical_exam_weight ?? 20 }})"
                                class="p-1.5 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-lg transition-colors cursor-pointer" title="{{ __('Edit') }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <form action="{{ route('admin.course.destroy', $course) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this course?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-primary-secondary hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-colors cursor-pointer" title="{{ __('Delete') }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <p class="text-primary-400 font-bold mb-2">{{ __('No subjects registered in this study program yet.') }}</p>
                <p class="text-xs text-primary-300">{{ __('Use the toolbar to select a different study program or create a new course.') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Tree SVG Styles & Script -->
    <style>
        .connector-line {
            stroke: #3b82f6;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            opacity: 0.65;
        }
    </style>

    <script>
        const courseConnections = @json($courseConnections);

        document.addEventListener('DOMContentLoaded', function() {
            const svg = document.getElementById('connector-svg');
            const container = document.querySelector('.overflow-x-auto');

            function drawConnectors() {
                if (!svg || !container) return;

                svg.setAttribute('width', container.scrollWidth);
                svg.setAttribute('height', container.scrollHeight);
                svg.innerHTML = '';

                courseConnections.forEach(function(conn) {
                    const courseEl = document.getElementById('course-' + conn.courseId);
                    const prereqEl = document.getElementById('course-' + conn.prereqId);

                    if (!courseEl || !prereqEl) return;

                    const courseRect = courseEl.getBoundingClientRect();
                    const prereqRect = prereqEl.getBoundingClientRect();
                    const containerRect = container.getBoundingClientRect();

                    const x1 = prereqRect.left + prereqRect.width / 2 - containerRect.left + container.scrollLeft;
                    const y1 = prereqRect.bottom - containerRect.top + container.scrollTop;
                    const x2 = courseRect.left + courseRect.width / 2 - containerRect.left + container.scrollLeft;
                    const y2 = courseRect.top - containerRect.top + container.scrollTop;

                    const midY = (y1 + y2) / 2;

                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    const d = `M ${x1} ${y1} L ${x1} ${midY} L ${x2} ${midY} L ${x2} ${y2}`;
                    path.setAttribute('d', d);
                    path.setAttribute('class', 'connector-line');
                    svg.appendChild(path);
                });
            }

            window.addEventListener('load', drawConnectors);
            window.addEventListener('resize', drawConnectors);
            container.addEventListener('scroll', drawConnectors);
            setTimeout(drawConnectors, 150);
        });
    </script>
    @else
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
                                <button onclick="editCourse({{ $mk->id }}, '{{ $mk->course_code }}', '{{ addslashes($mk->course_name) }}', {{ $mk->theory_credits }}, {{ $mk->semester }}, {{ $mk->study_program_id ?? 'null' }}, {{ $mk->studyProgram?->faculty_id ?? 'null' }}, {{ json_encode($mk->prerequisites->pluck('id')) }}, {{ $mk->subject_classification_id ?? 'null' }}, {{ json_encode($mk->description) }}, {{ $mk->has_practical ? 'true' : 'false' }}, {{ $mk->practical_hours }}, {{ $mk->attendance_weight ?? 10 }}, {{ $mk->midterm_weight ?? ($mk->has_practical ? 20 : 30) }}, {{ $mk->final_exam_weight ?? ($mk->has_practical ? 50 : 60) }}, {{ $mk->practical_attendance_weight ?? 5 }}, {{ $mk->practical_exam_weight ?? 20 }})"
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
    @endif
    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 modal-overlay" onclick="document.getElementById('createModal').classList.add('hidden')"></div>

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
                        <div class="space-y-5 max-h-[60vh] overflow-y-auto pr-2">
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

                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Description') }}</label>
                                <textarea name="description" rows="3"
                                    class="input-saas w-full px-4 py-3 text-sm rounded-xl resize-none"
                                    placeholder="{{ __('Optional: describe the course content, goals, or notes...') }}"></textarea>
                                <p class="text-[10px] text-primary-400 mt-1.5 ml-1">{{ __('Max 2000 characters. Visible to students on their subject tree.') }}</p>
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
                                <div id="createPrereqContainer" class="relative">
                                    <!-- Selected Tags -->
                                    <div id="createPrereqTags" class="flex flex-wrap gap-1.5 mb-2 min-h-[8px]"></div>
                                    <!-- Search Input -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" id="createPrereqSearch" placeholder="{{ __('Search courses to add as prerequisite...') }}"
                                            class="input-saas w-full pl-9 pr-4 py-2.5 text-sm rounded-xl"
                                            autocomplete="off"
                                            onfocus="showPrereqDropdown('create')" oninput="filterPrereqDropdown('create')">
                                    </div>
                                    <!-- Dropdown -->
                                    <div id="createPrereqDropdown" class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-primary-900 border border-primary-200 dark:border-primary-700 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                        @foreach($allCourses as $course)
                                        <div class="prereq-option px-4 py-2.5 text-sm cursor-pointer hover:bg-primary-50 dark:hover:bg-primary-800 transition-colors flex items-center justify-between"
                                            data-id="{{ $course->id }}" data-name="{{ $course->course_name }}" data-code="{{ $course->course_code }}"
                                            onclick="togglePrereq('create', {{ $course->id }}, '{{ addslashes($course->course_name) }}', '{{ $course->course_code }}')">
                                            <span>
                                                <span class="font-mono text-[10px] font-black text-primary-500 bg-primary-50 dark:bg-primary-800 px-1.5 py-0.5 rounded mr-2">{{ $course->course_code }}</span>
                                                <span class="text-primary-700 dark:text-primary-300">{{ $course->course_name }}</span>
                                            </span>
                                            <svg class="w-4 h-4 text-emerald-500 prereq-check hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        @endforeach
                                    </div>
                                    <!-- Hidden inputs container -->
                                    <div id="createPrereqInputs"></div>
                                </div>
                                <p class="text-[10px] text-primary-400 mt-2 ml-1 italic">{{ __('Search and click to add or remove prerequisites') }}</p>
                            </div>

                            <!-- Grade Components -->
                            <div class="border-t border-primary-100 dark:border-primary-700 pt-5 mt-2">
                                <h4 class="text-[11px] font-black text-primary-500 uppercase tracking-widest mb-4 ml-1">
                                    {{ __('Grade Components (%)') }}
                                </h4>

                                <div class="grid grid-cols-3 gap-4 mb-3">
                                    <div>
                                        <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Attendance') }}</label>
                                        <input type="number" name="attendance_weight" id="createAttendanceWeight" min="0" max="100" value="10" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required oninput="validateGradeComponentsCreate()">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Midterm') }}</label>
                                        <input type="number" name="midterm_weight" id="createMidtermWeight" min="0" max="100" value="30" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required oninput="validateGradeComponentsCreate()">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Final Exam') }}</label>
                                        <input type="number" name="final_exam_weight" id="createFinalExamWeight" min="0" max="100" value="60" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required oninput="validateGradeComponentsCreate()">
                                    </div>
                                </div>

                                <div id="createPracticalGradeComponents" class="grid grid-cols-2 gap-4 mb-3 hidden">
                                    <div>
                                        <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Practical Attendance') }}</label>
                                        <input type="number" name="practical_attendance_weight" id="createPracticalAttendanceWeight" min="0" max="100" value="5" class="input-saas w-full px-4 py-3 text-sm rounded-xl" oninput="validateGradeComponentsCreate()">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Practical Exam') }}</label>
                                        <input type="number" name="practical_exam_weight" id="createPracticalExamWeight" min="0" max="100" value="20" class="input-saas w-full px-4 py-3 text-sm rounded-xl" oninput="validateGradeComponentsCreate()">
                                    </div>
                                </div>

                                <div id="createGradeComponentsValidation" class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-xl border border-primary-100/50 dark:border-primary-800/50 text-xs font-black text-primary-600 dark:text-primary-300">
                                    {{ __('Total') }}: <span id="createTotalWeight" class="text-primary-primary">100%</span>
                                </div>
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
            <div class="fixed inset-0 modal-overlay" onclick="document.getElementById('editModal').classList.add('hidden')"></div>

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
                        <div class="space-y-5 max-h-[60vh] overflow-y-auto pr-2">
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

                            <div>
                                <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Description') }}</label>
                                <textarea name="description" id="editDescription" rows="3"
                                    class="input-saas w-full px-4 py-3 text-sm rounded-xl resize-none"
                                    placeholder="{{ __('Optional: describe the course content, goals, or notes...') }}"></textarea>
                                <p class="text-[10px] text-primary-400 mt-1.5 ml-1">{{ __('Max 2000 characters. Visible to students on their subject tree.') }}</p>
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
                                <div id="editPrereqContainer" class="relative">
                                    <!-- Selected Tags -->
                                    <div id="editPrereqTags" class="flex flex-wrap gap-1.5 mb-2 min-h-[8px]"></div>
                                    <!-- Search Input -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" id="editPrereqSearch" placeholder="{{ __('Search courses to add as prerequisite...') }}"
                                            class="input-saas w-full pl-9 pr-4 py-2.5 text-sm rounded-xl"
                                            autocomplete="off"
                                            onfocus="showPrereqDropdown('edit')" oninput="filterPrereqDropdown('edit')">
                                    </div>
                                    <!-- Dropdown -->
                                    <div id="editPrereqDropdown" class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-primary-900 border border-primary-200 dark:border-primary-700 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                        @foreach($allCourses as $course)
                                        <div class="prereq-option px-4 py-2.5 text-sm cursor-pointer hover:bg-primary-50 dark:hover:bg-primary-800 transition-colors flex items-center justify-between"
                                            data-id="{{ $course->id }}" data-name="{{ $course->course_name }}" data-code="{{ $course->course_code }}"
                                            onclick="togglePrereq('edit', {{ $course->id }}, '{{ addslashes($course->course_name) }}', '{{ $course->course_code }}')">
                                            <span>
                                                <span class="font-mono text-[10px] font-black text-primary-500 bg-primary-50 dark:bg-primary-800 px-1.5 py-0.5 rounded mr-2">{{ $course->course_code }}</span>
                                                <span class="text-primary-700 dark:text-primary-300">{{ $course->course_name }}</span>
                                            </span>
                                            <svg class="w-4 h-4 text-emerald-500 prereq-check hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        @endforeach
                                    </div>
                                    <!-- Hidden inputs container -->
                                    <div id="editPrereqInputs"></div>
                                </div>
                                <p class="text-[10px] text-primary-400 mt-2 ml-1 italic">{{ __('Search and click to add or remove prerequisites') }}</p>
                            </div>

                            <!-- Grade Components -->
                            <div class="border-t border-primary-100 dark:border-primary-700 pt-5 mt-2">
                                <h4 class="text-[11px] font-black text-primary-500 uppercase tracking-widest mb-4 ml-1">
                                    {{ __('Grade Components (%)') }}
                                </h4>

                                <div class="grid grid-cols-3 gap-4 mb-3">
                                    <div>
                                        <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Attendance') }}</label>
                                        <input type="number" name="attendance_weight" id="editAttendanceWeight" min="0" max="100" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required oninput="validateGradeComponentsEdit()">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Midterm') }}</label>
                                        <input type="number" name="midterm_weight" id="editMidtermWeight" min="0" max="100" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required oninput="validateGradeComponentsEdit()">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Final Exam') }}</label>
                                        <input type="number" name="final_exam_weight" id="editFinalExamWeight" min="0" max="100" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required oninput="validateGradeComponentsEdit()">
                                    </div>
                                </div>

                                <div id="editPracticalGradeComponents" class="grid grid-cols-2 gap-4 mb-3 hidden">
                                    <div>
                                        <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Practical Attendance') }}</label>
                                        <input type="number" name="practical_attendance_weight" id="editPracticalAttendanceWeight" min="0" max="100" class="input-saas w-full px-4 py-3 text-sm rounded-xl" oninput="validateGradeComponentsEdit()">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-2 ml-1">{{ __('Practical Exam') }}</label>
                                        <input type="number" name="practical_exam_weight" id="editPracticalExamWeight" min="0" max="100" class="input-saas w-full px-4 py-3 text-sm rounded-xl" oninput="validateGradeComponentsEdit()">
                                    </div>
                                </div>

                                <div id="editGradeComponentsValidation" class="bg-primary-50/50 dark:bg-primary-900/50 p-3 rounded-xl border border-primary-100/50 dark:border-primary-800/50 text-xs font-black text-primary-600 dark:text-primary-300">
                                    {{ __('Total') }}: <span id="editTotalWeight" class="text-primary-primary">100%</span>
                                </div>
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
        // ===== Prerequisite Picker State =====
        const selectedPrereqs = {
            create: new Map(),
            edit: new Map()
        };
        let editingCourseId = null;

        // Filter study program based on faculty for Create modal
        function filterStudyProgramCreate() {
            const facultyId = document.getElementById('createFacultySelect')?.value || '';
            const study_programSelect = document.getElementById('createStudyProgramSelect');
            const options = study_programSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === '') return;
                const optFacultyId = option.getAttribute('data-faculty');
                option.style.display = (facultyId === '' || optFacultyId === facultyId) ? '' : 'none';
            });

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

        // ===== Prerequisite Picker Functions =====
        function showPrereqDropdown(mode) {
            document.getElementById(mode + 'PrereqDropdown').classList.remove('hidden');
            filterPrereqDropdown(mode);
        }

        function hidePrereqDropdown(mode) {
            setTimeout(() => {
                document.getElementById(mode + 'PrereqDropdown').classList.add('hidden');
            }, 200);
        }

        function filterPrereqDropdown(mode) {
            const search = document.getElementById(mode + 'PrereqSearch').value.toLowerCase();
            const dropdown = document.getElementById(mode + 'PrereqDropdown');
            const options = dropdown.querySelectorAll('.prereq-option');
            let hasVisible = false;

            options.forEach(option => {
                const name = option.dataset.name.toLowerCase();
                const code = option.dataset.code.toLowerCase();
                const id = parseInt(option.dataset.id);
                const isCurrentCourse = (mode === 'edit' && id === editingCourseId);
                const matchesSearch = name.includes(search) || code.includes(search);

                if (isCurrentCourse) {
                    option.style.display = 'none';
                } else if (matchesSearch) {
                    option.style.display = '';
                    hasVisible = true;
                } else {
                    option.style.display = 'none';
                }

                // Update check icon
                const check = option.querySelector('.prereq-check');
                if (selectedPrereqs[mode].has(id)) {
                    check.classList.remove('hidden');
                    option.classList.add('bg-emerald-50/50', 'dark:bg-emerald-950/20');
                } else {
                    check.classList.add('hidden');
                    option.classList.remove('bg-emerald-50/50', 'dark:bg-emerald-950/20');
                }
            });

            dropdown.classList.remove('hidden');
        }

        function togglePrereq(mode, id, name, code) {
            if (selectedPrereqs[mode].has(id)) {
                selectedPrereqs[mode].delete(id);
            } else {
                selectedPrereqs[mode].set(id, {
                    name,
                    code
                });
            }
            renderPrereqTags(mode);
            renderPrereqInputs(mode);
            filterPrereqDropdown(mode);
        }

        function removePrereq(mode, id) {
            selectedPrereqs[mode].delete(id);
            renderPrereqTags(mode);
            renderPrereqInputs(mode);
            filterPrereqDropdown(mode);
        }

        function renderPrereqTags(mode) {
            const container = document.getElementById(mode + 'PrereqTags');
            container.innerHTML = '';

            if (selectedPrereqs[mode].size === 0) {
                container.innerHTML = '<span class="text-[10px] text-primary-300 italic py-1">{{ __('
                No prerequisites selected ') }}</span>';
                return;
            }

            selectedPrereqs[mode].forEach((data, id) => {
                const tag = document.createElement('span');
                tag.className = 'inline-flex items-center gap-1.5 pl-2.5 pr-1.5 py-1 text-[11px] font-bold bg-sky-50 dark:bg-sky-950/30 text-sky-700 dark:text-sky-300 rounded-lg border border-sky-200 dark:border-sky-800 transition-all hover:border-sky-300 dark:hover:border-sky-700 group/tag';
                tag.innerHTML = `
                    <span class="font-mono text-[9px] font-black text-sky-500 dark:text-sky-400">${data.code}</span>
                    <span class="max-w-[120px] truncate">${data.name}</span>
                    <button type="button" onclick="removePrereq('${mode}', ${id})" class="ml-0.5 p-0.5 rounded hover:bg-sky-200 dark:hover:bg-sky-800 transition-colors group-hover/tag:text-red-500">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                container.appendChild(tag);
            });
        }

        function renderPrereqInputs(mode) {
            const container = document.getElementById(mode + 'PrereqInputs');
            container.innerHTML = '';

            selectedPrereqs[mode].forEach((data, id) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'prerequisites[]';
                input.value = id;
                container.appendChild(input);
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            ['create', 'edit'].forEach(mode => {
                const container = document.getElementById(mode + 'PrereqContainer');
                if (container && !container.contains(e.target)) {
                    document.getElementById(mode + 'PrereqDropdown').classList.add('hidden');
                }
            });
        });

        // Initialize create modal tags
        document.addEventListener('DOMContentLoaded', function() {
            renderPrereqTags('create');
        });

        function editCourse(id, code, name, theoryCredits, semester, study_programId, facultyId, prerequisites, classificationId, description, hasPractical, practicalHours, attendanceWeight, midtermWeight, finalExamWeight, practicalAttendanceWeight, practicalExamWeight) {
            editingCourseId = id;
            document.getElementById('editForm').action = `/admin/course/${id}`;
            document.getElementById('editCode').value = code;
            document.getElementById('editName').value = name;
            document.getElementById('editTheoryCredits').value = theoryCredits;
            document.getElementById('editSemester').value = semester;
            document.getElementById('editClassification').value = classificationId || '';
            document.getElementById('editDescription').value = description || '';

            // Set grade component fields
            document.getElementById('editAttendanceWeight').value = attendanceWeight;
            document.getElementById('editMidtermWeight').value = midtermWeight;
            document.getElementById('editFinalExamWeight').value = finalExamWeight;
            document.getElementById('editPracticalAttendanceWeight').value = practicalAttendanceWeight;
            document.getElementById('editPracticalExamWeight').value = practicalExamWeight;

            // Set practical part fields
            const hasPrCheck = document.getElementById('editHasPractical');
            hasPrCheck.checked = !!hasPractical;
            document.getElementById('editPracticalHours').value = practicalHours || 0;

            togglePracticalInputEdit();
            calculateTotalCreditsEdit();
            validateGradeComponentsEdit();

            // Set faculty and study_program
            const facultySelect = document.getElementById('editFacultySelect');
            if (facultySelect) {
                facultySelect.value = facultyId || '';

                const study_programSelect = document.getElementById('editStudyProgramSelect');
                const options = study_programSelect.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value === '') return;
                    const optFacultyId = option.getAttribute('data-faculty');
                    option.style.display = (facultyId === '' || optFacultyId === facultyId) ? '' : 'none';
                });
            }
            document.getElementById('editStudyProgramSelect').value = study_programId || '';

            // Set prerequisites using the new picker
            selectedPrereqs.edit.clear();
            const dropdown = document.getElementById('editPrereqDropdown');
            const allOptions = dropdown.querySelectorAll('.prereq-option');

            prerequisites.forEach(prereqId => {
                allOptions.forEach(option => {
                    if (parseInt(option.dataset.id) === prereqId) {
                        selectedPrereqs.edit.set(prereqId, {
                            name: option.dataset.name,
                            code: option.dataset.code
                        });
                    }
                });
            });

            renderPrereqTags('edit');
            renderPrereqInputs('edit');
            document.getElementById('editPrereqSearch').value = '';

            document.getElementById('editModal').classList.remove('hidden');
        }

        // Live calculation and toggles for Create Modal
        function togglePracticalInputCreate() {
            const hasPractical = document.getElementById('createHasPractical').checked;
            const group = document.getElementById('createPracticalHoursGroup');
            const practicalGradeComponents = document.getElementById('createPracticalGradeComponents');
            if (hasPractical) {
                group.classList.remove('hidden');
                practicalGradeComponents.classList.remove('hidden');
                // Set default values for practical courses
                document.getElementById('createMidtermWeight').value = 20;
                document.getElementById('createFinalExamWeight').value = 50;
            } else {
                group.classList.add('hidden');
                practicalGradeComponents.classList.add('hidden');
                document.getElementById('createPracticalHours').value = 0;
                // Set default values for non-practical courses
                document.getElementById('createMidtermWeight').value = 30;
                document.getElementById('createFinalExamWeight').value = 60;
            }
            calculateTotalCreditsCreate();
            validateGradeComponentsCreate();
        }

        function calculateTotalCreditsCreate() {
            const theoryCredits = parseInt(document.getElementById('createTheoryCredits').value) || 0;
            const hasPractical = document.getElementById('createHasPractical').checked;
            const practicalHours = parseInt(document.getElementById('createPracticalHours').value) || 0;
            const total = theoryCredits + (hasPractical ? Math.floor(practicalHours / 2) : 0);
            document.getElementById('createTotalCreditsValue').innerText = total;
        }

        function validateGradeComponentsCreate() {
            const hasPractical = document.getElementById('createHasPractical').checked;
            const attendance = parseInt(document.getElementById('createAttendanceWeight').value) || 0;
            const midterm = parseInt(document.getElementById('createMidtermWeight').value) || 0;
            const finalExam = parseInt(document.getElementById('createFinalExamWeight').value) || 0;

            let total = attendance + midterm + finalExam;

            if (hasPractical) {
                const practicalAttendance = parseInt(document.getElementById('createPracticalAttendanceWeight').value) || 0;
                const practicalExam = parseInt(document.getElementById('createPracticalExamWeight').value) || 0;
                total += practicalAttendance + practicalExam;
            }

            const totalWeightEl = document.getElementById('createTotalWeight');
            const validationEl = document.getElementById('createGradeComponentsValidation');

            totalWeightEl.innerText = total + '%';

            if (total === 100) {
                validationEl.className = 'bg-emerald-50/50 dark:bg-emerald-900/30 p-3 rounded-xl border border-emerald-100/50 dark:border-emerald-700/50 text-xs font-black text-emerald-600 dark:text-emerald-400';
                totalWeightEl.className = 'text-emerald-600 dark:text-emerald-400';
            } else {
                validationEl.className = 'bg-red-50/50 dark:bg-red-900/30 p-3 rounded-xl border border-red-100/50 dark:border-red-700/50 text-xs font-black text-red-600 dark:text-red-400';
                totalWeightEl.className = 'text-red-600 dark:text-red-400';
            }
        }

        // Live calculation and toggles for Edit Modal
        function togglePracticalInputEdit() {
            const hasPractical = document.getElementById('editHasPractical').checked;
            const group = document.getElementById('editPracticalHoursGroup');
            const practicalGradeComponents = document.getElementById('editPracticalGradeComponents');
            if (hasPractical) {
                group.classList.remove('hidden');
                practicalGradeComponents.classList.remove('hidden');
            } else {
                group.classList.add('hidden');
                practicalGradeComponents.classList.add('hidden');
                document.getElementById('editPracticalHours').value = 0;
            }
            calculateTotalCreditsEdit();
            validateGradeComponentsEdit();
        }

        function calculateTotalCreditsEdit() {
            const theoryCredits = parseInt(document.getElementById('editTheoryCredits').value) || 0;
            const hasPractical = document.getElementById('editHasPractical').checked;
            const practicalHours = parseInt(document.getElementById('editPracticalHours').value) || 0;
            const total = theoryCredits + (hasPractical ? Math.floor(practicalHours / 2) : 0);
            document.getElementById('editTotalCreditsValue').innerText = total;
        }

        function validateGradeComponentsEdit() {
            const hasPractical = document.getElementById('editHasPractical').checked;
            const attendance = parseInt(document.getElementById('editAttendanceWeight').value) || 0;
            const midterm = parseInt(document.getElementById('editMidtermWeight').value) || 0;
            const finalExam = parseInt(document.getElementById('editFinalExamWeight').value) || 0;

            let total = attendance + midterm + finalExam;

            if (hasPractical) {
                const practicalAttendance = parseInt(document.getElementById('editPracticalAttendanceWeight').value) || 0;
                const practicalExam = parseInt(document.getElementById('editPracticalExamWeight').value) || 0;
                total += practicalAttendance + practicalExam;
            }

            const totalWeightEl = document.getElementById('editTotalWeight');
            const validationEl = document.getElementById('editGradeComponentsValidation');

            totalWeightEl.innerText = total + '%';

            if (total === 100) {
                validationEl.className = 'bg-emerald-50/50 dark:bg-emerald-900/30 p-3 rounded-xl border border-emerald-100/50 dark:border-emerald-700/50 text-xs font-black text-emerald-600 dark:text-emerald-400';
                totalWeightEl.className = 'text-emerald-600 dark:text-emerald-400';
            } else {
                validationEl.className = 'bg-red-50/50 dark:bg-red-900/30 p-3 rounded-xl border border-red-100/50 dark:border-red-700/50 text-xs font-black text-red-600 dark:text-red-400';
                totalWeightEl.className = 'text-red-600 dark:text-red-400';
            }
        }
    </script>
</x-app-layout>