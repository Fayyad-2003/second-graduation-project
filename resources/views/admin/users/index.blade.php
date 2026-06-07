<x-app-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight text-siakad-900 dark:text-white">{{ __('User Management') }}</h1>
        <p class="text-siakad-500 font-medium mt-2 flex items-center gap-2">
            <span class="w-8 h-px bg-siakad-200"></span>
            {{ __('Manage and monitor all registered system users and their roles.') }}
        </p>
    </div>

    <!-- Toolbar: Search, Filter, Add -->
    <div class="mb-8 card-saas p-6">
        <div class="flex flex-wrap items-center justify-between gap-6">
            <form method="GET" class="flex flex-wrap items-center gap-4 flex-1">
                <div class="relative flex-1 min-w-[240px] group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors">
                        <svg class="w-4 h-4 text-siakad-400 group-focus-within:text-siakad-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search by name or email...') }}" 
                        class="input-saas pl-11 pr-4 py-2.5 text-sm w-full">
                </div>
                <select name="role" class="input-saas px-4 py-2.5 text-sm w-full sm:w-48">
                    <option value="">{{ __('All Roles') }}</option>
                    <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>{{ __('Super Admin') }}</option>
                    <option value="admin_faculty" {{ request('role') == 'admin_faculty' ? 'selected' : '' }}>{{ __('Faculty Admin') }}</option>
                    <option value="lecturer" {{ request('role') == 'lecturer' ? 'selected' : '' }}>{{ __('Lecturer') }}</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>{{ __('Student') }}</option>
                </select>
                <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-siakad-600/20">{{ __('Filter') }}</button>
            </form>
            <button onclick="openModal('createModal')" class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg shadow-siakad-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                {{ __('Add New User') }}
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-soft dark:bg-emerald-950/20 dark:border-emerald-900/50 dark:text-emerald-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Desktop Table -->
    <div class="hidden md:block card-saas overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr class="bg-siakad-50/50 dark:bg-siakad-900/30 border-b border-siakad-100/50 dark:border-siakad-800/50">
                        <th class="py-5 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('User Info') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Email Address') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-center">{{ __('Role') }}</th>
                        <th class="py-5 px-6 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-start">{{ __('Faculty Context') }}</th>
                        <th class="py-5 px-8 text-[10px] font-black text-siakad-400 uppercase tracking-widest text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-siakad-50 dark:divide-siakad-800/50">
                    @forelse($users as $user)
                    <tr class="hover:bg-siakad-50/30 dark:hover:bg-siakad-900/20 transition-colors group">
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-4 text-start">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-siakad-50 to-siakad-100 dark:from-siakad-900/50 dark:to-siakad-800/50 flex items-center justify-center text-siakad-600 dark:text-siakad-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-siakad-900 dark:text-white truncate">{{ $user->name }}</p>
                                    @if($user->student)
                                    <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-wider text-start font-mono">{{ $user->student->student_number }}</p>
                                    @elseif($user->lecturer)
                                    <p class="text-[10px] text-siakad-400 font-bold uppercase tracking-wider text-start font-mono">{{ $user->lecturer->lecturer_number }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <span class="text-sm font-medium text-siakad-500">{{ $user->email }}</span>
                        </td>
                        <td class="py-5 px-6 text-center">
                            @php
                                $roleColors = [
                                    'superadmin' => 'bg-purple-50 text-purple-600 border-purple-100 dark:bg-purple-950/30 dark:text-purple-400 dark:border-purple-900/50',
                                    'admin_faculty' => 'bg-indigo-50 text-indigo-600 border-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 dark:border-indigo-900/50',
                                    'lecturer' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/50',
                                    'student' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50',
                                ];
                                $roleColor = $roleColors[$user->role] ?? 'bg-siakad-50 text-siakad-400 border-siakad-100';
                            @endphp
                            <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $roleColor }}">
                                @if($user->role === 'superadmin') {{ __('Super Admin') }}
                                @elseif($user->role === 'admin_faculty') {{ __('Faculty Admin') }}
                                @elseif($user->role === 'lecturer') {{ __('Lecturer') }}
                                @elseif($user->role === 'student') {{ __('Student') }}
                                @else {{ ucfirst($user->role) }} @endif
                            </span>
                        </td>
                        <td class="py-5 px-6 text-start">
                            <span class="text-xs font-bold text-siakad-600 dark:text-siakad-400">{{ $user->faculty?->name ?? ($user->student?->studyProgram?->faculty?->name ?? ($user->lecturer?->studyProgram?->faculty?->name ?? '-')) }}</span>
                        </td>
                        <td class="py-5 px-8 text-end">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal({{ json_encode($user) }})" class="p-2 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded-lg transition" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-siakad-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="{{ __('Delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-16 text-center text-siakad-400 font-bold text-sm">{{ __('No users found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card List -->
    <div class="md:hidden space-y-4 mb-6">
        @forelse($users as $user)
        <div class="card-saas p-6 group">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-siakad-50 to-siakad-100 dark:from-siakad-900/50 dark:to-siakad-800/50 flex items-center justify-center text-siakad-600 dark:text-siakad-400 text-sm font-black shadow-sm group-hover:scale-110 transition-transform">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-black text-siakad-900 dark:text-white truncate group-hover:text-siakad-primary transition-colors">{{ $user->name }}</h4>
                        <p class="text-[10px] text-siakad-400 font-black uppercase tracking-widest mt-1">{{ $user->email }}</p>
                    </div>
                </div>
                @php
                    $roleColor = $roleColors[$user->role] ?? 'bg-siakad-50 text-siakad-400 border-siakad-100';
                @endphp
                <span class="inline-flex px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border {{ $roleColor }}">
                    @if($user->role === 'superadmin') {{ __('Super Admin') }}
                    @elseif($user->role === 'admin_faculty') {{ __('Faculty Admin') }}
                    @elseif($user->role === 'lecturer') {{ __('Lecturer') }}
                    @elseif($user->role === 'student') {{ __('Student') }}
                    @else {{ ucfirst($user->role) }} @endif
                </span>
            </div>

            <div class="grid grid-cols-1 gap-3 mb-6">
                <div class="bg-siakad-50/50 dark:bg-siakad-900/50 p-3 rounded-2xl border border-siakad-100/50 dark:border-siakad-800/50">
                    <span class="block text-[10px] text-siakad-400 font-black uppercase tracking-widest mb-1">{{ __('Faculty Context') }}</span>
                    <span class="text-xs font-black text-siakad-900 dark:text-white line-clamp-1">{{ $user->faculty?->name ?? ($user->student?->studyProgram?->faculty?->name ?? ($user->lecturer?->studyProgram?->faculty?->name ?? '-')) }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-siakad-50 dark:border-siakad-800">
                <button onclick="openEditModal({{ json_encode($user) }})" class="flex-1 py-3 text-xs font-black text-siakad-700 dark:text-siakad-300 bg-siakad-50 dark:bg-siakad-800 rounded-xl hover:bg-siakad-primary hover:text-white transition-all text-center">
                    {{ __('Edit User') }}
                </button>
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')" class="flex-none">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-3 text-red-600 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="card-saas p-10 text-center">
            <p class="text-siakad-400 font-bold">{{ __('No user data available') }}</p>
        </div>
        @endforelse
    </div>

    @if($users->hasPages())
    <div class="md:hidden card-saas px-6 py-4 mb-6">
        {{ $users->links() }}
    </div>
    <div class="hidden md:block mt-6">
        {{ $users->links() }}
    </div>
    @endif

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-siakad-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('createModal')"></div>
            
            <div class="relative bg-white dark:bg-siakad-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden transform transition-all animate-fade-in">
                <!-- Decorative background -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-siakad-primary/5 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 bg-emerald-500/5 rounded-full"></div>

                <div class="relative">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-siakad-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-siakad-50 dark:bg-siakad-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            {{ __('Add User') }}
                        </h3>
                        <button onclick="closeModal('createModal')" class="text-siakad-400 hover:text-siakad-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Full Name') }}</label>
                                <input type="text" name="name" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="{{ __('Enter full name') }}" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Email Address') }}</label>
                                <input type="email" name="email" class="input-saas w-full px-4 py-3 text-sm rounded-xl" placeholder="user@siakad.com" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Password') }}</label>
                                <input type="password" name="password" minlength="8" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('System Role') }}</label>
                                <select name="role" id="createRole" required onchange="toggleFacultyField('create')" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                    <option value="superadmin">{{ __('Super Admin') }}</option>
                                    <option value="admin_faculty">{{ __('Faculty Admin') }}</option>
                                    <option value="lecturer">{{ __('Lecturer') }}</option>
                                    <option value="student">{{ __('Student') }}</option>
                                </select>
                            </div>
                            <div id="createFacultyField" class="hidden animate-fade-in">
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Assign Faculty') }}</label>
                                <select name="faculty_id" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                    <option value="">{{ __('-- Select Faculty --') }}</option>
                                    @foreach($faculties as $f)
                                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="closeModal('createModal')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-siakad-600/20">{{ __('Save User') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-siakad-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editModal')"></div>
            
            <div class="relative bg-white dark:bg-siakad-900 rounded-[2rem] shadow-2xl w-full max-w-lg p-8 overflow-hidden transform transition-all animate-fade-in">
                <!-- Decorative background -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-siakad-primary/5 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 bg-amber-500/5 rounded-full"></div>

                <div class="relative">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-siakad-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-siakad-50 dark:bg-siakad-800 flex items-center justify-center">
                                <svg class="w-5 h-5 text-siakad-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            {{ __('Edit User') }}
                        </h3>
                        <button onclick="closeModal('editModal')" class="text-siakad-400 hover:text-siakad-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form id="editForm" method="POST">
                        @csrf @method('PUT')
                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Full Name') }}</label>
                                <input type="text" name="name" id="editName" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Email Address') }}</label>
                                <input type="email" name="email" id="editEmail" class="input-saas w-full px-4 py-3 text-sm rounded-xl" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('New Password (Optional)') }}</label>
                                <input type="password" name="password" minlength="8" placeholder="{{ __('Leave empty to keep current') }}" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('System Role') }}</label>
                                <select name="role" id="editRole" required onchange="toggleFacultyField('edit')" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                    <option value="superadmin">{{ __('Super Admin') }}</option>
                                    <option value="admin_faculty">{{ __('Faculty Admin') }}</option>
                                    <option value="lecturer">{{ __('Lecturer') }}</option>
                                    <option value="student">{{ __('Student') }}</option>
                                </select>
                            </div>
                            <div id="editFacultyField" class="hidden animate-fade-in">
                                <label class="block text-[10px] font-black text-siakad-400 uppercase tracking-widest mb-2 ml-1">{{ __('Assign Faculty') }}</label>
                                <select name="faculty_id" id="editFacultyId" class="input-saas w-full px-4 py-3 text-sm rounded-xl">
                                    <option value="">{{ __('-- Select Faculty --') }}</option>
                                    @foreach($faculties as $f)
                                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-3">
                            <button type="button" onclick="closeModal('editModal')" class="btn-ghost-saas px-6 py-2.5 rounded-xl text-sm font-black">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-primary-saas px-8 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-siakad-600/20">{{ __('Update User') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
        function toggleFacultyField(prefix) {
            const role = document.getElementById(prefix + 'Role').value;
            document.getElementById(prefix + 'FacultyField').classList.toggle('hidden', role !== 'admin_faculty' && role !== 'admin_faculty');
        }
        function openEditModal(user) {
            document.getElementById('editForm').action = `/admin/users/${user.id}`;
            document.getElementById('editName').value = user.name;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editRole').value = user.role;
            document.getElementById('editFacultyId').value = user.faculty_id || '';
            toggleFacultyField('edit');
            openModal('editModal');
        }
    </script>
</x-app-layout>
