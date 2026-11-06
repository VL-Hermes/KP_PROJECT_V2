<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Theme Preference Initialization (Prevents Flash of Light Mode) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('laptophub_theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        })();
    </script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Used Laptop Catalog & Inventory'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts and Styles via Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --app-bg: #f8fafc;
            --app-color: #334155;
            --app-card-bg: #ffffff;
            --app-card-border: rgba(0, 0, 0, 0.08);
            --app-card-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            --app-item-border: #f1f5f9;
        }

        [data-bs-theme="dark"] {
            --app-bg: #0b1120;
            --app-color: #cbd5e1;
            --app-card-bg: #1e293b;
            --app-card-border: #334155;
            --app-card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -2px rgba(0, 0, 0, 0.3);
            --app-item-border: #334155;
        }

        body {
            background-color: var(--app-bg);
            color: var(--app-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        main {
            flex: 1;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .card {
            border-radius: 0.75rem;
            border: 1px solid var(--app-card-border);
            box-shadow: var(--app-card-shadow);
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }

        .badge {
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .spec-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--app-item-border);
        }

        .spec-item:last-child {
            border-bottom: none;
        }

        .quick-fill-highlight {
            animation: highlightFade 1.8s ease-in-out;
        }

        @keyframes highlightFade {
            0% { background-color: #fef08a; }
            100% { background-color: transparent; }
        }

        /* Dark Mode Specific Overrides for Elevated Appearance */
        [data-bs-theme="dark"] .bg-white {
            background-color: #1e293b !important;
            color: #f8fafc;
        }

        [data-bs-theme="dark"] .text-dark {
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .bg-light {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .text-secondary {
            color: #94a3b8 !important;
        }

        [data-bs-theme="dark"] .text-muted {
            color: #94a3b8 !important;
        }

        [data-bs-theme="dark"] .border {
            border-color: #334155 !important;
        }

        [data-bs-theme="dark"] .border-bottom,
        [data-bs-theme="dark"] .border-top,
        [data-bs-theme="dark"] .border-start,
        [data-bs-theme="dark"] .border-end {
            border-color: #334155 !important;
        }

        [data-bs-theme="dark"] .input-group-text.bg-white,
        [data-bs-theme="dark"] .input-group-text {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #94a3b8 !important;
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            background-color: #0f172a !important;
            border-color: #3b82f6 !important;
            color: #f8fafc !important;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        [data-bs-theme="dark"] .form-control::placeholder {
            color: #64748b !important;
        }

        [data-bs-theme="dark"] .table {
            color: #e2e8f0;
            border-color: #334155;
        }

        [data-bs-theme="dark"] .table-light {
            background-color: #1e293b !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }

        [data-bs-theme="dark"] .table-hover > tbody > tr:hover > * {
            background-color: #24334a !important;
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }

        [data-bs-theme="dark"] .dropdown-item {
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: #334155 !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .modal-content {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .alert-light {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .list-group-item {
            background-color: transparent !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .badge.bg-light {
            background-color: #334155 !important;
            color: #e2e8f0 !important;
            border-color: #475569 !important;
        }

        [data-bs-theme="dark"] .badge.bg-dark-subtle {
            background-color: #334155 !important;
            color: #f8fafc !important;
            border-color: #475569 !important;
        }

        [data-bs-theme="dark"] .breadcrumb-item.active {
            color: #94a3b8;
        }

        [data-bs-theme="dark"] footer.bg-white {
            background-color: #0b1120 !important;
            border-color: #1e293b !important;
        }

        [data-bs-theme="dark"] .card.bg-warning-subtle {
            background-color: rgba(234, 179, 8, 0.12) !important;
            border-color: rgba(234, 179, 8, 0.3) !important;
        }

        [data-bs-theme="dark"] .card.bg-warning-subtle h5,
        [data-bs-theme="dark"] .card.bg-warning-subtle p {
            color: #fef08a !important;
        }

        /* Subtle glow for dark mode navbar */
        [data-bs-theme="dark"] .navbar {
            background-color: #0b1120 !important;
            border-bottom: 1px solid #1e293b;
        }

        /* Floating AI Chatbot Widget (Always fixed to viewport bottom) */
        .ai-chat-container {
            position: fixed !important;
            bottom: 24px !important;
            right: 24px !important;
            z-index: 1060 !important;
        }

        #ai-chat-toggle-btn {
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        #ai-chat-toggle-btn:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 10px 28px rgba(13, 110, 253, 0.45);
        }

        #ai-chat-card {
            position: fixed !important;
            bottom: 84px !important;
            right: 24px !important;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.3) !important;
            animation: chatSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            z-index: 1065 !important;
        }

        @keyframes chatSlideUp {
            from {
                opacity: 0;
                transform: translateY(16px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .ai-bubble-user {
            background-color: #0d6efd;
            color: #ffffff;
            align-self: flex-end;
            border-radius: 1rem 1rem 0.2rem 1rem;
            padding: 0.6rem 0.9rem;
            max-width: 85%;
            word-break: break-word;
        }

        .ai-bubble-bot {
            background-color: var(--app-card-bg, #ffffff);
            border: 1px solid var(--app-item-border, #e2e8f0);
            color: inherit;
            align-self: flex-start;
            border-radius: 1rem 1rem 1rem 0.2rem;
            padding: 0.75rem 1rem;
            max-width: 95%;
            word-break: break-word;
        }

        .ai-bubble-bot p:last-child {
            margin-bottom: 0;
        }

        .ai-bubble-bot a {
            font-weight: 600;
            text-decoration: underline;
        }

        .ai-prompt-chip {
            transition: all 0.15s ease;
        }

        .ai-prompt-chip:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 576px) {
            .ai-chat-container {
                bottom: 16px !important;
                right: 16px !important;
            }
            #ai-chat-card {
                bottom: 14px !important;
                right: 12px !important;
                left: 12px !important;
                width: auto !important;
                max-width: none !important;
                height: calc(100vh - 28px) !important;
                height: calc(100dvh - 28px) !important;
                z-index: 1070 !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-2">
            <div class="container-fluid px-3 px-lg-4 px-xl-5">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('catalog.index') }}">
                    <i class="bi bi-laptop fs-4 text-warning"></i>
                    <span>Laptop<span class="text-warning">Hub</span></span>
                    <span class="badge bg-secondary ms-1 fs-6 fw-normal d-none d-sm-inline-block">Spec Catalog</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('catalog.*') ? 'active fw-bold text-white' : '' }}" href="{{ route('catalog.index') }}">
                                <i class="bi bi-grid-3x3-gap me-1"></i> Public Catalog
                            </a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('laptops.index') ? 'active fw-bold text-white' : '' }}" href="{{ route('laptops.index') }}">
                                    <i class="bi bi-table me-1"></i> Inventory Manager
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                        <!-- Theme Toggle Button -->
                        <li class="nav-item">
                            <button id="themeToggleBtn" 
                                    type="button" 
                                    class="btn btn-outline-light btn-sm d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill" 
                                    title="Toggle Light / Dark Mode" 
                                    aria-label="Toggle theme">
                                <i id="themeToggleIcon" class="bi bi-moon-stars-fill"></i>
                                <span class="small d-none d-sm-inline" id="themeToggleText">Theme</span>
                            </button>
                        </li>

                        @auth
                            <li class="nav-item">
                                <a class="btn btn-warning btn-sm fw-bold px-3 d-inline-flex align-items-center gap-1 shadow-sm" href="{{ route('laptops.create') }}">
                                    <i class="bi bi-plus-circle-fill"></i> Add Laptop
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-shield-check text-warning"></i>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('laptops.index') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> Inventory Manager
                                    </a>
                                    <a class="dropdown-item" href="{{ route('laptops.create') }}">
                                        <i class="bi bi-plus-circle me-2"></i> Add New Laptop
                                    </a>
                                    <a class="dropdown-item" href="{{ route('catalog.index') }}">
                                        <i class="bi bi-eye me-2"></i> Public Catalog
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                        <i class="bi bi-person-gear me-2"></i> Account Settings
                                    </a>
                                    @if(Auth::user()->isMainAdmin())
                                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        <i class="bi bi-people-fill me-2"></i> Manage Admins
                                        <span class="badge bg-warning text-dark ms-1" style="font-size:0.65rem;">Main</span>
                                    </a>
                                    @endif
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Log Out
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Global Flash Alerts -->
        <div class="container mt-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <div class="fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill fs-5 me-2"></i>
                        Mohon periksa form, terdapat kesalahan berikut:
                    </div>
                    <ul class="mb-0 ps-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="bg-white border-top py-4 mt-auto text-center text-muted small">
            <div class="container-fluid px-3 px-lg-4 px-xl-5">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                    <div>
                        <strong>LaptopHub</strong> &mdash; Katalog Spesifikasi Laptop Bekas & Sistem Inventaris
                    </div>
                    <div class="text-secondary">
                        Built with Laravel & Bootstrap 5
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Floating AI Laptop Recommendation Advisor Widget -->
    @include('components.ai-chat-widget')

    <!-- Theme Toggle Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const themeToggleIcon = document.getElementById('themeToggleIcon');
            const themeToggleText = document.getElementById('themeToggleText');

            function applyTheme(theme) {
                document.documentElement.setAttribute('data-bs-theme', theme);
                localStorage.setItem('laptophub_theme', theme);

                if (theme === 'dark') {
                    if (themeToggleIcon) themeToggleIcon.className = 'bi bi-sun-fill text-warning';
                    if (themeToggleText) themeToggleText.textContent = 'Light';
                    if (themeToggleBtn) {
                        themeToggleBtn.title = 'Switch to Light Mode';
                        themeToggleBtn.classList.remove('btn-outline-light');
                        themeToggleBtn.classList.add('btn-outline-warning');
                    }
                } else {
                    if (themeToggleIcon) themeToggleIcon.className = 'bi bi-moon-stars-fill text-white';
                    if (themeToggleText) themeToggleText.textContent = 'Dark';
                    if (themeToggleBtn) {
                        themeToggleBtn.title = 'Switch to Dark Mode';
                        themeToggleBtn.classList.remove('btn-outline-warning');
                        themeToggleBtn.classList.add('btn-outline-light');
                    }
                }
            }

            // Sync icon on startup
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            applyTheme(currentTheme);

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    const activeTheme = document.documentElement.getAttribute('data-bs-theme');
                    const newTheme = activeTheme === 'dark' ? 'light' : 'dark';
                    applyTheme(newTheme);
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
