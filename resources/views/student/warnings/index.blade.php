<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('My Warnings') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('View all warnings sent to you') }}
        </p>
    </div>

    <!-- Warnings List -->
    <div class="space-y-6">
        @forelse($warnings as $warning)
        <div class="card-saas p-6 relative overflow-hidden group transition-all duration-300 {{ !$warning->is_read ? 'border-l-4 border-amber-500' : '' }}">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl {{ $warning->is_read ? 'bg-gray-100 dark:bg-gray-800' : 'bg-amber-100 dark:bg-amber-900/30' }} flex items-center justify-center">
                        <svg class="w-6 h-6 {{ $warning->is_read ? 'text-gray-400' : 'text-amber-600 dark:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <p class="text-sm font-black text-primary-900 dark:text-white">{{ __('From') }}: {{ $warning->creator?->name ?? __('System') }}</p>
                            @if(!$warning->is_read)
                            <span class="px-2.5 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-full text-[10px] font-black uppercase tracking-widest">
                                {{ __('New') }}
                            </span>
                            @endif
                        </div>
                        <p class="text-xs text-primary-500 dark:text-gray-400 font-bold">{{ $warning->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                @if(!$warning->is_read)
                <form action="{{ route('students.warnings.mark-as-read', $warning) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-xl text-xs font-bold hover:bg-primary-200 dark:hover:bg-primary-800 transition-all">
                        {{ __('Mark as Read') }}
                    </button>
                </form>
                @endif
            </div>
            <div class="bg-primary-50 dark:bg-primary-900/20 rounded-xl p-4">
                <p class="text-sm text-primary-800 dark:text-gray-200 leading-relaxed">{{ $warning->message }}</p>
            </div>
        </div>
        @empty
        <div class="card-saas p-12 text-center">
            <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-black text-primary-900 dark:text-white mb-2">{{ __('No Warnings') }}</h3>
            <p class="text-primary-500 dark:text-gray-400">{{ __('Great! You have no warnings from the administration.') }}</p>
        </div>
        @endforelse
    </div>
</x-app-layout>