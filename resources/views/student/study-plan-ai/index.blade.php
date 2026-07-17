<x-app-layout>
    <x-slot name="header">
        {{ __('Smart Study Plan') }}
    </x-slot>

    <div class="py-12" x-data="studyPlanGenerator()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Selection Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ __('Plan Setup') }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Select a subject to get a study plan') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Course') }}</label>
                                <select id="class_id" x-model="selectedClass" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">{{ __('Select subject...') }}</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->course->course_name ?? 'N/A' }} - {{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button
                                @click="generatePlan"
                                :disabled="!selectedClass || isLoading"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-lg shadow-indigo-200 dark:shadow-none transition-all disabled:opacity-50 disabled:cursor-not-allowed group">
                                <span x-show="!isLoading">{{ __('Generate Study Plan') }}</span>
                                <span x-show="isLoading">{{ __('Generating...') }}</span>
                                <svg x-show="!isLoading" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                <svg x-show="isLoading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white shadow-xl">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="font-bold">{{ __('How does this work?') }}</h4>
                        </div>
                        <p class="text-sm text-indigo-100 leading-relaxed">
                            {{ __('AI analyzes course content, lectures, and attached files to create a personalized weekly study plan for you.') }}
                        </p>
                    </div>
                </div>

                <!-- Plan Result Area -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 min-h-[500px] flex flex-col relative">

                        <!-- Empty State -->
                        <div x-show="!plan && !isLoading" class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                            <div class="w-24 h-24 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">{{ __('No plan yet') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm">{{ __('Select a subject from the sidebar and click "Generate Plan" to start.') }}</p>
                        </div>

                        <!-- Loading State -->
                        <div x-show="isLoading" class="flex-1 flex flex-col items-center justify-center p-12 text-center animate-pulse">
                            <div class="w-20 h-20 rounded-2xl bg-indigo-500/10 flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-indigo-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2" x-text="loadingStatus">{{ __('Analyzing content...') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('This may take a minute...') }}</p>
                        </div>

                        <!-- Result State -->
                        <div x-show="plan && !isLoading" class="p-8 animate-fade-in">
                            <div class="flex items-center justify-between mb-8 border-b border-gray-100 dark:border-gray-700 pb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-extrabold text-gray-800 dark:text-gray-100" x-text="currentClassName"></h3>
                                        <p class="text-sm text-green-600 font-medium flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                            {{ __('Plan generated successfully') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="downloadPDF" class="flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 transition-all shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        <span class="text-sm font-semibold">{{ __('Download PDF') }}</span>
                                    </button>
                                    <button @click="window.print()" class="p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900 transition-all shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div id="plan-result-container" class="animate-fade-in">
                                <div class="prose prose-indigo dark:prose-invert max-w-none prose-p:leading-relaxed prose-li:my-1" x-html="formatPlan(plan)"></div>
                            </div>


                            <div class="mt-12 p-6 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-100 mb-2 uppercase tracking-wider">{{ __('Notice') }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('This plan was created by AI based on available course content. Please review and adjust it to fit your time and abilities.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .plan-content h2 {
            @apply text-2xl font-black text-indigo-700 dark:text-indigo-400 mt-12 mb-6 pb-2 border-b-2 border-indigo-100 dark:border-indigo-900/50 flex items-center gap-3;
        }

        .plan-content h3 {
            @apply text-lg font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 flex items-center gap-2;
        }

        .week-card {
            @apply bg-white dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 mb-6 shadow-sm hover:shadow-md transition-shadow;
        }

        .week-badge {
            @apply inline-flex items-center px-3 py-1 rounded-full bg-indigo-500 text-white text-xs font-bold mb-4;
        }

        .plan-content ul {
            @apply space-y-2 mb-6;
        }

        .plan-content li {
            @apply flex items-start gap-2 text-gray-600 dark:text-gray-400 text-sm;
        }

        .plan-content li::before {
            content: "•";
            @apply text-indigo-500 font-bold;
        }

        @media print {
            .no-print {
                display: none;
            }

            .py-12 {
                padding-top: 0;
                padding-bottom: 0;
            }

            .shadow-xl {
                shadow: none;
            }
        }

        /* Fix for html2pdf rendering */
        .html2pdf__container {
            background: white !important;
            color: black !important;
        }

        .html2pdf__container .week-card {
            background: #f8fafc !important;
            /* slate-50 */
            border-color: #e2e8f0 !important;
            /* slate-200 */
        }

        .html2pdf__container .week-badge {
            background: #4f46e5 !important;
            /* indigo-600 */
            color: white !important;
        }

        .html2pdf__container h2,
        .html2pdf__container h3 {
            color: #1e1b4b !important;
            /* indigo-950 */
        }
    </style>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    @push('scripts')
    <script>
        function studyPlanGenerator() {
            return {
                selectedClass: '',
                isLoading: false,
                plan: null,
                currentClassName: '',
                loadingStatus: '{{ __("Analyzing content...") }}',
                loadingInterval: null,

                async generatePlan() {
                    if (!this.selectedClass || this.isLoading) return;

                    this.isLoading = true;
                    this.plan = null;

                    const select = document.getElementById('class_id');
                    this.currentClassName = select.options[select.selectedIndex].text;

                    this.startLoadingAnimation();

                    try {
                        const response = await fetch('{{ route("students.study-plan-ai.generate") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                class_id: this.selectedClass
                            }),
                        });

                        const data = await response.json();

                        if (data.success === false) {
                            alert('{{ __("An error occurred:") }} ' + data.message);
                        } else {
                            this.plan = data;
                        }
                    } catch (error) {
                        alert('{{ __("An error occurred while connecting to the server.") }}');
                    } finally {
                        this.stopLoadingAnimation();
                        this.isLoading = false;
                    }
                },

                downloadPDF() {
                    const element = document.getElementById('plan-result-container');
                    const opt = {
                        margin: [10, 10],
                        filename: `Study_Plan_${this.currentClassName.replace(/\s+/g, '_')}.pdf`,
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2,
                            useCORS: true
                        },
                        jsPDF: {
                            unit: 'mm',
                            format: 'a4',
                            orientation: 'portrait'
                        }
                    };

                    // New Promise-based usage:
                    html2pdf().set(opt).from(element).save();
                },


                startLoadingAnimation() {
                    const statuses = [
                        '{{ __("Analyzing course content...") }}',
                        '{{ __("Reading lecture files...") }}',
                        '{{ __("Organizing weekly topics...") }}',
                        '{{ __("Formulating study tips...") }}',
                        '{{ __("Putting final touches...") }}'
                    ];
                    let idx = 0;
                    this.loadingStatus = statuses[0];
                    this.loadingInterval = setInterval(() => {
                        idx = (idx + 1) % statuses.length;
                        this.loadingStatus = statuses[idx];
                    }, 3000);
                },

                stopLoadingAnimation() {
                    if (this.loadingInterval) {
                        clearInterval(this.loadingInterval);
                        this.loadingInterval = null;
                    }
                },

                formatPlan(plan) {
                    if (!plan) return '';

                    let html = '';

                    // Summary
                    if (plan.summary) {
                        html += `<div class="mb-8 p-5 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">${plan.summary}</p>
                        </div>`;
                    }

                    // Weekly schedule
                    if (plan.weeks && plan.weeks.length) {
                        html += `<h2 class="text-lg font-black text-indigo-700 dark:text-indigo-400 mb-4 mt-2 uppercase tracking-wider">{{ __('Weekly Schedule') }}</h2>`;
                        plan.weeks.forEach(week => {
                            const objectives = (week.objectives || []).map(o => `<li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400"><span class="text-indigo-400 font-bold mt-0.5">•</span>${o}</li>`).join('');
                            html += `<div class="week-card">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="week-badge">{{ __('Week') }} ${week.week_number}</span>
                                    <span class="text-xs font-semibold text-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-full">${week.hours} {{ __('hours') }}</span>
                                </div>
                                <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-2">${week.topic}</h3>
                                ${objectives ? `<ul class="space-y-1 mb-3">${objectives}</ul>` : ''}
                                ${week.activities ? `<p class="text-sm text-gray-500 dark:text-gray-400 mt-2 border-t border-gray-100 dark:border-gray-700 pt-2">${week.activities}</p>` : ''}
                            </div>`;
                        });
                    }

                    // General tips
                    if (plan.tips && plan.tips.length) {
                        const tips = plan.tips.map(t => `<li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400"><span class="text-indigo-400 font-bold mt-0.5">•</span>${t}</li>`).join('');
                        html += `<div class="mt-8 p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200 dark:border-slate-700">
                            <h4 class="font-black text-gray-800 dark:text-gray-100 mb-3 uppercase tracking-wider text-sm">{{ __('Study Tips') }}</h4>
                            <ul class="space-y-2">${tips}</ul>
                        </div>`;
                    }

                    return `<div class="plan-content">${html}</div>`;
                },
            }
        }
    </script>
    @endpush
</x-app-layout>