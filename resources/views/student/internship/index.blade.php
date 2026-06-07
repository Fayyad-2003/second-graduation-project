<x-app-layout>
    <x-slot name="header">{{ __('Internship') }}</x-slot>

    <div class="mb-6">
        <p class="text-sm text-siakad-secondary">{{ __('Manage your practical work and logbook') }}</p>
    </div>

    @if(!$internship)
    <div class="card-saas p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-siakad-light/50 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-siakad-dark mb-2">{{ __('No KP application submitted yet') }}</h3>
        <p class="text-siakad-secondary mb-6">{{ __('Apply for practical work to start') }}</p>
        <a href="{{ route('students.internship.create') }}" class="btn-primary-saas px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ __('Apply for KP') }}
        </a>
    </div>
    @else
    <!-- Main Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 card-saas p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-{{ $internship->status_color }}-100 text-{{ $internship->status_color }}-700">{{ $internship->status_label }}</span>
                    <h2 class="text-lg font-bold text-siakad-dark mt-3">{{ $internship->company_name }}</h2>
                    <p class="text-sm text-siakad-secondary">{{ $internship->business_field }}</p>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-siakad-secondary">{{ __('Progress') }}</span>
                    <span class="font-semibold text-siakad-primary">{{ $internship->progress_percent }}%</span>
                </div>
                <div class="h-3 bg-siakad-light rounded-full overflow-hidden">
                    <div class="h-full bg-siakad-primary rounded-full transition-all" style="width: {{ $internship->progress_percent }}%"></div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6 text-sm">
                <div class="p-4 rounded-xl bg-siakad-light/30">
                    <p class="text-xs text-siakad-secondary mb-1">{{ __('Period') }}</p>
                    <p class="font-medium text-siakad-dark">{{ $internship->start_date->format('d M') }} - {{ $internship->completion_date->format('d M Y') }}</p>
                    <p class="text-xs text-siakad-secondary">({{ $internship->duration }})</p>
                </div>
                <div class="p-4 rounded-xl bg-siakad-light/30">
                    <p class="text-xs text-siakad-secondary mb-1">{{ __('Campus Advisor') }}</p>
                    <p class="font-medium text-siakad-dark">{{ $internship->supervisor?->user->name ?? __('Not yet specified') }}</p>
                </div>
                <div class="p-4 rounded-xl bg-siakad-light/30">
                    <p class="text-xs text-siakad-secondary mb-1">{{ __('Field Supervisor') }}</p>
                    <p class="font-medium text-siakad-dark">{{ $internship->field_supervisor_name ?? '-' }}</p>
                </div>
                <div class="p-4 rounded-xl bg-siakad-light/30">
                    <p class="text-xs text-siakad-secondary mb-1">{{ __('Address') }}</p>
                    <p class="font-medium text-siakad-dark text-xs">{{ $internship->company_address ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card-saas p-6">
            <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Timeline') }}</h3>
            @php
            // Determine completed steps based on status
            $allCompleted = $internship->status === 'completed' || $internship->progress_percent >= 100;
            $isApproved = in_array($internship->status, ['approved', 'ongoing', 'report_drafting', 'completed']);
            $isOngoing = in_array($internship->status, ['ongoing', 'report_drafting', 'completed']);
            $isReportPhase = in_array($internship->status, ['report_drafting', 'completed']);

            $milestones = [
            ['completed' => true, 'label' => __('Submit Application'), 'date' => $internship->created_at],
            ['completed' => $isApproved, 'label' => __('Approved'), 'date' => $internship->approved_date ?? null],
            ['completed' => $isOngoing, 'label' => __('Start Practical Work'), 'date' => $internship->start_date],
            ['completed' => $isReportPhase, 'label' => __('Prepare Report'), 'date' => null],
            ['completed' => $allCompleted, 'label' => __('Completed'), 'date' => $internship->completion_date],
            ];

            // Find current step
            $currentStep = -1;
            foreach($milestones as $idx => $m) {
            if (!$m['completed'] && $currentStep === -1) {
            $currentStep = $idx;
            break;
            }
            }
            @endphp
            <div class="space-y-0">
                @foreach($milestones as $index => $m)
                @php
                $isCompleted = $m['completed'];
                $isCurrent = $index === $currentStep;
                @endphp
                <div class="flex items-start gap-3 relative">
                    @if($index < count($milestones) - 1)
                        <div class="absolute left-[7px] top-4 w-0.5 h-full {{ $isCompleted ? 'bg-[#234C6A]' : 'bg-slate-200' }}">
                </div>
                @endif

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

                <div class="flex-1 pb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm {{ $isCompleted ? 'font-medium text-siakad-dark' : ($isCurrent ? 'font-medium text-[#456882]' : 'text-slate-400') }}">{{ $m['label'] }}</span>
                        <span class="text-xs {{ $isCompleted ? 'text-siakad-secondary' : 'text-slate-400' }}">
                            @if($m['date'] && $isCompleted)
                            {{ $m['date']->format('d/m/Y') }}
                            @elseif($isCompleted)
                            ✓
                            @else
                            -
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Stats -->
        <div class="mt-4 pt-4 border-t border-siakad-light">
            <div class="text-center">
                <p class="text-3xl font-bold text-siakad-primary">{{ $logbookList->count() }}</p>
                <p class="text-sm text-siakad-secondary">{{ __('Logbook Entries') }}</p>
            </div>
        </div>
    </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card-saas p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#234C6A]/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-[#234C6A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-siakad-dark">{{ $logbookList->count() }}</p>
                <p class="text-sm text-siakad-secondary">{{ __('Total Logbook') }}</p>
            </div>
        </div>
        <div class="card-saas p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-siakad-dark">{{ $internship->duration }}</p>
                <p class="text-sm text-siakad-secondary">{{ __('KP Duration') }}</p>
            </div>
        </div>
        <div class="card-saas p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-siakad-dark">{{ $internship->progress_percent }}%</p>
                <p class="text-sm text-siakad-secondary">{{ __('Progress') }}</p>
            </div>
        </div>
        <div class="card-saas p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                @php
                $daysLeft = (int) round(now()->diffInDays($internship->completion_date, false));
                @endphp
                <p class="text-2xl font-bold text-siakad-dark">{{ $daysLeft > 0 ? $daysLeft : 0 }}</p>
                <p class="text-sm text-siakad-secondary">{{ __('Days Left') }}</p>
            </div>
        </div>
    </div>

    <!-- Logbook Section -->
    @if(in_array($internship->status, ['ongoing', 'approved', 'report_drafting', 'completed']))
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card-saas overflow-hidden">
            <div class="px-6 py-4 border-b border-siakad-light flex items-center justify-between">
                <h3 class="font-semibold text-siakad-dark">{{ __('Activity Log') }}</h3>
                <span class="text-sm text-siakad-secondary">{{ $logbookList->count() }} {{ __('entries') }}</span>
            </div>
            @forelse($logbookList as $log)
            <div class="p-5 border-b border-siakad-light/50">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-lg bg-siakad-primary/10 flex flex-col items-center justify-center flex-shrink-0">
                        <p class="text-lg font-bold text-siakad-primary leading-none">{{ $log->date->format('d') }}</p>
                        <p class="text-[10px] text-siakad-secondary">{{ $log->date->format('M') }}</p>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs text-siakad-secondary">{{ $log->entry_time ?? '-' }} - {{ $log->exit_time ?? '-' }}</span>
                            <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $log->status_color }}-100 text-{{ $log->status_color }}-700">{{ $log->status_label }}</span>
                        </div>
                        <p class="text-sm text-siakad-dark">{{ $log->activity }}</p>
                        @if($log->supervisor_notes)
                        <div class="mt-3 p-3 rounded-lg bg-emerald-50">
                            <p class="text-xs text-emerald-600">💬 {{ $log->supervisor_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-siakad-secondary">{{ __('No entries yet') }}</div>
            @endforelse
        </div>

        <!-- Add Logbook Form -->
        <div class="card-saas p-6">
            <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Add Logbook') }}</h3>
            <form action="{{ route('students.internship.logbook.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Date') }}</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="input-saas w-full text-sm" style="background-color: var(--bg-card);" required>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Entry Time') }}</label>
                        <input type="time" name="entry_time" class="input-saas w-full text-sm" style="background-color: var(--bg-card);">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Exit Time') }}</label>
                        <input type="time" name="exit_time" class="input-saas w-full text-sm" style="background-color: var(--bg-card);">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Activity') }}</label>
                    <textarea name="activity" rows="4" class="input-saas w-full text-sm" style="background-color: var(--bg-card);" placeholder="{{ __('Describe what you did today...') }}" required></textarea>
                </div>

                <!-- Modern File Upload -->
                <div x-data="{ fileName: '' }">
                    <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Documentation (Optional)') }}</label>
                    <label class="relative flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-siakad-light dark:border-slate-600 rounded-xl cursor-pointer hover:border-[#234C6A] hover:bg-[#234C6A]/5 transition-all">
                        <div class="flex flex-col items-center justify-center text-center px-4">
                            <svg class="w-6 h-6 text-siakad-secondary mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-xs text-siakad-secondary" x-show="!fileName">{{ __('Upload Images') }}</p>
                            <p class="text-xs font-medium text-[#234C6A]" x-show="fileName" x-text="fileName"></p>
                            <p class="text-[10px] text-siakad-secondary mt-0.5">JPG, PNG (max 5MB)</p>
                        </div>
                        <input type="file" name="documentation" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="fileName = $event.target.files[0]?.name || ''">
                    </label>
                </div>

                <button type="submit" class="btn-primary-saas w-full py-2.5 rounded-lg text-sm font-medium">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('Save Logbook') }}
                    </span>
                </button>
            </form>
        </div>
    </div>
    @endif
    @endif
</x-app-layout>