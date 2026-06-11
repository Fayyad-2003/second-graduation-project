<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Document Applications') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('Review and process student requests for official documents.') }}
        </p>
    </div>

    <!-- Stats Summary (Optional but nice) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
        @php
        $stats = [
        ['label' => __('Pending'), 'count' => $applications->where('status', 'pending')->count(), 'color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => __('Processing'), 'count' => $applications->where('status', 'processing')->count(), 'color' => 'blue', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
        ['label' => __('Completed'), 'count' => $applications->where('status', 'completed')->count(), 'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => __('Total'), 'count' => $applications->count(), 'color' => 'siakad', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z']
        ];
        @endphp
        @foreach($stats as $stat)
        <div class="card-saas p-5 relative overflow-hidden group">
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-950/30 flex items-center justify-center text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400 shadow-sm border border-{{ $stat['color'] }}-100/50 dark:border-{{ $stat['color'] }}-900/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $stat['icon'] }}"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-black text-primary-900 dark:text-white leading-tight">{{ $stat['count'] }}</p>
                    <p class="text-[9px] text-primary-400 font-black uppercase tracking-widest mt-0.5">{{ $stat['label'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card-saas overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-primary-50/50 dark:bg-primary-900/30 border-b border-primary-100/50 dark:border-primary-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Student') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-start">{{ __('Document Type') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Status') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center">{{ __('Applied Date') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($applications as $app)
                    <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors group">
                        <td class="py-5 px-8 text-start">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center text-primary-600 dark:text-primary-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($app->student->user->name ?? '-', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-primary-900 dark:text-white truncate leading-tight">{{ $app->student->user->name ?? '-' }}</p>
                                    <p class="text-[10px] text-primary-400 font-mono font-bold uppercase tracking-wider mt-0.5">{{ $app->student->student_number }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <p class="text-xs font-bold text-primary-600 dark:text-primary-400 leading-tight">{{ $app->documentType->name }}</p>
                        </td>
                        <td class="py-5 px-6 text-center">
                            @php
                            $statusClasses = [
                            'pending' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 border-amber-100 dark:border-amber-900/50',
                            'processing' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 border-blue-100 dark:border-blue-900/50',
                            'completed' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/50',
                            'rejected' => 'bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400 border-red-100 dark:border-red-900/50',
                            ];
                            $statusClass = $statusClasses[$app->status] ?? 'bg-primary-50 text-primary-600 dark:bg-primary-950/30 dark:text-primary-400 border-primary-100 dark:border-primary-900/50';
                            @endphp
                            <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $statusClass }}">
                                {{ __($app->status) }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            <p class="text-xs font-bold text-primary-400 dark:text-primary-500 font-mono">{{ $app->created_at->format('d M Y') }}</p>
                            <p class="text-[9px] font-black text-primary-300 dark:text-primary-600 uppercase tracking-tighter">{{ $app->created_at->format('H:i') }}</p>
                        </td>
                        <td class="py-5 px-8 text-end">
                            <a href="{{ route('admin.document-application.show', $app) }}"
                                class="btn-ghost-saas px-4 py-2 rounded-xl text-[10px] font-black flex items-center gap-2 border border-primary-100 dark:border-primary-800 text-primary-primary hover:bg-primary-primary/10 transition-all inline-flex uppercase tracking-widest">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ __('Review') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center mb-4 text-primary-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-primary-400 font-bold uppercase tracking-widest text-[10px]">{{ __('No applications found') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden divide-y divide-primary-50 dark:divide-primary-800/50">
            @foreach($applications as $app)
            <div class="p-5 hover:bg-primary-50/30 dark:hover:bg-primary-900/20 transition-colors">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-primary-50 dark:bg-primary-900 flex items-center justify-center text-primary-600 dark:text-primary-400 text-sm font-black shadow-sm">
                            {{ strtoupper(substr($app->student->user->name ?? '-', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm font-black text-primary-900 dark:text-white truncate leading-tight">{{ $app->student->user->name ?? '-' }}</h4>
                            <p class="text-[10px] text-primary-400 font-mono font-bold mt-0.5 tracking-wider">{{ $app->student->student_number }}</p>
                        </div>
                    </div>
                    @php
                    $statusClass = $statusClasses[$app->status] ?? '';
                    @endphp
                    <span class="inline-flex px-2 py-0.5 text-[8px] font-black uppercase tracking-widest rounded-full border {{ $statusClass }}">
                        {{ __($app->status) }}
                    </span>
                </div>

                <div class="bg-primary-50/50 dark:bg-primary-900/30 p-3 rounded-2xl border border-primary-100/50 dark:border-primary-800/50 mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[8px] font-black text-primary-400 uppercase tracking-widest">{{ __('Document') }}</span>
                        <span class="text-xs font-bold text-primary-700 dark:text-primary-300">{{ $app->documentType->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[8px] font-black text-primary-400 uppercase tracking-widest">{{ __('Applied') }}</span>
                        <span class="text-[10px] font-bold text-primary-500 dark:text-primary-400 font-mono">{{ $app->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.document-application.show', $app) }}"
                    class="btn-ghost-saas w-full py-3 rounded-xl text-[10px] font-black flex items-center justify-center gap-2 border border-primary-100 dark:border-primary-800 text-primary-primary uppercase tracking-widest">
                    {{ __('Review Application') }}
                </a>
            </div>
            @endforeach
        </div>

        @if($applications->hasPages())
        <div class="px-8 py-6 border-t border-primary-50/50 dark:border-primary-800/50 bg-primary-50/20 dark:bg-primary-900/10">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</x-app-layout>