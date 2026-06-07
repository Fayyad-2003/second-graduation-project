<x-app-layout>
    <x-slot name="header">
        {{ __('Input Presence') }} - {{ __('Meeting') }} {{ $meeting->meeting_number }}
    </x-slot>

    <!-- Breadcrumb -->
    <div class="mb-5">
            <a href="{{ route('lecturers.attendance-input.class', $class) }}" class="inline-flex items-center gap-2 text-siakad-secondary hover:text-siakad-primary transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            {{ __('Back to') }} {{ $class->course->course_name }}
        </a>
    </div>

    <!-- Meeting Info -->
    <div class="card-saas p-5 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl bg-siakad-primary text-white flex items-center justify-center font-bold text-xl flex-shrink-0">
                    {{ $meeting->meeting_number }}
                </div>
                <div>
                    <h1 class="text-xl font-bold text-siakad-dark">{{ __('Meeting') }} {{ $meeting->meeting_number }}</h1>
                    <p class="text-sm text-siakad-secondary mt-0.5">{{ $class->course->course_name }} • {{ __('Class') }} {{ $class->class_name }}</p>
                    <p class="text-xs text-siakad-secondary mt-1">{{ $meeting->date->format('l, d F Y') }} • {{ $meeting->topic ?? __('No material yet') }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-siakad-primary">{{ $studentList->count() }}</p>
                <p class="text-sm text-siakad-secondary">{{ __('Students') }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('lecturers.attendance-input.store', $meeting) }}" method="POST">
        @csrf
        
        <div class="card-saas overflow-hidden mb-6">
            <!-- Quick Actions -->
            <div class="p-4 bg-siakad-light/50 border-b border-siakad-light flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-sm text-siakad-secondary">{{ __('Set attendance status for each student') }}</p>
                <div class="flex gap-2">
                    <button type="button" onclick="setAllStatus('present')" class="px-3 py-1.5 text-xs font-medium bg-siakad-primary/10 text-siakad-primary rounded-lg hover:bg-siakad-primary/20 transition">
                        {{ __('All Present') }}
                    </button>
                    <button type="button" onclick="setAllStatus('absent')" class="px-3 py-1.5 text-xs font-medium bg-siakad-light text-siakad-secondary rounded-lg hover:bg-siakad-primary/10 transition">
                        {{ __('All Absent') }}
                    </button>
                </div>
            </div>

            @if($studentList->isEmpty())
            <div class="p-10 text-center">
                <div class="w-16 h-16 rounded-full bg-siakad-light/50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <p class="text-siakad-secondary">{{ __('No students currently registered in this class') }}</p>
            </div>
            @else
            <div class="divide-y divide-siakad-light">
                @foreach($studentList as $student)
                @php
                    $existing = $existingAttendance[$student->id] ?? null;
                    $currentStatus = old("attendance.{$student->id}", $existing?->status ?? 'present');
                @endphp
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-siakad-light/30 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-siakad-primary text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-siakad-dark">{{ $student->user->name }}</p>
                            <p class="text-sm text-siakad-secondary">{{ $student->student_number }}</p>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 flex-wrap sm:flex-nowrap">
                        <label class="cursor-pointer">
                            <input type="radio" name="attendance[{{ $student->id }}]" value="present" class="peer hidden" {{ $currentStatus === 'present' ? 'checked' : '' }}>
                            <span class="px-3 py-2 rounded-lg text-sm font-medium border transition peer-checked:bg-siakad-primary peer-checked:text-white peer-checked:border-siakad-primary bg-white text-siakad-secondary border-siakad-light hover:border-siakad-primary/50">
                                {{ __('Present') }}
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="attendance[{{ $student->id }}]" value="sick" class="peer hidden" {{ $currentStatus === 'sick' ? 'checked' : '' }}>
                            <span class="px-3 py-2 rounded-lg text-sm font-medium border transition peer-checked:bg-siakad-primary/70 peer-checked:text-white peer-checked:border-siakad-primary/70 bg-white text-siakad-secondary border-siakad-light hover:border-siakad-primary/50">
                                {{ __('Sick') }}
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="attendance[{{ $student->id }}]" value="excused" class="peer hidden" {{ $currentStatus === 'excused' ? 'checked' : '' }}>
                            <span class="px-3 py-2 rounded-lg text-sm font-medium border transition peer-checked:bg-siakad-primary/50 peer-checked:text-white peer-checked:border-siakad-primary/50 bg-white text-siakad-secondary border-siakad-light hover:border-siakad-primary/50">
                                {{ __('Excused') }}
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="peer hidden" {{ $currentStatus === 'absent' ? 'checked' : '' }}>
                            <span class="px-3 py-2 rounded-lg text-sm font-medium border transition peer-checked:bg-siakad-secondary peer-checked:text-white peer-checked:border-siakad-secondary bg-white text-siakad-secondary border-siakad-light hover:border-siakad-secondary/50">
                                {{ __('Absent') }}
                            </span>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        @if($studentList->isNotEmpty())
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-3 bg-siakad-primary text-white rounded-lg font-medium hover:bg-siakad-primary/90 transition flex items-center gap-2 min-h-[44px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ __('Save Presence') }}
            </button>
            <a href="{{ route('lecturers.attendance-input.class', $class) }}" class="px-6 py-3 border border-siakad-light text-siakad-secondary rounded-lg font-medium hover:bg-siakad-light/50 transition">
                {{ __('Cancel') }}
            </a>
        </div>
        @endif
    </form>

    <script>
        function setAllStatus(status) {
            document.querySelectorAll(`input[value="${status}"]`).forEach(input => {
                input.checked = true;
            });
        }
    </script>
</x-app-layout>
