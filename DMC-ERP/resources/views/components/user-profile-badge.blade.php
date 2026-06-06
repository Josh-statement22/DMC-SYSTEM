@props(['user' => auth()->user(), 'gradientClasses' => 'from-emerald-700 to-teal-500'])

<div class="relative z-[90]">
    <button onclick="toggleDropdown()" class="flex items-center space-x-3 group">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $gradientClasses }} text-white flex items-center justify-center font-semibold shadow-md">
            {{ $user?->initials() ?? 'AC' }}
        </div>
        <span class="text-gray-700 font-medium group-hover:text-teal-700 transition">
            {{ $user?->name ?? 'User' }}
        </span>
        <i data-feather="chevron-down" class="w-4 h-4 text-gray-500 group-hover:text-teal-700 transition"></i>
    </button>

    <div id="dropdown" class="dropdown absolute right-0 z-[100] mt-3 w-48 bg-white rounded-2xl shadow-xl border opacity-0 scale-95 invisible">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="w-full text-left px-6 py-3 hover:bg-gray-50 transition text-red-500 font-medium">
                Logout
            </button>
        </form>
    </div>
</div>
