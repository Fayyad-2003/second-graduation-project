<x-app-layout>
    <x-slot name="header">
        {{ __('Thesis Details') }}
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.thesis.index') }}" class="inline-flex items-center gap-2 text-siakad-secondary hover:text-siakad-primary transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            {{ __('Back') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header -->
            <div class="card-saas p-6">
                <div class="flex items-start justify-between mb-4">
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-{{ $thesis->status_color }}-100 text-{{ $thesis->status_color }}-700">{{ $thesis->status_label }}</span>
                    <span class="text-2xl font-bold text-siakad-primary">{{ $thesis->progress_percent }}%</span>
                </div>
                <h2 class="text-lg font-bold text-siakad-dark">{{ $thesis->title }}</h2>
                @if($thesis->abstract)
                <p class="text-sm text-siakad-secondary mt-3">{{ $thesis->abstract }}</p>
                @endif

                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="p-3 rounded-lg bg-siakad-light/30">
                        <p class="text-xs text-siakad-secondary">{{ __('Student') }}</p>
                        <p class="font-medium text-siakad-dark">{{ $thesis->student->user->name }}</p>
                        <p class="text-xs text-siakad-secondary">{{ $thesis->student->student_number }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-siakad-light/30">
                        <p class="text-xs text-siakad-secondary">{{ __('Research Field') }}</p>
                        <p class="font-medium text-siakad-dark">{{ $thesis->research_field ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Assign Supervisor -->
            <div class="card-saas p-6">
                <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Supervisor') }}</h3>
                <form action="{{ route('admin.thesis.assign-supervisor', $thesis) }}" method="POST" class="grid grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Supervisor 1') }}*</label>
                        <select name="supervisor1_id" class="input-saas w-full text-sm" required>
                            <option value="">{{ __('Select Lecturer') }}</option>
                            @foreach($lecturerList as $lecturer)
                            <option value="{{ $lecturer->id }}" {{ $thesis->supervisor1_id == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Supervisor 2') }}</label>
                        <select name="supervisor2_id" class="input-saas w-full text-sm">
                            <option value="">{{ __('None') }}</option>
                            @foreach($lecturerList as $lecturer)
                            <option value="{{ $lecturer->id }}" {{ $thesis->supervisor2_id == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <button type="submit" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Save Supervisor') }}</button>
                    </div>
                </form>
            </div>

            <!-- Update Status -->
            <div class="card-saas p-6">
                <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Status Updates') }}</h3>
                <form action="{{ route('admin.thesis.update-status', $thesis) }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Status') }}</label>
                        <select name="status" class="input-saas w-full text-sm">
                            @foreach(\App\Models\Thesis::getStatusList() as $key => $label)
                            <option value="{{ $key }}" {{ $thesis->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Notes (Optional)') }}</label>
                        <textarea name="admin_notes" rows="2" class="input-saas w-full text-sm">{{ $thesis->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Update Status') }}</button>
                </form>
            </div>

            <!-- Input Grade -->
            @if($thesis->status === 'defense' || $thesis->status === 'revision')
            <div class="card-saas p-6">
                <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Final Grade Input') }}</h3>
                <form action="{{ route('admin.thesis.update-grades', $thesis) }}" method="POST" class="grid grid-cols-2 gap-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Numeric Grade') }}</label>
                        <input type="number" name="final_grade" value="{{ $thesis->final_grade }}" step="0.01" min="0" max="100" class="input-saas w-full text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Letter Grade') }}</label>
                        <select name="letter_grade" class="input-saas w-full text-sm" required>
                            @foreach(['A', 'B+', 'B', 'C+', 'C', 'D', 'E'] as $huruf)
                            <option value="{{ $huruf }}" {{ $thesis->letter_grade === $huruf ? 'selected' : '' }}>{{ $huruf }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <button type="submit" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Save & Complete') }}</button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Timeline -->
            <div class="card-saas p-5">
                <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Timeline') }}</h3>
                <div class="space-y-3 text-sm">
                    @php
                    $milestones = [
                    ['date' => $thesis->submission_date, 'label' => __('Title Submission')],
                    ['date' => $thesis->title_approval_date, 'label' => __('Title Approved')],
                    ['date' => $thesis->proposal_seminar_date, 'label' => __('Proposal Seminar')],
                    ['date' => $thesis->result_seminar_date, 'label' => __('Results Seminar')],
                    ['date' => $thesis->defense_date, 'label' => __('Final Exam')],
                    ['date' => $thesis->completion_date, 'label' => __('Completed')],
                    ];
                    @endphp
                    @foreach($milestones as $m)
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full {{ $m['date'] ? 'bg-emerald-500' : 'bg-siakad-light' }}"></div>
                        <span class="{{ $m['date'] ? 'text-siakad-dark' : 'text-siakad-secondary' }}">{{ $m['label'] }}</span>
                        <span class="ml-auto text-siakad-secondary">{{ $m['date']?->format('d/m/Y') ?? '-' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Supervision Summary -->
            <div class="card-saas p-5">
                <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Supervision History') }}</h3>
                <p class="text-2xl font-bold text-siakad-primary">{{ $thesis->supervision->count() }}</p>
                <p class="text-sm text-siakad-secondary">{{ __('Total Meetings') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>