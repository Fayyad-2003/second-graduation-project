<x-app-layout>
    <x-slot name="header">
        {{ __('Academic Skill Tree') }}
    </x-slot>

    <div class="py-12" x-data="skillTree()" x-init="fetchData()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Gamified Profile Header -->
            <div class="bg-gradient-to-br from-violet-600 to-indigo-800 rounded-3xl p-8 text-white shadow-2xl mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-400/20 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                    <div class="w-24 h-24 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-inner">
                        <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <div class="text-center md:text-right flex-1">
                        <h2 class="text-3xl font-black mb-2">{{ Auth::user()->name }}</h2>
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                            <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl border border-white/20">
                                <span class="text-xs opacity-70 block">{{ __('Academic Rank') }}</span>
                                <span class="text-lg font-bold" x-text="treeData ? treeData.stats.rank : '...'"></span>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl border border-white/20">
                                <span class="text-xs opacity-70 block">{{ __('Total Level') }}</span>
                                <span class="text-lg font-bold" x-text="treeData ? treeData.stats.total_level : '...'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="isLoading" class="flex flex-col items-center justify-center p-20 text-center">
                <div class="w-20 h-20 border-4 border-violet-500 border-t-transparent rounded-full animate-spin mb-6"></div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Analyzing your academic skills...') }}</h3>
                <p class="text-gray-500">{{ __('Converting records to skill tree...') }}</p>
            </div>

            <!-- Skill Tree View -->
            <div x-show="!isLoading && treeData" class="space-y-12 animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <template x-for="(branch, index) in treeData.branches" :key="index">
                        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden group hover:scale-[1.02] transition-all duration-300">
                            <!-- Branch Header -->
                            <div class="p-6 bg-gradient-to-r from-violet-500/10 to-indigo-500/10 border-b border-gray-100 dark:border-gray-700 relative">
                                <div class="flex items-center gap-4 relative z-10">
                                    <div class="w-12 h-12 rounded-2xl bg-violet-600 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <h3 class="text-lg font-black text-gray-800 dark:text-white" x-text="branch.name"></h3>
                                        <p class="text-xs text-gray-500" x-text="branch.skills.length + ' {{ __('skills') }}'"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Skills List -->
                            <div class="p-6 space-y-6">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 italic" x-text="branch.description"></p>

                                <div class="space-y-6 relative">
                                    <!-- Connecting Line -->
                                    <div class="absolute top-0 right-5 bottom-0 w-0.5 bg-gray-100 dark:bg-gray-700"></div>

                                    <template x-for="(skill, sIndex) in branch.skills" :key="sIndex">
                                        <div class="relative flex items-start gap-4 pr-10">
                                            <!-- Skill Node Icon -->
                                            <div class="absolute right-3 top-0 w-4 h-4 rounded-full border-4 z-10"
                                                :class="{
                                                    'bg-green-500 border-green-100 dark:border-green-900': skill.status === 'mastered',
                                                    'bg-blue-500 border-blue-100 dark:border-blue-900': skill.status === 'unlocked',
                                                    'bg-gray-300 border-gray-100 dark:border-gray-700': skill.status === 'locked'
                                                }">
                                            </div>

                                            <div class="flex-1 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
                                                <div class="flex justify-between items-start mb-2">
                                                    <span class="font-bold text-sm text-gray-800 dark:text-white" x-text="skill.name"></span>
                                                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full"
                                                        :class="{
                                                            'bg-green-100 text-green-700': skill.status === 'mastered',
                                                            'bg-blue-100 text-blue-700': skill.status === 'unlocked',
                                                            'bg-gray-100 text-gray-700': skill.status === 'locked'
                                                        }" x-text="skill.status === 'mastered' ? '{{ __('Mastered') }}' : (skill.status === 'unlocked' ? '{{ __('In Progress') }}' : '{{ __('Locked') }}')"></span>
                                                </div>

                                                <!-- Level Bar -->
                                                <div class="flex gap-1 mb-3">
                                                    <template x-for="i in 5">
                                                        <div class="h-1.5 flex-1 rounded-full"
                                                            :class="i <= skill.level ? 'bg-violet-500' : 'bg-gray-200 dark:bg-gray-700'"></div>
                                                    </template>
                                                </div>

                                                <p class="text-[11px] text-gray-500 leading-relaxed" x-text="skill.description"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.5s ease-out forwards;
        }
    </style>

    @push('scripts')
    <script>
        function skillTree() {
            return {
                isLoading: false,
                treeData: null,

                async fetchData() {
                    this.isLoading = true;
                    try {
                        const response = await fetch('{{ route("students.skill-tree.data") }}');
                        const data = await response.json();
                        if (data.success) {
                            this.treeData = data.tree;
                        } else {
                            console.error('Failed to load tree data');
                        }
                    } catch (error) {
                        console.error('Connection error');
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>