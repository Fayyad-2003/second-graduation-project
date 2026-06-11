<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-primary-900 via-primary-800 to-primary-900">
        <div class="text-center px-6">
            <h1 class="text-9xl font-bold text-yellow-500">503</h1>
            <h2 class="text-2xl font-semibold text-white mt-4">{{ __('Under Maintenance') }}</h2>
            <p class="text-primary-300 mt-2 max-w-md">
                {{ __('Siakad is undergoing scheduled maintenance. Please check back soon.') }}
            </p>
            <div class="mt-6 flex items-center justify-center gap-2 text-primary-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>{{ __('Processing...') }}</span>
            </div>
        </div>
    </div>
</x-guest-layout>