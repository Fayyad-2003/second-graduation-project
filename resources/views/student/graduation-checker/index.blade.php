<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>{{ __('Graduation Eligibility Checker') }}</span>
        </div>
    </x-slot>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto">

        <!-- Eligibility Banner Card -->
        <div class="card-saas p-6 md:p-8 mb-8 overflow-hidden relative">
            <!-- Decorative Background Glow -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-gradient-to-br {{ $eligible ? 'from-emerald-500/10 to-teal-500/10' : 'from-amber-500/10 to-orange-500/10' }} rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
                <!-- Large Animated Status Icon -->
                <div class="flex-shrink-0">
                    @if($eligible)
                    <div class="w-20 h-20 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20 transform hover:scale-105 transition-transform duration-300">
                        <!-- Graduation Cap Icon -->
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6M12 20h.01M5 12.5V17a3 3 0 003 3h8a3 3 0 003-3v-4.5" />
                        </svg>
                    </div>
                    @else
                    <div class="w-20 h-20 bg-amber-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20 transform hover:scale-105 transition-transform duration-300">
                        <!-- Warning/Progress Icon -->
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    @endif
                </div>

                <!-- Status Text & Details -->
                <div class="text-center md:text-left flex-1">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-full {{ $eligible ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                            {{ $eligible ? __('Eligible') : __('In Progress') }}
                        </span>
                        <span class="text-xs text-primary-secondary font-mono">{{ $student->student_number }}</span>
                    </div>

                    <h2 class="text-2xl font-bold text-primary-dark mb-2">
                        @if($eligible)
                        {{ __('Congratulations! You are eligible to graduate.') }}
                        @else
                        {{ __('You are on your way to graduation!') }}
                        @endif
                    </h2>

                    <p class="text-sm text-primary-secondary max-w-2xl">
                        @if($eligible)
                        {{ __('All required credits and subject classification limits have been fully satisfied. Please consult with your Academic Advisor for graduation registration procedures.') }}
                        @else
                        {{ __('To qualify for graduation, you need to complete the remaining credits and satisfy the classification requirements set by the Faculty.') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Academic Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- GPA Card -->
            <div class="card-saas p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-primary-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-primary-secondary uppercase font-semibold tracking-wider">{{ __('Cumulative GPA') }}</p>
                        <p class="text-2xl font-bold text-primary-dark mt-0.5">{{ number_format($cgpaData['gpa'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Credits Passed Card -->
            <div class="card-saas p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-primary-secondary uppercase font-semibold tracking-wider">{{ __('Total Passed Credits') }}</p>
                        <p class="text-2xl font-bold text-primary-dark mt-0.5">{{ $totalCreditsPassed }} / {{ $facultyRequiredCredits }} {{ __('Credits') }}</p>
                    </div>
                </div>
            </div>

            <!-- Remaining Credits Card -->
            <div class="card-saas p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-primary-secondary uppercase font-semibold tracking-wider">{{ __('Remaining Credits') }}</p>
                        <p class="text-2xl font-bold text-primary-dark mt-0.5">
                            @if($overallRemaining > 0)
                            {{ $overallRemaining }} {{ __('Credits') }}
                            @else
                            <span class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ __('Satisfied') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Credit Progress Card -->
        <div class="card-saas p-6 mb-8">
            <h3 class="text-lg font-bold text-primary-dark mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                {{ __('Overall Credits Requirement') }}
            </h3>

            @php
            $overallPercentage = $facultyRequiredCredits > 0 ? min(round(($totalCreditsPassed / $facultyRequiredCredits) * 100), 100) : 100;
            @endphp

            <div class="flex justify-between items-end mb-2">
                <span class="text-sm font-medium text-primary-secondary">{{ __('Progress to Target') }}</span>
                <span class="text-sm font-bold text-primary-dark">{{ $overallPercentage }}%</span>
            </div>

            <div class="w-full bg-primary-light/50 dark:bg-gray-700 rounded-full h-4 overflow-hidden mb-4">
                <div class="bg-gradient-to-r from-primary-primary to-primary-secondary h-full rounded-full transition-all duration-500" style="width: {{ $overallPercentage }}%"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-primary-light/50 text-center">
                <div>
                    <span class="text-xs text-primary-secondary block">{{ __('Faculty Requirements') }}</span>
                    <span class="font-bold text-primary-dark text-base mt-1 block">{{ $facultyRequiredCredits }} Credits</span>
                </div>
                <div>
                    <span class="text-xs text-primary-secondary block">{{ __('Credits Passed') }}</span>
                    <span class="font-bold text-emerald-600 dark:text-emerald-400 text-base mt-1 block">{{ $totalCreditsPassed }} Credits</span>
                </div>
                <div>
                    <span class="text-xs text-primary-secondary block">{{ __('Remaining Target') }}</span>
                    <span class="font-bold text-primary-dark text-base mt-1 block">{{ $overallRemaining }} Credits</span>
                </div>
                <div>
                    <span class="text-xs text-primary-secondary block">{{ __('Status') }}</span>
                    <span class="inline-block mt-1 font-bold text-xs px-2 py-0.5 rounded-full {{ $overallCreditsMet ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400' }}">
                        {{ $overallCreditsMet ? __('MET') : __('NOT YET MET') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Subject Classification Requirements Breakdown -->
        <div class="card-saas p-6">
            <h3 class="text-lg font-bold text-primary-dark mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                {{ __('Per-Classification Credit Requirements') }}
            </h3>

            @if(count($classificationsData) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($classificationsData as $classification)
                @php
                $isMet = $classification['status'] === 'MET';
                @endphp
                <div class="card-saas p-5 hover:border-primary-primary/30 transition-all duration-300 relative overflow-hidden group">
                    <!-- Background accent glow for satisfied classification -->
                    @if($isMet)
                    <div class="absolute right-0 bottom-0 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl pointer-events-none"></div>
                    @endif

                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-bold text-primary-dark text-base">{{ $classification['name'] }}</h4>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full {{ $isMet ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400' }}">
                            @if($isMet)
                            <!-- Checkmark -->
                            <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('Satisfied') }}
                            @else
                            {{ __('In Progress') }}
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between items-end mb-1 text-xs text-primary-secondary font-medium">
                        <span>{{ __('Completed') }}: <span class="font-semibold text-primary-dark">{{ $classification['completed'] }}</span> / {{ $classification['required'] }} Credits</span>
                        <span>{{ $classification['percentage'] }}%</span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-primary-light/50 dark:bg-gray-700 rounded-full h-2 overflow-hidden mb-2">
                        <div class="h-full rounded-full transition-all duration-500 {{ $isMet ? 'bg-emerald-500' : 'bg-primary-primary' }}" style="width: {{ $classification['percentage'] }}%"></div>
                    </div>

                    <!-- Additional details -->
                    <div class="flex justify-between items-center text-[10px] text-primary-secondary">
                        <div>
                            @if($classification['enrolled'] > 0)
                            <span class="text-indigo-600 dark:text-indigo-400 font-medium">
                                * {{ $classification['enrolled'] }} {{ __('Credits currently enrolled') }}
                            </span>
                            @endif
                        </div>
                        <div class="text-right">
                            @if($classification['remaining'] > 0)
                            <span class="font-semibold text-amber-700 dark:text-amber-400">
                                {{ $classification['remaining'] }} {{ __('Credits remaining') }}
                            </span>
                            @else
                            <span class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ __('Requirement satisfied') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-sm text-primary-secondary">{{ __('No classification requirements have been defined for your faculty.') }}</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>