<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ str_starts_with(app()->getLocale(), 'ar') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|vazirmatn:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', 'Vazirmatn', system-ui, sans-serif;
        }

        [dir="rtl"] body {
            font-family: 'Vazirmatn', 'Inter', system-ui, sans-serif;
        }
    </style>
</head>

<body class="font-sans antialiased text-surface-800 dark:text-surface-100 bg-surface-50 dark:bg-surface-900 selection:bg-primary-500/30 selection:text-primary-900 dark:selection:text-primary-100">
    <div class="min-h-screen flex">

        <!-- Left Side - Form -->
        <div class="w-full lg:w-[480px] xl:w-[560px] flex flex-col justify-center px-8 lg:px-16 relative z-10 bg-white dark:bg-surface-900">
            <!-- Mobile Logo & Language Switch -->
            <div class="lg:hidden absolute top-8 left-8 right-8 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl gradient-bg flex items-center justify-center text-white shadow-glow">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-xl gradient-text">{{ config('app.name') }}</span>
                </a>
                <!-- Language Switch -->
                <a href="{{ route('lang.switch', app()->getLocale() === 'en' ? 'ar' : 'en') }}"
                    class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 font-bold text-sm hover:scale-105 transition-transform"
                    title="{{ app()->getLocale() === 'en' ? 'العربية' : 'English' }}">
                    {{ app()->getLocale() === 'en' ? 'AR' : 'EN' }}
                </a>
            </div>

            <div class="w-full max-w-[400px] mx-auto">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="absolute bottom-8 left-0 right-0 text-center text-xs text-surface-400 dark:text-surface-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>

        <!-- Right Side - Visual -->
        <div class="hidden lg:flex flex-1 relative bg-surface-900 overflow-hidden items-center justify-center">
            <!-- Language Switch for Desktop -->
            <div class="absolute top-8 right-8 z-20">
                <a href="{{ route('lang.switch', app()->getLocale() === 'en' ? 'ar' : 'en') }}"
                    class="p-3 rounded-xl bg-white/10 text-white/80 backdrop-blur-sm font-bold text-sm hover:bg-white/20 hover:scale-105 transition-all"
                    title="{{ app()->getLocale() === 'en' ? 'العربية' : 'English' }}">
                    {{ app()->getLocale() === 'en' ? 'AR' : 'EN' }}
                </a>
            </div>
            <!-- Background Gradients -->
            <div class="absolute inset-0 bg-gradient-to-br from-surface-900 via-surface-800 to-surface-950"></div>
            <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary-500/15 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 animate-float"></div>
            <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-accent-500/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3 animate-float" style="animation-delay: -3s;"></div>
            <div class="absolute top-1/2 left-1/2 w-[400px] h-[400px] bg-primary-400/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 animate-pulse-soft"></div>

            <!-- Pattern Overlay -->
            <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

            <!-- Glassmorphism Card Content -->
            <div class="relative z-10 max-w-lg text-center p-12 animate-slide-up">
                <div class="glass rounded-3xl p-10 shadow-soft-xl relative overflow-hidden group hover:shadow-glow-lg transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                    <div class="w-24 h-24 bg-gradient-to-br from-primary-400 via-primary-500 to-accent-500 rounded-3xl mx-auto mb-8 flex items-center justify-center shadow-glow transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>

                    <h2 class="text-4xl font-bold text-white mb-4 tracking-tight">System AI</h2>
                    <p class="text-surface-300 text-lg leading-relaxed mb-6">
                        {{ __('A modern AI-powered academic management platform for efficiency and transparency in higher education.') }}
                    </p>

                    <div class="flex items-center justify-center gap-6 mb-8">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-surface-400">{{ __('Secure') }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-accent-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-surface-400">{{ __('Fast') }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-success-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-surface-400">{{ __('Reliable') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-center gap-2">
                        <div class="w-16 h-1.5 bg-primary-400 rounded-full animate-pulse-soft"></div>
                        <div class="w-4 h-1.5 bg-white/20 rounded-full"></div>
                        <div class="w-4 h-1.5 bg-white/20 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>