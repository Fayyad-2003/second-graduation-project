<x-app-layout>
    <x-slot name="header">{{ __('Semester Calendar') }}</x-slot>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-siakad-dark dark:text-white">
            {{ $activeYear ? $activeYear->year . ' - ' . ucfirst(__($activeYear->semester)) : __('Academic Calendar') }}
        </h2>
        <p class="text-sm text-siakad-secondary dark:text-gray-400">{{ __('Academic activities and important dates for this semester') }}</p>
    </div>

    @if(isset($message))
    <div class="mb-4 p-4 bg-amber-100 dark:bg-amber-900/30 border border-amber-400 text-amber-700 dark:text-amber-400 rounded-lg">
        {{ $message }}
    </div>
    @endif

    <div class="grid gap-6">
        @forelse($calendars->groupBy('week_number') as $week => $events)
        <div class="card-saas p-0 overflow-hidden dark:bg-gray-800 border-l-4 {{ $week ? 'border-siakad-primary' : 'border-gray-400' }}">
            <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 border-b border-siakad-light dark:border-gray-700 flex justify-between items-center">
                <span class="text-sm font-bold text-siakad-dark dark:text-white">
                    {{ $week ? __('Week') . ' ' . $week : __('General Events') }}
                </span>
            </div>
            <div class="p-4 space-y-4">
                @foreach($events as $event)
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-24">
                        @if($event->date)
                        <div class="text-xs font-bold text-siakad-dark dark:text-white uppercase">{{ $event->date->format('D') }}</div>
                        <div class="text-lg font-bold text-siakad-primary">{{ $event->date->format('d M') }}</div>
                        @else
                        <div class="text-xs font-medium text-siakad-secondary italic">{{ __('TBA') }}</div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="text-sm font-bold text-siakad-dark dark:text-white">{{ $event->title }}</h4>
                            @php
                                $typeColors = [
                                    'academic' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                    'holiday' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    'exam' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                    'other' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
                                ];
                                $color = $typeColors[$event->type] ?? $typeColors['other'];
                            @endphp
                            <span class="px-2 py-0.5 text-[10px] font-medium rounded-full {{ $color }}">
                                {{ ucfirst(__($event->type)) }}
                            </span>
                        </div>
                        @if($event->description)
                        <p class="text-xs text-siakad-secondary leading-relaxed">{{ $event->description }}</p>
                        @endif
                    </div>
                </div>
                @if(!$loop->last)
                <hr class="border-siakad-light dark:border-gray-700">
                @endif
                @endforeach
            </div>
        </div>
        @empty
        <div class="card-saas p-12 text-center text-siakad-secondary dark:bg-gray-800">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p>{{ __('No calendar entries available for this semester.') }}</p>
        </div>
        @endforelse
    </div>
</x-app-layout>
