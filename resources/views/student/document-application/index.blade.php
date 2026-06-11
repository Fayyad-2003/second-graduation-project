<x-app-layout>
    <x-slot name="header">
        {{ __('Document Requests') }}
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- New Application Form -->
        <div class="lg:col-span-1">
            <div class="card-saas p-6 sticky top-24">
                <h3 class="text-lg font-bold text-primary-dark mb-4">{{ __('Request New Document') }}</h3>
                <p class="text-xs text-primary-secondary mb-6">{{ __('Choose a document type to start your application.') }}</p>

                <form action="{{ route('students.document-application.store') }}" method="POST" enctype="multipart/form-data" id="requestForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-primary-dark mb-1">{{ __('Document Type') }}</label>
                            <select name="document_type_id" id="docTypeSelect" class="input-saas w-full" required onchange="handleDocTypeChange(this)">
                                <option value="">{{ __('-- Select Document --') }}</option>
                                @foreach($availableDocumentTypes as $type)
                                <option value="{{ $type->id }}" data-files="{{ json_encode($type->required_files) }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="required-files-container" class="space-y-4 hidden">
                            <p class="text-xs font-bold text-primary-primary uppercase tracking-wider">{{ __('Required Files (Max 2MB per file)') }}</p>
                            <div id="files-input-list" class="space-y-3"></div>
                        </div>

                        <button type="submit" id="submitBtn" class="btn-primary-saas w-full py-3 rounded-xl font-bold shadow-lg shadow-primary-primary/20 transition active:scale-95 hidden">
                            {{ __('Submit Request') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- My Applications -->
        <div class="lg:col-span-2">
            <div class="card-saas overflow-hidden">
                <div class="px-6 py-4 border-b border-primary-light">
                    <h3 class="font-semibold text-primary-dark">{{ __('My Document History') }}</h3>
                </div>
                <div class="divide-y divide-primary-light/50">
                    @forelse($applications as $app)
                    <div class="p-6 hover:bg-primary-light/10 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-bold text-primary-dark">{{ $app->documentType->name }}</h4>
                                    @php
                                    $statusColors = [
                                    'pending' => 'bg-amber-50 text-amber-700',
                                    'processing' => 'bg-blue-50 text-blue-700',
                                    'completed' => 'bg-emerald-50 text-emerald-700',
                                    'rejected' => 'bg-red-50 text-red-700',
                                    ];
                                    @endphp
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase {{ $statusColors[$app->status] ?? 'bg-slate-50 text-slate-700' }}">
                                        {{ __($app->status) }}
                                    </span>
                                </div>
                                <p class="text-xs text-primary-secondary mb-4">{{ __('Submitted on') }} {{ $app->created_at->format('d M Y, H:i') }}</p>

                                @if($app->admin_notes)
                                <div class="p-3 bg-primary-light/30 rounded-lg border border-primary-light/50 mb-4">
                                    <p class="text-[10px] font-bold text-primary-secondary uppercase mb-1">{{ __('Admin Notes') }}</p>
                                    <p class="text-sm text-primary-dark italic">"{{ $app->admin_notes }}"</p>
                                </div>
                                @endif

                                <div class="flex flex-wrap gap-2">
                                    @foreach($app->uploaded_files as $file)
                                    <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="flex items-center gap-1.5 px-2 py-1 bg-white border border-primary-light rounded text-[10px] text-primary-secondary hover:text-primary-primary transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-2.828-6.828l-6.414 6.586a6 6 0 008.485 8.486L20.5 13"></path>
                                        </svg>
                                        {{ $file['name'] }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 text-center">
                        <div class="w-16 h-16 bg-primary-light/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-primary-dark">{{ __('No requests yet') }}</h4>
                        <p class="text-xs text-primary-secondary mt-1">{{ __('Your document applications will appear here.') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function handleDocTypeChange(select) {
            const container = document.getElementById('required-files-container');
            const list = document.getElementById('files-input-list');
            const submitBtn = document.getElementById('submitBtn');

            if (!select.value) {
                container.classList.add('hidden');
                submitBtn.classList.add('hidden');
                return;
            }

            const files = JSON.parse(select.options[select.selectedIndex].getAttribute('data-files'));
            list.innerHTML = '';

            files.forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'animate-fade-in';
                div.innerHTML = `
                    <label class="block text-[11px] font-bold text-primary-secondary mb-1">${file.name}</label>
                    <input type="file" name="files[${index}]" class="block w-full text-xs text-primary-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary-primary/10 file:text-primary-primary hover:file:bg-primary-primary/20 transition" required>
                `;
                list.appendChild(div);
            });

            container.classList.remove('hidden');
            submitBtn.classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>