<x-app-layout>
    <x-slot name="header">
        {{ __('My Profile') }}
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card + Academic Info -->
        <div class="lg:col-span-1 flex flex-col gap-6">
            <!-- Profile Card -->
            <div class="card-saas p-6">
                <div class="text-center mb-6">
                    <div
                        class="w-24 h-24 mx-auto rounded-xl bg-primary-primary flex items-center justify-center text-white text-4xl font-bold mb-4">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h2 class="text-xl font-semibold text-primary-dark">{{ $user->name }}</h2>
                    <p class="text-sm font-mono text-primary-secondary">{{ $student->student_number }}</p>
                    <span
                        class="inline-flex mt-2 px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">
                        {{ $student->status === 'active' ? __('Active') : ucfirst($student->status) }}
                    </span>
                </div>

                <div class="space-y-3 pt-4 border-t border-primary-light">
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-primary-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="text-primary-dark">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-primary-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span class="text-primary-dark">{{ $student->studyProgram->faculty->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-primary-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        <span class="text-primary-dark">{{ $student->studyProgram->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-primary-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-primary-dark">{{ __('Academic Advisor') }}:
                            {{ $student->academicAdvisor->user->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Academic Info (Moved here) -->
            <div class="card-saas p-6 flex-1 flex flex-col">
                <h3 class="font-semibold text-primary-dark mb-4">{{ __('Academic Information') }}</h3>
                <div class="grid grid-cols-2 gap-3 flex-1">
                    <div class="flex flex-col items-center justify-center p-4 bg-primary-primary/10 rounded-xl">
                        <p class="text-2xl font-bold text-primary-primary">{{ $student->batch }}</p>
                        <p class="text-xs text-primary-secondary mt-1">{{ __('Batch') }}</p>
                    </div>
                    <div class="flex flex-col items-center justify-center p-4 bg-primary-primary/10 rounded-xl">
                        @php
                        $currentYear = date('Y');
                        $semester = (($currentYear - $student->batch) * 2) + (date('n') >= 7 ? 1 : 0);
                        @endphp
                        <p class="text-2xl font-bold text-primary-primary">{{ min($semester, 8) }}</p>
                        <p class="text-xs text-primary-secondary mt-1">{{ __('Semester') }}</p>
                    </div>
                    <div class="flex flex-col items-center justify-center p-4 bg-[#456882]/10 rounded-xl">
                        <p class="text-2xl font-bold text-[#456882]">{{ $student->studyPlans->count() ?? 0 }}</p>
                        <p class="text-xs text-primary-secondary mt-1">{{ __('Total Registered Courses') }} </p>
                    </div>
                    <div class="flex flex-col items-center justify-center p-4 bg-emerald-500/10 rounded-xl">
                        <p class="text-xl font-bold text-emerald-500">
                            {{ $student->status === 'active' ? __('Active') : ucfirst($student->status) }}
                        </p>
                        <p class="text-xs text-primary-secondary mt-1">{{ __('Status') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Update Profile -->
            <div class="card-saas">
                <div class="px-6 py-4 border-b border-primary-light">
                    <h3 class="font-semibold text-primary-dark">{{ __('Account Information') }}</h3>
                    <p class="text-xs text-primary-secondary mt-1">{{ __('Update your account information') }}</p>
                </div>
                <form action="{{ route('students.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Full Name') }}</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="input-saas w-full px-4 py-2.5 text-sm"
                                    style="background-color: var(--bg-card);" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Email Address') }}</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="input-saas w-full px-4 py-2.5 text-sm"
                                    style="background-color: var(--bg-card);" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Student ID') }}</label>
                                <input type="text" value="{{ $student->student_number }}"
                                    class="input-saas w-full px-4 py-2.5 text-sm"
                                    style="background-color: var(--bg-card); opacity: 0.6;" readonly disabled>
                                <p class="text-xs text-primary-secondary mt-1">{{ __('Student ID cannot be changed') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Study Programs') }}</label>
                                <input type="text" value="{{ $student->studyProgram->name ?? '-' }}"
                                    class="input-saas w-full px-4 py-2.5 text-sm"
                                    style="background-color: var(--bg-card); opacity: 0.6;" readonly disabled>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-primary-light flex justify-end">
                        <button type="submit" class="btn-primary-saas px-5 py-2.5 rounded-lg text-sm font-medium">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card-saas">
                <div class="px-6 py-4 border-b border-primary-light">
                    <h3 class="font-semibold text-primary-dark">{{ __('Change Password') }}</h3>
                    <p class="text-xs text-primary-secondary mt-1">{{ __('Ensure you use a strong password') }}</p>
                </div>
                <form action="{{ route('students.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Current Password') }}</label>
                            <input type="password" name="current_password" class="input-saas w-full px-4 py-2.5 text-sm"
                                style="background-color: var(--bg-card);" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('New Password') }}</label>
                                <input type="password" name="password" class="input-saas w-full px-4 py-2.5 text-sm"
                                    style="background-color: var(--bg-card);" required minlength="8">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Confirm Password') }}</label>
                                <input type="password" name="password_confirmation"
                                    class="input-saas w-full px-4 py-2.5 text-sm"
                                    style="background-color: var(--bg-card);" required>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-primary-light flex justify-end">
                        <button type="submit" class="btn-primary-saas px-5 py-2.5 rounded-lg text-sm font-medium">
                            {{ __('Change Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>