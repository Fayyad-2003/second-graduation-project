<x-app-layout>
    <x-slot name="header">
        {{ __('Career Roadmap') }}
    </x-slot>

    <div class="py-12" x-data="careerRoadmap()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Selection Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-teal-500/10 flex items-center justify-center text-teal-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ __('Define Your Path') }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Select your preferred specialization to get a plan') }}</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <!-- 1. Field -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">{{ __('Main Field') }}</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <template x-for="(options, fieldName) in dataStructure" :key="fieldName">
                                        <button @click="selectField(fieldName)"
                                            :class="selectedField === fieldName ? 'bg-teal-600 text-white border-teal-600' : 'bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-100 dark:border-gray-700'"
                                            class="px-3 py-2 rounded-xl border text-sm font-medium transition-all text-center" x-text="fieldName"></button>
                                    </template>
                                </div>
                            </div>

                            <!-- 2. Sub-Field -->
                            <div x-show="selectedField" class="animate-fade-in">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">{{ __('Sub-Field') }}</label>
                                <select x-model="selectedSubField" @change="resetSpecific" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-sm focus:ring-teal-500 focus:border-teal-500">
                                    <option value="">{{ __('Select sub-field...') }}</option>
                                    <template x-for="(options, subFieldName) in dataStructure[selectedField]" :key="subFieldName">
                                        <option :value="subFieldName" x-text="subFieldName"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- 3. Specific Field -->
                            <div x-show="selectedSubField" class="animate-fade-in">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">{{ __('Specific Field') }}</label>
                                <select x-model="selectedSpecificField" @change="resetTechnology" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-sm focus:ring-teal-500 focus:border-teal-500">
                                    <option value="">{{ __('Select specialization...') }}</option>
                                    <template x-for="(options, specName) in dataStructure[selectedField][selectedSubField]" :key="specName">
                                        <option :value="specName" x-text="specName"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- 4. Technology -->
                            <div x-show="selectedSpecificField" class="animate-fade-in">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">{{ __('Required Technology') }}</label>
                                <select x-model="selectedTechnology" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-sm focus:ring-teal-500 focus:border-teal-500">
                                    <option value="">{{ __('Select technology...') }}</option>
                                    <template x-for="tech in dataStructure[selectedField][selectedSubField][selectedSpecificField]" :key="tech">
                                        <option :value="tech" x-text="tech"></option>
                                    </template>
                                </select>
                            </div>

                            <button
                                @click="generateRoadmap"
                                :disabled="!selectedTechnology || isLoading"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold shadow-lg shadow-teal-200 dark:shadow-none transition-all disabled:opacity-50 disabled:cursor-not-allowed group">
                                <span x-show="!isLoading">{{ __('Generate Roadmap') }}</span>
                                <span x-show="isLoading">{{ __('Planning...') }}</span>
                                <svg x-show="!isLoading" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                <svg x-show="isLoading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Roadmap Result Area -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 min-h-[500px] flex flex-col relative">

                        <!-- Empty State -->
                        <div x-show="!roadmap && !isLoading" class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                            <div class="w-24 h-24 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">{{ __('Your Gateway to a Career Future') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm">{{ __('Select your specialization and the technology you want to master, and we will draw the appropriate path for you.') }}</p>
                        </div>

                        <!-- Loading State -->
                        <div x-show="isLoading" class="flex-1 flex flex-col items-center justify-center p-12 text-center animate-pulse">
                            <div class="w-20 h-20 rounded-2xl bg-teal-500/10 flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-teal-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2" x-text="loadingStatus">{{ __('Drawing roadmap...') }}</h3>
                        </div>

                        <!-- Result State -->
                        <div x-show="roadmap && !isLoading" class="p-8 animate-fade-in">
                            <div class="flex items-center justify-between mb-8 border-b border-gray-100 dark:border-gray-700 pb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-teal-600 flex items-center justify-center text-white shadow-lg shadow-teal-200">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-black text-gray-800 dark:text-gray-100" x-text="selectedTechnology"></h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 px-2 py-0.5 rounded-md font-bold" x-text="selectedField"></span>
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            <span class="text-xs bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 px-2 py-0.5 rounded-md font-bold" x-text="selectedSpecificField"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="downloadPDF" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-teal-50 dark:hover:bg-teal-900/30 hover:text-teal-600 transition-all shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        <span class="text-sm font-bold">{{ __('Download PDF') }}</span>
                                    </button>
                                </div>
                            </div>

                            <div id="roadmap-result-container" class="prose prose-teal dark:prose-invert max-w-none prose-headings:font-black prose-p:leading-relaxed" x-html="formatRoadmap(roadmap)"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .roadmap-content h2 {
            @apply text-2xl font-black text-teal-700 dark:text-teal-400 mt-12 mb-6 pb-2 border-b-2 border-teal-100 dark:border-teal-900/50 flex items-center gap-3;
        }

        .roadmap-content h3 {
            @apply text-lg font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 flex items-center gap-2;
        }

        .phase-card {
            @apply bg-white dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 mb-6 shadow-sm border-r-4 border-r-teal-500;
        }

        .phase-badge {
            @apply inline-flex items-center px-3 py-1 rounded-full bg-teal-600 text-white text-xs font-bold mb-4 uppercase tracking-tighter;
        }

        .roadmap-content ul {
            @apply space-y-2 mb-6;
        }

        .roadmap-content li {
            @apply flex items-start gap-2 text-gray-600 dark:text-gray-400 text-sm;
        }

        .roadmap-content li::before {
            content: "✓";
            @apply text-teal-500 font-black;
        }

        .html2pdf__container {
            background: white !important;
            color: black !important;
        }

        .html2pdf__container .phase-card {
            background: #f0fdfa !important;
            border-color: #ccfbf1 !important;
        }

        .html2pdf__container .phase-badge {
            background: #0d9488 !important;
            color: white !important;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/api/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    @push('scripts')
    <script>
        function careerRoadmap() {
            return {
                dataStructure: {
                    "IT & Software": {
                        "Programming": {
                            "Web Development": ["React", "Vue", "Laravel", "Node.js", "Django", "Next.js"],
                            "Mobile Development": ["Flutter", "React Native", "Swift", "Kotlin", "Ionic"],
                            "Game Development": ["Unity (C#)", "Unreal Engine (C++)", "Godot"],
                        },
                        "Infrastructure": {
                            "Networks": ["CCNA", "CompTIA Network+", "MikroTik", "Cisco"],
                            "Cloud Computing": ["AWS", "Azure", "Google Cloud", "DevOps"],
                            "Cyber Security": ["Ethical Hacking", "SOC Analyst", "Pentesting"],
                        },
                        "Data Science": {
                            "AI & ML": ["Python (PyTorch)", "TensorFlow", "NLP", "Computer Vision"],
                            "Data Analysis": ["SQL", "Power BI", "Tableau", "Pandas"],
                        }
                    },
                    "Design & Creative": {
                        "UI/UX Design": {
                            "Product Design": ["Figma", "Adobe XD", "Sketch"],
                            "User Research": ["UX Research Methods", "Prototyping"],
                        },
                        "Graphic Design": {
                            "Branding": ["Illustrator", "Photoshop", "InDesign"],
                            "Motion Graphics": ["After Effects", "Premiere Pro", "Cinema 4D"],
                        }
                    }
                },

                selectedField: '',
                selectedSubField: '',
                selectedSpecificField: '',
                selectedTechnology: '',
                isLoading: false,
                roadmap: null,
                loadingStatus: '{{ __("Planning...") }}',

                selectField(field) {
                    this.selectedField = field;
                    this.selectedSubField = '';
                    this.selectedSpecificField = '';
                    this.selectedTechnology = '';
                },

                resetSpecific() {
                    this.selectedSpecificField = '';
                    this.selectedTechnology = '';
                },

                resetTechnology() {
                    this.selectedTechnology = '';
                },

                async generateRoadmap() {
                    if (!this.selectedTechnology || this.isLoading) return;

                    this.isLoading = true;
                    this.roadmap = null;
                    this.startLoadingAnimation();

                    try {
                        const response = await fetch('{{ route("students.career-roadmap.generate") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                field: this.selectedField,
                                subField: this.selectedSubField,
                                specificField: this.selectedSpecificField,
                                technology: this.selectedTechnology
                            }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.roadmap = data.message;
                        } else {
                            alert('Error: ' + data.message);
                        }
                    } catch (error) {
                        alert('Connection Error');
                    } finally {
                        this.isLoading = false;
                    }
                },

                startLoadingAnimation() {
                    const statuses = [
                        '{{ __("Analyzing market requirements...") }}',
                        '{{ __("Identifying core skills...") }}',
                        '{{ __("Building learning phases...") }}',
                        '{{ __("Selecting suggested projects...") }}',
                        '{{ __("Putting final touches...") }}'
                    ];
                    let idx = 0;
                    this.loadingStatus = statuses[0];
                    const interval = setInterval(() => {
                        if (!this.isLoading) {
                            clearInterval(interval);
                            return;
                        }
                        idx = (idx + 1) % statuses.length;
                        this.loadingStatus = statuses[idx];
                    }, 3000);
                },

                downloadPDF() {
                    const element = document.getElementById('roadmap-result-container');
                    const opt = {
                        margin: [10, 10],
                        filename: `Roadmap_${this.selectedTechnology.replace(/\s+/g, '_')}.pdf`,
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2,
                            useCORS: true
                        },
                        jsPDF: {
                            unit: 'mm',
                            format: 'a4',
                            orientation: 'portrait'
                        }
                    };
                    html2pdf().set(opt).from(element).save();
                },

                formatRoadmap(text) {
                    if (!text) return '';

                    let html = text
                        .replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-gray-900 dark:text-white">$1</strong>')
                        .replace(/\*(.*?)\*/g, '<em class="italic text-gray-700 dark:text-gray-300">$1</em>');

                    // Wrap "Phase" or "المرحلة" into cards
                    const sections = html.split(/## (المرحلة|Phase) (\d+)/i);

                    if (sections.length > 1) {
                        let formattedHtml = sections[0];
                        for (let i = 1; i < sections.length; i += 3) {
                            const phaseLabel = sections[i];
                            const phaseNum = sections[i + 1];
                            const content = sections[i + 2];

                            formattedHtml += `
                                <div class="phase-card">
                                    <span class="phase-badge">${phaseLabel} ${phaseNum}</span>
                                    <div class="phase-content">
                                        ${this.parseMarkdownBody(content)}
                                    </div>
                                </div>
                            `;
                        }
                        html = formattedHtml;
                    } else {
                        html = this.parseMarkdownBody(html);
                    }

                    return `<div class="roadmap-content">${html}</div>`;
                },

                parseMarkdownBody(text) {
                    return text
                        .replace(/## (.*)/g, '<h2>$1</h2>')
                        .replace(/### (.*)/g, '<h3>$1</h3>')
                        .replace(/\n\n/g, '</p><p class="mb-4 text-gray-600 dark:text-gray-400">')
                        .replace(/\n/g, '<br>')
                        .replace(/^- (.*)/gm, '<li>$1</li>')
                        .replace(/^(\d+)\. (.*)/gm, '<li class="list-decimal ml-4">$2</li>');
                }
            }
        }
    </script>
    @endpush
</x-app-layout>