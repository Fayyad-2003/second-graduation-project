<x-app-layout>
    <x-slot name="header">
        {{ __('Subject Tree') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Subject Tree') }}</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm">{{ __('View all college subjects, finished subjects are highlighted, and prerequisites are connected.') }}</p>

                    <!-- Progress Summary -->
                    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-2xl border border-green-200 dark:border-green-700">
                            <div class="text-[10px] font-bold text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">{{ __('Credits Completed') }}</div>
                            <div class="text-3xl font-black text-green-800 dark:text-green-100">{{ $creditsCompleted }}</div>
                            <div class="text-xs text-green-600/80 dark:text-green-400/80 mt-1">{{ __('of') }} {{ $totalCurriculumCredits }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-2xl border border-blue-200 dark:border-blue-700">
                            <div class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1">{{ __('Credits Remaining') }}</div>
                            <div class="text-3xl font-black text-blue-800 dark:text-blue-100">{{ $creditsRemaining }}</div>
                            <div class="text-xs text-blue-600/80 dark:text-blue-400/80 mt-1">{{ __('to graduate') }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-br from-amber-50 to-yellow-100 dark:from-amber-900/30 dark:to-yellow-900/30 rounded-2xl border border-amber-200 dark:border-amber-700">
                            <div class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">{{ __('Current GPA') }}</div>
                            <div class="text-3xl font-black text-amber-800 dark:text-amber-100">{{ $cgpaData['gpa'] }}</div>
                            <div class="text-xs text-amber-600/80 dark:text-amber-400/80 mt-1">{{ __('Cumulative') }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-2xl border border-purple-200 dark:border-purple-700">
                            <div class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">{{ __('Progress') }}</div>
                            <div class="text-3xl font-black text-purple-800 dark:text-purple-100">{{ $percentageCompleted }}%</div>
                            <div class="mt-2 w-full bg-purple-200 dark:bg-purple-800 rounded-full h-2">
                                <div class="bg-purple-600 dark:bg-purple-400 h-2 rounded-full transition-all duration-500" style="width: {{ $percentageCompleted }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-8 overflow-x-auto relative">
                    <svg id="connector-svg" class="absolute top-0 left-0 w-full h-full pointer-events-none" style="z-index: 0;"></svg>

                    @foreach($coursesBySemester as $semester => $courses)
                    <div class="relative z-10">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-1 border-b border-gray-200 dark:border-gray-700">
                            {{ __('Semester') }} {{ $semester }}
                        </h2>
                        <div class="flex gap-4 flex-wrap">
                            @foreach($courses as $course)
                            <div id="course-{{ $course->id }}" class="relative min-w-[180px] p-4 rounded-xl border transition-all duration-300 
                                        @if(in_array($course->id, $finishedCourseIds))
                                            bg-green-50 dark:bg-green-900/30 border-green-300 dark:border-green-700 shadow-md
                                        @else
                                            bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 shadow-sm
                                        @endif">

                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <div class="font-bold text-sm {{ in_array($course->id, $finishedCourseIds) ? 'text-green-800 dark:text-green-100' : 'text-gray-900 dark:text-white' }}">
                                            {{ $course->course_code }}
                                        </div>
                                        <div class="text-xs {{ in_array($course->id, $finishedCourseIds) ? 'text-green-700 dark:text-green-200' : 'text-gray-600 dark:text-gray-400' }}">
                                            {{ $course->course_name }}
                                        </div>
                                    </div>
                                    @if(in_array($course->id, $finishedCourseIds))
                                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center text-white">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 90 0 11-18 0 9 90 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $course->credits }} {{ __('Credits') }}</span>
                                </div>

                                @if(count($course->prerequisites) > 0)
                                <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-[10px] font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('Prereq') }}:</div>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($course->prerequisites as $prereq)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full 
                                                            @if(in_array($prereq->id, $finishedCourseIds))
                                                                bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                                            @else
                                                                bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                                            @endif">
                                            {{ $prereq->course_code }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        .connector-line {
            stroke: #6b7280;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }

        .connector-line-finished {
            stroke: #22c55e;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }
    </style>

    <script>
        // Pass connection data from PHP to JavaScript
        const courseConnections = @json($courseConnections);

        document.addEventListener('DOMContentLoaded', function() {
            const svg = document.getElementById('connector-svg');
            const container = document.querySelector('.overflow-x-auto');

            function drawConnectors() {
                // Set SVG size to container scroll dimensions
                svg.setAttribute('width', container.scrollWidth);
                svg.setAttribute('height', container.scrollHeight);
                svg.innerHTML = '';

                // Draw each connection
                courseConnections.forEach(function(conn) {
                    const courseEl = document.getElementById('course-' + conn.courseId);
                    const prereqEl = document.getElementById('course-' + conn.prereqId);

                    if (!courseEl || !prereqEl) return;

                    const courseRect = courseEl.getBoundingClientRect();
                    const prereqRect = prereqEl.getBoundingClientRect();
                    const containerRect = container.getBoundingClientRect();

                    // Calculate positions relative to container with scroll offset
                    const x1 = prereqRect.left + prereqRect.width / 2 - containerRect.left + container.scrollLeft;
                    const y1 = prereqRect.bottom - containerRect.top + container.scrollTop;
                    const x2 = courseRect.left + courseRect.width / 2 - containerRect.left + container.scrollLeft;
                    const y2 = courseRect.top - containerRect.top + container.scrollTop;

                    // Calculate midpoint for the corner
                    const midY = (y1 + y2) / 2;

                    // Create path with rounded corner
                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    const d = `M ${x1} ${y1} L ${x1} ${midY} L ${x2} ${midY} L ${x2} ${y2}`;
                    path.setAttribute('d', d);
                    path.setAttribute('class', conn.prereqFinished ? 'connector-line-finished' : 'connector-line');
                    svg.appendChild(path);
                });
            }

            // Draw connectors after DOM content loaded, window load, resize, and scroll
            window.addEventListener('load', drawConnectors);
            window.addEventListener('resize', drawConnectors);
            container.addEventListener('scroll', drawConnectors);
            // Also draw after a small delay to ensure all elements are rendered
            setTimeout(drawConnectors, 100);
        });
    </script>
</x-app-layout>