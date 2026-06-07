<x-app-layout>
    <x-slot name="header">
        {{ __('Smart Subject Search') }}
    </x-slot>

    <div class="py-12" x-data="subjectSearch()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Sidebar: Subject Selection -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-6 sticky top-24">
                        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-4 uppercase tracking-wider">{{ __('Select Subject') }}</h3>
                        <div class="space-y-2">
                            @foreach($classes as $class)
                            <button
                                @click="selectClass({{ $class->id }}, '{{ $class->course->course_name ?? 'N/A' }}')"
                                :class="selectedClassId == {{ $class->id }} ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                class="w-full text-right px-4 py-3 rounded-xl border text-sm font-medium transition-all">
                                {{ $class->course->course_name ?? 'N/A' }}
                            </button>
                            @endforeach
                        </div>

                        <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                            <p class="text-xs text-blue-700 dark:text-blue-400 leading-relaxed">
                                {{ __('Ask any question about the subject topics, and the AI will answer based on available lectures and files.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Main Content: Search/Chat Area -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 flex flex-col h-[650px]">

                        <!-- Header -->
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 z-10">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2-2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-800 dark:text-gray-100" x-text="selectedClassName || '{{ __('Select a subject to start') }}'"></h3>
                                    <p class="text-xs text-green-600 font-bold" x-show="selectedClassId">{{ __('Online - Smart Teaching Assistant') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages -->
                        <div id="chat-container" class="flex-1 overflow-y-auto p-6 space-y-6 scroll-smooth bg-slate-50/50 dark:bg-gray-900/50">
                            <template x-if="messages.length === 0">
                                <div class="h-full flex flex-col items-center justify-center text-center opacity-50">
                                    <div class="w-20 h-20 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500">{{ __('Start by asking a question about the subject...') }}</p>
                                </div>
                            </template>

                            <template x-for="(msg, index) in messages" :key="index">
                                <div :class="msg.role === 'user' ? 'flex flex-row-reverse' : 'flex flex-row'">
                                    <div :class="msg.role === 'user' ? 'bg-blue-600 text-white rounded-2xl rounded-tr-none' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-none'"
                                        class="max-w-[85%] p-4 shadow-sm animate-fade-in">
                                        <div class="prose prose-sm dark:prose-invert max-w-none prose-p:leading-relaxed" x-html="formatContent(msg.content)"></div>
                                    </div>
                                </div>
                            </template>

                            <!-- Loading Indicator -->
                            <div x-show="isLoading" class="flex flex-row animate-pulse">
                                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-none p-4 max-w-[85%] shadow-sm">
                                    <div class="flex gap-2">
                                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input Area -->
                        <div class="p-6 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                            <form @submit.prevent="askQuestion" class="relative">
                                <textarea
                                    x-model="userInput"
                                    @keydown.enter.prevent="if(!isLoading && userInput.trim()) askQuestion()"
                                    :disabled="!selectedClassId || isLoading"
                                    placeholder="{{ __('Write your question here...') }}"
                                    class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 pr-12 pl-4 py-4 text-sm focus:ring-blue-500 focus:border-blue-500 resize-none min-h-[60px] disabled:opacity-50"
                                    rows="1"></textarea>
                                <button
                                    type="submit"
                                    :disabled="!selectedClassId || isLoading || !userInput.trim()"
                                    class="absolute right-3 top-3 p-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 disabled:opacity-50 transition-all">
                                    <svg class="w-5 h-5 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function subjectSearch() {
            return {
                selectedClassId: null,
                selectedClassName: '',
                userInput: '',
                isLoading: false,
                messages: [],

                selectClass(id, name) {
                    this.selectedClassId = id;
                    this.selectedClassName = name;
                    this.messages = [];
                },

                async askQuestion() {
                    if (!this.selectedClassId || !this.userInput.trim() || this.isLoading) return;

                    const question = this.userInput.trim();
                    this.messages.push({
                        role: 'user',
                        content: question
                    });
                    this.userInput = '';
                    this.isLoading = true;

                    this.scrollToBottom();

                    try {
                        const response = await fetch('{{ route("students.subject-search.ask") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                class_id: this.selectedClassId,
                                question: question
                            }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.messages.push({
                                role: 'assistant',
                                content: data.answer
                            });
                        } else {
                            this.messages.push({
                                role: 'assistant',
                                content: '{{ __("Sorry, an error occurred while processing your question.") }}'
                            });
                        }
                    } catch (error) {
                        this.messages.push({
                            role: 'assistant',
                            content: '{{ __("Unable to connect to the smart server.") }}'
                        });
                    } finally {
                        this.isLoading = false;
                        this.scrollToBottom();
                    }
                },

                formatContent(text) {
                    if (!text) return '';
                    return text
                        .replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold">$1</strong>')
                        .replace(/\*(.*?)\*/g, '<em class="italic">$1</em>')
                        .replace(/\n/g, '<br>');
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const container = document.getElementById('chat-container');
                        container.scrollTop = container.scrollHeight;
                    }, 50);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>