<x-app-layout>
    <x-slot name="header">
        {{ __('Study Plan Details') }} - {{ $studyPlan->student->user->name ?? 'Unknown' }}
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Student Info -->
        <div class="lg:col-span-1">
            <div class="card-saas p-6 sticky top-24 dark:bg-gray-800">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-primary to-primary-dark flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
                        {{ strtoupper(substr($studyPlan->student->user->name ?? 'X', 0, 1)) }}
                    </div>
                    <h3 class="text-xl font-bold text-primary-dark dark:text-white">{{ $studyPlan->student->user->name ?? '-' }}</h3>
                    <p class="text-primary-secondary dark:text-gray-400">{{ $studyPlan->student->student_number ?? '-' }}</p>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-primary-secondary dark:text-gray-400">{{ __('Study Program') }}</span>
                        <span class="font-medium text-primary-dark dark:text-white text-right">{{ $studyPlan->student->studyProgram->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-primary-secondary dark:text-gray-400">{{ __('Batch') }}</span>
                        <span class="font-medium text-primary-dark dark:text-white">{{ $studyPlan->student->batch ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-primary-secondary dark:text-gray-400">{{ __('Academic Year') }}</span>
                        <span class="font-medium text-primary-dark dark:text-white">{{ $studyPlan->academicYear->year ?? '-' }} {{ $studyPlan->academicYear->semester ?? '' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-primary-secondary dark:text-gray-400">{{ __('Total Credits') }}</span>
                        <span class="font-bold text-primary-primary dark:text-blue-400 text-lg">{{ $totalCredits }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-primary-light dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-primary-secondary dark:text-gray-400">{{ __('Status') }}</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium capitalize
                            {{ $studyPlan->status == 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 dark:border dark:border-emerald-500/20' : 
                               ($studyPlan->status == 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 dark:border dark:border-amber-500/20' : 
                               ($studyPlan->status == 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 dark:border dark:border-red-500/20' : 'bg-slate-100 text-slate-800 dark:bg-gray-700 dark:text-gray-300 dark:border dark:border-gray-600')) }}">
                            {{ __($studyPlan->status) }}
                        </span>
                    </div>
                </div>

                <a href="{{ url('admin/study-plan-approval') }}" class="mt-6 block text-center text-sm text-primary-secondary dark:text-gray-400 hover:text-primary-primary dark:hover:text-blue-400 transition">
                    ← {{ __('Back to List') }}
                </a>
            </div>
        </div>

        <!-- Course List -->
        <div class="lg:col-span-2">
            <div class="card-saas overflow-hidden dark:bg-gray-800">
                <div class="p-6 border-b border-primary-light dark:border-gray-700">
                    <h3 class="text-lg font-bold text-primary-dark dark:text-white">{{ __('Courses Taken') }}</h3>
                    <p class="text-sm text-primary-secondary dark:text-gray-400">{{ $studyPlan->details->count() }} {{ __('courses') }}</p>
                </div>

                <div class="divide-y divide-primary-light dark:divide-gray-700">
                    @forelse($studyPlan->details as $detail)
                    <div class="p-4 flex items-center gap-4 hover:bg-primary-light/30 dark:hover:bg-gray-700/50 transition">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-primary to-primary-dark flex items-center justify-center text-white font-bold text-lg">
                            {{ $detail->academicClass->class_name ?? '-' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-primary-dark dark:text-white">{{ $detail->academicClass->course->course_name ?? '-' }}</p>
                            <p class="text-sm text-primary-secondary dark:text-gray-400">{{ $detail->academicClass->course->course_code ?? '-' }} • {{ $detail->academicClass->lecturer->user->name ?? '-' }}</p>
                        </div>
                        <div class="text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary-primary/10 text-primary-primary dark:bg-blue-500/20 dark:text-blue-400 font-bold">
                                {{ $detail->academicClass->course->credits ?? 0 }}
                            </span>
                            <p class="text-xs text-primary-secondary dark:text-gray-400 mt-1">{{ __('Credits') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-primary-secondary dark:text-gray-400">
                        {{ __('No courses listed') }}
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>