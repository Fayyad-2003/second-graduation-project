<x-app-layout>
    <x-slot name="header">{{ __('Smart Subject Search') }}</x-slot>

    <div class="h-[calc(100vh-120px)] flex gap-6" x-data="subjectSearch()">

        <!-- Sidebar -->
        <div class="w-72 flex-shrink-0 flex flex-col gap-4">

            <!-- Subject List -->
            <div class="card-saas flex-1 overflow-hidden flex flex-col dark:bg-gray-800">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-primary-dark dark:text-white">{{ __('Select Subject') }}</p>
                            <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $classes->count() }} {{ __('subjects') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-3 space-y-1">
                    @forelse($classes as $class)
                    <button
                        @click="selectClass({{ $class->id }}, '{{ addslashes($class->course->course_name ?? 'N/A') }}', '{{ $class->course->course_code ?? '' }}')"
                        :class="selectedClassId == {{ $class->id }}
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20'
                            : 'text-primary-dark dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        class="w-full text-start px-4 py-3 rounded-xl text-sm font-medium transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-xs font-black transition-all"
                                :class="selectedClassId == {{ $class->id }} ? 'bg-white/20 text-white' : 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'">
                                {{ strtoupper(substr($class->course->course_code ?? 'C', 0, 2)) }}
                            </div>
                            <div class="min-w-0 text-start">
                                <p class="truncate font-semibold leading-tight">{{ $class->course->course_name ?? 'N/A' }}</p>
                                <p class="text-xs mt-0.5 transition-all"
                                    :class="selectedClassId == {{ $class->id }} ? 'text-blue-100' : 'text-primary-secondary dark:text-gray-500'">
                                    {{ $class->course->course_code ?? '' }} • {{ $class->course->credits ?? 0 }} {{ __('Credits') }}
                                </p>
                            </div>
                        </div>
                    </button>
                    @empty
                    <div class="py-8 text-center">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <p class="text-xs text-primary-secondary dark:text-gray-500">{{ __('No classes found.') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/20">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span class="text-sm font-bold">{{ __('AI Teaching Assistant') }}</span>
                </div>
                <p class="text-xs text-blue-100 leading-relaxed">
                    {{ __('Ask any question about the subject topics, and the AI will answer based on available lectures and files.') }}
                </p>
            </div>
        </div>

        <!-- Chat Panel -->
        <div class="flex-1 flex flex-col card-saas overflow-hidden dark:bg-gray-800">

            <!-- Chat Header -->
            <div class="flex-shrink-0 px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-primary-dark dark:text-white text-sm" x-text="selectedClassName || '{{ __('Select a subject to start') }}'"></h3>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <template x-if="selectedClassId">
                                <span class="flex items-center gap-1 text-xs text-green-600 dark:text-green-400 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    {{ __('Online - Smart Teaching Assistant') }}
                                </span>
                            </template>
                            <template x-if="!selectedClassId">
                                <span class="text-xs text-primary-secondary dark:text-gray-500">{{ __('Powered by AI') }}</span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Clear chat button -->
                <button x-show="messages.length > 0" @click="messages = []"
                    class="p-2 rounded-xl text-primary-secondary dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-red-500 transition-all"
                    title="{{ __('Clear chat') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6" x-ref="chatContainer">

                <!-- Empty state -->
                <template x-if="messages.length === 0 && !isLoading">
                    <div class="h-full flex flex-col items-center justify-center text-center py-16">
                        <div class="w-20 h-20 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center mb-5">
                            <svg class="w-10 h-10 text-blue-400 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2-2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-primary-dark dark:text-white mb-1">
                            <span x-text="selectedClassId ? '{{ __('Start by asking a question about the subject...') }}' : '{{ __('Select a subject to start') }}'"></span>
                        </h4>
                        <p class="text-sm text-primary-secondary dark:text-gray-400 max-w-xs">
                            {{ __('Ask any question about the subject topics, and the AI will answer based on available lectures and files.') }}
                        </p>
                    </div>
                </template>

                <!-- Messages loop -->
                <template x-for="(msg, index) in messages" :key="index">
                    <div>
                        <!-- User message -->
                        <template x-if="msg.role === 'user'">
                            <div class="flex justify-end">
                                <div class="max-w-[75%] bg-blue-600 text-white px-5 py-3 rounded-2xl rounded-br-md shadow-lg shadow-blue-500/10">
                                    <p class="text-sm leading-relaxed" x-text="msg.content"></p>
                                </div>
                            </div>
                        </template>

                        <!-- AI message -->
                        <template x-if="msg.role === 'assistant'">
                            <div class="flex gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-md shadow-blue-500/20 mt-0.5">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 max-w-[85%] bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-md px-5 py-4 shadow-sm">
                                    <div class="text-sm text-primary-dark dark:text-gray-200 leading-relaxed prose prose-sm dark:prose-invert max-w-none" x-html="formatContent(msg.content)"></div>

                                    <!-- Sources -->
                                    <template x-if="msg.sources && msg.sources.length">
                                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-1.5">
                                            <span class="text-xs text-primary-secondary dark:text-gray-500 font-medium me-1">{{ __('Sources') }}:</span>
                                            <template x-for="src in msg.sources" :key="src">
                                                <span class="px-2 py-0.5 text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full border border-blue-100 dark:border-blue-800" x-text="src"></span>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Related questions -->
                                    <template x-if="msg.related && msg.related.length">
                                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 space-y-1.5">
                                            <p class="text-xs font-semibold text-primary-secondary dark:text-gray-500">{{ __('Related Questions') }}:</p>
                                            <template x-for="rq in msg.related" :key="rq">
                                                <button @click="askRelated(rq)"
                                                    class="block w-full text-start text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline transition-colors" x-text="rq"></button>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Thinking indicator -->
                <div x-show="isLoading" class="flex gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-md shadow-blue-500/20 animate-pulse">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-md px-5 py-4 shadow-sm">
                        <div class="flex items-center gap-2 text-sm text-primary-secondary dark:text-gray-400 mb-2">
                            <span x-text="thinkingStatus"></span>
                        </div>
                        <div class="flex gap-1.5">
                            <span class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                            <span class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                            <span class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                        </div>
                        <div class="mt-2 h-1 w-40 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full animate-pulse" style="width:65%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="flex-shrink-0 px-6 pb-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="relative bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-600 overflow-hidden transition-all focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent">
                    <textarea
                        x-model="userInput"
                        x-ref="inputField"
                        @keydown.enter.prevent="if (!$event.shiftKey && !isLoading && userInput.trim()) askQuestion()"
                        @input="$el.style.height='52px'; $el.style.height = Math.min($el.scrollHeight, 140) + 'px'"
                        :disabled="!selectedClassId || isLoading"
                        placeholder="{{ __('Write your question here...') }}"
                        rows="1"
                        class="w-full px-5 py-3.5 pe-14 bg-transparent text-primary-dark dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm resize-none border-0 focus:ring-0 focus:outline-none disabled:opacity-50"
                        style="min-height:52px; max-height:140px;"></textarea>
                    <button
                        @click="askQuestion"
                        :disabled="!selectedClassId || isLoading || !userInput.trim()"
                        class="absolute end-3 bottom-2.5 p-2.5 rounded-xl bg-blue-600 text-white disabled:bg-gray-200 dark:disabled:bg-gray-700 disabled:text-gray-400 disabled:cursor-not-allowed hover:bg-blue-700 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center justify-center mt-2 text-[11px] text-gray-400 dark:text-gray-500 gap-1">
                    <span>{{ __('Enter to send') }}</span>
                    <span>•</span>
                    <span>{{ __('Shift+Enter for new line') }}</span>
                    <span>•</span>
                    <span>{{ __('Powered by AI') }}</span>
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
                thinkingStatus: '{{ __("Analyzing question...") }}',
                thinkingInterval: null,

                selectClass(id, name, code) {
                    if (this.selectedClassId === id) return;
                    this.selectedClassId = id;
                    this.selectedClassName = name;
                    this.messages = [];
                },

                startThinking() {
                    const statuses = [
                        '{{ __("Analyzing question...") }}',
                        '{{ __("Reading lecture files...") }}',
                        '{{ __("Checking curriculum...") }}',
                        '{{ __("Preparing answer...") }}',
                        '{{ __("Completing analysis...") }}'
                    ];
                    let idx = 0;
                    this.thinkingStatus = statuses[0];
                    this.thinkingInterval = setInterval(() => {
                        if (idx < statuses.length - 1) {
                            idx++;
                            this.thinkingStatus = statuses[idx];
                        }
                    }, 2500);
                },

                stopThinking() {
                    if (this.thinkingInterval) {
                        clearInterval(this.thinkingInterval);
                        this.thinkingInterval = null;
                    }
                },

                async askQuestion() {
                    if (!this.selectedClassId || !this.userInput.trim() || this.isLoading) return;

                    const question = this.userInput.trim();
                    this.userInput = '';
                    if (this.$refs.inputField) {
                        this.$refs.inputField.style.height = '52px';
                    }

                    this.messages.push({ role: 'user', content: question });
                    this.isLoading = true;
                    this.startThinking();
                    this.scrollToBottom();

                    try {
                        const response = await fetch('{{ route("students.subject-search.ask") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ class_id: this.selectedClassId, question }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.messages.push({
                                role: 'assistant',
                                content: data.answer || '',
                                sources: data.sources || [],
                                related: data.related_questions || [],
                            });
                        } else {
                            this.messages.push({
                                role: 'assistant',
                                content: '{{ __("Sorry, an error occurred while processing your question.") }}',
                                sources: [], related: [],
                            });
                        }
                    } catch (error) {
                        this.messages.push({
                            role: 'assistant',
                            content: '{{ __("Unable to connect to the smart server.") }}',
                            sources: [], related: [],
                        });
                    } finally {
                        this.stopThinking();
                        this.isLoading = false;
                        this.scrollToBottom();
                    }
                },

                askRelated(question) {
                    this.userInput = question;
                    this.askQuestion();
                },

                formatContent(text) {
                    if (!text) return '';
                    return text
                        .replace(/\*\*(.*?)\*\*/g, '<strong class="font-semibold text-primary-dark dark:text-white">$1</strong>')
                        .replace(/\*(.*?)\*/g, '<em class="italic">$1</em>')
                        .replace(/`(.*?)`/g, '<code class="bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded text-xs font-mono">$1</code>')
                        .replace(/\n\n/g, '</p><p class="mt-3">')
                        .replace(/\n/g, '<br>')
                        .replace(/^- (.*)/gm, '<span class="text-blue-500 me-1">•</span>$1')
                        .replace(/^(\d+)\. (.*)/gm, '<span class="font-semibold text-blue-500 me-1">$1.</span>$2');
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const c = this.$refs.chatContainer;
                        if (c) c.scrollTo({ top: c.scrollHeight, behavior: 'smooth' });
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
