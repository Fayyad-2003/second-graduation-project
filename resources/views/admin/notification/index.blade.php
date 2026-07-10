<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">{{ __('Notification Center') }}</h1>
        <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-primary-200"></span>
            {{ __('Broadcast announcements and system alerts to students and staff.') }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Send Notification Form -->
        <div class="lg:col-span-1">
            <div class="card-saas p-8 sticky top-6">
                <div class="flex items-center gap-4 mb-8 pb-4 border-b border-primary-50 dark:border-primary-800">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 dark:bg-primary-900 flex items-center justify-center text-primary-primary shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-primary-900 dark:text-white">{{ __('New Broadcast') }}</h3>
                        <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest">{{ __('System Alert') }}</p>
                    </div>
                </div>

                <form action="{{ route('admin.notification.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Recipients') }}</label>
                            <select name="target_type" id="target_type" class="input-saas w-full py-2.5" onchange="toggleTargetId(this.value)">
                                <option value="all_students">{{ __('All Students') }}</option>
                                <option value="instructors">{{ __('All Instructors') }}</option>
                                <option value="subject">{{ __('Specific Class') }}</option>
                                <option value="level">{{ __('Specific Semester') }}</option>
                            </select>
                        </div>

                        <div id="subject_select" class="hidden space-y-1.5 animate-fade-in">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Select Class') }}</label>
                            <select name="target_id_subject" class="input-saas w-full py-2.5">
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->course->course_name ?? '-' }} ({{ $class->class_name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="level_select" class="hidden space-y-1.5 animate-fade-in">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Select Semester') }}</label>
                            <select name="target_id_level" class="input-saas w-full py-2.5">
                                @foreach(range(1, 8) as $sem)
                                <option value="{{ $sem }}">{{ __('Semester') }} {{ $sem }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="target_id" id="target_id_final">

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Title') }}</label>
                            <input type="text" name="title" class="input-saas w-full py-2.5" placeholder="{{ __('e.g., Important Announcement') }}" required>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-primary-400 uppercase tracking-widest ml-1">{{ __('Message Content') }}</label>
                            <textarea name="message" rows="5" class="input-saas w-full py-2.5" placeholder="{{ __('Type your message here...') }}" required></textarea>
                        </div>

                        <button type="submit" class="btn-primary-saas w-full py-3.5 rounded-2xl font-black text-sm shadow-lg shadow-primary-600/20 flex items-center justify-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            {{ __('Send Notification') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notification History -->
        <div class="lg:col-span-2">
            <div class="card-saas overflow-hidden">
                <div class="px-8 py-6 border-b border-primary-50 dark:border-primary-800 bg-primary-50/30 dark:bg-primary-900/20 flex items-center justify-between">
                    <h3 class="text-lg font-black text-primary-900 dark:text-white flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-primary-primary rounded-full"></div>
                        {{ __('Broadcast History') }}
                    </h3>
                </div>
                <div class="divide-y divide-primary-50 dark:divide-primary-800/50">
                    @forelse($sentNotifications as $notif)
                    <div class="p-8 hover:bg-primary-50/20 transition-colors group">
                        <div class="flex items-start justify-between gap-6">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    <h4 class="text-base font-black text-primary-900 dark:text-white leading-tight">{{ $notif->title }}</h4>
                                    <span class="px-3 py-1 bg-primary-50 text-primary-primary dark:bg-primary-900/50 dark:text-primary-400 text-[9px] font-black rounded-full uppercase tracking-widest border border-primary-100 dark:border-primary-800">
                                        {{ __(ucwords(str_replace('_', ' ', $notif->target_type))) }}
                                    </span>
                                </div>
                                <p class="text-sm font-bold text-primary-600 dark:text-primary-400 leading-relaxed mb-6">{{ $notif->message }}</p>

                                <div class="flex flex-wrap items-center gap-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-primary-50 dark:bg-primary-900 flex items-center justify-center text-primary-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest">{{ $notif->sender->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-primary-50 dark:bg-primary-900 flex items-center justify-center text-primary-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest font-mono">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-24 text-center">
                        <div class="w-20 h-20 bg-primary-50 dark:bg-primary-900/50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-primary-200">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <p class="text-primary-400 font-bold uppercase tracking-widest text-[10px]">{{ __('No notifications sent yet') }}</p>
                    </div>
                    @endforelse
                </div>
                @if($sentNotifications->hasPages())
                <div class="px-8 py-6 border-t border-primary-50 dark:border-primary-800 bg-primary-50/20 dark:bg-primary-900/10">
                    {{ $sentNotifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleTargetId(type) {
            document.getElementById('subject_select').classList.add('hidden');
            document.getElementById('level_select').classList.add('hidden');

            if (type === 'subject') {
                document.getElementById('subject_select').classList.remove('hidden');
            } else if (type === 'level') {
                document.getElementById('level_select').classList.remove('hidden');
            }
        }

        document.querySelector('form').addEventListener('submit', function() {
            const type = document.getElementById('target_type').value;
            const targetIdFinal = document.getElementById('target_id_final');

            if (type === 'subject') {
                targetIdFinal.value = document.querySelector('select[name="target_id_subject"]').value;
            } else if (type === 'level') {
                targetIdFinal.value = document.querySelector('select[name="target_id_level"]').value;
            } else {
                targetIdFinal.value = '';
            }
        });
    </script>
    @endpush
</x-app-layout>