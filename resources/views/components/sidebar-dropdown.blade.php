@props(['title', 'active' => false])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }" class="space-y-1">
    <button @click="open = !open"
        class="sidebar-link flex items-center justify-between w-full gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ $active ? 'active' : '' }}">
        <div class="flex items-center gap-3">
            {{ $icon }}
            <span class="sidebar-text">{{ $title }}</span>
        </div>
        <svg class="w-4 h-4 transition-transform sidebar-text" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open" x-collapse class="pl-4 space-y-1">
        {{ $slot }}
    </div>
</div>