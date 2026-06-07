<x-app-layout>
    <x-slot name="header">
        {{ __('Instructor Notifications') }}
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Send Notification Form -->
        <div class="lg:col-span-1">
            <div class="card-saas p-6">
                <h3 class="text-lg font-semibold text-siakad-dark mb-4">{{ __('Send to Students') }}</h3>
                <p class="text-xs text-siakad-secondary mb-6">{{ __('Send messages to students enrolled in your classes.') }}</p>
                
                <form action="{{ route('lecturers.notification.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="target_type" value="subject">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark mb-1">{{ __('Select Your Class') }}</label>
                            <select name="target_id" class="input-saas w-full" required>
                                <option value="">{{ __('-- Choose Class --') }}</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->course->course_name }} ({{ $class->class_name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-siakad-dark mb-1">{{ __('Subject / Title') }}</label>
                            <input type="text" name="title" class="input-saas w-full" placeholder="{{ __('e.g., Tomorrow\'s Class Update') }}" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-siakad-dark mb-1">{{ __('Message') }}</label>
                            <textarea name="message" rows="5" class="input-saas w-full" placeholder="{{ __('Write your message to students...') }}" required></textarea>
                        </div>

                        <button type="submit" class="btn-primary-saas w-full py-2.5 rounded-lg font-semibold shadow-lg shadow-siakad-primary/20">
                            {{ __('Broadcast Message') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- History -->
        <div class="lg:col-span-2">
            <div class="card-saas overflow-hidden">
                <div class="px-6 py-4 border-b border-siakad-light">
                    <h3 class="font-semibold text-siakad-dark">{{ __('Your Recent Broadcasts') }}</h3>
                </div>
                <div class="divide-y divide-siakad-light/50">
                    @forelse($sentNotifications as $notif)
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-bold text-siakad-dark mb-1">{{ $notif->title }}</h4>
                                <p class="text-sm text-siakad-secondary mb-3">{{ $notif->message }}</p>
                                <div class="flex items-center gap-4 text-xs text-siakad-secondary/70">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $notif->created_at->format('d M Y, H:i') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path></svg>
                                        {{ __('Subject Target') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 text-center">
                        <p class="text-siakad-secondary">{{ __('You haven\'t sent any broadcasts yet.') }}</p>
                    </div>
                    @endforelse
                </div>
                @if($sentNotifications->hasPages())
                <div class="px-6 py-4 bg-siakad-light/10 border-t border-siakad-light">
                    {{ $sentNotifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
