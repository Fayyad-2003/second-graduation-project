<x-app-layout>
    <x-slot name="header">{{ __('Send New Report') }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card-saas dark:bg-gray-800 p-6">
            <form action="{{ route('students.report.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Subject') }}</label>
                    <input type="text" name="subject" id="subject" class="input-saas w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" required value="{{ old('subject') }}">
                    @error('subject')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Report Message') }}</label>
                    <textarea name="message" id="message" rows="6" class="input-saas w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" required>{{ old('message') }}</textarea>
                    @error('message')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('students.report.index') }}" class="px-4 py-2 text-sm font-medium text-primary-secondary dark:text-gray-400 hover:text-primary-dark dark:hover:text-white transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn-primary-saas px-6 py-2 rounded-lg text-sm font-medium">
                        {{ __('Send Report') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>