<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DMC ERP - Accounting</title>

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
            color: #0f766e;
            box-shadow: inset 4px 0 0 #0f766e;
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

<div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-emerald-200 opacity-30 rounded-full blur-3xl"></div>
<div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-teal-100 opacity-40 rounded-full blur-3xl"></div>

<div class="relative flex h-screen overflow-hidden">

    <aside id="sidebar" class="sidebar w-64 bg-white shadow-xl flex flex-col border-r z-20">
        <div class="h-20 flex items-center justify-center border-b">
            <img src="{{ asset('images/logo.png') }}" class="h-12 object-contain transition-all duration-300 hover:scale-105">
        </div>

        <nav class="flex-1 p-4 space-y-2">
            @php
                $active = "active-item";
                $normal = "text-gray-600 hover:bg-gray-100 hover:text-teal-700";
            @endphp

            <a id="accountingDashboardNav" href="{{ route('accounting.dashboard') }}"
               class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl font-medium {{ request()->routeIs('accounting.dashboard') ? $active : $normal }}">
                <i data-feather="home"></i>
                <span class="menu-text">Dashboard</span>
            </a>

            <a id="accountingLiquidateExpensesNav" href="{{ route('accounting.liquidate-expenses') }}"
               class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl font-medium {{ request()->routeIs('accounting.liquidate-expenses') ? $active : $normal }}">
                <i data-feather="receipt"></i>
                <span class="menu-text">Liquidate Expenses</span>
            </a>

            <a id="accountingLiquidationNav" href="{{ route('accounting.liquidation') }}"
               class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl font-medium {{ request()->routeIs('accounting.liquidation*') ? $active : $normal }}">
                <i data-feather="clipboard"></i>
                <span class="menu-text">Liquidation Tracking</span>
            </a>
        </nav>

        <div class="p-4 border-t text-xs text-gray-400 text-center">
            © 2026 DMC ERP
        </div>
    </aside>

    <div class="flex-1 flex flex-col relative z-10">
        <header class="h-16 glass bg-white/70 border-b flex items-center justify-between px-8 shadow-sm">
            <div class="flex items-center space-x-4">
                <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 transition">
                    <i data-feather="menu"></i>
                </button>

                <h1 id="accountingPageTitle" class="text-lg font-semibold text-gray-700 tracking-wide">@yield('title')</h1>
            </div>

            <div class="relative">
                <button onclick="toggleDropdown()" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-700 to-teal-500 text-white flex items-center justify-center font-semibold shadow-md">
                        {{ auth()->user()?->initials() ?? 'AC' }}
                    </div>
                    <span class="text-gray-700 font-medium group-hover:text-teal-700 transition">
                        {{ auth()->user()?->name ?? 'Accounting' }}
                    </span>
                    <i data-feather="chevron-down" class="w-4 h-4 text-gray-500 group-hover:text-teal-700 transition"></i>
                </button>

                <div id="dropdown" class="dropdown absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border z-50 opacity-0 scale-95 invisible">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full text-left px-6 py-3 hover:bg-gray-50 transition text-red-500 font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>

<script>
    feather.replace();

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('collapsed');
    }

    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown');
        dropdown.classList.toggle('opacity-0');
        dropdown.classList.toggle('scale-95');
        dropdown.classList.toggle('invisible');
    }

    function setAccountingTab(tabId) {
        const tabs = ['cashAdvanceTab', 'liquidationTab'];
        tabs.forEach(function (id) {
            const tab = document.getElementById(id);
            if (tab) {
                tab.classList.toggle('hidden', id !== tabId);
            }
        });

        const pageTitle = document.getElementById('accountingPageTitle');
        if (pageTitle) {
            pageTitle.textContent = tabId === 'liquidationTab'
                ? 'Accounting - Liquidation Tracking'
                : 'Accounting - Cash Disbursement';
        }

        const dashboardNav = document.getElementById('accountingDashboardNav');
        const liquidationNav = document.getElementById('accountingLiquidationNav');
        if (dashboardNav && liquidationNav) {
            dashboardNav.classList.toggle('active-item', tabId !== 'liquidationTab');
            liquidationNav.classList.toggle('active-item', tabId === 'liquidationTab');
        }

        const buttons = document.querySelectorAll('[data-accounting-tab]');
        buttons.forEach(function (button) {
            const isActive = button.dataset.accountingTab === tabId;
            button.classList.toggle('bg-white', isActive);
            button.classList.toggle('text-teal-700', isActive);
            button.classList.toggle('shadow', isActive);
            button.classList.toggle('text-gray-600', !isActive);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('cashAdvanceTab')) {
            setAccountingTab('cashAdvanceTab');
        }
    });
</script>

@stack('scripts')

</body>
</html>
