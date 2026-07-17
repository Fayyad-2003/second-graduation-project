<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>{{ __('AI Graduation Predictor') }}</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto" x-data="graduationPredictor()" x-init="runPrediction()">

        {{-- Intro Card --}}
        <div class="card-saas p-6 md:p-8 mb-8 overflow-hidden relative">
            <div class="absolute right-0 top-0 w-72 h-72 bg-gradient-to-br from-violet-500/10 to-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
                <div class="flex-shrink-0 w-20 h-20 bg-violet-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-violet-500/20">
                    <svg class="w-11 h-11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="text-center md:text-right flex-1">
                    <h2 class="text-2xl font-bold text-primary-dark mb-2">متنبئ التخرج بالذكاء الاصطناعي</h2>
                    <p class="text-sm text-primary-secondary max-w-2xl">
                        يقوم الذكاء الاصطناعي بتحليل مسار معدلك التراكمي، وتقدمك في الساعات المعتمدة، والمواد الراسبة، والمقررات المتبقية — ليتنبأ بموعد تخرجك ويكشف عن العقبات التي تعترض طريقك.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <button @click="runPrediction()"
                        :disabled="loading"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-violet-600 hover:bg-violet-700 disabled:opacity-60 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-violet-500/20">
                        <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span x-text="loading ? 'جارٍ التحليل...' : 'توقع موعد تخرجي'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Error --}}
        <div x-show="error" x-cloak class="card-saas p-5 mb-6 border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-950/20">
            <p class="text-sm text-red-600 dark:text-red-400" x-text="error"></p>
        </div>

        {{-- Results --}}
        <div x-show="result" x-cloak class="space-y-6">

            {{-- Top KPI Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- Estimated Graduation --}}
                <div class="card-saas p-5">
                    <p class="text-xs text-primary-secondary uppercase font-semibold tracking-wider mb-1">الفصل المتوقع للتخرج</p>
                    <p class="text-lg font-bold text-primary-dark leading-tight" x-text="result?.estimated_graduation_semester ?? '-'"></p>
                </div>

                {{-- Semesters Remaining --}}
                <div class="card-saas p-5">
                    <p class="text-xs text-primary-secondary uppercase font-semibold tracking-wider mb-1">الفصول المتبقية</p>
                    <p class="text-3xl font-bold text-violet-600 dark:text-violet-400" x-text="result?.estimated_semesters_remaining ?? '-'"></p>
                </div>

                {{-- Risk Level --}}
                <div class="card-saas p-5">
                    <p class="text-xs text-primary-secondary uppercase font-semibold tracking-wider mb-1">مستوى الخطر</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-bold"
                        :class="{
                            'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400': result?.risk_level === 'On Track',
                            'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400': result?.risk_level === 'At Risk',
                            'bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-400': result?.risk_level === 'Critical',
                        }"
                        x-text="riskLevelAr(result?.risk_level)">
                    </span>
                </div>

                {{-- GPA Trend --}}
                <div class="card-saas p-5">
                    <p class="text-xs text-primary-secondary uppercase font-semibold tracking-wider mb-1">اتجاه المعدل التراكمي</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xl"
                            x-text="result?.gpa_trend === 'Improving' ? '📈' : result?.gpa_trend === 'Declining' ? '📉' : '➡️'">
                        </span>
                        <span class="font-bold text-primary-dark" x-text="gpaTrendAr(result?.gpa_trend)"></span>
                    </div>
                </div>
            </div>

            {{-- Summary + Risk Reason --}}
            <div class="card-saas p-6">
                <h3 class="text-base font-bold text-primary-dark mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    ملخص تحليل الذكاء الاصطناعي
                </h3>
                <p class="text-sm text-primary-dark leading-relaxed mb-4" x-text="result?.summary"></p>
                <div class="p-4 rounded-xl bg-primary-light/40 dark:bg-gray-700/40 border border-primary-light dark:border-gray-600">
                    <p class="text-xs font-semibold text-primary-secondary uppercase tracking-wider mb-1">تحليل اتجاه المعدل</p>
                    <p class="text-sm text-primary-dark" x-text="result?.gpa_trend_analysis"></p>
                </div>
            </div>

            {{-- Two Column: Bottlenecks + Recommendations --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Bottleneck Courses --}}
                <div class="card-saas p-6">
                    <h3 class="text-base font-bold text-primary-dark mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        المقررات العائقة للتخرج
                    </h3>
                    <div class="space-y-3">
                        <template x-for="(course, i) in (result?.bottleneck_courses ?? [])" :key="i">
                            <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <span class="font-semibold text-sm text-primary-dark" x-text="course.course"></span>
                                    <span class="text-xs font-mono bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-2 py-0.5 rounded flex-shrink-0" x-text="course.code"></span>
                                </div>
                                <p class="text-xs text-primary-secondary" x-text="course.reason"></p>
                            </div>
                        </template>
                        <p x-show="!result?.bottleneck_courses?.length" class="text-sm text-primary-secondary">لا توجد مقررات عائقة.</p>
                    </div>
                </div>

                {{-- Recommendations --}}
                <div class="card-saas p-6">
                    <h3 class="text-base font-bold text-primary-dark mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        توصيات الذكاء الاصطناعي
                    </h3>
                    <div class="space-y-3">
                        <template x-for="(rec, i) in (result?.recommendations ?? [])" :key="i">
                            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800">
                                <p class="font-semibold text-sm text-primary-dark mb-1" x-text="rec.title"></p>
                                <p class="text-xs text-primary-secondary leading-relaxed" x-text="rec.description"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Risk Reason Banner --}}
            <div class="card-saas p-5 border-r-4"
                :class="{
                    'border-emerald-500': result?.risk_level === 'On Track',
                    'border-amber-500': result?.risk_level === 'At Risk',
                    'border-red-500': result?.risk_level === 'Critical',
                }">
                <p class="text-xs font-semibold uppercase tracking-wider text-primary-secondary mb-1">تقييم مستوى الخطر</p>
                <p class="text-sm text-primary-dark" x-text="result?.risk_reason"></p>
            </div>

        </div>

        {{-- Empty state before prediction --}}
        <div x-show="!result && !loading && !error" x-cloak class="card-saas p-12 text-center">
            <div class="w-16 h-16 bg-violet-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-primary-dark mb-2">جاهز للتحليل</h3>
            <p class="text-sm text-primary-secondary max-w-md mx-auto">
                اضغط على زر "توقع موعد تخرجي" وسيقوم الذكاء الاصطناعي بتحليل سجلك الأكاديمي الكامل لإعطائك توقعاً شخصياً دقيقاً.
            </p>
        </div>

    </div>

    @push('scripts')
    <script>
        function graduationPredictor() {
            return {
                loading: false,
                result: null,
                error: null,

                riskLevelAr(level) {
                    const map = { 'On Track': 'على المسار الصحيح', 'At Risk': 'في خطر', 'Critical': 'حرج' };
                    return map[level] ?? level ?? '-';
                },

                gpaTrendAr(trend) {
                    const map = { 'Improving': 'في تحسن', 'Stable': 'مستقر', 'Declining': 'في تراجع' };
                    return map[trend] ?? trend ?? '-';
                },

                async runPrediction() {
                    this.loading = true;
                    this.result  = null;
                    this.error   = null;

                    try {
                        const res = await fetch('{{ route('students.graduation-predictor.predict') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                        });

                        const data = await res.json();

                        if (!data.success) {
                            this.error = data.message || 'حدث خطأ ما. يرجى المحاولة مرة أخرى.';
                        } else {
                            this.result = data;
                        }
                    } catch (e) {
                        this.error = 'تعذّر الاتصال بخدمة الذكاء الاصطناعي. يرجى المحاولة مرة أخرى.';
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
