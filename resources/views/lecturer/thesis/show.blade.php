<x-app-layout>
    <x-slot name="header">
        {{ __('Thesis Details') }}
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('lecturers.thesis.index') }}" class="inline-flex items-center gap-2 text-primary-secondary hover:text-primary-primary transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            {{ __('Back') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header Card -->
            <div class="card-saas p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-{{ $thesis->status_color }}-100 text-{{ $thesis->status_color }}-700">{{ $thesis->status_label }}</span>
                        <h2 class="text-lg font-bold text-primary-dark mt-3">{{ $thesis->title }}</h2>
                    </div>
                    <span class="text-2xl font-bold text-primary-primary">{{ $thesis->progress_percent }}%</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="p-3 rounded-lg bg-primary-light/30">
                        <p class="text-xs text-primary-secondary">{{ __('Student') }}</p>
                        <p class="font-medium text-primary-dark">{{ $thesis->student->user->name }}</p>
                        <p class="text-xs text-primary-secondary">{{ $thesis->student->student_number }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-primary-light/30">
                        <p class="text-xs text-primary-secondary">{{ __('Field of Study') }}</p>
                        <p class="font-medium text-primary-dark">{{ $thesis->research_field ?? '-' }}</p>
                    </div>
                </div>

                <!-- Update Status -->
                <form action="{{ route('lecturers.thesis.update-status', $thesis) }}" method="POST" class="mt-6 flex items-end gap-3">
                    @csrf @method('PUT')
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-primary-dark mb-1">{{ __('Update Status') }}</label>
                        <select name="status" class="input-saas w-full text-sm">
                            @foreach(\App\Models\Thesis::getStatusList() as $key => $label)
                            <option value="{{ $key }}" @selected($thesis->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Update') }}</button>
                </form>
            </div>

            <!-- Supervision History -->
            <div class="card-saas overflow-hidden">
                <div class="px-6 py-4 border-b border-primary-light">
                    <h3 class="font-semibold text-primary-dark">{{ __('Supervision History') }}</h3>
                </div>
                @forelse($thesis->supervisions as $supervision)
                <div class="p-5 border-b border-primary-light/50">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-{{ $supervision->status_color }}-100 flex items-center justify-center text-{{ $supervision->status_color }}-600">
                            @if($supervision->status === 'approved')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            @elseif($supervision->status === 'revision')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-medium text-primary-dark">{{ $supervision->supervision_date->format('d M Y') }}</span>
                                <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $supervision->status_color }}-100 text-{{ $supervision->status_color }}-700">{{ $supervision->status_label }}</span>
                            </div>
                            <p class="text-sm text-primary-secondary">{{ $supervision->student_notes }}</p>

                            @if($supervision->document_file)
                            <a href="{{ Storage::url($supervision->document_file) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-primary-primary mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ __('View Document') }}
                            </a>
                            @endif

                            @if($supervision->lecturer_notes)
                            <div class="mt-3 p-3 rounded-lg bg-emerald-50 border border-emerald-100">
                                <p class="text-xs text-emerald-600 mb-1">{{ __('Your Notes') }}:</p>
                                <p class="text-sm text-emerald-800">{{ $supervision->lecturer_notes }}</p>
                            </div>
                            @elseif($supervision->status === 'waiting')
                            <!-- Review Form -->
                            <form action="{{ route('lecturers.thesis.supervision.review', $supervision) }}" method="POST" class="mt-3 p-3 rounded-lg bg-amber-50 border border-amber-100">
                                @csrf
                                <textarea name="lecturer_notes" rows="2" class="w-full text-sm border border-amber-200 rounded-lg p-2 focus:ring-amber-500 focus:border-amber-500" placeholder="{{ __('Provide feedback...') }}" required></textarea>
                                <div class="flex items-center gap-2 mt-2">
                                    <button type="submit" name="status" value="approved" class="px-3 py-1.5 text-xs font-medium bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">{{ __('Approve') }}</button>
                                    <button type="submit" name="status" value="revision" class="px-3 py-1.5 text-xs font-medium bg-red-600 text-white rounded-lg hover:bg-red-700">{{ __('Needs Revision') }}</button>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-primary-secondary">{{ __('No supervision notes yet') }}</div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Supervisors -->
            <div class="card-saas p-5">
                <h3 class="font-semibold text-primary-dark mb-4">{{ __('Supervision Team') }}</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-primary-primary text-white flex items-center justify-center font-bold">1</div>
                        <div>
                            <p class="font-medium text-primary-dark">{{ $thesis->supervisor1?->user->name ?? '-' }}</p>
                            <p class="text-xs text-primary-secondary">{{ __('Main Supervisor') }}</p>
                        </div>
                    </div>
                    @if($thesis->supervisor2)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-primary-secondary/20 text-primary-secondary flex items-center justify-center font-bold">2</div>
                        <div>
                            <p class="font-medium text-primary-dark">{{ $thesis->supervisor2->user->name }}</p>
                            <p class="text-xs text-primary-secondary">{{ __('Co-Supervisor') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card-saas p-5">
                <h3 class="font-semibold text-primary-dark mb-4">{{ __('Timeline') }}</h3>
                <div class="space-y-3 text-sm">
                    @php
                    $milestones = [
                    ['date' => $thesis->submission_date, 'label' => __('Application Submission')],
                    ['date' => $thesis->title_approval_date, 'label' => __('Title Approved')],
                    ['date' => $thesis->proposal_seminar_date, 'label' => __('Proposal Seminar')],
                    ['date' => $thesis->result_seminar_date, 'label' => __('Results Seminar')],
                    ['date' => $thesis->defense_date, 'label' => __('Final Exam')],
                    ['date' => $thesis->completion_date, 'label' => __('Completed')],
                    ];
                    @endphp
                    @foreach($milestones as $m)
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full {{ $m['date'] ? 'bg-emerald-500' : 'bg-primary-light' }}"></div>
                        <span class="{{ $m['date'] ? 'text-primary-dark' : 'text-primary-secondary' }}">{{ $m['label'] }}</span>
                        <span class="ml-auto text-primary-secondary">{{ $m['date'] ? $m['date']->format('d/m/Y') : '-' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>