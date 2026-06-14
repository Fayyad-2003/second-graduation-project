<x-app-layout>
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <a href="{{ route('lecturers.grading.index') }}" class="group inline-flex items-center gap-2 text-xs font-black text-primary-400 uppercase tracking-widest hover:text-primary-primary transition-colors mb-4">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    {{ __('Back to Courses') }}
                </a>
                <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Input Grades') }}</h1>
                <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
                    <span class="w-8 h-px bg-primary-200"></span>
                    {{ __('Submit final assessment for students in this academic session.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Class Info -->
    <div class="card-saas p-8 mb-10 bg-gradient-to-br from-primary-900 to-primary-950 text-white relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/5 rounded-full -mr-32 -mt-32 blur-3xl transition-transform duration-700 group-hover:scale-110"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 rounded-lg bg-white/10 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest border border-white/5">{{ $class->course->course_code }}</span>
                    <span class="px-3 py-1 rounded-lg bg-primary-500/20 backdrop-blur-md text-primary-400 text-[10px] font-black uppercase tracking-widest border border-primary-500/10">{{ $class->course->credits }} {{ __('SKS') }}</span>
                    @if($class->course->has_practical)
                        <span class="px-3 py-1 rounded-lg bg-emerald-500/20 backdrop-blur-md text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-500/10">{{ __('With Practical') }}</span>
                    @endif
                </div>
                <h2 class="text-3xl font-black mb-4 tracking-tight">{{ $class->course->course_name }}</h2>
                <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-white/60">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold px-2 py-0.5 bg-white/5 rounded uppercase tracking-wider text-white">{{ __('Class') }} {{ $class->class_name }}</span>
                    </div>
                    <span class="w-1 h-1 rounded-full bg-white/20"></span>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-xs font-bold">{{ $class->studyPlanDetails->count() }} {{ __('Total Students') }}</span>
                    </div>
                </div>
                <!-- Grade Breakdown Info -->
                <div class="mt-4 grid grid-cols-2 md:grid-cols-{{ $class->course->has_practical ? '5' : '3' }} gap-2">
                    @php
                    $components = $class->course->grade_components;
                    @endphp
                    @foreach($components as $name => $weight)
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-lg p-3 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-white/60">{{ __(ucwords(str_replace('_', ' ', $name))) }}</p>
                        <p class="text-xl font-black text-white">{{ $weight }}%</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="w-24 h-24 rounded-3xl bg-white/5 border border-white/10 flex items-center justify-center text-4xl font-black text-white/20">
                {{ substr($class->class_name, 0, 1) }}
            </div>
        </div>
    </div>

    <!-- Input Form -->
    <div class="card-saas overflow-hidden">
        <form action="{{ route('lecturers.grading.store', $class->id) }}" method="POST">
            @csrf

            <div class="px-8 py-6 bg-primary-50/50 dark:bg-primary-900/20 border-b border-primary-100/50 dark:border-primary-800/50 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-primary-900 dark:text-white text-base">{{ __('Student Grading List') }}</h3>
                    <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-0.5">{{ __('Enter numeric value (0.00 - 100.00) for each component') }}</p>
                </div>
                <button type="submit" class="btn-primary-saas px-8 py-3 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20 hover:shadow-primary-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                    {{ __('Save Changes') }}
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-primary-50/30 dark:bg-primary-900/10 border-b border-primary-100/30 dark:border-primary-800/30">
                            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest w-12 text-center">#</th>
                            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest">{{ __('Student') }}</th>
                            <!-- Component Columns -->
                            <th class="py-5 px-3 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-24">{{ __('Attendance') }} ({{ $components['attendance'] }}%)</th>
                            <th class="py-5 px-3 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-24">{{ __('Midterm') }} ({{ $components['midterm'] }}%)</th>
                            <th class="py-5 px-3 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-24">{{ __('Final Exam') }} ({{ $components['final_exam'] }}%)</th>
                            @if($class->course->has_practical)
                            <th class="py-5 px-3 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-24">{{ __('Practical Attendance') }} ({{ $components['practical_attendance'] }}%)</th>
                            <th class="py-5 px-3 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-24">{{ __('Practical Exam') }} ({{ $components['practical_exam'] }}%)</th>
                            @endif
                            <th class="py-5 px-6 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-28">{{ __('Total') }}</th>
                            <th class="py-5 px-8 text-[10px] font-black text-primary-400 uppercase tracking-widest text-center w-32">{{ __('Grade') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary-50/50 dark:divide-primary-800/30">
                        @forelse($class->studyPlanDetails as $index => $detail)
                        @php
                        $grade = $class->grades->where('student_id', $detail->studyPlan->student_id)->first();
                        @endphp
                        <tr class="hover:bg-primary-50/20 dark:hover:bg-primary-900/10 transition-colors group">
                            <td class="py-5 px-6 text-xs font-bold text-primary-400 text-center">{{ $index + 1 }}</td>
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-xl bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center text-primary-600 dark:text-primary-400 text-xs font-black border border-primary-100 dark:border-primary-800">
                                        {{ strtoupper(substr($detail->studyPlan->student->user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-primary-900 dark:text-white group-hover:text-primary-primary transition-colors">{{ $detail->studyPlan->student->user->name }}</span>
                                        <span class="text-[10px] font-bold text-primary-500 dark:text-primary-400 font-mono tracking-wider">{{ $detail->studyPlan->student->student_number }}</span>
                                    </div>
                                </div>
                            </td>
                            <!-- Component Inputs -->
                            <td class="py-5 px-3 text-center">
                                <input type="number"
                                    name="grades[{{ $detail->studyPlan->student_id }}][attendance]"
                                    value="{{ $grade?->attendance }}"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    class="input-saas text-center font-black text-xs py-2 px-2 w-full focus:ring-4 focus:ring-primary-primary/10"
                                    placeholder="0.00">
                            </td>
                            <td class="py-5 px-3 text-center">
                                <input type="number"
                                    name="grades[{{ $detail->studyPlan->student_id }}][midterm]"
                                    value="{{ $grade?->midterm }}"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    class="input-saas text-center font-black text-xs py-2 px-2 w-full focus:ring-4 focus:ring-primary-primary/10"
                                    placeholder="0.00">
                            </td>
                            <td class="py-5 px-3 text-center">
                                <input type="number"
                                    name="grades[{{ $detail->studyPlan->student_id }}][final_exam]"
                                    value="{{ $grade?->final_exam }}"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    class="input-saas text-center font-black text-xs py-2 px-2 w-full focus:ring-4 focus:ring-primary-primary/10"
                                    placeholder="0.00">
                            </td>
                            @if($class->course->has_practical)
                            <td class="py-5 px-3 text-center">
                                <input type="number"
                                    name="grades[{{ $detail->studyPlan->student_id }}][practical_attendance]"
                                    value="{{ $grade?->practical_attendance }}"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    class="input-saas text-center font-black text-xs py-2 px-2 w-full focus:ring-4 focus:ring-primary-primary/10"
                                    placeholder="0.00">
                            </td>
                            <td class="py-5 px-3 text-center">
                                <input type="number"
                                    name="grades[{{ $detail->studyPlan->student_id }}][practical_exam]"
                                    value="{{ $grade?->practical_exam }}"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    class="input-saas text-center font-black text-xs py-2 px-2 w-full focus:ring-4 focus:ring-primary-primary/10"
                                    placeholder="0.00">
                            </td>
                            @endif
                            <td class="py-5 px-6 text-center">
                                <div class="text-sm font-black text-primary-900 dark:text-white">
                                    {{ $grade?->numeric_grade ?? '-' }}
                                </div>
                            </td>
                            <td class="py-5 px-8 text-center">
                                @if($grade?->letter_grade)
                                @php
                                $gradeColors = [
                                'A' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/50',
                                'B+' => 'bg-primary-50 text-primary-600 border-primary-100 dark:bg-primary-950/30 dark:text-primary-400 dark:border-primary-900/50',
                                'B' => 'bg-primary-50 text-primary-600 border-primary-100 dark:bg-primary-950/30 dark:text-primary-400 dark:border-primary-900/50',
                                'C+' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50',
                                'C' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50',
                                'D' => 'bg-red-50 text-red-600 border-red-100 dark:bg-red-950/30 dark:text-red-400 dark:border-red-900/50',
                                'E' => 'bg-red-50 text-red-600 border-red-100 dark:bg-red-950/30 dark:text-red-400 dark:border-red-900/50',
                                ];
                                @endphp
                                <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $gradeColors[$grade->letter_grade] ?? 'bg-primary-50 text-primary-400' }}">
                                    {{ $grade->letter_grade }}
                                </span>
                                @else
                                <span class="w-2 h-2 rounded-full bg-primary-100 dark:bg-primary-800 inline-block"></span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $class->course->has_practical ? '9' : '7' }}" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-primary-50 dark:bg-primary-900/50 rounded-2xl flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 3 0 0112 0v1zm0 0h6v-1a6 3 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-primary-400 text-sm font-bold uppercase tracking-widest">{{ __('No students enrolled in this class.') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-6 bg-primary-50/50 dark:bg-primary-900/20 border-t border-primary-100/50 dark:border-primary-800/50 flex justify-end">
                <button type="submit" class="btn-primary-saas px-10 py-3.5 rounded-xl text-sm font-black shadow-lg shadow-primary-600/20 hover:shadow-primary-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                    {{ __('Finalize & Save Grades') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>