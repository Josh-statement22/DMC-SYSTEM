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
        }

        .sidebar {
            transition: transform 0.3s ease, width 0.3s ease;
        }

        /* Tablet/mobile: sidebar hidden by default, slide in from left */
        @media (max-width: 1023px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                transform: translateX(-100%);
                z-index: 50;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }

            .sidebar-overlay.mobile-open {
                display: block;
            }
        }

        /* Desktop: sidebar always visible */
        @media (min-width: 1024px) {
            .sidebar {
                position: static;
                transform: none;
                height: auto;
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

<body class="relative overflow-hidden">

<!-- Decorative Background -->
<div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-200 opacity-30 rounded-full blur-3xl hidden md:block"></div>
<div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-blue-100 opacity-40 rounded-full blur-3xl hidden md:block"></div>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="app-shell relative flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="app-sidebar sidebar w-64 bg-white shadow-xl flex flex-col border-r z-20 lg:translate-x-0">

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
    <div class="app-shell-main relative z-10 flex min-w-0 flex-1 flex-col">

        <!-- TOPBAR -->
        <header class="app-topbar relative z-[80] h-16 glass bg-white/70 border-b
                       flex items-center justify-between gap-3 px-4 md:px-8 shadow-sm">

            <div class="flex min-w-0 items-center gap-3 md:gap-4">
                <button onclick="toggleSidebar()"
                    class="mobile-toggle shrink-0 p-2 rounded-lg hover:bg-gray-100 transition lg:hidden">
                    <i data-feather="menu"></i>
                </button>

                <h1 class="app-page-title min-w-0 text-base font-semibold leading-tight text-gray-700 tracking-wide sm:text-lg">
                    @yield('title')
                </h1>
            </div>

            <x-user-profile-badge gradientClasses="from-[#1C446D] to-blue-700" />

        </header>

        <!-- CONTENT -->
        <main class="app-content min-w-0 flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-10">

            @if(request()->routeIs('admin.dashboard') || request()->routeIs('admin.liquidation'))
                @yield('content')
            @else
                <div class="app-content-card glass bg-white/80 rounded-2xl md:rounded-3xl
                            shadow-lg p-6 md:p-10 border border-white/40
                            transition hover:shadow-2xl">

                    @yield('content')

                </div>
            @endif

        </main>

    </div>
</div>

<script>
    feather.replace()

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('mobile-open');
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('mobile-open');
    }

    // Close sidebar when clicking on a menu item
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
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
