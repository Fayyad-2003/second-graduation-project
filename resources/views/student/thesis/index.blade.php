<x-app-layout>
    <x-slot name="header">
        {{ __('Thesis / Final Project') }}
    </x-slot>

    <div class="mb-6">
        <p class="text-sm text-primary-secondary">{{ __('Manage your thesis and final project') }}</p>
    </div>

    @if(!$thesis)
    <!-- No Thesis Yet -->
    <div class="card-saas p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-primary-light/50 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-primary-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-primary-dark mb-2">{{ __('No thesis submissions yet') }}</h3>
        <p class="text-primary-secondary mb-6">{{ __('Submit your thesis title to start the supervision process') }}</p>
        <a href="{{ route('students.thesis.create') }}" class="btn-primary-saas px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ __('Submit Thesis Title') }}
        </a>
    </div>
    @else
    <!-- Thesis Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Status Card -->
        <div class="lg:col-span-2 card-saas p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-{{ $thesis->status_color }}-100 text-{{ $thesis->status_color }}-700">
                        {{ $thesis->status_label }}
                    </span>
                    <h2 class="text-lg font-bold text-primary-dark mt-3">{{ $thesis->title }}</h2>
                    @if($thesis->research_field)
                    <p class="text-sm text-primary-secondary mt-1">{{ __('Field') }}: {{ $thesis->research_field }}</p>
                    @endif
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-primary-secondary">{{ __('Progress') }}</span>
                    <span class="font-semibold text-primary-primary">{{ $thesis->progress_percent }}%</span>
                </div>
                <div class="h-3 bg-primary-light rounded-full overflow-hidden">
                    <div class="h-full bg-primary-primary rounded-full transition-all" style="width: {{ $thesis->progress_percent }}%"></div>
                </div>
            </div>

            <!-- Supervisor -->
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-primary-light/30">
                    <p class="text-xs text-primary-secondary mb-1">{{ __('Supervisor 1') }}</p>
                    <p class="font-medium text-primary-dark">{{ $thesis->supervisor1?->user->name ?? __('Not yet determined') }}</p>
                </div>
                <div class="p-4 rounded-xl bg-primary-light/30">
                    <p class="text-xs text-primary-secondary mb-1">{{ __('Supervisor 2') }}</p>
                    <p class="font-medium text-primary-dark">{{ $thesis->supervisor2?->user->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card-saas p-6">
            <h3 class="font-semibold text-primary-dark mb-4">{{ __('Timeline') }}</h3>
            @php
            // If status is 'completed' or progress is 100%, all milestones are completed
            $allCompleted = $thesis->status === 'completed' || $thesis->progress_percent >= 100;

            $milestones = [
            ['date' => $thesis->submission_date, 'label' => __('Application Submission')],
            ['date' => $thesis->title_approval_date, 'label' => __('Title Approved')],
            ['date' => $thesis->proposal_seminar_date, 'label' => __('Proposal Seminar')],
            ['date' => $thesis->result_seminar_date, 'label' => __('Results Seminar')],
            ['date' => $thesis->defense_date, 'label' => __('Final Exam')],
            ['date' => $thesis->completion_date, 'label' => __('Completed')],
            ];

            // Find the last completed milestone index
            $lastCompletedIndex = -1;
            foreach($milestones as $idx => $m) {
            if ($m['date'] != null) {
            $lastCompletedIndex = $idx;
            }
            }
            @endphp
            <div class="space-y-0">
                @foreach($milestones as $index => $m)
                @php
                // Mark as completed if has date OR if all milestones should be completed
                $isCompleted = $m['date'] != null || $allCompleted;
                // Current is the next step after the last completed one (only if not all completed)
                $isCurrent = !$allCompleted && !$m['date'] && $index == $lastCompletedIndex + 1;
                $isPending = !$isCompleted && !$isCurrent;
                @endphp
                <div class="flex items-start gap-3 relative">
                    <!-- Connector Line -->
                    @if($index < count($milestones) - 1)
                        <div class="absolute left-[7px] top-4 w-0.5 h-full {{ $isCompleted ? 'bg-[#234C6A]' : 'bg-slate-200' }}">
                </div>
                @endif

                <!-- Dot -->
                <div class="relative z-10 mt-1 flex-shrink-0">
                    @if($isCompleted)
                    <div class="w-4 h-4 rounded-full bg-[#234C6A] flex items-center justify-center">
                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    @elseif($isCurrent)
                    <div class="w-4 h-4 rounded-full bg-[#456882] animate-pulse"></div>
                    @else
                    <div class="w-4 h-4 rounded-full border-2 border-slate-300 bg-white"></div>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-1 pb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm {{ $isCompleted ? 'font-medium text-primary-dark' : ($isCurrent ? 'font-medium text-[#456882]' : 'text-slate-400') }}">{{ $m['label'] }}</span>
                        <span class="text-xs {{ $isCompleted ? 'text-primary-secondary' : 'text-slate-400' }}">{{ $m['date'] ? $m['date']->format('d/m/Y') : ($isCompleted ? '✓' : '-') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>

    <!-- Quick Stats & Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="card-saas p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#234C6A]/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-[#234C6A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-primary-dark">{{ $supervisionList->count() ?? 0 }}</p>
                <p class="text-sm text-primary-secondary">{{ __('Total Supervision') }}</p>
            </div>
        </div>
        <div class="card-saas p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-primary-dark">{{ $thesis->submission_date ? round($thesis->submission_date->diffInMonths(now())) : 0 }}</p>
                <p class="text-sm text-primary-secondary">{{ __('Duration (Months)') }}</p>
            </div>
        </div>
        <div class="card-saas p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-primary-dark">{{ $thesis->progress_percent }}%</p>
                <p class="text-sm text-primary-secondary">{{ __('Progress Completed') }}</p>
            </div>
        </div>
    </div>

    <!-- Supervision Section -->
    @if(in_array($thesis->status, ['supervision', 'penelitian', 'diterima', 'completed']))
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card-saas overflow-hidden">
            <div class="px-6 py-4 border-b border-primary-light flex items-center justify-between">
                <h3 class="font-semibold text-primary-dark">{{ __('Supervision History') }}</h3>
                <span class="text-sm text-primary-secondary">{{ $supervisionList->count() }} {{ __('records') }}</span>
            </div>
            @forelse($supervisionList as $supervision)
            <div class="p-5 border-b border-primary-light/50">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-primary-primary/10 flex items-center justify-center text-primary-primary font-bold text-sm">
                        {{ $loop->iteration }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="font-medium text-primary-dark">{{ $supervision->supervision_date->format('d M Y') }}</span>
                            <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $supervision->status_color }}-100 text-{{ $supervision->status_color }}-700">{{ $supervision->status_label }}</span>
                        </div>
                        <p class="text-sm text-primary-secondary mb-2">{{ $supervision->student_notes }}</p>
                        @if($supervision->lecturer_notes)
                        <div class="mt-3 p-3 rounded-lg bg-primary-light/30">
                            <p class="text-xs text-primary-secondary mb-1">Feedback {{ $supervision->lecturer->user->name }}:</p>
                            <p class="text-sm text-primary-dark">{{ $supervision->lecturer_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-primary-secondary">{{ __('No supervision notes yet') }}</div>
            @endforelse
        </div>

        <!-- Add Supervision Form -->
        <div class="lg:col-span-1">
            <div class="card-saas p-6 sticky top-24">
                <h3 class="font-semibold text-primary-dark mb-1">{{ __('Add Supervision Note') }}</h3>
                <p class="text-xs text-primary-secondary mb-6">{{ __('Record your supervision process') }}</p>

                <form action="{{ route('students.thesis.supervision.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Supervision Date') }}</label>
                        <input type="date" name="supervision_date" value="{{ date('Y-m-d') }}" class="input-saas w-full px-4 py-2.5 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Materials / Topics discussed') }}</label>
                        <textarea name="student_notes" rows="4" class="input-saas w-full px-4 py-2.5 text-sm" placeholder="{{ __('Enter your supervision progress...') }}" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Document File') }} (Optional)</label>
                        <input type="file" name="document_file" class="input-saas w-full px-4 py-2.5 text-sm">
                    </div>
                    <button type="submit" class="w-full btn-primary-saas py-2.5 rounded-lg text-sm font-medium">
                        {{ __('Add Record') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endif
</x-app-layout>