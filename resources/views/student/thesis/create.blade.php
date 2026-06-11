<x-app-layout>
    <x-slot name="header">
        {{ __('Submit Thesis Title') }}
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('students.thesis.index') }}" class="inline-flex items-center gap-2 text-primary-secondary hover:text-primary-primary transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                {{ __('Back') }}
            </a>
        </div>

        <div class="card-saas overflow-hidden">
            <div class="px-6 py-4 border-b border-primary-light bg-gradient-to-r from-primary-primary to-primary-primary/80">
                <h2 class="text-lg font-semibold text-white">{{ __('Thesis Title Submission Form') }}</h2>
                <p class="text-sm text-white/70 mt-1">{{ __('Fill in the form below to submit your thesis title') }}</p>
            </div>

            <form action="{{ route('students.thesis.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Thesis Title') }} *</label>
                    <textarea name="title" rows="3" class="input-saas w-full text-sm @error('title') border-red-500 @enderror" placeholder="{{ __('Enter your thesis title...') }}" required>{{ old('title') }}</textarea>
                    @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Field of Study') }}</label>
                    <input type="text" name="bidang_kajian" value="{{ old('bidang_kajian') }}" class="input-saas w-full text-sm" placeholder="{{ __('e.g., Machine Learning, Web Development, etc.') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Abstract / Summary') }}</label>
                    <textarea name="abstrak" rows="5" class="input-saas w-full text-sm" placeholder="{{ __('Write an abstract of your research plan...') }}">{{ old('abstrak') }}</textarea>
                    <p class="text-xs text-primary-secondary mt-1">{{ __('Optional, can be completed later') }}</p>
                </div>

                <div class="pt-4 border-t border-primary-light flex items-center justify-end gap-3">
                    <a href="{{ route('students.thesis.index') }}" class="btn-ghost-saas px-4 py-2.5 rounded-lg text-sm font-medium">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-lg text-sm font-medium">{{ __('Submit Title') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>