<x-app-layout>
    <x-slot name="header">
        {{ __('Document Types') }}
    </x-slot>

    <div class="mb-6">
        <button onclick="document.getElementById('createModal').classList.remove('hidden')"
            class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('Add New Document Type') }}
        </button>
    </div>

    <div class="card-saas overflow-hidden">
        <table class="w-full table-saas">
            <thead>
                <tr class="bg-primary-light/30">
                    <th class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary uppercase tracking-wider">{{ __('Name') }}</th>
                    <th class="text-left py-3 px-5 text-xs font-semibold text-primary-secondary uppercase tracking-wider">{{ __('Required Files') }}</th>
                    <th class="text-center py-3 px-5 text-xs font-semibold text-primary-secondary uppercase tracking-wider">{{ __('Status') }}</th>
                    <th class="text-right py-3 px-5 text-xs font-semibold text-primary-secondary uppercase tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-primary-light/50">
                @foreach($documentTypes as $type)
                <tr>
                    <td class="py-4 px-5">
                        <div class="font-bold text-primary-dark">{{ $type->name }}</div>
                        <div class="text-xs text-primary-secondary">{{ $type->description }}</div>
                    </td>
                    <td class="py-4 px-5">
                        <div class="flex flex-wrap gap-1">
                            @foreach($type->required_files as $file)
                            <span class="px-2 py-0.5 bg-primary-light text-primary-secondary text-[10px] rounded-full border border-primary-light/50">
                                {{ $file['name'] }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="py-4 px-5 text-center">
                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full {{ $type->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                            {{ $type->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </td>
                    <td class="py-4 px-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="editDocType({{ $type->id }}, '{{ addslashes($type->name) }}', '{{ addslashes($type->description) }}', {{ json_encode($type->required_files) }}, {{ $type->is_active ? 'true' : 'false' }})"
                                class="p-2 text-primary-secondary hover:text-primary-primary hover:bg-primary-primary/10 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <form action="{{ route('admin.document-type.destroy', $type) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-primary-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($documentTypes->hasPages())
        <div class="px-5 py-4 border-t border-primary-light/50">
            {{ $documentTypes->links() }}
        </div>
        @endif
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-primary-dark/50 transition-opacity" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-lg w-full p-6">
                <h3 class="text-lg font-bold text-primary-dark dark:text-white mb-4">{{ __('Add Document Type') }}</h3>
                <form action="{{ route('admin.document-type.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Document Name') }}</label>
                            <input type="text" name="name" class="input-saas w-full" placeholder="{{ __('e.g., College ID Card') }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Description') }}</label>
                            <textarea name="description" class="input-saas w-full" rows="2"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Required Files') }}</label>
                            <div id="file-list-create" class="space-y-2 mb-2">
                                <div class="flex gap-2">
                                    <input type="text" name="file_names[]" class="input-saas flex-1 text-sm" placeholder="{{ __('File name, e.g., Photo') }}" required>
                                    <button type="button" onclick="removeFileRow(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="addFileRow('file-list-create')" class="text-xs font-bold text-primary-primary hover:underline flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ __('Add Another File') }}
                            </button>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="this.closest('#createModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-primary-secondary">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-6 py-2 rounded-lg text-sm font-bold shadow-lg shadow-primary-primary/20">{{ __('Create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-primary-dark/50 transition-opacity" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-lg w-full p-6">
                <h3 class="text-lg font-bold text-primary-dark dark:text-white mb-4">{{ __('Edit Document Type') }}</h3>
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Document Name') }}</label>
                            <input type="text" name="name" id="editName" class="input-saas w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Description') }}</label>
                            <textarea name="description" id="editDescription" class="input-saas w-full" rows="2"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-primary-dark mb-2">{{ __('Required Files') }}</label>
                            <div id="file-list-edit" class="space-y-2 mb-2"></div>
                            <button type="button" onclick="addFileRow('file-list-edit')" class="text-xs font-bold text-primary-primary hover:underline flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ __('Add Another File') }}
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" id="editIsActive" value="1" class="rounded text-primary-primary focus:ring-primary-primary">
                            <label for="editIsActive" class="text-sm text-primary-dark">{{ __('Active') }}</label>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="this.closest('#editModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-primary-secondary">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn-primary-saas px-6 py-2 rounded-lg text-sm font-bold shadow-lg shadow-primary-primary/20">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addFileRow(containerId, value = '') {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'flex gap-2 animate-fade-in';
            div.innerHTML = `
                <input type="text" name="file_names[]" class="input-saas flex-1 text-sm" placeholder="{{ __('File name') }}" value="${value}" required>
                <button type="button" onclick="removeFileRow(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            `;
            container.appendChild(div);
        }

        function removeFileRow(btn) {
            const rows = btn.closest('.space-y-2').querySelectorAll('.flex');
            if (rows.length > 1) {
                btn.closest('.flex').remove();
            } else {
                alert('{{ __("At least one file is required") }}');
            }
        }

        function editDocType(id, name, desc, files, isActive) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            const fileList = document.getElementById('file-list-edit');

            form.action = `/admin/document-type/${id}`;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = desc || '';
            document.getElementById('editIsActive').checked = isActive;

            fileList.innerHTML = '';
            files.forEach(f => addFileRow('file-list-edit', f.name));

            modal.classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>