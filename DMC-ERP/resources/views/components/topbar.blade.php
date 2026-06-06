<header class="relative z-[80] h-16 bg-white/70 border-b shadow-sm flex items-center justify-between px-8 backdrop-blur">
    <div class="flex items-center space-x-4">
        <button class="p-2 rounded-lg hover:bg-gray-100 transition">
            <img src="{{ asset('images/logo.png') }}" alt="DMC Logo" class="h-8 w-8 object-contain">
        </button>
        <h1 class="text-lg font-semibold text-gray-700 tracking-wide">Super Admin</h1>
    </div>

    <div class="relative z-[90]">
        <button onclick="toggleDropdown()" class="flex items-center space-x-3 group">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#1C446D] to-blue-700 text-white flex items-center justify-center font-semibold shadow-md">
                SA
            </div>
            <span class="text-gray-700 font-medium group-hover:text-[#1C446D] transition">Super Admin</span>
            <i class="fa fa-chevron-down text-gray-500 group-hover:text-[#1C446D] transition"></i>
        </button>

        <div id="dropdown" class="absolute right-0 z-[100] mt-3 w-48 bg-white rounded-2xl shadow-xl border opacity-0 scale-95 invisible transition">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-6 py-3 hover:bg-gray-50 transition text-red-500 font-medium">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
