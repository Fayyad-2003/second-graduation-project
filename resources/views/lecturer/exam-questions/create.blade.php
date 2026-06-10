<x-app-layout>
    <x-slot name="header">
        {{ __('Add New Question') }} - {{ $course->course_name }}
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card-saas p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-siakad-primary/5 rounded-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-siakad-900 dark:text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-siakad-50 dark:bg-siakad-800 flex items-center justify-center">
                            <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        {{ __('Create New Question') }}
                    </h3>
                    <a href="{{ route('lecturers.exam-questions.index', $class->id) }}" class="text-siakad-400 hover:text-siakad-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                <form action="{{ route('lecturers.exam-questions.store', $class->id) }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Question Type') }}</label>
                            <select name="question_type" id="question-type" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                                <option value="multiple_choice">{{ __('Multiple Choice') }}</option>
                                <option value="true_false">{{ __('True/False') }}</option>
                                <option value="short_answer">{{ __('Short Answer') }}</option>
                                <option value="essay">{{ __('Essay') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Question Text') }}</label>
                            <textarea name="question_text" id="question-text" rows="4" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('Enter your question here...') }}" required></textarea>
                        </div>

                        <div id="options-container" class="hidden">
                            <label class="block text-xs font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Options (for Multiple Choice)') }}</label>
                            <div id="options-list" class="space-y-3">
                                <div class="flex gap-2 items-center">
                                    <span class="font-bold text-siakad-500 w-6">A</span>
                                    <input type="text" name="options[]" class="input-saas flex-1 px-4 py-2 text-sm rounded-xl" placeholder="{{ __('Option A') }}">
                                </div>
                                <div class="flex gap-2 items-center">
                                    <span class="font-bold text-siakad-500 w-6">B</span>
                                    <input type="text" name="options[]" class="input-saas flex-1 px-4 py-2 text-sm rounded-xl" placeholder="{{ __('Option B') }}">
                                </div>
                                <div class="flex gap-2 items-center">
                                    <span class="font-bold text-siakad-500 w-6">C</span>
                                    <input type="text" name="options[]" class="input-saas flex-1 px-4 py-2 text-sm rounded-xl" placeholder="{{ __('Option C') }}">
                                </div>
                                <div class="flex gap-2 items-center">
                                    <span class="font-bold text-siakad-500 w-6">D</span>
                                    <input type="text" name="options[]" class="input-saas flex-1 px-4 py-2 text-sm rounded-xl" placeholder="{{ __('Option D') }}">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Correct Answer') }}</label>
                            <textarea name="correct_answer" id="correct-answer" rows="3" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('Enter the correct answer...') }}"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Points') }}</label>
                                <input type="number" name="points" step="0.01" min="0" class="input-saas w-full px-4 py-3 text-sm rounded-xl" value="1" required>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Order') }}</label>
                                <input type="number" name="order" min="0" class="input-saas w-full px-4 py-3 text-sm rounded-xl" value="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('lecturers.exam-questions.index', $class->id) }}" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-siakad-600/20">
                            {{ __('Save Question') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const questionTypeSelect = document.getElementById('question-type');
        const optionsContainer = document.getElementById('options-container');

        function toggleOptions() {
            if (questionTypeSelect.value === 'multiple_choice') {
                optionsContainer.classList.remove('hidden');
            } else {
                optionsContainer.classList.add('hidden');
            }
        }

        questionTypeSelect.addEventListener('change', toggleOptions);
        toggleOptions();
    </script>
</x-app-layout>