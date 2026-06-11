<x-app-layout>
    <x-slot name="header">
        {{ __('Questions Bank') }} - {{ $course->course_name }}
    </x-slot>

    <div class="card-saas p-6 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-primary/5 rounded-full"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-primary-900 dark:bg-primary-950 rounded-[1.25rem] flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-primary-900/20">
                    {{ $class->class_name }}
                </div>
                <div>
                    <h2 class="text-2xl font-black text-primary-900 dark:text-white tracking-tight">{{ $course->course_name }}</h2>
                    <p class="text-primary-500 font-medium mt-1 flex items-center gap-2">
                        <span class="w-8 h-px bg-primary-200"></span>
                        {{ $course->course_code }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('lecturers.exam.index', $class->id) }}" class="btn-ghost-saas px-4 py-2.5 rounded-xl text-sm font-black">
                    {{ __('Back to Exams') }}
                </a>
                <a href="{{ route('lecturers.exam-questions.create', $class->id) }}" class="btn-primary-saas px-4 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-primary-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Add Question') }}
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($questions as $question)
        <div class="card-saas hover:shadow-xl transition-all duration-300 group overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="px-3 py-1 text-xs font-bold bg-primary-100 dark:bg-primary-800 text-primary-700 dark:text-primary-300 rounded-full">
                            {{ __($question->question_type) }}
                        </span>
                        @php
                        $difficultyColors = [
                        'easy' => ['bg-green-100', 'text-green-700', 'dark:bg-green-900/30', 'dark:text-green-400'],
                        'medium' => ['bg-yellow-100', 'text-yellow-700', 'dark:bg-yellow-900/30', 'dark:text-yellow-400'],
                        'hard' => ['bg-orange-100', 'text-orange-700', 'dark:bg-orange-900/30', 'dark:text-orange-400'],
                        'very_hard' => ['bg-red-100', 'text-red-700', 'dark:bg-red-900/30', 'dark:text-red-400'],
                        ];
                        $colors = $difficultyColors[$question->difficulty] ?? $difficultyColors['medium'];
                        $difficultyLabels = [
                        'easy' => __('Easy'),
                        'medium' => __('Medium'),
                        'hard' => __('Hard'),
                        'very_hard' => __('Very Hard'),
                        ];
                        @endphp
                        <span class="px-3 py-1 text-xs font-bold {{ $colors[0] }} {{ $colors[1] }} {{ $colors[2] }} {{ $colors[3] }} rounded-full">
                            {{ $difficultyLabels[$question->difficulty] }}
                        </span>
                        <span class="px-3 py-1 text-xs font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full">
                            {{ $question->points }} {{ __('points') }}
                        </span>
                        <span class="px-3 py-1 text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-400 rounded-full">
                            {{ __('Order') }}: {{ $question->order }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('lecturers.exam-questions.edit', [$class->id, $question->id]) }}" class="p-2 text-primary-500 hover:text-primary-700 hover:bg-primary-50 dark:hover:bg-primary-800 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('lecturers.exam-questions.destroy', [$class->id, $question->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition-all" onclick="return confirm('{{ __('Are you sure you want to delete this question?') }}')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="prose dark:prose-invert">
                    <p class="text-lg text-primary-900 dark:text-white font-semibold mb-3">{{ $question->question_text }}</p>
                    @if($question->options && $question->question_type === 'multiple_choice')
                    <ul class="space-y-2 mb-3">
                        @foreach($question->options as $index => $option)
                        <li class="flex items-center gap-2">
                            <span class="font-bold text-primary-500">{{ chr(65 + $index) }}.</span>
                            <span class="text-primary-700 dark:text-primary-300">{{ $option }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @if($question->correct_answer)
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                        <strong>{{ __('Correct Answer') }}:</strong> {{ $question->correct_answer }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="card-saas p-12 text-center">
            <div class="w-20 h-20 bg-primary-50 dark:bg-primary-900/50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-primary-400 font-bold text-lg mb-2">{{ __('No questions yet') }}</p>
            <p class="text-primary-300 text-sm mb-6">{{ __('Add your first question to the bank') }}</p>
            <a href="{{ route('lecturers.exam-questions.create', $class->id) }}" class="btn-primary-saas px-4 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20">
                {{ __('Add First Question') }}
            </a>
        </div>
        @endforelse
    </div>
</x-app-layout>