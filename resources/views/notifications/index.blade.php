<x-app-layout>
    <x-slot name="header">{{ __('Notifications') }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm text-primary-secondary">{{ __('All your notifications') }}</p>
        </div>
        @if($unreadCount > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit"
                    class="btn-ghost-saas px-4 py-2 rounded-lg text-sm font-medium dark:text-white text-indigo-600 hover:bg-indigo-50">
                    {{ __('Mark all as read') }}
                </button>
            </form>
        @endif
    </div>

    <div class="card-saas overflow-hidden">
        @forelse($notifications as $notif)
            @php
                $isRead = $notif instanceof \App\Models\Notification ? $notif->isRead() : !is_null($notif->read_at);
            @endphp
            <div class="flex items-start gap-4 p-5 {{ !$isRead ? 'notif-unread' : '' }}"
                style="border-bottom: 1px solid var(--border-color);">
                <div
                    class="w-10 h-10 rounded-xl bg-{{ $notif->color ?? 'blue' }}-100 flex items-center justify-center text-lg">
                    {{ $notif->icon ?? '🔔' }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-semibold {{ !$isRead ? 'text-indigo-700' : 'text-primary-dark' }}">
                                {{ $notif->title }}</h3>
                            <p class="text-sm text-primary-secondary mt-1">{{ $notif->message }}</p>
                            @php $data = is_string($notif->data) ? json_decode($notif->data, true) : $notif->data; @endphp
                            @if(!empty($data['changes']))
                                <div class="mt-2 text-xs space-y-1">
                                    @foreach($data['changes'] as $field => $change)
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ ucfirst($field) }}</span>
                                            <span class="text-slate-400">{{ $change['old'] }}</span>
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                            <span class="font-medium text-indigo-600">{{ $change['new'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <p class="text-xs text-primary-secondary whitespace-nowrap">
                            {{ $notif->created_at instanceof \Carbon\Carbon ? $notif->created_at->diffForHumans() : \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @if(!$isRead)
                    <form action="{{ route('notifications.mark-read', $notif->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="p-2 rounded-lg hover:bg-slate-100 transition text-slate-400 hover:text-indigo-600"
                            title="{{ __('Mark as read') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-1">{{ __('No notifications') }}</h3>
                <p class="text-sm text-slate-500">{{ __("You don't have any notifications at this time") }}</p>
            </div>
        @endforelse
    </div>
</x-app-layout>