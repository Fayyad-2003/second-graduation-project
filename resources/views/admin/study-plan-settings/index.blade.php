<x-app-layout>
    {{-- ═══════════════════════════════════════════════════════
         Page Header
    ═══════════════════════════════════════════════════════ --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary-900 dark:text-white">
                {{ __('Study Plan Settings') }}
            </h1>
            <p class="text-primary-500 font-medium mt-2 flex items-center gap-2">
                <span class="w-8 h-px bg-primary-200"></span>
                {{ __('Configure credit hour limits based on student GPA ranges.') }}
            </p>
        </div>
        {{-- Reset to Defaults --}}
        <form action="{{ route('admin.study-plan-settings.reset-defaults') }}" method="POST"
              onsubmit="return confirm('{{ __('Reset all rules to system defaults? This will delete all custom rules.') }}')">
            @csrf
            <button type="submit"
                class="btn-ghost-saas px-5 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 border border-primary-100 dark:border-primary-800 text-primary-600 dark:text-primary-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                {{ __('Reset to Defaults') }}
            </button>
        </form>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         Flash Messages
    ═══════════════════════════════════════════════════════ --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-soft dark:bg-emerald-950/20 dark:border-emerald-900/50 dark:text-emerald-400">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl dark:bg-red-950/20 dark:border-red-900/50 dark:text-red-400">
        <div class="flex items-center gap-3 mb-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm font-black">{{ __('Please fix the following errors:') }}</span>
        </div>
        <ul class="list-disc list-inside text-xs font-bold space-y-1 ms-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════
         How It Works — Info Card
    ═══════════════════════════════════════════════════════ --}}
    <div class="mb-8 card-saas p-6 border-s-4 border-primary-primary bg-primary-50/40 dark:bg-primary-900/20">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-primary-primary/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-black text-primary-900 dark:text-white mb-1">{{ __('How It Works') }}</h3>
                <p class="text-xs text-primary-500 dark:text-primary-400 leading-relaxed">
                    {{ __('Each rule maps a GPA range to a maximum number of credit hours a student may register. When a student submits a study plan, the system automatically applies the matching rule based on their current cumulative GPA. Rules must not overlap. If no rules are defined, the system falls back to built-in defaults.') }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- ═══════════════════════════════════════════════════════
             LEFT — Existing Rules List
        ═══════════════════════════════════════════════════════ --}}
        <div class="xl:col-span-2 space-y-4">
            <h2 class="text-base font-black text-primary-900 dark:text-white flex items-center gap-3">
                <span class="w-6 h-6 rounded-lg bg-primary-primary/10 flex items-center justify-center text-primary-primary text-xs font-black">
                    {{ $rules->count() }}
                </span>
                {{ __('Active Rules') }}
            </h2>

            @if($rules->isEmpty())
            <div class="card-saas p-12 text-center">
                <div class="w-16 h-16 bg-primary-50 dark:bg-primary-900 rounded-3xl flex items-center justify-center mx-auto mb-4 text-primary-300 shadow-sm border border-primary-100/50 dark:border-primary-800/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
                <h3 class="text-base font-black text-primary-900 dark:text-white mb-2">{{ __('No Rules Defined') }}</h3>
                <p class="text-xs text-primary-400 font-bold max-w-xs mx-auto leading-relaxed mb-6">
                    {{ __('The system is using built-in defaults. Add custom rules or reset to defaults to get started.') }}
                </p>
                <form action="{{ route('admin.study-plan-settings.reset-defaults') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-black">
                        {{ __('Load System Defaults') }}
                    </button>
                </form>
            </div>
            @else
            <div class="space-y-3">
                @foreach($rules as $rule)
                <div class="card-saas p-5 group relative overflow-hidden" id="rule-{{ $rule->id }}">
                    {{-- Colour accent bar (GPA-based colour coding) --}}
                    @php
                        $accentColor = match(true) {
                            $rule->min_gpa >= 3.51 => 'bg-emerald-500',
                            $rule->min_gpa >= 3.01 => 'bg-teal-500',
                            $rule->min_gpa >= 2.51 => 'bg-sky-500',
                            $rule->min_gpa >= 2.00 => 'bg-amber-500',
                            default               => 'bg-red-500',
                        };
                        $badgeColor = match(true) {
                            $rule->min_gpa >= 3.51 => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/50',
                            $rule->min_gpa >= 3.01 => 'bg-teal-50 text-teal-700 border-teal-100 dark:bg-teal-950/30 dark:text-teal-400 dark:border-teal-900/50',
                            $rule->min_gpa >= 2.51 => 'bg-sky-50 text-sky-700 border-sky-100 dark:bg-sky-950/30 dark:text-sky-400 dark:border-sky-900/50',
                            $rule->min_gpa >= 2.00 => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50',
                            default               => 'bg-red-50 text-red-700 border-red-100 dark:bg-red-950/30 dark:text-red-400 dark:border-red-900/50',
                        };
                    @endphp
                    <div class="absolute start-0 top-0 bottom-0 w-1 {{ $accentColor }} rounded-s-2xl"></div>

                    {{-- View mode --}}
                    <div class="view-mode-{{ $rule->id }} ps-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-black text-primary-900 dark:text-white truncate mb-1">
                                    {{ $rule->label }}
                                </p>
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-black rounded-xl border {{ $badgeColor }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        GPA {{ number_format($rule->min_gpa, 2) }} – {{ number_format($rule->max_gpa, 2) }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-black bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-300 border border-primary-100 dark:border-primary-800 rounded-xl">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        {{ $rule->max_credits }} {{ __('Credits Max') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button onclick="toggleEdit({{ $rule->id }})"
                                    class="p-2.5 text-primary-400 hover:text-primary-primary hover:bg-primary-primary/10 rounded-xl transition-all"
                                    title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.study-plan-settings.destroy', $rule) }}" method="POST"
                                      onsubmit="return confirm('{{ __('Delete this rule?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2.5 text-primary-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition-all"
                                        title="{{ __('Delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Edit mode (hidden by default) --}}
                    <div class="edit-mode-{{ $rule->id }} hidden ps-4">
                        <form action="{{ route('admin.study-plan-settings.update', $rule) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                                <div class="sm:col-span-2">
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1.5">
                                        {{ __('Label') }}
                                    </label>
                                    <input type="text" name="label" value="{{ $rule->label }}" required
                                        class="input-saas w-full px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1.5">
                                        {{ __('Min GPA') }}
                                    </label>
                                    <input type="number" name="min_gpa" value="{{ $rule->min_gpa }}" step="0.01" min="0" max="4" required
                                        class="input-saas w-full px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1.5">
                                        {{ __('Max GPA') }}
                                    </label>
                                    <input type="number" name="max_gpa" value="{{ $rule->max_gpa }}" step="0.01" min="0" max="4" required
                                        class="input-saas w-full px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1.5">
                                        {{ __('Max Credits') }}
                                    </label>
                                    <input type="number" name="max_credits" value="{{ $rule->max_credits }}" min="1" max="50" required
                                        class="input-saas w-full px-3 py-2 text-sm">
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="btn-primary-saas px-4 py-2 rounded-xl text-xs font-black flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ __('Save Changes') }}
                                </button>
                                <button type="button" onclick="toggleEdit({{ $rule->id }})"
                                    class="btn-ghost-saas px-4 py-2 rounded-xl text-xs font-black">
                                    {{ __('Cancel') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════════════
             RIGHT — Add New Rule
        ═══════════════════════════════════════════════════════ --}}
        <div>
            <h2 class="text-base font-black text-primary-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Add New Rule') }}
            </h2>

            <div class="card-saas p-6">
                <form action="{{ route('admin.study-plan-settings.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Label --}}
                    <div>
                        <label for="label" class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1.5">
                            {{ __('Rule Label') }}
                        </label>
                        <input type="text" id="label" name="label" required
                            value="{{ old('label') }}"
                            placeholder="{{ __('e.g. Excellent (3.51 – 4.00)') }}"
                            class="input-saas w-full px-4 py-2.5 text-sm">
                    </div>

                    {{-- GPA Range --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="min_gpa" class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1.5">
                                {{ __('Min GPA') }}
                            </label>
                            <input type="number" id="min_gpa" name="min_gpa" step="0.01" min="0" max="4" required
                                value="{{ old('min_gpa') }}"
                                placeholder="0.00"
                                class="input-saas w-full px-4 py-2.5 text-sm">
                        </div>
                        <div>
                            <label for="max_gpa" class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1.5">
                                {{ __('Max GPA') }}
                            </label>
                            <input type="number" id="max_gpa" name="max_gpa" step="0.01" min="0" max="4" required
                                value="{{ old('max_gpa') }}"
                                placeholder="4.00"
                                class="input-saas w-full px-4 py-2.5 text-sm">
                        </div>
                    </div>

                    {{-- Max Credits --}}
                    <div>
                        <label for="max_credits" class="block text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1.5">
                            {{ __('Max Credit Hours') }}
                        </label>
                        <div class="relative">
                            <input type="number" id="max_credits" name="max_credits" min="1" max="50" required
                                value="{{ old('max_credits') }}"
                                placeholder="24"
                                class="input-saas w-full px-4 py-2.5 text-sm pe-16">
                            <span class="absolute end-4 top-1/2 -translate-y-1/2 text-xs font-black text-primary-400 uppercase tracking-widest pointer-events-none">
                                {{ __('SKS') }}
                            </span>
                        </div>
                        <p class="text-[10px] text-primary-400 mt-1.5 font-bold">
                            {{ __('Typical range: 14 – 24 credit hours per semester.') }}
                        </p>
                    </div>

                    {{-- GPA Scale Visual --}}
                    <div class="bg-primary-50/50 dark:bg-primary-900/30 rounded-2xl p-4 border border-primary-100/50 dark:border-primary-800/50">
                        <p class="text-[10px] font-black text-primary-400 uppercase tracking-widest mb-3">{{ __('GPA Scale Reference') }}</p>
                        <div class="flex h-2 rounded-full overflow-hidden gap-0.5">
                            <div class="flex-1 bg-red-400 rounded-s-full" title="0.00 – 1.99"></div>
                            <div class="flex-1 bg-amber-400" title="2.00 – 2.49"></div>
                            <div class="flex-1 bg-sky-400" title="2.50 – 2.99"></div>
                            <div class="flex-1 bg-teal-400" title="3.00 – 3.49"></div>
                            <div class="flex-1 bg-emerald-400 rounded-e-full" title="3.50 – 4.00"></div>
                        </div>
                        <div class="flex justify-between mt-1.5">
                            <span class="text-[9px] font-black text-primary-400">0.00</span>
                            <span class="text-[9px] font-black text-primary-400">4.00</span>
                        </div>
                    </div>

                    <button type="submit"
                        class="btn-primary-saas w-full py-3 rounded-xl text-sm font-black flex items-center justify-center gap-2 shadow-lg shadow-primary-600/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('Add Rule') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleEdit(id) {
            const viewEl = document.querySelector('.view-mode-' + id);
            const editEl = document.querySelector('.edit-mode-' + id);
            if (!viewEl || !editEl) return;
            viewEl.classList.toggle('hidden');
            editEl.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
