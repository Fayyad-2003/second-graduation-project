<x-app-layout>
    <x-slot name="header">
        {{ __('Review Application') }}
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.document-application.index') }}" class="btn-ghost-saas inline-flex items-center gap-2 text-sm font-medium text-siakad-secondary hover:text-siakad-primary transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            {{ __('Back to List') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card-saas p-6">
                <h3 class="text-lg font-bold text-siakad-dark mb-6">{{ __('Application Details') }}</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] text-siakad-secondary uppercase font-bold tracking-wider mb-1">{{ __('Student') }}</p>
                        <p class="font-bold text-siakad-dark">{{ $application->student->user->name }}</p>
                        <p class="text-xs text-siakad-secondary">{{ $application->student->student_number }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-siakad-secondary uppercase font-bold tracking-wider mb-1">{{ __('Document Type') }}</p>
                        <p class="font-bold text-siakad-dark">{{ $application->documentType->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-siakad-secondary uppercase font-bold tracking-wider mb-1">{{ __('Submission Date') }}</p>
                        <p class="text-sm text-siakad-dark">{{ $application->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-siakad-secondary uppercase font-bold tracking-wider mb-1">{{ __('Current Status') }}</p>
                        <span class="inline-flex px-2.5 py-1 text-[10px] font-bold rounded-full uppercase bg-slate-100 text-slate-700">
                            {{ __($application->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-saas p-6">
                <h3 class="text-lg font-bold text-siakad-dark mb-6">{{ __('Uploaded Files') }}</h3>
                <div class="space-y-4">
                    @foreach($application->uploaded_files as $file)
                    <div class="flex items-center justify-between p-4 bg-siakad-light/20 rounded-xl border border-siakad-light/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-siakad-primary/10 rounded-lg flex items-center justify-center text-siakad-primary text-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-siakad-dark text-sm">{{ $file['name'] }}</p>
                                <p class="text-[10px] text-siakad-secondary uppercase">{{ __('Document Attachment') }}</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="btn-ghost-saas px-4 py-2 rounded-lg text-xs font-bold text-siakad-primary border border-siakad-primary/20 hover:bg-siakad-primary/10 transition">
                            {{ __('View File') }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="lg:col-span-1">
            <div class="card-saas p-6 sticky top-24">
                <h3 class="text-lg font-bold text-siakad-dark mb-6">{{ __('Update Status') }}</h3>
                <form action="{{ route('admin.document-application.update-status', $application) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark mb-1">{{ __('Status') }}</label>
                            <select name="status" class="input-saas w-full" required>
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="processing" {{ $application->status == 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                                <option value="completed" {{ $application->status == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark mb-1">{{ __('Admin Notes') }}</label>
                            <textarea name="admin_notes" rows="5" class="input-saas w-full" placeholder="{{ __('Add notes for the student...') }}">{{ $application->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn-primary-saas w-full py-3 rounded-xl font-bold shadow-lg shadow-siakad-primary/20 transition active:scale-95">
                            {{ __('Save Status') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
