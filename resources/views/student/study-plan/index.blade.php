<x-app-layout>
    <x-slot name="header">
        <span class="md:hidden">{{ __('Study Plan') }}</span>
        <span class="hidden md:inline">{{ __('Study Plan Card') }}</span>
    </x-slot>

    <!-- Status Banner -->
    <div class="mb-8">
        <div class="bg-gradient-to-br from-siakad-primary to-siakad-600 rounded-2xl p-8 text-white shadow-soft-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-siakad-400/10 rounded-full -ml-10 -mb-10 blur-2xl"></div>

            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-8">
                <div class="space-y-1">
                    <p class="text-[10px] md:text-xs font-semibold opacity-80 uppercase tracking-widest">{{ __('Active Academic Year') }}</p>
                    <h3 class="text-2xl md:text-3xl font-bold tracking-tight">{{ \App\Models\AcademicYear::where('is_active', true)->first()?->year ?? '-' }} <span class="text-white/60 font-medium">/</span> {{ \App\Models\AcademicYear::where('is_active', true)->first()?->semester ?? '-' }}</h3>
                </div>
                <div class="flex items-center gap-10 border-t border-white/10 pt-6 md:border-0 md:pt-0">
                    <div class="text-center">
                        <p class="text-3xl font-black">{{ $studyPlan->details->sum(fn($d) => $d->academicClass->course->credits) }}</p>
                        <p class="text-[11px] font-medium opacity-70 uppercase tracking-wide">{{ __('Total Credits') }}</p>
                    </div>
                    <div class="text-center">
                        @php
                        $statusColors = [
                        'approved' => 'bg-emerald-400/20 text-emerald-50 border border-emerald-400/30',
                        'rejected' => 'bg-red-400/20 text-red-50 border border-red-400/30',
                        'pending' => 'bg-amber-400/20 text-amber-50 border border-amber-400/30',
                        'draft' => 'bg-white/10 text-white border border-white/20',
                        ];
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold backdrop-blur-md {{ $statusColors[$studyPlan->status] ?? 'bg-white/20' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-2 bg-current"></span>
                            {{ __(ucfirst($studyPlan->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            @if($studyPlan->status == 'draft')
            <div class="mt-8 pt-6 border-t border-white/10 relative">
                <form action="{{ route('students.study-plan.submit') }}" method="POST" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    @csrf
                    <p class="text-sm text-white/80 font-medium">{{ __('Once submitted, Study Plan cannot be changed.') }}</p>
                    <button type="submit" onclick="return confirm('{{ __('Are you sure you want to submit Study Plan?') }}')"
                        class="w-full sm:w-auto px-8 py-3 bg-white text-siakad-primary rounded-xl font-bold text-sm hover:bg-siakad-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        {{ __('Submit Study Plan') }}
                    </button>
                </form>
            </div>
            @elseif($studyPlan->status == 'rejected')
            <div class="mt-8 pt-6 border-t border-white/10 relative">
                <div class="flex flex-col sm:flex-row items-start justify-between gap-6">
                    <div class="bg-red-500/10 rounded-xl p-4 border border-red-500/20 flex-1 w-full">
                        <p class="text-sm font-bold text-red-100 mb-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ __('Study Plan Rejected') }}
                        </p>
                        <p class="text-sm text-red-50/80">{{ $studyPlan->notes ?? __('Please revise your Study Plan and resubmit.') }}</p>
                    </div>
                    <form action="{{ route('students.study-plan.revise') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" onclick="return confirm('{{ __('Change Study Plan status to draft for revision?') }}')"
                            class="w-full px-8 py-3 bg-white text-siakad-primary rounded-xl font-bold text-sm hover:bg-siakad-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap">
                            {{ __('Revise Study Plan') }}
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Classification Progress & Taken Classes -->
        <div class="{{ $studyPlan->status == 'draft' ? 'lg:col-span-2' : 'lg:col-span-3' }} space-y-6">

            <!-- Classification Progress -->
            <div class="card-saas p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-siakad-900 dark:text-white flex items-center gap-2 text-base">
                        <div class="p-2 bg-siakad-50 dark:bg-siakad-900/50 rounded-lg">
                            <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        {{ __('Graduation Progress') }}
                    </h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($classificationProgress as $progress)
                    <div class="group">
                        <div class="flex justify-between items-end mb-2.5">
                            <span class="text-xs font-bold text-siakad-700 dark:text-siakad-300 uppercase tracking-wider">{{ __($progress['name']) }}</span>
                            <span class="text-sm font-black {{ $progress['total'] > $progress['required'] && $progress['required'] > 0 ? 'text-red-500' : 'text-siakad-900 dark:text-white' }}">
                                {{ $progress['total'] }} <span class="text-[10px] text-siakad-400 font-medium">/</span> {{ $progress['required'] }}
                            </span>
                        </div>
                        <div class="w-full bg-siakad-50 dark:bg-siakad-900/30 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out shadow-sm {{ $progress['total'] > $progress['required'] && $progress['required'] > 0 ? 'bg-gradient-to-r from-red-500 to-rose-500' : 'bg-gradient-to-r from-siakad-500 to-siakad-600' }}" style="width: {{ $progress['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Taken Classes -->
            <div class="card-saas overflow-hidden">
                <div class="px-8 py-6 bg-siakad-50/50 dark:bg-siakad-900/20 border-b border-siakad-100/50 dark:border-siakad-800/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-siakad-900 dark:text-white text-base">{{ __('Courses Taken') }}</h3>
                        <p class="text-xs text-siakad-500 font-medium mt-0.5">{{ $studyPlan->details->count() }} {{ __('courses currently selected') }}</p>
                    </div>
                    <div class="px-3 py-1 bg-white dark:bg-siakad-800 rounded-lg shadow-sm border border-siakad-100 dark:border-siakad-700">
                        <span class="text-xs font-bold text-siakad-600 dark:text-siakad-400">{{ $studyPlan->details->sum(fn($d) => $d->academicClass->course->credits) }} {{ __('Credits') }}</span>
                    </div>
                </div>

                <div class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
                    @forelse($studyPlan->details as $detail)
                    <div class="p-6 flex items-center gap-6 hover:bg-siakad-50/50 dark:hover:bg-siakad-900/30 transition-all duration-200 group">
                        <div class="w-12 h-12 rounded-2xl bg-siakad-100/50 dark:bg-siakad-800/50 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                            <span class="text-siakad-600 dark:text-siakad-400 font-bold text-sm">{{ substr($detail->academicClass->course->course_name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-siakad-900 dark:text-white truncate group-hover:text-siakad-600 transition-colors">{{ $detail->academicClass->course->course_name }}</p>
                            <div class="flex items-center gap-3 mt-1">
                                <p class="text-xs text-siakad-500 font-medium">{{ $detail->academicClass->course->course_code }}</p>
                                <span class="w-1 h-1 rounded-full bg-siakad-200 dark:bg-siakad-700"></span>
                                <p class="text-xs text-siakad-500 font-medium truncate">{{ $detail->academicClass->lecturer->user->name }}</p>
                            </div>
                        </div>
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-sm font-black text-siakad-900 dark:text-white">{{ $detail->academicClass->course->credits }}</span>
                            <span class="text-[10px] text-siakad-400 font-bold uppercase tracking-tighter">{{ __('SKS') }}</span>
                        </div>
                        @if($studyPlan->status == 'draft')
                        <form action="{{ route('students.study-plan.destroy', $detail->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 text-siakad-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <div class="w-20 h-20 rounded-3xl bg-siakad-50 dark:bg-siakad-900/50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-siakad-200 dark:text-siakad-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-siakad-900 dark:text-white font-bold">{{ __('No courses taken yet') }}</p>
                        <p class="text-xs text-siakad-400 mt-1 font-medium">{{ __('Select from the available list to get started') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Available Classes -->
        @if($studyPlan->status == 'draft')
        <div class="lg:col-span-1">
            <div class="card-saas overflow-hidden sticky top-24">
                <div class="px-8 py-6 bg-siakad-primary dark:bg-siakad-900 border-b border-siakad-600/20">
                    <h3 class="font-bold text-white text-base">{{ __('Available Classes') }}</h3>
                    <p class="text-xs text-siakad-100/70 font-medium mt-0.5">{{ __('Select courses to add to your plan') }}</p>
                </div>

                <div class="max-h-[65vh] overflow-y-auto divide-y divide-siakad-50 dark:divide-siakad-800/50">
                    @forelse($availableClasses as $semester => $classList)
                    <div x-data="{ open: false }" class="bg-white dark:bg-siakad-800">
                        <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between hover:bg-siakad-50/50 dark:hover:bg-siakad-900/30 transition-all duration-200 cursor-pointer group">
                            <div class="text-left">
                                <h4 class="font-bold text-siakad-900 dark:text-white text-sm group-hover:text-siakad-600 transition-colors">{{ $semester }}</h4>
                                <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-wider">{{ $classList->count() }} {{ __('Classes') }}</p>
                            </div>
                            <div class="p-1.5 rounded-lg bg-siakad-50 dark:bg-siakad-900/50 group-hover:bg-siakad-100 dark:group-hover:bg-siakad-900 transition-colors">
                                <svg class="w-4 h-4 text-siakad-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>
                        <div x-show="open" x-collapse>
                            <div class="px-2 pb-2 space-y-1">
                                @foreach($classList as $k)
                                @php
                                $enrolled = $k->details->count();
                                $capacity = $k->capacity;
                                $pct = $capacity > 0 ? ($enrolled / $capacity) * 100 : 0;
                                $isFull = $enrolled >= $capacity;
                                $isWaiting = isset($waitlistedClassIds[$k->id]);

                                if ($isFull) {
                                $barColor = 'bg-red-500';
                                $pillColor = 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400';
                                $pillLabel = __('Full');
                                } elseif ($pct >= 75) {
                                $barColor = 'bg-amber-500';
                                $pillColor = 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400';
                                $pillLabel = $enrolled . '/' . $capacity;
                                } else {
                                $barColor = 'bg-siakad-500';
                                $pillColor = 'bg-siakad-50 text-siakad-600 dark:bg-siakad-900/30 dark:text-siakad-400';
                                $pillLabel = $enrolled . '/' . $capacity;
                                }
                                @endphp
                                <div class="p-4 rounded-xl hover:bg-siakad-50/50 dark:hover:bg-siakad-900/20 transition-all duration-200 {{ $isFull ? 'opacity-75' : '' }} group/item">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="min-w-0">
                                            <p class="font-bold text-siakad-900 dark:text-white text-sm truncate">{{ $k->course->course_name }}</p>
                                            <p class="text-[11px] text-siakad-500 font-medium mt-0.5 truncate">{{ $k->lecturer->user->name ?? '-' }}</p>
                                        </div>
                                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black {{ $pillColor }}">
                                            {{ $pillLabel }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="flex-1 h-1.5 bg-siakad-100 dark:bg-siakad-900 rounded-full overflow-hidden">
                                            <div class="h-full {{ $barColor }} rounded-full transition-all duration-500 shadow-sm" style="width: {{ min(100, $pct) }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-siakad-900 dark:text-white">{{ $k->course->credits }} SKS</span>
                                    </div>

                                    @if($isFull)
                                    <form action="{{ route('students.study-plan.waitlist', $k->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-2.5 px-4 flex items-center justify-center gap-2 rounded-xl font-bold text-xs transition-all duration-200 {{ $isWaiting ? 'bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-900/20 dark:border-amber-800' : 'bg-siakad-50 text-siakad-600 border border-siakad-100 dark:bg-siakad-900/30 dark:border-siakad-800' }} hover:shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                            {{ $isWaiting ? __('Unsubscribe') : __('Notify Me') }}
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('students.study-plan.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="academic_class_id" value="{{ $k->id }}">
                                        <button type="submit"
                                            class="w-full py-2.5 px-4 bg-siakad-600 text-white rounded-xl font-bold text-xs hover:bg-siakad-700 hover:shadow-lg hover:shadow-siakad-600/20 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                            </svg>
                                            {{ __('Add Course') }}
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 px-6 text-center">
                        <p class="text-siakad-400 text-xs font-medium">{{ __('No classes available for your program.') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>