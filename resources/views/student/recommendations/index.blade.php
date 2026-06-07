<x-app-layout>
    <x-slot name="header">
        {{ __('Course Recommendations') }}
    </x-slot>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-siakad-dark dark:text-white">{{ __('Smart Course Suggestions') }}</h2>
        <p class="text-sm text-siakad-secondary dark:text-gray-400">
            {{ __('Based on your academic performance, here are some elective courses that match your strengths.') }}
        </p>
    </div>

    @if($recommendations->isEmpty())
        <div class="card-saas p-8 text-center dark:bg-gray-800">
            <div class="w-16 h-16 bg-siakad-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-2">{{ __('No Recommendations Yet') }}</h3>
            <p class="text-siakad-secondary dark:text-gray-400 max-w-md mx-auto">
                {{ __('We need more grade data to analyze your strengths. Once you complete more subjects, we can suggest electives that suit you.') }}
            </p>
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recommendations as $course)
                <div class="card-saas overflow-hidden dark:bg-gray-800 flex flex-col h-full hover:shadow-lg transition-shadow">
                    <div class="p-5 flex-1">
                        <div class="flex items-start justify-between mb-3">
                            <span class="px-2 py-1 bg-siakad-primary/10 text-siakad-primary text-[10px] font-bold uppercase tracking-wider rounded">
                                {{ $course->course_code }}
                            </span>
                            <div class="flex items-center gap-1 text-amber-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="text-sm font-bold">{{ number_format($course->match_score, 1) }}</span>
                            </div>
                        </div>

                        <h3 class="font-bold text-siakad-dark dark:text-white mb-2 leading-tight">
                            {{ $course->course_name }}
                        </h3>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2 text-xs text-siakad-secondary dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span>{{ $course->credits }} {{ __('Credits') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-siakad-secondary dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                <span>{{ $course->classification->name ?? 'Unclassified' }}</span>
                            </div>
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-[10px] text-siakad-secondary dark:text-gray-500 uppercase tracking-widest font-semibold mb-1">
                                {{ __('Why this suggestion?') }}
                            </p>
                            <p class="text-xs text-siakad-dark dark:text-gray-300">
                                {{ __('You excel in') }} <span class="font-semibold text-siakad-primary">{{ $course->strength_category }}</span> {{ __('subjects.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
