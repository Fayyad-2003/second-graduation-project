<x-app-layout>
    <x-slot name="header">
        {{ __('Manage Exam Questions') }} - {{ $exam->title }}
    </x-slot>

    <div class="card-saas p-6 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-siakad-primary/5 rounded-full"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <a href="{{ route('lecturers.exam.index', $class->id) }}" class="w-12 h-12 rounded-xl bg-siakad-50 dark:bg-siakad-800 flex items-center justify-center hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-siakad-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-siakad-900 dark:text-white">{{ $exam->title }}</h2>
                    <p class="text-siakad-500 font-medium mt-1">{{ $class->course->course_name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-xs font-black text-siakad-400 uppercase tracking-widest">{{ __('Exam Max Points') }}</p>
                    <p class="text-2xl font-black text-siakad-700 dark:text-siakad-300">{{ $exam->max_score }}</p>
                </div>
                <div class="h-12 w-px bg-slate-200 dark:bg-slate-700"></div>
                <div class="text-right">
                    <p class="text-xs font-black text-siakad-400 uppercase tracking-widest">{{ __('Total Points') }}</p>
                    <p id="total-points" class="text-2xl font-black text-emerald-600 dark:text-emerald-400">0</p>
                </div>
            </div>
        </div>
    </div>

    <div id="points-warning" class="hidden mb-6">
        <div class="card-saas p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-bold text-red-700 dark:text-red-300" id="warning-text"></p>
            </div>
        </div>
    </div>

    <form id="questions-form" action="{{ route('lecturers.exam.questions.sync', [$class->id, $exam->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            @forelse($subjectQuestions as $question)
            <div class="question-card card-saas p-6 hover:shadow-lg transition-all duration-300 group border-2 border-transparent relative" data-question-id="{{ $question->id }}">
                <div class="selected-badge absolute -top-3 -right-3 hidden w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg border-4 border-white dark:border-slate-800 z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4 flex-1">
                        <label class="relative flex items-center cursor-pointer select-none">
                            <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" data-points="{{ $question->points }}" @if(in_array($question->id, $examQuestionIds)) checked @endif class="question-checkbox peer sr-only">
                            <div class="w-8 h-8 rounded-xl border-3 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 transition-all duration-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 peer-checked:scale-110"></div>
                            <svg class="w-5 h-5 text-white absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </label>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 text-[10px] font-black bg-siakad-100 dark:bg-siakad-800 text-siakad-700 dark:text-siakad-300 rounded-full">{{ __($question->question_type) }}</span>
                                <span class="px-3 py-1 text-[10px] font-black bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full">{{ $question->points }} {{ __('points') }}</span>
                            </div>
                            <p class="text-sm font-bold text-siakad-800 dark:text-white">{{ $question->question_text }}</p>
                            @if($question->question_type === 'multiple_choice' && $question->options)
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($question->options as $key => $option)
                                <div class="text-xs text-siakad-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 px-3 py-2 rounded-lg border border-slate-100 dark:border-slate-700">
                                    <span class="font-black text-siakad-400 mr-2">{{ strtoupper($key) }}:</span> {{ $option }}
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card-saas p-12 text-center">
                <div class="w-20 h-20 bg-siakad-50 dark:bg-siakad-900/50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-siakad-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 0 002-2M9 5a2 2 0 012-2h2a2 0 012-2"></path>
                    </svg>
                </div>
                <p class="text-siakad-400 font-bold text-lg mb-2">{{ __('No questions yet') }}</p>
                <p class="text-siakad-300 text-sm mb-6">{{ __('Add your first question to the bank') }}</p>
                <a href="{{ route('lecturers.exam-questions.index', $class->id) }}" class="btn-primary-saas px-6 py-3 rounded-2xl text-sm font-black shadow-lg shadow-siakad-600/20">
                    {{ __('Add First Question') }}
                </a>
            </div>
            @endforelse
        </div>

        @if($subjectQuestions->count() > 0)
        <div class="mt-8 flex items-center justify-end gap-3">
            <a href="{{ route('lecturers.exam.index', $class->id) }}" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Back to Exams') }}</a>
            <button type="submit" id="save-btn" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-siakad-600/20">{{ __('Save Questions') }}</button>
        </div>
        @endif
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const examMaxPoints = {{ $exam->max_score }};
            const checkboxes = document.querySelectorAll('.question-checkbox');
            const totalPointsElement = document.getElementById('total-points');
            const pointsWarning = document.getElementById('points-warning');
            const warningText = document.getElementById('warning-text');
            const questionCards = document.querySelectorAll('.question-card');
            const saveBtn = document.getElementById('save-btn');
            const warningExceed = '{{ __("Total points exceed exam max points!") }}';
            const warningLess = '{{ __("Total points are less than exam max points!") }}';

            console.log('Number of checkboxes found:', checkboxes.length);
            console.log('Exam max points:', examMaxPoints);

            function updateTotalPoints() {
                let total = 0;

                checkboxes.forEach((checkbox, index) => {
                    const card = questionCards[index];
                    const badge = card.querySelector('.selected-badge');

                    if (checkbox.checked) {
                        const points = parseInt(checkbox.dataset.points) || 0;
                        total += points;

                        card.classList.add('border-4', 'border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20', 'shadow-xl', 'scale-[1.01]', 'ring-4', 'ring-emerald-100', 'dark:ring-emerald-900/30');
                        badge.classList.remove('hidden');
                    } else {
                        card.classList.remove('border-4', 'border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20', 'shadow-xl', 'scale-[1.01]', 'ring-4', 'ring-emerald-100', 'dark:ring-emerald-900/30');
                        badge.classList.add('hidden');
                    }
                });

                console.log('Calculated total points:', total);
                totalPointsElement.textContent = total;

                if (total > examMaxPoints) {
                    totalPointsElement.classList.remove('text-emerald-600', 'dark:text-emerald-400', 'text-amber-600', 'dark:text-amber-400');
                    totalPointsElement.classList.add('text-red-600', 'dark:text-red-400');
                    pointsWarning.classList.remove('hidden');
                    warningText.textContent = warningExceed;
                    saveBtn.disabled = true;
                    saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else if (total < examMaxPoints) {
                    totalPointsElement.classList.remove('text-emerald-600', 'dark:text-emerald-400', 'text-red-600', 'dark:text-red-400');
                    totalPointsElement.classList.add('text-amber-600', 'dark:text-amber-400');
                    pointsWarning.classList.remove('hidden');
                    warningText.textContent = warningLess;
                    saveBtn.disabled = false;
                    saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    totalPointsElement.classList.remove('text-amber-600', 'dark:text-amber-400', 'text-red-600', 'dark:text-red-400');
                    totalPointsElement.classList.add('text-emerald-600', 'dark:text-emerald-400');
                    pointsWarning.classList.add('hidden');
                    saveBtn.disabled = false;
                    saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotalPoints);
            });

            updateTotalPoints();
        });
    </script>
</x-app-layout>
