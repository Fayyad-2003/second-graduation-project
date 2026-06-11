<x-app-layout>
    <x-slot name="header">
        <span class="md:hidden">{{ __('Study Plan') }}</span>
        <span class="hidden md:inline">{{ __('Study Plan Card') }}</span>
    </x-slot>

    <!-- Status Banner -->
    <div class="mb-8">
        <div class="bg-gradient-to-br from-primary-primary to-primary-600 rounded-2xl p-8 text-white shadow-soft-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-primary-400/10 rounded-full -ml-10 -mb-10 blur-2xl"></div>

            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-8">
                <div class="space-y-1">
                    <p class="text-[10px] md:text-xs font-semibold opacity-80 uppercase tracking-widest">{{ __('Active Academic Year') }}</p>
                    <h3 class="text-2xl md:text-3xl font-bold tracking-tight">{{ \App\Models\AcademicYear::where('is_active', true)->first()?->year ?? '-' }} <span class="text-white/60 font-medium">/</span> {{ \App\Models\AcademicYear::where('is_active', true)->first()?->semester ?? '-' }}</h3>
                </div>
                <div class="flex items-center gap-6 flex-wrap border-t border-white/10 pt-6 md:border-0 md:pt-0">
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
                    <button id="generateOverallPlanBtn" class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-xl text-sm font-bold transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        {{ __('AI Study Plan') }}
                    </button>
                </div>
            </div>

            @if($studyPlan->status == 'draft')
            <div class="mt-8 pt-6 border-t border-white/10 relative">
                <form action="{{ route('students.study-plan.submit') }}" method="POST" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    @csrf
                    <p class="text-sm text-white/80 font-medium">{{ __('Once submitted, Study Plan cannot be changed.') }}</p>
                    <button type="submit" onclick="return confirm('{{ __('Are you sure you want to submit Study Plan?') }}')"
                        class="w-full sm:w-auto px-8 py-3 bg-white text-primary-primary rounded-xl font-bold text-sm hover:bg-primary-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
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
                            class="w-full px-8 py-3 bg-white text-primary-primary rounded-xl font-bold text-sm hover:bg-primary-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap">
                            {{ __('Revise Study Plan') }}
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingOverallPlan" class="card-saas mb-8 p-8 hidden">
        <div class="flex items-center gap-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500"></div>
            <p class="text-sm font-bold text-primary-700 dark:text-primary-300">{{ __('Generating study plan...') }}</p>
        </div>
    </div>

    <!-- Generated Study Plan -->
    <div id="overallPlanSection" class="card-saas mb-8 p-8 hidden">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <h3 class="text-base font-bold text-primary-900 dark:text-white">{{ __('Your Study Plan') }}</h3>
        </div>
        <div id="overallPlanContent"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Classification Progress & Taken Classes -->
        <div class="{{ $studyPlan->status == 'draft' ? 'lg:col-span-2' : 'lg:col-span-3' }} space-y-6">

            <!-- Classification Progress -->
            <div class="card-saas p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-primary-900 dark:text-white flex items-center gap-2 text-base">
                        <div class="p-2 bg-primary-100 dark:bg-primary-800 rounded-lg">
                            <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012-2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012-2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        {{ __('Graduation Progress') }}
                    </h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($classificationProgress as $progress)
                    <div class="group">
                        <div class="flex justify-between items-end mb-2.5">
                            <span class="text-xs font-bold text-primary-800 dark:text-primary-200 uppercase tracking-wider">{{ __($progress['name']) }}</span>
                            <span class="text-sm font-black {{ $progress['total'] > $progress['required'] && $progress['required'] > 0 ? 'text-red-600' : 'text-primary-900 dark:text-white' }}">
                                {{ $progress['total'] }} <span class="text-[10px] text-primary-500 dark:text-primary-400 font-medium">/</span> {{ $progress['required'] }}
                            </span>
                        </div>
                        <div class="w-full bg-primary-200 dark:bg-primary-700 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out shadow-sm {{ $progress['total'] > $progress['required'] && $progress['required'] > 0 ? 'bg-gradient-to-r from-red-500 to-rose-600' : 'bg-gradient-to-r from-primary-500 to-primary-700' }}" style="width: {{ $progress['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Finished Subjects -->
            <div class="card-saas overflow-hidden mb-6">
                <div class="px-8 py-6 bg-green-50 dark:bg-green-900/20 border-b border-green-100 dark:border-green-800/30 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-green-800 dark:text-green-300 text-base">{{ __('Finished Courses') }}</h3>
                        <p class="text-xs text-green-600 dark:text-green-400 font-medium mt-0.5">{{ $finishedSubjects->flatten()->count() }} {{ __('courses completed') }}</p>
                    </div>
                    <div class="px-3 py-1 bg-white dark:bg-green-900/30 rounded-lg shadow-sm border border-green-200 dark:border-green-700">
                        <span class="text-xs font-bold text-green-700 dark:text-green-300">{{ $finishedSubjects->flatten()->sum(fn($grade) => $grade->academicClass->course->credits) }} {{ __('Credits') }}</span>
                    </div>
                </div>

                <div class="divide-y divide-green-50 dark:divide-green-800/20 max-h-[400px] overflow-y-auto">
                    @forelse($finishedSubjects as $semester => $grades)
                    <div class="bg-green-50/30 dark:bg-green-900/10">
                        <button class="w-full px-8 py-4 flex items-center justify-between hover:bg-green-100/50 dark:hover:bg-green-900/20 transition-all duration-200 cursor-pointer group" onclick="this.nextElementSibling.classList.toggle('hidden')">
                            <div class="text-left">
                                <h4 class="font-bold text-green-800 dark:text-green-300 text-sm group-hover:text-green-700 transition-colors">{{ $semester }}</h4>
                                <p class="text-[10px] text-green-600 dark:text-green-400 font-bold uppercase tracking-wider">{{ $grades->count() }} {{ __('Courses') }}</p>
                            </div>
                            <div class="p-1.5 rounded-lg bg-green-100 dark:bg-green-900/30 group-hover:bg-green-200 dark:group-hover:bg-green-900/40 transition-colors">
                                <svg class="w-4 h-4 text-green-700 dark:text-green-300 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>
                        <div class="hidden">
                            <div class="divide-y divide-green-100 dark:divide-green-800/20">
                                @foreach($grades as $grade)
                                <div class="p-6 flex items-center gap-6 hover:bg-green-50/50 dark:hover:bg-green-900/20 transition-all duration-200 group">
                                    <div class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                        <span class="text-green-700 dark:text-green-300 font-bold text-sm">{{ substr($grade->academicClass->course->course_name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-green-900 dark:text-green-300 truncate">{{ $grade->academicClass->course->course_name }}</p>
                                        <div class="flex items-center gap-3 mt-1">
                                            <p class="text-xs text-green-700 dark:text-green-400 font-medium">{{ $grade->academicClass->course->course_code }}</p>
                                            <span class="w-1 h-1 rounded-full bg-green-300 dark:bg-green-700"></span>
                                            <p class="text-xs text-green-700 dark:text-green-400 font-medium truncate">{{ $grade->academicClass->lecturer->user->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $grade->letter_grade >= 'B' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' }}">{{ $grade->letter_grade }}</span>
                                        <span class="text-sm font-black text-green-800 dark:text-green-200 mt-1">{{ $grade->academicClass->course->credits }} SKS</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <div class="w-20 h-20 rounded-3xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-green-300 dark:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-green-900 dark:text-green-300 font-bold">{{ __('No courses completed yet') }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1 font-medium">{{ __('Keep studying to complete your courses') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Taken Classes -->
            <div class="card-saas overflow-hidden">
                <div class="px-8 py-6 bg-primary-50 dark:bg-primary-900/20 border-b border-primary-200 dark:border-primary-700 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-primary-900 dark:text-white text-base">{{ __('Courses Taken') }}</h3>
                        <p class="text-xs text-primary-600 dark:text-primary-400 font-medium mt-0.5">{{ $studyPlan->details->count() }} {{ __('courses currently selected') }}</p>
                    </div>
                    <div class="px-3 py-1 bg-white dark:bg-primary-800 rounded-lg shadow-sm border border-primary-300 dark:border-primary-600">
                        <span class="text-xs font-bold text-primary-700 dark:text-primary-300">{{ $studyPlan->details->sum(fn($d) => $d->academicClass->course->credits) }} {{ __('Credits') }}</span>
                    </div>
                </div>

                <div class="divide-y divide-primary-100 dark:divide-primary-700">
                    @forelse($studyPlan->details as $detail)
                    <div class="p-6 flex items-center gap-6 hover:bg-primary-50/50 dark:hover:bg-primary-900/30 transition-all duration-200 group">
                        <div class="w-12 h-12 rounded-2xl bg-primary-100 dark:bg-primary-800 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                            <span class="text-primary-700 dark:text-primary-300 font-bold text-sm">{{ substr($detail->academicClass->course->course_name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-primary-900 dark:text-white truncate group-hover:text-primary-600 transition-colors">{{ $detail->academicClass->course->course_name }}</p>
                            <div class="flex items-center gap-3 mt-1">
                                <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">{{ $detail->academicClass->course->course_code }}</p>
                                <span class="w-1 h-1 rounded-full bg-primary-300 dark:bg-primary-600"></span>
                                <p class="text-xs text-primary-600 dark:text-primary-400 font-medium truncate">{{ $detail->academicClass->lecturer->user->name }}</p>
                            </div>
                        </div>
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-sm font-black text-primary-900 dark:text-white">{{ $detail->academicClass->course->credits }}</span>
                            <span class="text-[10px] text-primary-500 font-bold uppercase tracking-tighter">{{ __('SKS') }}</span>
                        </div>
                        @if($studyPlan->status == 'draft')
                        <form action="{{ route('students.study-plan.destroy', $detail->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 text-primary-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <div class="w-20 h-20 rounded-3xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-primary-300 dark:text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-primary-900 dark:text-white font-bold">{{ __('No courses taken yet') }}</p>
                        <p class="text-xs text-primary-500 mt-1 font-medium">{{ __('Select from the available list to get started') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Available Classes -->
        @if($studyPlan->status == 'draft')
        <div class="lg:col-span-1">
            <div class="card-saas overflow-hidden sticky top-24">
                <div class="px-8 py-6 bg-primary-primary dark:bg-primary-900 border-b border-primary-400/30 dark:border-primary-700">
                    <h3 class="font-bold text-white text-base">{{ __('Available Classes') }}</h3>
                    <p class="text-xs text-primary-100 font-medium mt-0.5">{{ __('Select courses to add to your plan') }}</p>
                </div>

                <div class="max-h-[65vh] overflow-y-auto divide-y divide-primary-100 dark:divide-primary-700">
                    @forelse($availableClasses as $semester => $classList)
                    <div x-data="{ open: false }" class="bg-white dark:bg-primary-800">
                        <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-all duration-200 cursor-pointer group">
                            <div class="text-left">
                                <h4 class="font-bold text-primary-900 dark:text-white text-sm group-hover:text-primary-700 transition-colors">{{ $semester }}</h4>
                                <p class="text-[10px] text-primary-600 dark:text-primary-400 font-bold uppercase tracking-wider">{{ $classList->count() }} {{ __('Classes') }}</p>
                            </div>
                            <div class="p-1.5 rounded-lg bg-primary-100 dark:bg-primary-700 group-hover:bg-primary-200 dark:group-hover:bg-primary-600 transition-colors">
                                <svg class="w-4 h-4 text-primary-700 dark:text-primary-300 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                $barColor = 'bg-red-600';
                                $pillColor = 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 border border-red-200 dark:border-red-700';
                                $pillLabel = __('Full');
                                } elseif ($pct >= 75) {
                                $barColor = 'bg-amber-500';
                                $pillColor = 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-200 dark:border-amber-700';
                                $pillLabel = $enrolled . '/' . $capacity;
                                } else {
                                $barColor = 'bg-primary-500';
                                $pillColor = 'bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300 border border-primary-200 dark:border-primary-700';
                                $pillLabel = $enrolled . '/' . $capacity;
                                }
                                @endphp
                                <div class="p-4 rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-all duration-200 {{ $isFull ? 'opacity-75' : '' }} group/item">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="min-w-0">
                                            <p class="font-bold text-primary-900 dark:text-white text-sm truncate">{{ $k->course->course_name }}</p>
                                            <p class="text-[11px] text-primary-600 dark:text-primary-400 font-medium mt-0.5 truncate">{{ $k->lecturer->user->name ?? '-' }}</p>
                                        </div>
                                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black {{ $pillColor }}">
                                            {{ $pillLabel }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="flex-1 h-1.5 bg-primary-200 dark:bg-primary-700 rounded-full overflow-hidden">
                                            <div class="h-full {{ $barColor }} rounded-full transition-all duration-500 shadow-sm" style="width: {{ min(100, $pct) }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-primary-800 dark:text-primary-200">{{ $k->course->credits }} SKS</span>
                                    </div>

                                    @if($isFull)
                                    <form action="{{ route('students.study-plan.waitlist', $k->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-2.5 px-4 flex items-center justify-center gap-2 rounded-xl font-bold text-xs transition-all duration-200 {{ $isWaiting ? 'bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-900/20 dark:border-amber-800' : 'bg-primary-50 text-primary-600 border border-primary-100 dark:bg-primary-900/30 dark:border-primary-800' }} hover:shadow-sm">
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
                                            class="w-full py-2.5 px-4 bg-primary-600 text-white rounded-xl font-bold text-xs hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-600/20 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
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
                        <p class="text-primary-600 dark:text-primary-400 text-xs font-medium">{{ __('No classes available for your program.') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const generateBtn = document.getElementById('generateOverallPlanBtn');
            const loadingSection = document.getElementById('loadingOverallPlan');
            const planSection = document.getElementById('overallPlanSection');
            const planContent = document.getElementById('overallPlanContent');

            generateBtn.addEventListener('click', async function() {
                // Show loading state
                loadingSection.classList.remove('hidden');
                planSection.classList.add('hidden');
                generateBtn.disabled = true;
                generateBtn.classList.add('opacity-50', 'cursor-not-allowed');

                try {
                    const response = await fetch("{{ route('students.study-plan-ai.generate-overall') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        renderOverallPlan(data);
                        planSection.classList.remove('hidden');
                    } else {
                        // Show error
                        planContent.innerHTML = `
                            <div class="bg-red-50 dark:bg-red-900/30 p-4 rounded-xl border border-red-100 dark:border-red-800/50">
                                <p class="text-sm font-bold text-red-700 dark:text-red-400">${data.message || '{{ __('Failed to generate study plan.') }}'}</p>
                            </div>
                        `;
                        planSection.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    planContent.innerHTML = `
                        <div class="bg-red-50 dark:bg-red-900/30 p-4 rounded-xl border border-red-100 dark:border-red-800/50">
                            <p class="text-sm font-bold text-red-700 dark:text-red-400">{{ __('An error occurred while generating study plan.') }}</p>
                        </div>
                    `;
                    planSection.classList.remove('hidden');
                } finally {
                    // Hide loading
                    loadingSection.classList.add('hidden');
                    generateBtn.disabled = false;
                    generateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });

            function renderOverallPlan(data) {
                let html = '';

                if (data.summary) {
                    html += `
                        <div class="mb-6 p-4 bg-purple-50 dark:bg-purple-900/30 rounded-xl border border-purple-100 dark:border-purple-800/50">
                            <p class="text-sm font-semibold text-purple-700 dark:text-purple-400">${data.summary}</p>
                        </div>
                    `;
                }

                if (data.subject_specific_tips && data.subject_specific_tips.length > 0) {
                    html += `
                        <h4 class="text-sm font-bold text-primary-900 dark:text-white mb-4">{{ __('Subject-Specific Tips') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    `;
                    data.subject_specific_tips.forEach(subject => {
                        html += `
                            <div class="p-4 bg-primary-50 dark:bg-primary-900/30 rounded-xl border border-primary-100 dark:border-primary-800/50">
                                <h5 class="text-sm font-bold text-primary-900 dark:text-white mb-2">${subject.subject_name}</h5>
                                <ul class="text-sm text-primary-600 dark:text-slate-400 list-disc list-inside space-y-1">
                                    ${subject.tips.map(tip => `<li>${tip}</li>`).join('')}
                                </ul>
                            </div>
                        `;
                    });
                    html += `</div>`;
                }

                if (data.weekly_schedule && data.weekly_schedule.length > 0) {
                    html += `
                        <h4 class="text-sm font-bold text-primary-900 dark:text-white mb-4">{{ __('Weekly Schedule') }}</h4>
                        <div class="space-y-4 mb-6">
                    `;
                    data.weekly_schedule.forEach(week => {
                        html += `
                            <div class="p-4 bg-primary-50 dark:bg-primary-900/30 rounded-xl border border-primary-100 dark:border-primary-800/50">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="w-10 h-10 rounded-full bg-primary-500 text-white flex items-center justify-center font-bold text-sm">${week.week_number}</span>
                                    <h5 class="text-sm font-bold text-primary-900 dark:text-white">${week.focus}</h5>
                                </div>
                                <ul class="text-sm text-primary-600 dark:text-slate-400 list-disc list-inside space-y-1 ml-13">
                                    ${week.key_tasks.map(task => `<li>${task}</li>`).join('')}
                                </ul>
                            </div>
                        `;
                    });
                    html += `</div>`;
                }

                if (data.general_tips && data.general_tips.length > 0) {
                    html += `
                        <h4 class="text-sm font-bold text-primary-900 dark:text-white mb-4">{{ __('General Study Tips') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    `;
                    data.general_tips.forEach(tip => {
                        html += `
                            <div class="p-4 bg-primary-50 dark:bg-primary-900/30 rounded-xl border border-primary-100 dark:border-primary-800/50">
                                <p class="text-sm text-primary-600 dark:text-slate-400">${tip}</p>
                            </div>
                        `;
                    });
                    html += `</div>`;
                }

                if (data.retake_advice) {
                    html += `
                        <h4 class="text-sm font-bold text-primary-900 dark:text-white mb-4">{{ __('Advice for Retaking Failed Subjects') }}</h4>
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/30 rounded-xl border border-amber-100 dark:border-amber-800/50">
                            <p class="text-sm text-amber-700 dark:text-amber-400">${data.retake_advice}</p>
                        </div>
                    `;
                }

                planContent.innerHTML = html;
            }
        });
    </script>
</x-app-layout>