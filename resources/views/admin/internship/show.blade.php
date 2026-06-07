<x-app-layout>
    <x-slot name="header">{{ __('Internship Details') }}</x-slot>
    <div class="mb-6"><a href="{{ route('admin.internship.index') }}" class="text-siakad-secondary hover:text-siakad-primary text-sm">← {{ __('Back') }}</a></div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card-saas p-6">
                <div class="flex items-start justify-between mb-4"><span class="px-3 py-1 text-xs font-medium rounded-full bg-{{ $internship->status_color }}-100 text-{{ $internship->status_color }}-700">{{ $internship->status_label }}</span><span class="text-2xl font-bold text-siakad-primary">{{ $internship->progress_percent }}%</span></div>
                <h2 class="text-lg font-bold text-siakad-dark">{{ $internship->company_name }}</h2>
                <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                    <div class="p-3 rounded-lg bg-siakad-light/30"><p class="text-xs text-siakad-secondary">{{ __('Student') }}</p><p class="font-medium text-siakad-dark">{{ $internship->student->user->name }}</p><p class="text-xs text-siakad-secondary">{{ $internship->student->student_number }}</p></div>
                    <div class="p-3 rounded-lg bg-siakad-light/30"><p class="text-xs text-siakad-secondary">{{ __('Period') }}</p><p class="font-medium text-siakad-dark">{{ $internship->start_date->format('d M') }} - {{ $internship->completion_date->format('d M Y') }}</p></div>
                </div>
            </div>
            <div class="card-saas p-6">
                <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Assign Supervisor') }}</h3>
                <form action="{{ route('admin.internship.assign-supervisor', $internship) }}" method="POST" class="flex items-end gap-3">@csrf
                    <div class="flex-1"><label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Campus Advisor') }}</label><select name="supervisor_id" class="input-saas w-full text-sm" required><option value="">{{ __('Select Lecturer') }}</option>@foreach($lecturerList as $d)<option value="{{ $d->id }}" {{ $internship->supervisor_id == $d->id ? 'selected' : '' }}>{{ $d->user->name }}</option>@endforeach</select></div>
                    <button type="submit" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Save') }}</button>
                </form>
            </div>
            <div class="card-saas p-6">
                <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Status Updates') }}</h3>
                <form action="{{ route('admin.internship.update-status', $internship) }}" method="POST" class="space-y-4">@csrf @method('PUT')
                    <div><label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Status') }}</label><select name="status" class="input-saas w-full text-sm">@foreach(\App\Models\Internship::getStatusList() as $k => $v)<option value="{{ $k }}" {{ $internship->status === $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
                    <div><label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Note') }}</label><textarea name="notes" rows="2" class="input-saas w-full text-sm">{{ $internship->notes }}</textarea></div>
                    <button type="submit" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Update') }}</button>
                </form>
            </div>
            @if(in_array($internship->status, ['seminar', 'revision']))
            <div class="card-saas p-6">
                <h3 class="font-semibold text-siakad-dark mb-4">{{ __('Input Grades') }}</h3>
                <form action="{{ route('admin.internship.update-grades', $internship) }}" method="POST" class="grid grid-cols-2 gap-4">@csrf @method('PUT')
                    <div><label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Company Grade') }}</label><input type="number" name="company_grade" value="{{ $internship->company_grade }}" step="0.01" class="input-saas w-full text-sm"></div>
                    <div><label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Advisor Grade') }}</label><input type="number" name="supervisor_grade" value="{{ $internship->supervisor_grade }}" step="0.01" class="input-saas w-full text-sm"></div>
                    <div><label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Seminar Grade') }}</label><input type="number" name="seminar_grade" value="{{ $internship->seminar_grade }}" step="0.01" class="input-saas w-full text-sm"></div>
                    <div><label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Final Grade *') }}</label><input type="number" name="final_grade" value="{{ $internship->final_grade }}" step="0.01" class="input-saas w-full text-sm" required></div>
                    <div class="col-span-2"><label class="block text-xs font-medium text-siakad-dark mb-1">{{ __('Letter Grade *') }}</label><select name="letter_grade" class="input-saas w-full text-sm" required>@foreach(['A','B+','B','C+','C','D','E'] as $h)<option value="{{ $h }}" {{ $internship->letter_grade === $h ? 'selected' : '' }}>{{ $h }}</option>@endforeach</select></div>
                    <div class="col-span-2"><button type="submit" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Save Grade') }}</button></div>
                </form>
            </div>
            @endif
        </div>
        <div class="space-y-6">
            <div class="card-saas p-5"><h3 class="font-semibold text-siakad-dark mb-3">{{ __('Company Information') }}</h3><p class="text-sm text-siakad-dark">{{ $internship->company_name }}</p><p class="text-xs text-siakad-secondary">{{ $internship->company_address }}</p><p class="text-xs text-siakad-secondary">{{ $internship->business_field }}</p></div>
            <div class="card-saas p-5"><h3 class="font-semibold text-siakad-dark mb-3">{{ __('Logbook') }}</h3><p class="text-3xl font-bold text-siakad-primary">{{ $internship->logbook->count() }}</p><p class="text-sm text-siakad-secondary">{{ __('Entries') }}</p></div>
        </div>
    </div>
</x-app-layout>
