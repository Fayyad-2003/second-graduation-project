<x-app-layout>
    <x-slot name="header">{{ __('Internship Details') }}</x-slot>
    <div class="mb-6"><a href="{{ route('lecturers.internship.index') }}"
            class="text-primary-secondary hover:text-primary-primary text-sm">← {{ __('Back') }}</a></div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card-saas p-6">
                <div class="flex items-start justify-between mb-4">
                    <div><span class="px-3 py-1 text-xs font-medium rounded-full bg-{{ $internship->status_color }}-100 text-{{ $internship->status_color }}-700 dark:bg-gray-800 dark:text-{{ $internship->status_color }}-400 border dark:border-{{ $internship->status_color }}-400/20">{{ __($internship->status_label) }}</span>
                        <h2 class="text-lg font-bold text-primary-dark dark:text-white mt-3">{{ $internship->company_name }}</h2>
                    </div>
                    <span class="text-2xl font-bold text-primary-primary dark:text-blue-400">{{ $internship->progress_percent }}%</span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="p-3 rounded-lg bg-primary-light/30 dark:bg-gray-700/50">
                        <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Students') }}</p>
                        <p class="font-medium text-primary-dark dark:text-white">{{ $internship->student->user->name }}</p>
                        <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $internship->student->student_number }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-primary-light/30 dark:bg-gray-700/50">
                        <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Period') }}</p>
                        <p class="font-medium text-primary-dark dark:text-white">{{ $internship->start_date->format('d M') }} -
                            {{ $internship->completion_date->format('d M Y') }}
                        </p>
                    </div>
                </div>
                <form action="{{ route('lecturers.internship.update-status', $internship) }}" method="POST"
                    class="mt-4 flex items-end gap-3">@csrf @method('PUT')
                    <div class="flex-1"><label
                            class="block text-xs font-medium text-primary-dark dark:text-gray-300 mb-1">{{ __('Update Status') }}</label><select name="status"
                            class="input-saas w-full text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">@foreach(\App\Models\Internship::getStatusList() as $k => $v)
                            <option value="{{ $k }}" {{ $internship->status === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select></div>
                    <button type="submit"
                        class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Update') }}</button>
                </form>
            </div>
            <div class="card-saas overflow-hidden">
                <div class="px-6 py-4 border-b border-primary-light dark:border-gray-700">
                    <h3 class="font-semibold text-primary-dark dark:text-white">Logbook ({{ $internship->logbook->count() }})
                    </h3>
                </div>
                @forelse($internship->logbook as $log)
                <div class="p-4 border-b border-primary-light/50 dark:border-gray-700/50">
                    <div class="flex items-start gap-4">
                        <div class="text-center">
                            <p class="text-lg font-bold text-primary-primary dark:text-blue-400">
                                {{ $log->date->format('d') }}
                            </p>
                            <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $log->date->format('M') }}
                            </p>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1"><span
                                    class="text-xs text-primary-secondary dark:text-gray-400">{{ $log->entry_time ?? '-' }}
                                    - {{ $log->exit_time ?? '-' }}</span><span
                                    class="px-2 py-0.5 text-xs rounded-full bg-{{ $log->status_color }}-100 text-{{ $log->status_color }}-700 dark:bg-gray-800 dark:text-{{ $log->status_color }}-400 border dark:border-{{ $log->status_color }}-400/20">{{ __($log->status_label) }}</span>
                            </div>
                            <p class="text-sm text-primary-dark dark:text-gray-300">{{ $log->activity }}</p>
                            @if($log->supervisor_notes)
                            <p class="text-xs text-emerald-600 mt-2 p-2 bg-emerald-50 rounded">
                                {{ $log->supervisor_notes }}
                            </p>
                            @elseif($log->status === 'pending')
                            <form action="{{ route('lecturers.internship.logbook.review', $log) }}" method="POST"
                                class="mt-2 flex items-center gap-2">@csrf
                                <input type="text" name="supervisor_notes" class="input-saas flex-1 text-xs py-1"
                                    placeholder="{{ __('Notes...') }}">
                                <button type="submit" name="status" value="approved"
                                    class="px-2 py-1 text-xs bg-emerald-600 text-white rounded">✓</button>
                                <button type="submit" name="status" value="revision"
                                    class="px-2 py-1 text-xs bg-red-600 text-white rounded">✗</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-primary-secondary">{{ __('No entries yet') }}</div>
                @endforelse
            </div>
        </div>
        <div class="space-y-6">
            <div class="card-saas p-5">
                <h3 class="font-semibold text-primary-dark dark:text-white mb-3">{{ __('Company Information') }}</h3>
                <div class="text-sm space-y-2">
                    <p class="text-primary-dark dark:text-gray-300">{{ $internship->company_name }}</p>
                    <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $internship->company_address }}</p>
                    <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Business Field') }}: {{ $internship->business_field ?? '-' }}</p>
                </div>
            </div>
            <div class="card-saas p-5">
                <h3 class="font-semibold text-primary-dark dark:text-white mb-3">{{ __('Field Supervisor') }}</h3>
                <p class="text-sm text-primary-dark dark:text-gray-300">{{ $internship->field_supervisor_name ?? '-' }}</p>
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $internship->field_supervisor_title }}</p>
                <p class="text-xs text-primary-secondary dark:text-gray-400">{{ $internship->supervisor_phone }}</p>
            </div>
        </div>
    </div>
</x-app-layout>