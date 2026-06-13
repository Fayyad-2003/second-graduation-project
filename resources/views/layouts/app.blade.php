<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ str_starts_with(app()->getLocale(), 'ar') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Academic Assistant') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|vazirmatn:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Dark Mode Flash Prevention -->
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
        // Sidebar state - apply immediately to prevent FOUC
        (function() {
            var sidebarState = localStorage.getItem('sidebarOpen');
            if (sidebarState === 'false') {
                document.documentElement.classList.add('sidebar-collapsed-init');
            }
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-dark: #0c4a6e;
            --primary-primary: #0284c7;
            --primary-secondary: #0ea5e9;
            --primary-light: #e0f2fe;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --bg-sidebar: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --gradient-start: #0ea5e9;
            --gradient-end: #0284c7;
        }

        html {
            scroll-behavior: smooth;
        }

        .dark {
            --bg-body: #0f172a;
            --bg-card: #1e293b;
            --bg-sidebar: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #334155;
            --gradient-start: #0ea5e9;
            --gradient-end: #3b82f6;
        }

        body {
            font-family: 'Inter', 'Vazirmatn', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            background-color: var(--bg-body);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        [dir="rtl"] body {
            font-family: 'Vazirmatn', 'Inter', system-ui, sans-serif;
        }

        /* Ensure gradient cards with text-white work in light mode */
        .bg-gradient-to-r,
        .bg-gradient-to-l,
        .bg-gradient-to-t,
        .bg-gradient-to-b {
            color: inherit;
        }

        [style*="color: white"] * {
            color: inherit;
        }

        /* Sidebar Links */
        .sidebar-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .sidebar-link:hover {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.08) 0%, rgba(56, 189, 248, 0.08) 100%);
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            color: white;
            box-shadow: 0 8px 24px -4px rgba(14, 165, 233, 0.4), 0 0 0 1px rgba(14, 165, 233, 0.1);
        }

        .sidebar-link.active:hover {
            box-shadow: 0 12px 32px -4px rgba(14, 165, 233, 0.5), 0 0 0 1px rgba(14, 165, 233, 0.2);
            transform: translateX(0) scale(1.02);
        }

        /* Cards - Modern Glassmorphism */
        .card-saas {
            background: var(--bg-card);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.08);
            backdrop-filter: blur(10px);
        }

        .card-saas:hover {
            box-shadow: 0 20px 40px -12px rgba(14, 165, 233, 0.15), 0 8px 16px -4px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
            border-color: rgba(14, 165, 233, 0.15);
        }

        .dark .card-saas {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(148, 163, 184, 0.1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
        }

        .dark .card-saas:hover {
            box-shadow: 0 24px 48px -12px rgba(14, 165, 233, 0.25), 0 12px 24px -6px rgba(0, 0, 0, 0.4);
            border-color: rgba(14, 165, 233, 0.3);
        }

        /* Dark Mode Text Overrides */
        .dark .text-primary-dark {
            color: var(--text-primary) !important;
        }

        .dark .text-primary-secondary {
            color: #D1D5DB !important;
        }

        .dark .text-primary-primary {
            color: #60A5FA !important;
        }

        .dark .border-primary-light {
            border-color: var(--border-color) !important;
        }

        .dark .divide-primary-light> :not([hidden])~ :not([hidden]) {
            border-color: var(--border-color) !important;
        }

        .dark .bg-primary-light {
            background-color: #334155 !important;
        }

        /* Tables */
        .table-saas tbody tr {
            transition: background-color 0.15s ease;
        }

        .table-saas tbody tr:hover {
            background-color: rgba(35, 76, 106, 0.04);
        }

        /* Buttons - Modern 3D Effect */
        .btn-primary-saas {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            color: white;
            border-radius: 14px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px -2px rgba(14, 165, 233, 0.3), 0 0 0 1px rgba(14, 165, 233, 0.1);
        }

        .btn-primary-saas:hover {
            box-shadow: 0 12px 24px -4px rgba(14, 165, 233, 0.4), 0 0 0 1px rgba(14, 165, 233, 0.2);
            transform: translateY(-2px) scale(1.02);
        }

        .btn-primary-saas:active {
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 8px -2px rgba(14, 165, 233, 0.3);
        }

        .btn-ghost-saas {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(56, 189, 248, 0.05) 100%);
            color: var(--primary-primary);
            border-radius: 14px;
            transition: all 0.3s ease;
            border: 1px solid rgba(14, 165, 233, 0.1);
        }

        .btn-ghost-saas:hover {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.12) 0%, rgba(56, 189, 248, 0.12) 100%);
            transform: translateY(-2px);
            border-color: rgba(14, 165, 233, 0.2);
            box-shadow: 0 8px 16px -4px rgba(14, 165, 233, 0.15);
        }

        /* Inputs - Neumorphic Style */
        .input-saas {
            border: 1px solid rgba(14, 165, 233, 0.08);
            background: linear-gradient(145deg, rgba(248, 250, 252, 0.8), rgba(241, 245, 249, 0.6));
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--text-primary);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .dark .input-saas {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.6), rgba(15, 23, 42, 0.8));
            border-color: rgba(148, 163, 184, 0.1);
            color: var(--text-primary);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .input-saas:focus {
            background-color: var(--bg-card);
            border-color: var(--primary-primary);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12), 0 4px 12px -2px rgba(14, 165, 233, 0.2);
            outline: none;
            transform: translateY(-1px);
        }

        .dark .input-saas:focus {
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.2), 0 4px 12px -2px rgba(14, 165, 233, 0.3);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-secondary);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.2s ease-out;
        }

        /* Sidebar Collapse - Super Smooth Animations */
        aside {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-text,
        .sidebar-logo-text,
        .sidebar-section-title,
        .sidebar-user-info {
            transition: opacity 0.2s ease, transform 0.2s ease;
            opacity: 1;
            transform: translateX(0);
        }

        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .sidebar-logo-text,
        .sidebar-collapsed .sidebar-section-title,
        .sidebar-collapsed .sidebar-user-info {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
            width: 0;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar-link {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-collapsed .sidebar-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
            gap: 0;
        }

        .sidebar-link svg {
            transition: margin 0.25s ease;
            flex-shrink: 0;
        }

        .sidebar-collapsed .sidebar-link svg {
            margin: 0;
        }

        .user-section {
            transition: justify-content 0.25s ease;
        }

        .sidebar-collapsed .user-section {
            justify-content: center;
            gap: 0;
        }

        .sidebar-toggle-icon {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-collapsed .sidebar-toggle-icon {
            transform: rotate(180deg);
        }

        .logo-section {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-collapsed .logo-section {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        .sidebar-collapsed .logo-section>div {
            justify-content: center;
            gap: 0;
        }

        .toggle-btn {
            transition: opacity 0.2s ease, transform 0.2s ease;
            opacity: 1;
        }

        .sidebar-collapsed .toggle-btn {
            opacity: 0;
            pointer-events: none;
            position: absolute;
        }

        /* Initial sidebar collapsed state (before Alpine loads) */
        .sidebar-collapsed-init aside {
            width: 5rem !important;
            /* w-20 */
        }

        .sidebar-collapsed-init .sidebar-text,
        .sidebar-collapsed-init .sidebar-logo-text,
        .sidebar-collapsed-init .sidebar-section-title,
        .sidebar-collapsed-init .sidebar-user-info {
            opacity: 0 !important;
            width: 0 !important;
            overflow: hidden !important;
        }

        .sidebar-collapsed-init .sidebar-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
            gap: 0;
        }

        .sidebar-collapsed-init .logo-section {
            justify-content: center;
        }

        .sidebar-collapsed-init .logo-section>div {
            justify-content: center;
            gap: 0;
        }

        .sidebar-collapsed-init .user-section {
            justify-content: center;
            gap: 0;
        }

        .sidebar-collapsed-init .toggle-btn {
            opacity: 0;
            pointer-events: none;
        }

        /* Prevent transition on page load */
        [x-cloak] {
            display: none !important;
        }

        .no-transition * {
            transition: none !important;
        }
    </style>
    <script>
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            document.body.classList.toggle('dark', isDark);
            localStorage.setItem('darkMode', isDark);
            // Toggle icons
            const moonIcon = document.getElementById('moonIcon');
            const sunIcon = document.getElementById('sunIcon');
            if (moonIcon && sunIcon) {
                moonIcon.classList.toggle('hidden', isDark);
                sunIcon.classList.toggle('hidden', !isDark);
            }
        }
    </script>
</head>

<body class="antialiased no-transition" x-data="{ 
              sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false', 
              mobileSidebarOpen: false 
          }" :class="{ 'sidebar-collapsed': !sidebarOpen }" x-init="
              setTimeout(() => document.body.classList.remove('no-transition'), 100);
              $watch('sidebarOpen', val => {
                  localStorage.setItem('sidebarOpen', val);
                  document.documentElement.classList.toggle('sidebar-collapsed-init', !val);
              });
          ">

    <!-- Mobile Sidebar Overlay (not for student) -->
    @if(Auth::user()->role !== 'student')
    <div x-cloak x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false"
        class="fixed inset-0 z-30 bg-gray-900/50 backdrop-blur-sm md:hidden transition-opacity duration-300"></div>
    @endif

    <div class="min-h-screen flex">
        <!-- Sidebar (hidden on mobile for student since they have bottom nav) -->
        <aside
            class="fixed inset-y-0 left-0 z-40 w-64 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl transition-transform duration-300 transform md:translate-x-0 md:sticky md:top-0 md:h-screen {{ Auth::user()->role === 'student' ? 'hidden md:block' : '' }}"
            :class="{ 
                       'translate-x-0': mobileSidebarOpen, 
                       '-translate-x-full': !mobileSidebarOpen,
                       'w-64': sidebarOpen, 
                       'w-20': !sidebarOpen && !mobileSidebarOpen 
                   }" style="background-color: var(--bg-sidebar);">

            <!-- Logo -->
            <div class="h-20 flex items-center justify-between px-5 logo-section">
                <div class="flex items-center gap-3 overflow-hidden">
                    <button @click="if(!sidebarOpen) sidebarOpen = true"
                        :class="!sidebarOpen ? 'cursor-pointer hover:scale-110' : 'cursor-default'"
                        class="w-11 h-11 rounded-2xl bg-gradient-to-br from-primary-primary to-primary-dark flex items-center justify-center flex-shrink-0 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                    </button>
                    <div class="sidebar-logo-text">
                        <h1 class="text-lg font-bold bg-gradient-to-r from-primary-primary to-primary-dark bg-clip-text text-transparent">{{ config('app.name') }}
                        </h1>
                        <p class="text-[10px] font-medium tracking-wider uppercase" style="color: var(--text-secondary);">{{ __('Academic Management') }}
                        </p>
                    </div>
                </div>
                <!-- Desktop Toggle -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="hidden md:flex items-center justify-center w-8 h-8 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 flex-shrink-0 toggle-btn"
                    style="color: var(--text-secondary);">
                    <svg class="w-5 h-5 sidebar-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </button>
                <!-- Mobile Close -->
                <button @click="mobileSidebarOpen = false" class="md:hidden p-2 rounded-lg transition flex-shrink-0"
                    style="color: var(--text-secondary);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="px-3 py-6 space-y-1.5 overflow-y-auto" style="max-height: calc(100vh - 200px);">

                @if(Auth::user()->isAdmin())
                <!-- Admin Panel -->
                <a href="{{ url('admin/dashboard') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Admin Panel') }}</span>
                </a>

                <div class="pt-5 pb-2">
                    <p
                        class="px-3 text-[9px] font-bold text-primary-secondary/50 uppercase tracking-[0.15em] sidebar-section-title">
                        {{ __('Master Data') }}
                    </p>
                </div>
                @if(Auth::user()->role === 'superadmin')
                <a href="{{ url('admin/faculty') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/faculty*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Faculties') }}</span>
                </a>
                <a href="{{ url('admin/academic-year') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/academic-year*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Academic Year') }}</span>
                </a>
                <a href="{{ url('admin/users') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('User Management') }}</span>
                </a>
                @endif
                <a href="{{ url('admin/study-program') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/study-program*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Study Programs') }}</span>
                </a>
                <a href="{{ url('admin/course') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/course*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Courses') }}</span>
                </a>
                <a href="{{ url('admin/class') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/class*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                    <span class="sidebar-text">{{ __('Classes') }}</span>
                </a>

                <div class="pt-5 pb-2">
                    <p
                        class="px-3 text-[9px] font-bold text-primary-secondary/50 uppercase tracking-[0.15em] sidebar-section-title">
                        {{ __('Administration') }}
                    </p>
                </div>
                <a href="{{ url('admin/student') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/student*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Students') }}</span>
                </a>
                <a href="{{ url('admin/lecturer') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/lecturer*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="sidebar-text">{{ __('Lecturers') }}</span>
                </a>
                <a href="{{ route('admin.notification.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/notification*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Notifications') }}</span>
                </a>
                <a href="{{ route('admin.document-application.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/document-*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Document Requests') }}</span>
                    @php $pendingDocs = \App\Models\DocumentApplication::where('status', 'pending')->count(); @endphp
                    @if($pendingDocs > 0)
                    <span class="ml-auto px-2.5 py-1 text-[10px] font-bold bg-gradient-to-r from-amber-400 to-orange-500 text-white rounded-full shadow-lg">{{ $pendingDocs }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.document-type.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/document-type*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="sidebar-text">{{ __('Document Settings') }}</span>
                </a>
                <a href="{{ route('admin.report.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/report*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Student Reports') }}</span>
                    @php $pendingReports = \App\Models\Report::where('status', 'pending')->count(); @endphp
                    @if($pendingReports > 0)
                    <span class="ml-auto px-2.5 py-1 text-[10px] font-bold bg-gradient-to-r from-red-400 to-red-600 text-white rounded-full shadow-lg">{{ $pendingReports }}</span>
                    @endif
                </a>
                <a href="{{ url('admin/room') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/room*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Rooms') }}</span>
                </a>

                <div class="pt-5 pb-2">
                    <p
                        class="px-3 text-[9px] font-bold text-primary-secondary/50 uppercase tracking-[0.15em] sidebar-section-title">
                        {{ __('Academic') }}
                    </p>
                </div>
                <a href="{{ route('admin.schedule-analysis.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('admin.schedule-analysis.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 17v-2m3 2v-4m3 2v-6m-8-3h7a2 2 0 012 2v12a2 2 0 01-2 2h-7a2 2 0 01-2-2V5a2 2 0 012-2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Schedule Analysis') }}</span>
                </a>
                <a href="{{ url('admin/study-plan-approval') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/study-plan-approval*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Plan Approval') }}</span>
                    @php $pendingCount = \App\Models\StudyPlan::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                    <span
                        class="ml-auto px-2.5 py-1 text-[10px] font-bold bg-gradient-to-r from-amber-400 to-orange-500 text-white rounded-full shadow-lg">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ url('admin/study-plan-settings') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/study-plan-settings*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Plan Settings') }}</span>
                </a>

                <a href="{{ url('admin/thesis') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/thesis*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Theses & Projects') }}</span>
                </a>
                <a href="{{ url('admin/internship') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/internship*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Internship') }}</span>
                </a>
                <a href="{{ url('admin/lecturer-attendance') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/lecturer-attendance*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="sidebar-text">{{ __('Lecturer Attendance') }}</span>
                </a>
                <a href="{{ url('admin/semester-calendar') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('admin/semester-calendar*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Semester Calendar') }}</span>
                </a>
                @endif

                @if(Auth::user()->role === 'student')
                <a href="{{ url('students/dashboard') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/dashboard') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Academic Portal') }}</span>
                </a>

                <div class="pt-5 pb-2">
                    <p
                        class="px-3 text-[9px] font-bold text-primary-secondary/50 uppercase tracking-[0.15em] sidebar-section-title">
                        {{ __('Academic') }}
                    </p>
                </div>
                <a href="{{ url('students/study-plan') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/study-plan*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="sidebar-text">{{ __('Fill Study Plan') }}</span>
                    @php
                    $student = Auth::user()->student;
                    $studyPlanStatus = $student ? \App\Models\StudyPlan::where('student_id', $student->id)->latest()->first() : null;
                    @endphp
                    @if(!$studyPlanStatus || $studyPlanStatus->status === 'draft')
                    <span
                        class="ml-auto px-2.5 py-1 text-[10px] font-bold bg-gradient-to-r from-emerald-400 to-green-500 text-white rounded-full shadow-lg">{{ __('New') }}</span>
                    @endif
                </a>
                <a href="{{ url('students/transcript') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/transcript*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Academic Transcript') }}</span>
                </a>
                <a href="{{ url('students/semester-calendar') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/semester-calendar*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Semester Calendar') }}</span>
                </a>
                <a href="{{ url('students/graduation-checker') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/graduation-checker*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6M12 20h.01M5 12.5V17a3 3 0 003 3h8a3 3 0 003-3v-4.5" />
                    </svg>
                    <span class="sidebar-text">{{ __('Graduation Checker') }}</span>
                </a>
                <a href="{{ route('students.document-application.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/document-application*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Document Requests') }}</span>
                </a>
                <a href="{{ url('students/attendance') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/attendance*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Attendance') }}</span>
                </a>
                <a href="{{ url('students/schedule') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/schedule*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Academic Schedule') }}</span>
                </a>
                <a href="{{ url('students/grade-report') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/grade-report*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Grade Report') }}</span>
                </a>
                <a href="{{ url('students/thesis') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/thesis*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Theses & Projects') }}</span>
                </a>
                <a href="{{ url('students/internship') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/internship*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Internship') }}</span>
                </a>
                <a href="{{ route('students.report.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/report*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('My Reports') }}</span>
                </a>

                <div class="pt-5 pb-2">
                    <p
                        class="px-3 text-[9px] font-bold text-primary-secondary/50 uppercase tracking-[0.15em] sidebar-section-title">
                        {{ __('AI Assistant') }}
                    </p>
                </div>
                <x-sidebar-dropdown title="Smart Advisor" :active="request()->is('students/ai-advisor*')">
                    <x-slot name="icon">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </x-slot>
                    <a href="{{ url('students/ai-advisor') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/ai-advisor*') ? 'active' : '' }}">
                        <span class="sidebar-text">{{ __('Chat with AI') }}</span>
                    </a>
                </x-sidebar-dropdown>

                <x-sidebar-dropdown title="AI Learning Tools" :active="request()->is('students/study-plan-ai*') || request()->is('students/quiz-ai*') || request()->is('students/career-roadmap*') || request()->is('students/subject-search*') || request()->is('students/skill-tree*') || request()->is('students/source-finder*') || request()->routeIs('students.recommendations.*')">
                    <x-slot name="icon">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </x-slot>
                    <a href="{{ url('students/study-plan-ai') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/study-plan-ai*') ? 'active' : '' }}">
                        <span class="sidebar-text">{{ __('Smart Study Plan') }}</span>
                    </a>
                    <a href="{{ url('students/quiz-ai') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/quiz-ai*') ? 'active' : '' }}">
                        <span class="sidebar-text">{{ __('AI Quizzes') }}</span>
                    </a>
                    <a href="{{ url('students/career-roadmap') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/career-roadmap*') ? 'active' : '' }}">
                        <span class="sidebar-text">{{ __('Career Roadmap') }}</span>
                    </a>
                    <a href="{{ url('students/subject-search') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/subject-search*') ? 'active' : '' }}">
                        <span class="sidebar-text">{{ __('Subject Search') }}</span>
                    </a>
                    <a href="{{ url('students/skill-tree') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/skill-tree*') ? 'active' : '' }}">
                        <span class="sidebar-text">{{ __('Skill Tree') }}</span>
                    </a>
                    <a href="{{ url('students/source-finder') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/source-finder*') ? 'active' : '' }}">
                        <span class="sidebar-text">{{ __('Source Finder') }}</span>
                    </a>
                    <a href="{{ route('students.recommendations.index') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('students.recommendations.*') ? 'active' : '' }}">
                        <span class="sidebar-text">{{ __('Recommendations') }}</span>
                    </a>
                </x-sidebar-dropdown>
                <div class="pt-5 pb-2">
                    <p
                        class="px-3 text-[9px] font-bold text-primary-secondary/50 uppercase tracking-[0.15em] sidebar-section-title">
                        {{ __('E-Learning') }}
                    </p>
                </div>
                <a href="{{ url('students/lms') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/lms*') || request()->is('students/material*') || request()->is('students/assignment*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Materials & Assignments') }}</span>
                </a>

                <div class="pt-5 pb-2">
                    <p
                        class="px-3 text-[9px] font-bold text-primary-secondary/50 uppercase tracking-[0.15em] sidebar-section-title">
                        {{ __('Personal') }}
                    </p>
                </div>
                <a href="{{ route('students.profile.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/profile*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="sidebar-text">{{ __('Personal Data') }}</span>
                </a>
                @endif

                @if(Auth::user()->role === 'lecturer')
                <a href="{{ route('lecturers.dashboard') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('lecturers.dashboard') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Dashboard') }}</span>
                </a>

                <div class="pt-5 pb-2">
                </div>
                <a href="{{ route('lecturers.supervision.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('lecturers.supervision.index') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Supervised Students') }}</span>
                </a>
                <a href="{{ route('lecturers.supervision.study-plan-approval') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('lecturers.supervision.study-plan-approval') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Plan Approval') }}</span>

                    @php
                    $lecturer = Auth::user()->lecturer;
                    $pendingStudyPlans = $lecturer ? \App\Models\StudyPlan::whereIn('student_id', $lecturer->advisedStudents()->pluck('id'))->where('status', 'pending')->count() : 0;
                    @endphp
                    @if($pendingStudyPlans > 0)
                    <span
                        class="ml-auto px-2.5 py-1 text-[10px] font-bold bg-gradient-to-r from-amber-400 to-orange-500 text-white rounded-full shadow-lg">{{ $pendingStudyPlans }}</span>
                    @endif
                </a>

                <div class="pt-5 pb-2">
                    <p
                        class="px-3 text-[9px] font-bold text-primary-secondary/50 uppercase tracking-[0.15em] sidebar-section-title">
                        {{ __('Communication') }}
                    </p>
                </div>
                <a href="{{ route('lecturers.chat-requests.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('lecturers.chat-requests.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span class="sidebar-text">{{ __('Chat Requests') }}</span>
                    @php
                    $lecturer = Auth::user()->lecturer;
                    $pendingChatRequests = $lecturer ? \App\Models\ChatRequest::where('lecturer_id', $lecturer->id)->where('status', 'pending')->count() : 0;
                    @endphp
                    @if($pendingChatRequests > 0)
                    <span
                        class="ml-auto px-2.5 py-1 text-[10px] font-bold bg-gradient-to-br from-primary-primary to-primary-dark text-white rounded-full shadow-lg">{{ $pendingChatRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('lecturers.notification.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('lecturers/notification*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Broadcast Messages') }}</span>
                </a>

                <div class="pt-5 pb-2">
                    <p
                        class="px-3 text-[9px] font-bold text-primary-secondary/50 uppercase tracking-[0.15em] sidebar-section-title">
                        {{ __('Teaching') }}
                    </p>
                </div>
                <a href="{{ route('lecturers.grading.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('lecturers.grading.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Grading') }}</span>
                </a>
                <a href="{{ route('lecturers.attendance-input.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('lecturers.attendance-input.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Student Attendance') }}</span>
                </a>
                <a href="{{ route('lecturers.thesis.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('lecturers.thesis.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Thesis Supervision') }}</span>
                </a>
                <a href="{{ route('lecturers.internship.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('lecturers.internship.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Internship Supervision') }}</span>
                </a>
                <a href="{{ route('lecturers.attendance.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('lecturers.attendance.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="sidebar-text">{{ __('Lecturer Presence') }}</span>
                </a>

                <div class="pt-5 pb-2">
                    <p
                        class="px-3 text-[9px] font-bold text-primary-secondary/50 uppercase tracking-[0.15em] sidebar-section-title">
                        {{ __('E-Learning') }}
                    </p>
                </div>
                <a href="{{ route('lecturers.lms.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-primary-secondary text-sm font-medium {{ request()->routeIs('lecturers.lms.*') || request()->routeIs('lecturers.material.*') || request()->routeIs('lecturers.assignment.*') ? 'active' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <span class="sidebar-text">{{ __('Materials & Assignments') }}</span>
                </a>
                @endif
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 left-0 right-0 p-4" style="background: linear-gradient(to top, var(--bg-sidebar) 0%, transparent 100%);">
                <div class="flex items-center gap-3 user-section bg-gradient-to-r from-primary-primary/10 to-primary-dark/10 rounded-2xl p-3 backdrop-blur-sm">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-primary to-primary-dark flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0 sidebar-user-info">
                        <p class="text-sm font-medium truncate" style="color: var(--text-primary);">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-[11px] capitalize" style="color: var(--text-secondary);">{{ Auth::user()->role }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="sidebar-user-info">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg transition-colors"
                            style="color: var(--text-secondary);" title="{{ __('Log Out') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300 min-w-0">
            <!-- Top Header -->
            <header class="h-20 flex items-center justify-between px-6 md:px-10 sticky top-0 z-20 backdrop-blur-xl bg-white/80 dark:bg-gray-900/80"
                style="background-color: var(--bg-card);">
                <div class="flex items-center gap-4">
                    <!-- Mobile Hamburger (not for student) -->
                    @if(Auth::user()->role !== 'student')
                    <button @click="mobileSidebarOpen = true"
                        class="md:hidden p-2 -ml-2 rounded-lg text-primary-secondary hover:bg-primary-light/50 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    @endif

                    @isset($header)
                    <h1 class="text-xl font-bold truncate max-w-[200px] md:max-w-none bg-gradient-to-r from-primary-primary to-primary-dark bg-clip-text text-transparent">{{ $header }}</h1>
                    @endisset
                </div>
                <div class="flex items-center gap-3 md:gap-5">
                    @if(in_array(auth()->user()->role, ['student', 'lecturer', 'admin', 'superadmin', 'admin_faculty']))
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" onclick="toggleDarkMode()"
                        class="p-2.5 rounded-xl transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:scale-105"
                        style="color: var(--text-secondary);" title="{{ __('Toggle Dark Mode') }}">
                        <svg id="moonIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                        <svg id="sunIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </button>
                    <script>
                        // Sync icon state on load
                        if (localStorage.getItem('darkMode') === 'true') {
                            document.getElementById('moonIcon')?.classList.add('hidden');
                            document.getElementById('sunIcon')?.classList.remove('hidden');
                        }
                    </script>
                    @endif

                    <!-- Notifications Bell -->
                    @php $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->unread()->count(); @endphp
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen"
                            class="p-2.5 rounded-xl transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:scale-105 relative"
                            style="color: var(--text-secondary);" title="{{ __('Notifications') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            @if($unreadCount > 0)
                            <span
                                class="absolute -top-1 -right-1 w-5 h-5 text-[10px] font-bold bg-gradient-to-br from-red-500 to-red-600 text-white rounded-full flex items-center justify-center shadow-lg animate-pulse">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-cloak x-show="notifOpen" @click.away="notifOpen = false" x-transition
                            class="absolute end-0 mt-3 w-[calc(100vw-2rem)] sm:w-96 rounded-2xl shadow-2xl z-50 overflow-hidden animate-fade-in backdrop-blur-xl bg-white/95 dark:bg-gray-900/95"
                            style="background-color: var(--bg-card);">
                            <div class="px-4 py-3 flex items-center justify-between"
                                style="border-bottom: 1px solid var(--border-color);">
                                <h3 class="font-semibold" style="color: var(--text-primary);">{{ __('Notifications') }}</h3>
                                @if($unreadCount > 0)
                                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs text-indigo-600 hover:underline">{{ __('Mark all as read') }}</button>
                                </form>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @php $notifications = \App\Models\Notification::where('user_id', Auth::id())->orderBy('created_at', 'desc')->limit(10)->get(); @endphp
                                @forelse($notifications as $notif)
                                @php $isRead = $notif->read_at !== null; @endphp
                                <a href="{{ route('notifications.index') }}"
                                    class="block px-4 py-3 hover:bg-primary-light/30 transition {{ !$isRead ? 'bg-indigo-50/50' : '' }}"
                                    style="border-bottom: 1px solid var(--border-color);">
                                    <div class="flex gap-3">
                                        <span class="text-lg">{{ $notif->icon }}</span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium truncate {{ !$isRead ? 'text-indigo-700' : '' }}"
                                                style="color: {{ $isRead ? 'var(--text-primary)' : '' }};">
                                                {{ $notif->title }}
                                            </p>
                                            <p class="text-xs mt-0.5 truncate" style="color: var(--text-secondary);">
                                                {{ Str::limit($notif->message, 50) }}
                                            </p>
                                            <p class="text-[10px] mt-1" style="color: var(--text-secondary);">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                                @empty
                                <div class="px-4 py-8 text-center">
                                    <p class="text-sm" style="color: var(--text-secondary);">{{ __('No notifications') }}</p>
                                </div>
                                @endforelse
                            </div>
                            @if($notifications->isNotEmpty())
                            <a href="{{ route('notifications.index') }}"
                                class="block px-4 py-3 text-center text-sm text-indigo-600 hover:bg-primary-light/30 transition">{{ __('View All') }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="ltr:text-right rtl:text-left hidden md:block">
                        <p class="text-sm font-medium" style="color: var(--text-primary);">{{ Auth::user()->name }}</p>
                        <p class="text-[11px]" style="color: var(--text-secondary);">
                            {{ now()->translatedFormat('l, d M Y') }}
                        </p>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 md:p-8 pb-24 md:pb-8">
                <!-- Flash Messages -->
                @if(session('success'))
                <div
                    class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm font-medium animate-fade-in">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div
                    class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm font-medium animate-fade-in">
                    {{ session('error') }}
                </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @if(Auth::user()->role === 'student')
    <!-- Bottom Navigation (Mobile Only) -->
    <nav
        class="fixed bottom-0 z-50 w-full bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700 md:hidden flex justify-around items-center h-16 pb-safe safe-area-bottom">
        <a href="{{ url('students/dashboard') }}"
            class="flex flex-col items-center justify-center w-full h-full text-[10px] font-medium {{ request()->is('students/dashboard') ? 'text-primary-primary dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            <span>{{ __('Home') }}</span>
        </a>

        <a href="{{ url('students/schedule') }}"
            class="flex flex-col items-center justify-center w-full h-full text-[10px] font-medium {{ request()->is('students/schedule*') ? 'text-primary-primary dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>{{ __('Schedule') }}</span>
        </a>

        <a href="{{ route('students.attendance.index') }}"
            class="flex flex-col items-center justify-center w-full h-full text-[10px] font-medium {{ request()->routeIs('students.attendance.*') ? 'text-primary-primary dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
            <div
                class="w-12 h-12 bg-primary-primary rounded-full flex items-center justify-center -mt-6 border-4 border-white dark:border-gray-900 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 16H4v4h2v-4zM6 9H4v5h2V9zm6 0h-2v5h2V9zm6 0h-2v5h2V9z">
                    </path>
                </svg>
            </div>
            <span class="mt-1">{{ __('Attendance') }}</span>
        </a>

        <a href="{{ url('students/grade-report') }}"
            class="flex flex-col items-center justify-center w-full h-full text-[10px] font-medium {{ request()->is('students/grade-report*') ? 'text-primary-primary dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg>
            <span>{{ __('Grades') }}</span>
        </a>

        <button type="button" @click="$dispatch('open-mobile-menu')"
            class="flex flex-col items-center justify-center w-full h-full text-[10px] font-medium text-gray-500 dark:text-gray-400">
            <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
            <span>{{ __('Menu') }}</span>
        </button>
    </nav>

    <!-- Mobile Menu Drawer -->
    <div x-data="{ open: false }" @open-mobile-menu.window="open = true" @keydown.escape.window="open = false"
        x-show="open" class="relative z-50 md:hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true"
        style="display: none;">
        <div x-show="open" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

        <div class="fixed inset-x-0 bottom-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-x-0 bottom-0 flex max-h-full">
                <div x-show="open" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
                    class="pointer-events-auto w-screen max-w-md">
                    <div
                        class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-gray-800 shadow-xl pb-20 rounded-t-2xl">
                        <div class="px-4 py-6 sm:px-6">
                            <div class="flex item-center justify-between">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-white" id="slide-over-title">
                                    {{ __('Other Menus') }}
                                </h2>
                                <button type="button"
                                    class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none"
                                    @click="open = false">
                                    <span class="sr-only">{{ __('Close Panel') }}</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="relative flex-1 px-4 sm:px-6">
                            <div class="grid grid-cols-3 gap-4">
                                <a href="{{ url('students/study-plan') }}"
                                    class="flex flex-col items-center justify-center p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700 dark:text-gray-300">{{ __('Fill Study Plan') }}</span>
                                </a>

                                <a href="{{ url('students/transcript') }}"
                                    class="flex flex-col items-center justify-center p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700 dark:text-gray-300">{{ __('Academic Transcript') }}</span>
                                </a>

                                <a href="{{ url('students/graduation-checker') }}"
                                    class="flex flex-col items-center justify-center p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <div
                                        class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 14l9-5-9-5-9 5 9 5zm0 0v6M12 20h.01M5 12.5V17a3 3 0 003 3h8a3 3 0 003-3v-4.5">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-center font-medium text-gray-700 dark:text-gray-300">{{ __('Graduation Checker') }}</span>
                                </a>

                                <a href="{{ route('students.profile.index') }}"
                                    class="flex flex-col items-center justify-center p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <div
                                        class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <span
                                        class="text-xs text-center font-medium text-gray-700 dark:text-gray-300">{{ __('Personal Data') }}</span>
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="block w-full">
                                    @csrf
                                    <button type="submit"
                                        class="flex flex-col items-center justify-center p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-red-50 dark:hover:bg-red-900/20 transition w-full h-full">
                                        <div
                                            class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
                                            </svg>
                                        </div>
                                        <span
                                            class="text-xs text-center font-medium text-red-600 dark:text-red-400">{{ __('Log Out') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @stack('scripts')
</body>

</html>