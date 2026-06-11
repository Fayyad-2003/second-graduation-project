<x-app-layout>
    <x-slot name="header">
        {{ __('Credit Requirements') }} - {{ $faculty->name }}
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.faculty.index') }}" class="btn-ghost-saas inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium dark:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('Back to List') }}
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="card-saas p-6 dark:bg-gray-800">
            <h3 class="text-lg font-semibold text-primary-dark dark:text-white mb-1">{{ __('Set Graduation Requirements') }}</h3>
            <p class="text-sm text-primary-secondary dark:text-gray-400 mb-6">
                {{ __('Define the minimum Credits (Credits) required for each subject classification for this faculty.') }}
            </p>

            <form action="{{ route('admin.faculty.update_requirements', $faculty) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Total Hours --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-primary-primary/5 dark:bg-blue-900/20 rounded-xl border-2 border-primary-primary/30 dark:border-blue-600/30">
                        <div>
                            <h4 class="font-bold text-primary-dark dark:text-white">{{ __('Total Required Hours') }}</h4>
                            <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Overall graduation credit requirement') }}</p>
                        </div>
                        <div class="w-full sm:w-36">
                            <div class="relative">
                                <input
                                    type="number"
                                    name="total_credits"
                                    value="{{ $faculty->total_credits }}"
                                    min="0"
                                    class="input-saas w-full pr-16 text-center font-bold text-primary-primary dark:text-blue-400"
                                    required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-xs font-semibold text-primary-secondary dark:text-gray-400">{{ __('Credits') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <p class="text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase tracking-wider mb-3">{{ __('Per Classification') }}</p>
                        <div class="space-y-3">
                            @foreach($classifications as $classification)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-primary-light/20 dark:bg-gray-700/30 rounded-xl border border-primary-light/50 dark:border-gray-700">
                                <div>
                                    <h4 class="font-bold text-primary-dark dark:text-white">{{ __($classification->name) }}</h4>
                                    <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Minimum Credits required') }}</p>
                                </div>
                                <div class="w-full sm:w-32">
                                    <div class="relative">
                                        <input
                                            type="number"
                                            name="requirements[{{ $classification->id }}]"
                                            value="{{ $requirements->get($classification->id)?->required_credits ?? 0 }}"
                                            min="0"
                                            class="input-saas w-full pr-12 text-center font-bold text-primary-primary dark:text-blue-400">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-xs font-semibold text-primary-secondary">{{ __('Credits') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-lg font-medium shadow-lg shadow-primary-primary/20">
                        {{ __('Save Requirements') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>