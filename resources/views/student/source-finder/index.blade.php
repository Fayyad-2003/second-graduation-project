<x-app-layout>
    <x-slot name="header">
        {{ __('Reliable Source Finder') }}
    </x-slot>

    <div class="py-12" x-data="sourceFinder()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Search Bar Section -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 mb-8">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="w-16 h-16 bg-amber-500/10 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-800 dark:text-white mb-4">{{ __('Search for Reliable Academic Sources') }}</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">{{ __('Enter your research topic and the AI will suggest the best research papers, books, and scientific sources.') }}</p>

                    <form @submit.prevent="search" class="relative">
                        <input
                            type="text"
                            x-model="searchQuery"
                            placeholder="{{ __('Example: Distributed Databases, AI in Education...') }}"
                            class="w-full pl-16 pr-6 py-5 bg-gray-50 dark:bg-gray-900 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 text-lg shadow-inner">
                        <button
                            type="submit"
                            :disabled="isLoading || searchQuery.length < 3"
                            class="absolute left-3 top-3 bottom-3 px-6 bg-amber-500 text-white rounded-xl font-bold shadow-lg shadow-amber-200 dark:shadow-none hover:bg-amber-600 transition-all disabled:opacity-50">
                            <span x-show="!isLoading">{{ __('Search') }}</span>
                            <svg x-show="isLoading" class="animate-spin h-5 w-5 mx-auto" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Results Section -->
            <div x-show="sources.length > 0" class="animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-gray-800 dark:text-white" x-text="'{{ __('Search results for:') }} ' + lastQuery"></h3>
                    <span class="text-sm text-gray-500" x-text="sources.length + ' {{ __('suggested sources') }}'"></span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="(source, index) in sources" :key="index">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-md hover:shadow-xl transition-all group">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                                    :class="{
                                        'bg-blue-500/10 text-blue-500': source.type.toLowerCase().includes('paper'),
                                        'bg-orange-500/10 text-orange-500': source.type.toLowerCase().includes('book'),
                                        'bg-emerald-500/10 text-emerald-500': !source.type.toLowerCase().includes('paper') && !source.type.toLowerCase().includes('book')
                                    }">
                                    <template x-if="source.type.toLowerCase().includes('paper')">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="source.type.toLowerCase().includes('book')">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </template>
                                    <template x-if="!source.type.toLowerCase().includes('paper') && !source.type.toLowerCase().includes('book')">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                        </svg>
                                    </template>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start gap-2 mb-1">
                                        <h4 class="font-bold text-gray-800 dark:text-white leading-tight" x-text="source.title"></h4>
                                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-500" x-text="source.year"></span>
                                    </div>
                                    <p class="text-xs text-amber-600 font-medium mb-3" x-text="source.author"></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4" x-text="source.description"></p>

                                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50 dark:border-gray-700">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full" :class="source.credibility === 'High' ? 'bg-green-500' : 'bg-orange-500'"></span>
                                            <span class="text-[11px] font-bold text-gray-400" x-text="source.credibility === 'High' ? '{{ __('High reliability') }}' : '{{ __('Medium reliability') }}'"></span>
                                        </div>
                                        <a :href="source.link || 'https://scholar.google.com/scholar?q=' + encodeURIComponent(source.title)" target="_blank" class="text-sm font-bold text-amber-500 hover:text-amber-600 flex items-center gap-1 group/link">
                                            {{ __('View Source') }}
                                            <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Empty/Intro State -->
            <div x-show="sources.length === 0 && !isLoading" class="flex flex-col items-center justify-center p-20 opacity-30">
                <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="mt-4 text-xl font-bold">{{ __('Start searching to get sources') }}</p>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function sourceFinder() {
            return {
                searchQuery: '',
                lastQuery: '',
                isLoading: false,
                sources: [],

                async search() {
                    if (this.searchQuery.length < 3 || this.isLoading) return;

                    this.isLoading = true;
                    this.lastQuery = this.searchQuery;
                    this.sources = [];

                    try {
                        const response = await fetch('{{ route("students.source-finder.search") }}?query=' + encodeURIComponent(this.searchQuery), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.sources = data.data.sources;
                        } else {
                            alert('{{ __("Search error occurred.") }}');
                        }
                    } catch (error) {
                        alert('{{ __("Unable to connect to the server.") }}');
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>