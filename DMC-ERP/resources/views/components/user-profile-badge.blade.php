@props(['user' => auth()->user(), 'gradientClasses' => 'from-emerald-700 to-teal-500'])

<div class="relative z-[90] shrink-0">
    <button onclick="toggleDropdown()" class="flex min-w-0 items-center gap-2 sm:gap-3 group">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br {{ $gradientClasses }} text-white font-semibold shadow-md">
            {{ $user?->initials() ?? 'AC' }}
        </div>
        <span class="hidden max-w-[9rem] truncate text-gray-700 font-medium group-hover:text-teal-700 transition sm:inline">
            {{ $user?->name ?? 'User' }}
        </span>
        <i data-feather="chevron-down" class="h-4 w-4 shrink-0 text-gray-500 group-hover:text-teal-700 transition"></i>
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
