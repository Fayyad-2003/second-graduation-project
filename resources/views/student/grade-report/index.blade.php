<x-app-layout>
    <x-slot name="header">
        {{ __('Grade Report') }}
    </x-slot>

    <div class="mb-8">
        <h1 class="text-xl font-semibold text-primary-dark hidden md:block">{{ __('Grade Report') }}</h1>
        <p class="text-primary-secondary mt-1 hidden md:block">{{ __('View grades for each semester') }}</p>
    </div>

    <!-- GPA Summary Card -->
    <div class="rounded-2xl p-6 bg-[#1B3C53] text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-white/70">{{ __('GPA') }}</p>
                <p class="text-4xl font-bold mt-2 text-white">{{ number_format($cgpaData['gpa'], 2) }}</p>
                <p class="text-sm text-white/50 mt-1">{{ __('Total') }} {{ $cgpaData['total_credits'] }} {{ __('Credits from') }} {{ $semesterList->count() }} {{ __('Semesters') }}</p>
            </div>
            <div class="w-20 h-20 rounded-full bg-white/10 flex items-center justify-center">
                <svg class="w-10 h-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>

    @if($semesterList->isEmpty())
    <div class="card-saas p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-primary-light/50 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-primary-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-primary-dark mb-2">{{ __('No grade data yet') }}</h3>
        <p class="text-primary-secondary mb-4">{{ __('Your study plan has not been approved yet.') }}</p>
        <a href="{{ route('students.study-plan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-primary text-white rounded-lg hover:bg-primary-primary/90 transition">
            {{ __('See Study Plan') }}
        </a>
    </div>
    @else
    <!-- Semester List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($semesterList as $item)
        @php
        $ta = $item['academicYear'];
        // Use system palette colors instead of Tailwind defaults
        $gpaColor = $item['gpa'] >= 3.5 ? '#234C6A' : ($item['gpa'] >= 3.0 ? '#456882' : ($item['gpa'] >= 2.5 ? '#F59E0B' : '#EF4444'));
        @endphp
        <a href="{{ route('students.grade-report.show', $ta) }}" class="card-saas group overflow-hidden hover:ring-2 hover:ring-primary-primary transition-all">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-primary-dark group-hover:text-primary-primary transition">{{ $ta->year }}</h3>
                        <p class="text-sm text-primary-secondary">{{ __('Semester') }} {{ __($ta->semester) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold" style="color: {{ $gpaColor }}">{{ number_format($item['gpa'], 2) }}</p>
                        <p class="text-xs text-primary-secondary">{{ __('Semester GPA') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-sm text-primary-secondary">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        {{ $item['course_count'] }} {{ __('Courses') }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                        {{ $item['total_credits'] }} {{ __('Credits') }}
                    </span>
                </div>

                <!-- Progress Bar - Using system palette -->
                <div class="mt-4 h-2 bg-primary-light rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all" style="width: {{ min($item['gpa'] / 4 * 100, 100) }}%; background-color: {{ $gpaColor }}"></div>
                </div>
            </div>

            <div class="px-6 py-3 bg-primary-light/30 border-t border-primary-light flex items-center justify-between">
                <span class="text-xs text-primary-secondary">{{ __('View Details') }}</span>
                <svg class="w-4 h-4 text-primary-secondary group-hover:text-primary-primary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</x-app-layout>