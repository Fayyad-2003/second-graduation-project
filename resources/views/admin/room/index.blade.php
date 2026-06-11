<x-app-layout>
    <x-slot name="header">
        {{ __('Room Master Data') }}
    </x-slot>

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm text-primary-secondary dark:text-gray-400">{{ __('Manage classroom data in the system') }}
            </p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <form method="GET" class="flex-1 md:flex-none">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('Search for rooms / buildings...') }}"
                    class="input-saas px-4 py-2.5 text-sm w-full md:w-64 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
            </form>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('Add Room') }}
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-slate-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-gray-400">{{ __('Total Rooms') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-slate-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['active'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-gray-400">{{ __('Active Rooms') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-slate-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['capacity'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-gray-400">{{ __('Total Capacity') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-slate-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['building_count'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-gray-400">{{ __('Building') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <!-- Table Card (Desktop) -->
    <div class="hidden md:block card-saas overflow-hidden dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead>
                    <tr class="bg-primary-light/30 dark:bg-gray-900 border-b border-primary-light dark:border-gray-700">
                        <th
                            class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase tracking-wider w-16">
                            #</th>

                        <!-- Sortable: Code -->
                        <th
                            class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.room.index', array_merge(request()->all(), ['sort' => 'code_room', 'order' => request('sort') == 'code_room' && request('order') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="group flex items-center gap-1 hover:text-primary-primary transition">
                                {{ __('Code') }}
                                <span
                                    class="flex flex-col text-[10px] leading-none {{ request('sort') == 'code_room' ? 'text-primary-primary' : 'text-gray-300' }}">
                                    <i
                                        class="opacity-{{ request('sort') == 'code_room' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i
                                        class="opacity-{{ request('sort') == 'code_room' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Name Room -->
                        <th
                            class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.room.index', array_merge(request()->all(), ['sort' => 'room_name', 'order' => request('sort') == 'room_name' && request('order') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="group flex items-center gap-1 hover:text-primary-primary transition">
                                {{ __('Room Name') }}
                                <span
                                    class="flex flex-col text-[10px] leading-none {{ request('sort') == 'room_name' ? 'text-primary-primary' : 'text-gray-300' }}">
                                    <i
                                        class="opacity-{{ request('sort') == 'room_name' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i
                                        class="opacity-{{ request('sort') == 'room_name' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Location (Building) -->
                        <th
                            class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.room.index', array_merge(request()->all(), ['sort' => 'building', 'order' => request('sort') == 'building' && request('order') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="group flex items-center gap-1 hover:text-primary-primary transition">
                                {{ __('Location') }}
                                <span
                                    class="flex flex-col text-[10px] leading-none {{ request('sort') == 'building' ? 'text-primary-primary' : 'text-gray-300' }}">
                                    <i
                                        class="opacity-{{ request('sort') == 'building' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i
                                        class="opacity-{{ request('sort') == 'building' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Capacity -->
                        <th
                            class="text-center py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.room.index', array_merge(request()->all(), ['sort' => 'capacity', 'order' => request('sort') == 'capacity' && request('order') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="group flex items-center gap-1 hover:text-primary-primary transition justify-center">
                                {{ __('Capacity') }}
                                <span
                                    class="flex flex-col text-[10px] leading-none {{ request('sort') == 'capacity' ? 'text-primary-primary' : 'text-gray-300' }}">
                                    <i
                                        class="opacity-{{ request('sort') == 'capacity' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i
                                        class="opacity-{{ request('sort') == 'capacity' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <th
                            class="text-center py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th
                            class="text-right py-3 px-5 text-xs font-semibold text-primary-secondary dark:text-gray-400 uppercase tracking-wider w-32">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-light dark:divide-gray-700">
                    @forelse($roomList as $index => $room)
                    <tr class="hover:bg-primary-light/10 dark:hover:bg-gray-700/30 transition">
                        <td class="py-4 px-5 text-sm text-primary-secondary dark:text-gray-400">
                            {{ $roomList->firstItem() + $index }}
                        </td>
                        <td class="py-4 px-5">
                            <span
                                class="inline-flex px-3 py-1.5 text-sm font-semibold bg-primary-primary text-white dark:bg-blue-600 rounded-lg">{{ $room->room_code }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <p class="font-medium text-primary-dark dark:text-white">{{ $room->room_name }}</p>
                            @if($room->facilities)
                            <p class="text-xs text-primary-secondary dark:text-gray-400 mt-1">
                                {{ Str::limit($room->facilities, 50) }}
                            </p>
                            @endif
                        </td>
                        <td class="py-4 px-5">
                            @if($room->building)
                            <span class="text-sm text-primary-dark dark:text-white">{{ $room->building }}</span>
                            @if($room->floor)
                            <span class="text-xs text-primary-secondary dark:text-gray-400"> • Lt.
                                {{ $room->floor }}</span>
                            @endif
                            @else
                            <span class="text-sm text-primary-secondary dark:text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-center">
                            <span
                                class="inline-flex px-2.5 py-1 text-xs font-medium bg-primary-secondary/10 text-primary-secondary dark:bg-gray-700 dark:text-gray-300 rounded-full">{{ $room->capacity }}
                                {{ __('People') }}</span>
                        </td>
                        <td class="py-4 px-5 text-center">
                            @if($room->is_active)
                            <span
                                class="inline-flex px-2.5 py-1 text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400 rounded-full">{{ __('Active') }}</span>
                            @else
                            <span
                                class="inline-flex px-2.5 py-1 text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400 rounded-full">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="editRoom({{ json_encode($room) }})"
                                    class="p-2 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-lg transition"
                                    title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.room.destroy', $room) }}" method="POST"
                                    onsubmit="return confirm('{{ __('Delete this room?') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-primary-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="{{ __('Delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-primary-secondary">
                            <p class="mb-2">{{ __('No room data available') }}</p>
                            <a href="{{ route('admin.room.index') }}"
                                class="text-sm text-primary-primary hover:underline">{{ __('Reset Filters') }}</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-primary-light dark:border-gray-700 bg-white dark:bg-gray-800">
            {{ $roomList->links() }}
        </div>
    </div>

    <!-- Mobile Card List -->
    <div class="md:hidden space-y-4">
        @forelse($roomList as $room)
        <div class="card-saas p-4 dark:bg-gray-800">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <span
                        class="inline-flex px-2.5 py-1 text-xs font-semibold bg-primary-primary text-white dark:bg-blue-600 rounded-md mb-2">{{ $room->code_room }}</span>
                    <h4 class="font-bold text-primary-dark dark:text-white">{{ $room->name_room }}</h4>
                    @if($room->facilities)
                    <p class="text-xs text-primary-secondary dark:text-gray-400 mt-1 line-clamp-1">{{ $room->facilities }}
                    </p>
                    @endif
                </div>
                <div>
                    @if($room->is_active)
                    <span
                        class="inline-flex px-2 py-1 text-[10px] font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400 rounded-full">{{ __('Active') }}</span>
                    @else
                    <span
                        class="inline-flex px-2 py-1 text-[10px] font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400 rounded-full">{{ __('Inactive') }}</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg">
                    <span
                        class="block text-[10px] text-primary-secondary dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('Location') }}</span>
                    <p class="text-sm font-medium text-primary-dark dark:text-white">{{ $room->building ?? '-' }}</p>
                    @if($room->floor)
                    <p class="text-xs text-primary-secondary dark:text-gray-400">{{ __('Floor') }} {{ $room->floor }}
                    </p>
                    @endif
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg">
                    <span
                        class="block text-[10px] text-primary-secondary dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('Capacity') }}</span>
                    <p class="text-sm font-medium text-primary-dark dark:text-white">{{ $room->capacity }}
                        {{ __('People') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 pt-3 border-t border-primary-light dark:border-gray-700">
                <button onclick="editRoom({{ json_encode($room) }})"
                    class="flex-1 py-2 text-sm font-medium text-primary-secondary bg-primary-light/50 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-primary-light hover:text-primary-primary dark:hover:bg-gray-600 transition text-center">
                    {{ __('Edit') }}
                </button>
                <form action="{{ route('admin.room.destroy', $room) }}" method="POST"
                    onsubmit="return confirm('{{ __('Delete this room?') }}')" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="w-full py-2 text-sm font-medium text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400 rounded-lg hover:bg-red-100 hover:text-red-700 dark:hover:bg-red-900/40 transition">
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="card-saas p-8 text-center">
            <p class="text-primary-secondary dark:text-gray-400 mb-2">{{ __('No room data available') }}</p>
            <a href="{{ route('admin.room.index') }}"
                class="text-sm text-primary-primary hover:underline">{{ __('Reset Filters') }}</a>
        </div>
        @endforelse
    </div>

    @if($roomList->hasPages())
    <div
        class="md:hidden card-saas px-5 py-4 border-t border-primary-light dark:border-gray-700 dark:bg-gray-800 mt-4 md:mt-0">
        {{ $roomList->links() }}
    </div>
    @endif
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-lg animate-fade-in max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-primary-light dark:border-gray-700">
                <h3 class="text-lg font-semibold text-primary-dark dark:text-white">{{ __('Add Room') }}</h3>
            </div>
            <form action="{{ route('admin.room.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Room Code *') }}</label>
                            <input type="text" name="code_room"
                                class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                                placeholder="{{ __('Example: LT-101') }}" required>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Capacity *') }}</label>
                            <input type="number" name="capacity" min="1"
                                class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                                placeholder="40" value="40" required>
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Room Name *') }}</label>
                        <input type="text" name="name_room"
                            class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                            placeholder="{{ __('Example: Computer Lab 1') }}" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Building') }}</label>
                            <input type="text" name="building"
                                class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                                placeholder="{{ __('Example: Building A') }}">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Floor') }}</label>
                            <input type="number" name="floor" min="1"
                                class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                                placeholder="1">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Facilities') }}</label>
                        <textarea name="facilities" rows="2"
                            class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                            placeholder="{{ __('Example: AC, Projector, Whiteboard') }}"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="createIsActive" checked
                            class="rounded border-primary-light text-primary-primary focus:ring-primary-primary dark:border-gray-700 dark:bg-gray-900">
                        <label for="createIsActive"
                            class="text-sm text-primary-dark dark:text-gray-300">{{ __('Active Room') }}</label>
                    </div>
                </div>
                <div
                    class="px-6 py-4 border-t border-primary-light dark:border-gray-700 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="btn-ghost-saas px-4 py-2 rounded-lg text-sm font-medium dark:text-white">{{ __('Cancel') }}</button>
                    <button type="submit"
                        class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-lg animate-fade-in max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-primary-light dark:border-gray-700">
                <h3 class="text-lg font-semibold text-primary-dark dark:text-white">{{ __('Edit Room') }}</h3>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Room Code *') }}</label>
                            <input type="text" name="code_room" id="editCode"
                                class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                                required>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Capacity *') }}</label>
                            <input type="number" name="capacity" id="editCapacity" min="1"
                                class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                                required>
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Room Name *') }}</label>
                        <input type="text" name="name_room" id="editName"
                            class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"
                            required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Building') }}</label>
                            <input type="text" name="building" id="editBuilding"
                                class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Floor') }}</label>
                            <input type="number" name="floor" id="editFloor" min="1"
                                class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-primary-dark dark:text-gray-300 mb-2">{{ __('Facilities') }}</label>
                        <textarea name="facilities" id="editFacilities" rows="2"
                            class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="editIsActive"
                            class="rounded border-primary-light text-primary-primary focus:ring-primary-primary dark:border-gray-700 dark:bg-gray-900">
                        <label for="editIsActive"
                            class="text-sm text-primary-dark dark:text-gray-300">{{ __('Active Room') }}</label>
                    </div>
                </div>
                <div
                    class="px-6 py-4 border-t border-primary-light dark:border-gray-700 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="btn-ghost-saas px-4 py-2 rounded-lg text-sm font-medium dark:text-white">{{ __('Cancel') }}</button>
                    <button type="submit"
                        class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editRoom(data) {
            document.getElementById('editForm').action = `/admin/room/${data.id}`;
            document.getElementById('editCode').value = data.room_code;
            document.getElementById('editName').value = data.room_name;
            document.getElementById('editCapacity').value = data.capacity;
            document.getElementById('editBuilding').value = data.building || '';
            document.getElementById('editFloor').value = data.floor || '';
            document.getElementById('editFacilities').value = data.facilities || '';
            document.getElementById('editIsActive').checked = data.is_active;
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>