<x-app-layout>
    <x-slot name="header">{{ __('Apply for Internship') }}</x-slot>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6"><a href="{{ route('students.internship.index') }}" class="text-primary-secondary hover:text-primary-primary text-sm">← {{ __('Back') }}</a></div>
        <div class="card-saas overflow-hidden">
            <div class="px-6 py-4 border-b border-primary-light bg-gradient-to-r from-primary-primary to-primary-primary/80">
                <h2 class="text-lg font-semibold text-white">{{ __('Internship Application Form') }}</h2>
            </div>
            <form action="{{ route('students.internship.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div><label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Company Name *') }}</label><input type="text" name="company_name" class="input-saas w-full text-sm" required></div>
                <div><label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Address') }}</label><textarea name="company_address" rows="2" class="input-saas w-full text-sm"></textarea></div>
                <div><label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Business Fields') }}</label><input type="text" name="business_field" class="input-saas w-full text-sm"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Start Date *') }}</label><input type="date" name="start_date" class="input-saas w-full text-sm" required></div>
                    <div><label class="block text-sm font-medium text-primary-dark mb-1">{{ __('End Date *') }}</label><input type="date" name="completion_date" class="input-saas w-full text-sm" required></div>
                </div>
                <hr class="border-primary-light">
                <p class="text-xs text-primary-secondary">{{ __('Field Guide (Optional)') }}</p>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Name') }}</label><input type="text" name="field_supervisor_name" class="input-saas w-full text-sm"></div>
                    <div><label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Position') }}</label><input type="text" name="field_supervisor_title" class="input-saas w-full text-sm"></div>
                </div>
                <div><label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Supervisor Phone Number') }}</label><input type="text" name="supervisor_phone" class="input-saas w-full text-sm"></div>
                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('students.internship.index') }}" class="btn-ghost-saas px-4 py-2.5 rounded-lg text-sm">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-lg text-sm font-medium">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>