@props(['user' => auth()->user(), 'gradientClasses' => 'from-emerald-700 to-teal-500'])

<div class="relative shrink-0">
    <button type="button" onclick="toggleDropdown()" class="group flex min-w-0 items-center space-x-2 sm:space-x-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br {{ $gradientClasses }} font-semibold text-white shadow-md">
            {{ $user?->initials() ?? 'AC' }}
        </div>
        <span class="hidden max-w-[9rem] truncate font-medium text-gray-700 transition group-hover:text-teal-700 sm:inline">
            {{ $user?->name ?? 'User' }}
        </span>
        <i data-feather="chevron-down" class="h-4 w-4 shrink-0 text-gray-500 transition group-hover:text-teal-700"></i>
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
