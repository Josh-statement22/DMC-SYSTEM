<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DMC ERP - Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        body {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        }

        .sidebar {
            transition: width 0.3s ease;
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
<div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-200 opacity-30 rounded-full blur-3xl"></div>
<div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-blue-100 opacity-40 rounded-full blur-3xl"></div>

<div class="relative flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="sidebar w-64 bg-white shadow-xl flex flex-col border-r z-20">

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

            <a href="{{ route('admin.pricelist') }}"
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
            </a>

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
    <div class="flex-1 flex flex-col relative z-10">

        <!-- TOPBAR -->
        <header class="h-16 glass bg-white/70 border-b
                       flex items-center justify-between px-8 shadow-sm">

            <div class="flex items-center space-x-4">
                <button onclick="toggleSidebar()"
                    class="p-2 rounded-lg hover:bg-gray-100 transition">
                    <i data-feather="menu"></i>
                </button>

                <h1 class="text-lg font-semibold text-gray-700 tracking-wide">
                    @yield('title')
                </h1>
            </div>

            <!-- USER DROPDOWN -->
            <div class="relative">
                <button onclick="toggleDropdown()"
                    class="flex items-center space-x-3 group">

                    <div class="w-10 h-10 rounded-full bg-gradient-to-br
                                from-[#1C446D] to-blue-700
                                text-white flex items-center justify-center
                                font-semibold shadow-md">
                        A
                    </div>

                    <span class="text-gray-700 font-medium group-hover:text-[#1C446D] transition">
                        Admin
                    </span>

                    <i data-feather="chevron-down"
                       class="w-4 h-4 text-gray-500 group-hover:text-[#1C446D] transition"></i>
                </button>

                <!-- DROPDOWN (LOGOUT ONLY) -->
                <div id="dropdown"
                    class="dropdown absolute right-0 mt-3 w-48 bg-white
                    rounded-2xl shadow-xl border z-50
                    opacity-0 scale-95 invisible">

                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-6 py-3
                                   hover:bg-gray-50 transition
                                   text-red-500 font-medium">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </div>

        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-10 overflow-y-auto">

            <div class="glass bg-white/80 rounded-3xl
                        shadow-lg p-10 border border-white/40
                        transition hover:shadow-2xl">

                @yield('content')

            </div>

        </main>

    </div>
</div>

<script>
    feather.replace()

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('collapsed')
    }

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