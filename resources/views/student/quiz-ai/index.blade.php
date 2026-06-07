<x-app-layout>
    <x-slot name="header">
        {{ __('AI Quizzes') }}
    </x-slot>

    <div class="py-12" x-data="quizGenerator()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Selection Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ __('Generate Quiz') }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Select a subject to start the smart quiz') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Course') }}</label>
                                <select id="class_id" x-model="selectedClass" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-sm focus:ring-orange-500 focus:border-orange-500">
                                    <option value="">{{ __('Select subject...') }}</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->course->course_name ?? 'N/A' }} - {{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button
                                @click="generateQuiz"
                                :disabled="!selectedClass || isLoading"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-semibold shadow-lg shadow-orange-200 dark:shadow-none transition-all disabled:opacity-50 disabled:cursor-not-allowed group">
                                <span x-show="!isLoading">{{ __('Start Smart Quiz') }}</span>
                                <span x-show="isLoading">{{ __('Preparing...') }}</span>
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

                    <!-- Instructions -->
                    <div class="bg-gradient-to-br from-orange-600 to-red-700 rounded-2xl p-6 text-white shadow-xl">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="font-bold">{{ __('How does the quiz work?') }}</h4>
                        </div>
                        <ul class="text-sm text-orange-50 text-right space-y-2">
                            <li>{{ __('1. Course files are analyzed.') }}</li>
                            <li>{{ __('2. Multiple-choice questions are generated.') }}</li>
                            <li>{{ __('3. You\'ll get instant feedback and explanation.') }}</li>
                        </ul>
                    </div>
                </div>

                <!-- Quiz Area -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 min-h-[500px] flex flex-col relative">

                        <!-- Empty State -->
                        <div x-show="!quiz && !isLoading" class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                            <div class="w-24 h-24 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">{{ __('Ready for a quiz?') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm">{{ __('Select a subject and click "Start Quiz" to challenge yourself.') }}</p>
                        </div>

                        <!-- Loading State -->
                        <div x-show="isLoading" class="flex-1 flex flex-col items-center justify-center p-12 text-center animate-pulse">
                            <div class="w-20 h-20 rounded-2xl bg-orange-500/10 flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-orange-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2" x-text="loadingStatus">{{ __('Generating questions...') }}</h3>
                        </div>

                        <!-- Active Quiz View -->
                        <div x-show="quiz && !isFinished" class="p-8 animate-fade-in flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-8 border-b border-gray-100 dark:border-gray-700 pb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-orange-600 flex items-center justify-center text-white font-bold" x-text="currentQuestionIndex + 1"></div>
                                    <div>
                                        <h3 class="text-xl font-extrabold text-gray-800 dark:text-gray-100" x-text="quiz.title"></h3>
                                        <p class="text-sm text-gray-500" x-text="'{{ __('Question') }} ' + (currentQuestionIndex + 1) + ' {{ __('of') }} ' + quiz.questions.length"></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-orange-600 bg-orange-50 dark:bg-orange-950 px-2 py-1 rounded" x-text="percentageComplete + '%'"></span>
                                </div>
                            </div>

                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 leading-relaxed" x-text="currentQuestion.question"></h4>

                                <div class="grid grid-cols-1 gap-3">
                                    <template x-for="(option, index) in currentQuestion.options" :key="index">
                                        <button
                                            @click="selectOption(index)"
                                            :disabled="showExplanation"
                                            :class="{
                                                'border-orange-500 bg-orange-50 dark:bg-orange-900/30': selectedOption === index && !showExplanation,
                                                'border-green-500 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400': showExplanation && index === currentQuestion.correct_answer,
                                                'border-red-500 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400': showExplanation && selectedOption === index && index !== currentQuestion.correct_answer,
                                                'border-gray-100 dark:border-gray-700 hover:border-orange-300 dark:hover:border-orange-700': selectedOption !== index || (showExplanation && index !== currentQuestion.correct_answer && index !== selectedOption)
                                            }"
                                            class="w-full text-right p-4 rounded-xl border-2 transition-all flex items-center justify-between group">
                                            <span class="font-medium" x-text="option"></span>
                                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center flex-shrink-0"
                                                :class="{
                                                    'border-orange-500 bg-orange-500': selectedOption === index && !showExplanation,
                                                    'border-green-500 bg-green-500': showExplanation && index === currentQuestion.correct_answer,
                                                    'border-red-500 bg-red-500': showExplanation && selectedOption === index && index !== currentQuestion.correct_answer
                                                }">
                                                <svg x-show="selectedOption === index || (showExplanation && index === currentQuestion.correct_answer)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </button>
                                    </template>
                                </div>

                                <div x-show="showExplanation" class="mt-6 p-4 bg-blue-50 dark:bg-blue-950/30 rounded-xl border border-blue-100 dark:border-blue-900/50 animate-fade-in">
                                    <div class="flex items-start gap-3">
                                        <div class="p-1.5 bg-blue-500 text-white rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-bold text-blue-900 dark:text-blue-300">{{ __('Explanation:') }}</h5>
                                            <p class="text-sm text-blue-800 dark:text-blue-400 mt-1" x-text="currentQuestion.explanation"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button
                                    x-show="!showExplanation"
                                    @click="checkAnswer"
                                    :disabled="selectedOption === null"
                                    class="px-8 py-3 bg-orange-600 text-white rounded-xl font-bold shadow-lg disabled:opacity-50">
                                    {{ __('Check Answer') }}
                                </button>
                                <button
                                    x-show="showExplanation"
                                    @click="nextQuestion"
                                    class="px-8 py-3 bg-orange-600 text-white rounded-xl font-bold shadow-lg">
                                    <span x-text="currentQuestionIndex + 1 === quiz.questions.length ? '{{ __('Finish Quiz') }}' : '{{ __('Next Question') }}'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Quiz Result View -->
                        <div x-show="isFinished" class="p-12 animate-fade-in flex-1 flex flex-col items-center justify-center text-center">
                            <div class="w-32 h-32 rounded-full flex items-center justify-center mb-8 relative">
                                <svg class="w-full h-full text-orange-500" viewBox="0 0 36 36">
                                    <path class="text-gray-100 dark:text-gray-700" stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                                    <path class="text-orange-500" :stroke-dasharray="scorePercentage + ', 100'" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-3xl font-black text-gray-800 dark:text-white" x-text="score"></span>
                                    <span class="text-xs text-gray-500 uppercase tracking-widest" x-text="'{{ __('of') }} ' + quiz.questions.length"></span>
                                </div>
                            </div>

                            <h3 class="text-2xl font-black text-gray-800 dark:text-white mb-2">{{ __('Quiz Completed!') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-xs" x-text="resultMessage"></p>

                            <div class="flex gap-4">
                                <button @click="resetQuiz" class="px-8 py-3 border-2 border-orange-600 text-orange-600 rounded-xl font-bold hover:bg-orange-50 transition-all">
                                    {{ __('Try Again') }}
                                </button>
                                <button @click="generateQuiz" class="px-8 py-3 bg-orange-600 text-white rounded-xl font-bold shadow-lg hover:bg-orange-700 transition-all">
                                    {{ __('New Quiz') }}
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function quizGenerator() {
            return {
                selectedClass: '',
                isLoading: false,
                quiz: null,
                currentQuestionIndex: 0,
                selectedOption: null,
                showExplanation: false,
                score: 0,
                isFinished: false,
                loadingStatus: '{{ __('
                Generating questions...') }}',

                get currentQuestion() {
                    return this.quiz ? this.quiz.questions[this.currentQuestionIndex] : null;
                },

                get percentageComplete() {
                    if (!this.quiz) return 0;
                    return Math.round(((this.currentQuestionIndex) / this.quiz.questions.length) * 100);
                },

                get scorePercentage() {
                    if (!this.quiz) return 0;
                    return Math.round((this.score / this.quiz.questions.length) * 100);
                },

                get resultMessage() {
                    const p = this.scorePercentage;
                    if (p === 100) return '{{ __("Excellent! You answered all questions correctly.") }}';
                    if (p >= 80) return '{{ __("Great job! You have a strong understanding of the material.") }}';
                    if (p >= 60) return '{{ __("Good, but you can improve by reviewing the material.") }}';
                    return '{{ __("It seems you need more review. Try again!") }}';
                },

                async generateQuiz() {
                    if (!this.selectedClass || this.isLoading) return;

                    this.isLoading = true;
                    this.quiz = null;
                    this.resetQuiz();

                    this.startLoadingAnimation();

                    try {
                        const response = await fetch('{{ route("students.quiz-ai.generate") }}', {
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

                        if (data.success) {
                            this.quiz = data.quiz;
                        } else {
                            alert('{{ __("An error occurred:") }} ' + data.message);
                        }
                    } catch (error) {
                        alert('{{ __("An error occurred while connecting to the server.") }}');
                    } finally {
                        this.isLoading = false;
                    }
                },

                startLoadingAnimation() {
                    const statuses = [
                        '{{ __("Analyzing course content...") }}',
                        '{{ __("Generating questions...") }}',
                        '{{ __("Formulating answers...") }}',
                        '{{ __("Preparing quiz...") }}'
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
                    }
                },

                selectOption(index) {
                    this.selectedOption = index;
                },

                checkAnswer() {
                    if (this.selectedOption === null) return;

                    if (this.selectedOption === this.currentQuestion.correct_answer) {
                        this.score++;
                    }

                    this.showExplanation = true;
                },

                nextQuestion() {
                    if (this.currentQuestionIndex + 1 < this.quiz.questions.length) {
                        this.currentQuestionIndex++;
                        this.selectedOption = null;
                        this.showExplanation = false;
                    } else {
                        this.isFinished = true;
                    }
                },

                resetQuiz() {
                    this.currentQuestionIndex = 0;
                    this.selectedOption = null;
                    this.showExplanation = false;
                    this.score = 0;
                    this.isFinished = false;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>