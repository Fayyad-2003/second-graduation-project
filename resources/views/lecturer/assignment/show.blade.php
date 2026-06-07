<x-app-layout>
    <x-slot name="header">
        {{ $assignment->title }}
    </x-slot>

    <!-- Back Link -->
    <div class="mb-4">
        <a href="{{ route('lecturers.assignment.index', $class->id) }}" class="text-sm text-siakad-secondary hover:text-siakad-primary transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            {{ __('Back to Assignment List') }}
        </a>
    </div>

    <!-- Assignment Info -->
    <div class="card-saas p-5 mb-6 dark:bg-gray-800">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-siakad-dark dark:text-white">{{ $assignment->title }}</h2>
                <p class="text-sm text-siakad-secondary dark:text-gray-400">{{ $class->course->course_name }} - {{ $class->class_name }}</p>
            </div>
            <div class="text-right">
                @if($assignment->isOverdue())
                <span class="px-3 py-1 text-sm bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg">{{ __('Overdue') }}</span>
                @else
                <span class="px-3 py-1 text-sm bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-lg">{{ $assignment->remaining_time }}</span>
                @endif
            </div>
        </div>
        @if($assignment->description)
        <p class="text-siakad-secondary dark:text-gray-400 mb-4">{{ $assignment->description }}</p>
        @endif
        <div class="flex items-center gap-6 text-sm">
            <span><strong>{{ __('Deadline') }}:</strong> {{ $assignment->deadline->format('d M Y, H:i') }}</span>
            <span><strong>{{ __('Extensions') }}:</strong> {{ $assignment->allowed_extensions }}</span>
            <span><strong>{{ __('Max Size') }}:</strong> {{ $assignment->formatted_max_file_size }}</span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="card-saas p-4 dark:bg-gray-800 text-center">
            <p class="text-2xl font-bold text-siakad-primary">{{ $enrolledStudents->count() }}</p>
            <p class="text-xs text-siakad-secondary">{{ __('Total Students') }}</p>
        </div>
        <div class="card-saas p-4 dark:bg-gray-800 text-center">
            <p class="text-2xl font-bold text-green-500">{{ $assignment->submissions->count() }}</p>
            <p class="text-xs text-siakad-secondary">{{ __('Submitted') }}</p>
        </div>
        <div class="card-saas p-4 dark:bg-gray-800 text-center">
            <p class="text-2xl font-bold text-blue-500">{{ $assignment->submissions->whereNotNull('grade')->count() }}</p>
            <p class="text-xs text-siakad-secondary">{{ __('Graded') }}</p>
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="card-saas overflow-hidden dark:bg-gray-800">
        <div class="px-5 py-4 border-b border-siakad-light dark:border-gray-700">
            <h3 class="font-semibold text-siakad-dark dark:text-white">{{ __('Submission List') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase">{{ __('Student') }}</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase">{{ __('Student ID') }}</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase">{{ __('Status') }}</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase">{{ __('Submit Time') }}</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase">{{ __('Grade') }}</th>
                        <th class="py-3 px-4 text-right text-xs font-medium text-siakad-secondary dark:text-gray-400 uppercase">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-siakad-light dark:divide-gray-700">
                    @foreach($enrolledStudents as $student)
                    @php
                        $submission = $assignment->submissions->where('student_id', $student->id)->first();
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="py-3 px-4 text-sm text-siakad-dark dark:text-white">{{ $student->user->name }}</td>
                        <td class="py-3 px-4 text-sm text-siakad-secondary dark:text-gray-400 font-mono">{{ $student->student_number }}</td>
                        <td class="py-3 px-4">
                            @if($submission)
                                @if($submission->isOnTime())
                                <span class="px-2 py-0.5 text-xs bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded">{{ __('On Time') }}</span>
                                @else
                                <span class="px-2 py-0.5 text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded">{{ __('Late') }}</span>
                                @endif
                            @else
                            <span class="px-2 py-0.5 text-xs bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded">{{ __('Not Submitted') }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-siakad-secondary dark:text-gray-400">
                            {{ $submission?->submitted_at?->format('d M Y, H:i') ?? '-' }}
                        </td>
                        <td class="py-3 px-4">
                            @if($submission && $submission->isGraded())
                            <span class="font-bold text-siakad-dark dark:text-white">{{ $submission->grade }}</span>
                            <span class="text-xs text-siakad-secondary">({{ $submission->grade_letter }})</span>
                            @elseif($submission)
                            <span class="text-siakad-secondary">{{ __('Not Graded') }}</span>
                            @else
                            <span class="text-siakad-secondary">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($submission)
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('lecturers.assignment.submission.download', [$class->id, $submission->id]) }}" class="p-1.5 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded transition" title="{{ __('Download') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                                <button onclick="openGradeModal({{ $submission->id }}, '{{ $student->user->name }}', {{ $submission->grade ?? 'null' }}, '{{ addslashes($submission->feedback ?? '') }}')" class="px-2 py-1 text-xs bg-siakad-primary text-white rounded hover:bg-siakad-primary/90 transition">
                                    {{ $submission->isGraded() ? __('Edit Grade') : __('Give Grade') }}
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grade Modal -->
    <div id="gradeModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-md animate-fade-in">
            <div class="px-6 py-4 border-b border-siakad-light dark:border-gray-700">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white">{{ __('Grade') }}</h3>
                <p class="text-sm text-siakad-secondary" id="gradeStudentName"></p>
            </div>
            <form id="gradeForm" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2">{{ __('Grade (0-100)') }}</label>
                        <input type="number" name="grade" id="gradeGrade" min="0" max="100" step="0.01" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2">{{ __('Feedback (Optional)') }}</label>
                        <textarea name="feedback" id="gradeFeedback" rows="3" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="{{ __('Leave a comment...') }}"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-siakad-light dark:border-gray-700 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('gradeModal').classList.add('hidden')" class="btn-ghost-saas px-4 py-2 rounded-lg text-sm font-medium dark:text-white">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium">{{ __('Save Grade') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openGradeModal(submissionId, studentName, grade, feedback) {
            document.getElementById('gradeForm').action = `/lecturer/assignment/{{ $class->id }}/submission/${submissionId}/grade`;
            document.getElementById('gradeStudentName').textContent = studentName;
            document.getElementById('gradeGrade').value = grade || '';
            document.getElementById('gradeFeedback').value = feedback || '';
            document.getElementById('gradeModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
