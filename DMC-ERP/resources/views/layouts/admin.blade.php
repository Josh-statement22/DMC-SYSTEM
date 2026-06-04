<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DMC ERP - Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        body {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            overflow-x: hidden;
        }

        body.sidebar-open {
            overflow: hidden;
        }

        .app-shell {
            height: 100vh;
            height: 100dvh;
            min-width: 0;
        }

        .app-main,
        .app-content,
        .app-content-card {
            min-width: 0;
        }

        .sidebar {
            transition: transform 0.3s ease, width 0.3s ease;
            width: 16rem;
            max-width: calc(100vw - 3rem);
            flex-shrink: 0;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.5);
            z-index: 30;
        }

        /* Mobile: sidebar hidden by default, slide in from left */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                height: 100dvh;
                transform: translateX(-100%);
                z-index: 40;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar-overlay.mobile-open {
                display: block;
            }

            .menu-item:hover {
                transform: none;
            }

            .app-topbar {
                min-height: 4rem;
                height: auto;
            }

            .app-page-title {
                font-size: 1rem;
                line-height: 1.35;
            }
        }

        /* Desktop: sidebar always visible */
        @media (min-width: 769px) {
            .sidebar {
                position: static;
                transform: none;
                height: 100vh;
                height: 100dvh;
            }

            .sidebar-overlay {
                display: none !important;
            }

            .mobile-toggle {
                display: none;
            }
        }

        .sidebar.collapsed {
            width: 90px;
        }

        .sidebar.collapsed .menu-text {
            display: none;
        }

        .sidebar.collapsed nav a {
            justify-content: center;
        }

        .menu-item {
            transition: all 0.25s ease;
        }

        .menu-item:hover {
            transform: translateX(6px);
        }

        .active-item {
            background: #EFEFEF;
            color: #1C446D;
            box-shadow: inset 4px 0 0 #1C446D;
        }

        .dropdown {
            transition: all 0.2s ease;
        }

        .glass {
            backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="relative overflow-x-hidden">

<!-- Decorative Background -->
<div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-200 opacity-30 rounded-full blur-3xl hidden md:block"></div>
<div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-blue-100 opacity-40 rounded-full blur-3xl hidden md:block"></div>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="app-shell relative flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="sidebar bg-white shadow-xl flex flex-col border-r z-20 md:translate-x-0"
        aria-label="Admin navigation">

        <!-- LOGO -->
        <div class="h-20 flex items-center justify-center border-b">
            <img src="{{ asset('images/logo.png') }}"
                 class="h-12 object-contain transition-all duration-300 hover:scale-105">
        </div>

        <!-- MENU -->
        <nav class="flex-1 p-4 space-y-2">

            @php
                $active = "active-item";
                $normal = "text-gray-600 hover:bg-gray-100 hover:text-[#1C446D]";
            @endphp

            <a href="{{ route('admin.dashboard') }}"
               class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl font-medium
               {{ request()->routeIs('admin.dashboard') ? $active : $normal }}">

                <i data-feather="home"></i>
                <span class="menu-text">Dashboard</span>
            </a>

            <!-- <a href="{{ route('admin.pricelist') }}"
               class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl font-medium
               {{ request()->routeIs('admin.pricelist') ? $active : $normal }}">

                <i data-feather="tag"></i>
                <span class="menu-text">Price List</span>
            </a>

            <a href="{{ route('admin.purchase') }}"
               class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl font-medium
               {{ request()->routeIs('admin.purchase') ? $active : $normal }}">

                <i data-feather="shopping-cart"></i>
                <span class="menu-text">Purchase Orders</span>
            </a>

            <a href="{{ route('admin.additem') }}"
               class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl font-medium
               {{ request()->routeIs('admin.additem') ? $active : $normal }}">

                <i data-feather="plus-circle"></i>
                <span class="menu-text">Add Item</span>
            </a>

            <a href="{{ route('admin.priceanalysis') }}"
               class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl font-medium
               {{ request()->routeIs('admin.priceanalysis') ? $active : $normal }}">

                <i data-feather="bar-chart-2"></i>
                <span class="menu-text">Price Analysis</span>
            </a> -->

            <a href="{{ route('admin.liquidation') }}"
               class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl font-medium
               {{ request()->routeIs('admin.liquidation') ? $active : $normal }}">

                <i data-feather="trash-2"></i>
                <span class="menu-text">Liquidation</span>
            </a>

        </nav>

        <div class="p-4 border-t text-xs text-gray-400 text-center">
            © 2026 DMC ERP
        </div>

    </aside>

    <!-- MAIN -->
    <div class="app-main flex-1 flex flex-col relative z-10">

        <!-- TOPBAR -->
        <header class="app-topbar h-16 glass bg-white/70 border-b
                       flex items-center justify-between gap-3 px-4 md:px-8 shadow-sm">

            <div class="flex min-w-0 items-center space-x-3 md:space-x-4">
                <button type="button" onclick="toggleSidebar()"
                    class="mobile-toggle inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg hover:bg-gray-100 transition md:hidden"
                    aria-controls="sidebar"
                    aria-expanded="false"
                    aria-label="Open navigation menu">
                    <i data-feather="menu"></i>
                </button>

                <h1 class="app-page-title truncate text-lg font-semibold text-gray-700 tracking-wide">
                    @yield('title')
                </h1>
            </div>

            <x-user-profile-badge gradientClasses="from-[#1C446D] to-blue-700" />

        </header>

        <!-- CONTENT -->
        <main class="app-content flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-10">

            <div class="app-content-card glass bg-white/80 rounded-2xl md:rounded-3xl
                        shadow-lg p-6 md:p-10 border border-white/40
                        transition hover:shadow-2xl">

                @yield('content')

            </div>

        </main>

    </div>
</div>

<script>
    feather.replace()

    function setSidebarState(isOpen) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.querySelector('.mobile-toggle');

        if (!sidebar || !overlay) {
            return;
        }

        sidebar.classList.toggle('mobile-open', isOpen);
        overlay.classList.toggle('mobile-open', isOpen);
        document.body.classList.toggle('sidebar-open', isOpen);

        if (toggle) {
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            toggle.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
        }
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        setSidebarState(!sidebar.classList.contains('mobile-open'));
    }

    function closeSidebar() {
        setSidebarState(false);
    }

    // Close sidebar when clicking on a menu item
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth < 769) {
                closeSidebar();
            }
        });
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 769) {
            closeSidebar();
        }
    });

    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown')
        dropdown.classList.toggle('opacity-0')
        dropdown.classList.toggle('scale-95')
        dropdown.classList.toggle('invisible')
    }
</script>

@stack('scripts')

</body>
</html>
