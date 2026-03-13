<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Super Admin - DMC ERP</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .user-management-hero {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            border: 1px solid rgba(148, 163, 184, 0.24);
            background: linear-gradient(135deg, #0f2f75 0%, #123c9b 50%, #ebf3ff 50%, #f8fbff 100%);
            box-shadow: 0 22px 52px rgba(15, 23, 42, 0.12);
        }

        .user-management-hero::before,
        .user-management-hero::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .user-management-hero::before {
            top: -6rem;
            right: -4rem;
            width: 15rem;
            height: 15rem;
            background: rgba(255, 255, 255, 0.14);
        }

        .user-management-hero::after {
            bottom: -5rem;
            left: 42%;
            width: 12rem;
            height: 12rem;
            background: rgba(147, 197, 253, 0.22);
        }

        .user-management-shell {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            justify-content: space-between;
            gap: 2rem;
            padding: 2rem;
        }

        .user-management-copy {
            flex: 1 1 24rem;
            max-width: 44rem;
            color: #fff;
        }

        .user-management-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.55rem 0.95rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            color: #dbeafe;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .user-management-copy h1 {
            margin-top: 1rem;
            font-size: clamp(2.1rem, 3.4vw, 3.25rem);
            line-height: 1.02;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .user-management-copy p {
            margin-top: 1rem;
            max-width: 38rem;
            color: rgba(239, 246, 255, 0.84);
            font-size: 1rem;
            line-height: 1.75;
        }

        .user-management-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1.75rem;
        }

        .user-management-stat {
            padding: 1rem 1.1rem;
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
        }

        .user-management-stat span {
            display: block;
            color: rgba(219, 234, 254, 0.86);
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .user-management-stat strong {
            display: block;
            margin-top: 0.45rem;
            color: #fff;
            font-size: 1.9rem;
            line-height: 1;
            letter-spacing: -0.03em;
        }

        .user-management-toolbar {
            display: flex;
            flex: 1 1 20rem;
            max-width: 29rem;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            gap: 1rem;
            margin-left: auto;
        }

        .user-management-note {
            width: 100%;
            padding: 1rem 1.1rem;
            border-radius: 22px;
            border: 1px solid rgba(191, 219, 254, 0.8);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
        }

        .user-management-note-label {
            display: block;
            color: #123c9b;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .user-management-note p {
            margin-top: 0.5rem;
            color: #475569;
            font-size: 0.92rem;
            line-height: 1.65;
        }

        .user-management-controls {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 0.9rem;
            width: 100%;
        }

        .toolbar-search {
            position: relative;
            flex: 1 1 18rem;
            max-width: 24rem;
            width: 100%;
        }

        .toolbar-search-icon {
            position: absolute;
            top: 50%;
            left: 1rem;
            color: #64748b;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .toolbar-search-input {
            width: 100%;
            height: 3.75rem;
            border: 1px solid rgba(148, 163, 184, 0.34);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.95);
            padding: 0 3.15rem 0 3rem;
            color: #0f172a;
            font-size: 0.98rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .toolbar-search-input:focus {
            outline: none;
            border-color: #123c9b;
            box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.12), 0 16px 32px rgba(15, 23, 42, 0.12);
            transform: translateY(-1px);
        }

        .toolbar-clear-btn {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            display: none;
            align-items: center;
            justify-content: center;
            width: 2.2rem;
            height: 2.2rem;
            border: none;
            border-radius: 999px;
            background: transparent;
            color: #64748b;
            transform: translateY(-50%);
            transition: background 0.2s ease, color 0.2s ease;
        }

        .toolbar-clear-btn:hover {
            background: #eff6ff;
            color: #123c9b;
        }

        .toolbar-primary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            height: 3.75rem;
            padding: 0 1.45rem;
            border: none;
            border-radius: 18px;
            background: linear-gradient(135deg, #0f2f75 0%, #123c9b 100%);
            color: #fff;
            font-size: 0.98rem;
            font-weight: 700;
            box-shadow: 0 18px 30px rgba(18, 60, 155, 0.26);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .toolbar-primary-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 22px 36px rgba(18, 60, 155, 0.3);
            filter: brightness(1.02);
        }

        .toolbar-primary-btn:active {
            transform: translateY(0);
        }

        .user-management-summary {
            width: 100%;
            margin: 0;
            color: #334155;
            font-size: 0.88rem;
            text-align: right;
        }

        .user-management-summary strong {
            color: #0f2f75;
        }

        .table-shell {
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.92);
            border-radius: 24px;
            background: #fff;
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.08);
        }

        .table-shell-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .table-shell-title {
            color: #0f172a;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .table-shell-copy {
            margin-top: 0.35rem;
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .table-shell-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.6rem 0.95rem;
            border-radius: 999px;
            background: #eff6ff;
            color: #123c9b;
            font-size: 0.85rem;
            font-weight: 700;
            white-space: nowrap;
        }

        @media (max-width: 1024px) {
            .user-management-hero {
                background: linear-gradient(180deg, #0f2f75 0%, #123c9b 58%, #f8fbff 58%, #f8fbff 100%);
            }

            .user-management-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .user-management-toolbar {
                align-items: stretch;
                max-width: none;
            }

            .user-management-controls {
                justify-content: flex-start;
            }

            .user-management-summary {
                text-align: left;
            }
        }

        @media (max-width: 640px) {
            .user-management-shell {
                padding: 1.5rem;
            }

            .user-management-stats {
                grid-template-columns: 1fr;
            }

            .user-management-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar-search {
                max-width: none;
            }

            .toolbar-primary-btn {
                width: 100%;
            }

            .table-shell-header {
                flex-direction: column;
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

<x-topbar />

    <!-- Page Content -->
    <div class="p-8">

        <!-- Success Message -->
        @if(session('success'))
            <div id="successMessage" class="mb-6 p-4 bg-green-50 border border-green-300 rounded-xl flex items-start gap-3 transition-opacity duration-500">
                <i class="fa fa-check-circle text-green-600 mt-0.5"></i>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-300 rounded-xl">
                <div class="flex items-start gap-3">
                    <i class="fa fa-exclamation-circle text-red-600 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-red-800 mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

 <!-- Header Container -->
<div class="user-management-hero mb-8">
    <div class="user-management-shell">
        <div class="user-management-copy">
            <span class="user-management-kicker">
                <i class="fa fa-shield-halved"></i>
                Administration Console
            </span>

            <h1>User Management</h1>

            <p>
                Provision new ERP access, review account ownership, and keep every employee record aligned with the right role and contact details.
            </p>

            <div class="user-management-stats">
                <div class="user-management-stat">
                    <span>Total Accounts</span>
                    <strong>{{ number_format($totalUsers) }}</strong>
                </div>

                <div class="user-management-stat">
                    <span>Superadmins</span>
                    <strong>{{ number_format($superadminCount) }}</strong>
                </div>

                <div class="user-management-stat">
                    <span>Admins</span>
                    <strong>{{ number_format($adminCount) }}</strong>
                </div>
            </div>
        </div>

        <div class="user-management-toolbar">
            <div class="user-management-note">
                <span class="user-management-note-label">Workspace Details</span>
                <p>
                    Search across numeric ID, employee ID, name, email address, or role. Results stay consistent with pagination so you can jump directly to the right account.
                </p>
            </div>

            <div class="user-management-controls">
                <div class="toolbar-search">
                    <span class="toolbar-search-icon">
                        <i class="fa fa-search text-base"></i>
                    </span>

                    <input
                        type="text"
                        id="searchInput"
                        value="{{ $search }}"
                        placeholder="Search ID, employee ID, name, email, or role"
                        autocomplete="off"
                        class="toolbar-search-input"
                    >

                    <button
                        type="button"
                        id="clearSearchBtn"
                        class="toolbar-clear-btn"
                        aria-label="Clear search"
                    >
                        <i class="fa fa-times text-sm"></i>
                    </button>
                </div>

                <button
                    type="button"
                    onclick="openModal()"
                    class="toolbar-primary-btn"
                >
                    <i class="fa fa-user-plus text-sm"></i>
                    Add User
                </button>
            </div>

            <p id="resultSummary" class="user-management-summary">
                @if($search !== '')
                    Showing <strong>{{ number_format($filteredUsers) }}</strong> {{ $filteredUsers === 1 ? 'matching account' : 'matching accounts' }} for "<strong>{{ $search }}</strong>".
                @else
                    Showing <strong>{{ number_format($filteredUsers) }}</strong> {{ $filteredUsers === 1 ? 'account' : 'accounts' }} in the directory.
                @endif
            </p>
        </div>
    </div>
</div>

<div id="addUserModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center">

    <!-- Modal Box -->
    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">

        <h2 class="text-lg font-semibold mb-6">
            Add Employee Account
        </h2>

        <form method="POST" action="{{ route('superadmin.users.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Employee ID
                </label>
                <input type="text" 
                       name="employee_id" 
                       value="{{ old('employee_id') }}"
                       required
                       class="w-full border rounded-lg px-4 py-2 @error('employee_id') border-red-500 @enderror">
                @error('employee_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Name
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name') }}"
                       required
                       class="w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Email (Optional)
                </label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       class="w-full border rounded-lg px-4 py-2 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Role
                </label>
                <select name="role_id" 
                        required
                        class="w-full border rounded-lg px-4 py-2 @error('role_id') border-red-500 @enderror">
                    <option value="">Select Role</option>
                    <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Superadmin</option>
                    <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">
                    Password
                </label>
                <input type="password" 
                       name="password" 
                       required
                       minlength="6"
                       class="w-full border rounded-lg px-4 py-2 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-[#001BB7] text-white rounded-lg hover:bg-blue-800 transition">
                    Save
                </button>
            </div>

        </form>

    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center">

    <!-- Modal Box -->
    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">

        <h2 class="text-lg font-semibold mb-6">
            Edit Employee Account
        </h2>

        <form method="POST" id="editUserForm">
            @csrf
            @method('PUT')

            <input type="hidden" id="edit_user_id" name="user_id">

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Employee ID
                </label>
                <input type="text" 
                       id="edit_employee_id"
                       name="employee_id" 
                       required
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Name
                </label>
                <input type="text" 
                       id="edit_name"
                       name="name" 
                       required
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Email (Optional)
                </label>
                <input type="email" 
                       id="edit_email"
                       name="email" 
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Role
                </label>
                <select id="edit_role_id"
                        name="role_id" 
                        required
                        class="w-full border rounded-lg px-4 py-2">
                    <option value="">Select Role</option>
                    <option value="1">Superadmin</option>
                    <option value="2">Admin</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    New Password (Leave blank to keep current)
                </label>
                <input type="password" 
                       id="edit_password"
                       name="password" 
                       minlength="6"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">
                    Confirm Password
                </label>
                <input type="password" 
                       id="edit_password_confirmation"
                       name="password_confirmation" 
                       minlength="6"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-[#001BB7] text-white rounded-lg hover:bg-blue-800 transition">
                    Update
                </button>
            </div>

        </form>

    </div>
</div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border">

            <table class="w-full table-auto text-left">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-sm font-semibold text-gray-600">
                            ID
                        </th>
                        <th class="p-4 text-left text-sm font-semibold text-gray-600">
                            Employee ID
                        </th>
                        <th class="p-4 text-left text-sm font-semibold text-gray-600">
                            Name
                        </th>
                        <th class="p-4 text-left text-sm font-semibold text-gray-600">
                            Role
                        </th>
                        <th class="p-4 text-left text-sm font-semibold text-gray-600">
                            Email
                        </th>
                        <th class="p-4 text-left text-sm font-semibold text-gray-600">
                            Password
                        </th>
                        <th class="p-4 text-center text-sm font-semibold text-gray-600">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-left font-medium text-gray-800">
                            {{ $user->id }}
                        </td>
                        <td class="p-4 text-left text-gray-600">
                            {{ $user->employee_id }}
                        </td>
                        <td class="p-4 text-left text-gray-600 max-w-[220px] break-words">
                            {{ $user->name }}
                        </td>
                        <td class="p-4 text-left text-gray-600">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ $user->role_id == 1 ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $user->role->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="p-4 text-left text-gray-600 max-w-xs break-all">
                            {{ $user->email ?? 'N/A' }}
                        </td>
                        <td class="p-4 text-left whitespace-nowrap">
                            <span class="password-display cursor-pointer select-none text-gray-500 hover:text-blue-600 transition" 
                                  data-password="{{ $user->password }}"
                                  onclick="togglePassword(this, event)">
                                ••••••••••
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-4 text-sm">
                                <button onclick="openEditModal({{ $user->id }}, '{{ $user->employee_id }}', '{{ $user->name }}', '{{ $user->email }}', {{ $user->role_id }})"
                                        class="flex items-center gap-2 text-gray-600 hover:text-teal-700">
                                    <i class="fa fa-gear"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('superadmin.users.destroy', $user->id) }}" 
                                      onsubmit="return confirm('Are you sure you want to remove {{ $user->name }}? This action cannot be undone.');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center gap-2 text-gray-600 hover:text-red-600">
                                        <i class="fa fa-times-circle"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="noUsersRow">
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            No users found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div id="paginationControls" class="flex items-center justify-end p-4 gap-2 text-sm text-gray-600">
                @if($totalPages >= 3)
                <button onclick="goToPage(1)" class="px-3 py-1 border rounded hover:bg-gray-100 transition {{ $currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $currentPage <= 1 ? 'disabled' : '' }} title="First Page">
                    &lt;&lt;
                </button>
                @endif
                
                <button id="prevBtn" onclick="goToPage({{ $currentPage - 1 }})" class="px-3 py-1 border rounded hover:bg-gray-100 transition {{ $currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $currentPage <= 1 ? 'disabled' : '' }}>
                    &lt;
                </button>
                
                <span class="px-3 py-1">Page <span id="currentPageNum">{{ $currentPage }}</span> of {{ $totalPages }}</span>
                
                <button id="nextBtn" onclick="goToPage({{ $currentPage + 1 }})" class="px-3 py-1 border rounded hover:bg-gray-100 transition {{ $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $currentPage >= $totalPages ? 'disabled' : '' }}>
                    &gt;
                </button>
                
                @if($totalPages >= 3)
                <button onclick="goToPage({{ $totalPages }})" class="px-3 py-1 border rounded hover:bg-gray-100 transition {{ $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $currentPage >= $totalPages ? 'disabled' : '' }} title="Last Page">
                    &gt;&gt;
                </button>
                @endif
            </div>

        </div>
    </div>
</body>
</html>


<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown');
        dropdown.classList.toggle('opacity-0');
        dropdown.classList.toggle('scale-95');
        dropdown.classList.toggle('invisible');
    }

    function openModal() {
        document.getElementById('addUserModal')
            .classList.remove('hidden');
        document.getElementById('addUserModal')
            .classList.add('flex');
    }

    function closeModal() {
        document.getElementById('addUserModal')
            .classList.add('hidden');
        document.getElementById('addUserModal')
            .classList.remove('flex');
    }

    function openEditModal(id, employeeId, name, email, roleId) {
        document.getElementById('edit_user_id').value = id;
        document.getElementById('edit_employee_id').value = employeeId;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email || '';
        document.getElementById('edit_role_id').value = roleId;
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';
        
        // Set form action
        document.getElementById('editUserForm').action = `/superadmin/users/${id}`;
        
        document.getElementById('editUserModal')
            .classList.remove('hidden');
        document.getElementById('editUserModal')
            .classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editUserModal')
            .classList.add('hidden');
        document.getElementById('editUserModal')
            .classList.remove('flex');
    }

    function togglePassword(element, event) {
        event.stopPropagation();
        const password = element.getAttribute('data-password');
        if (element.textContent === '••••••••••') {
            element.textContent = password;
            element.classList.add('text-blue-600');
        } else {
            element.textContent = '••••••••••';
            element.classList.remove('text-blue-600');
        }
    }

    // Close modals when clicking outside
    document.getElementById('addUserModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    document.getElementById('editUserModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('dropdown');
        if (!dropdown) {
            return;
        }

        if (!e.target.closest('[onclick="toggleDropdown()"]') && !dropdown.contains(e.target)) {
            dropdown.classList.add('opacity-0', 'scale-95', 'invisible');
        }
    });

    // Pagination function
    function goToPage(page) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', page);
        window.location.href = url.toString();
    }

    // Real-time search filtering
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const tableRows = document.querySelectorAll('tbody tr:not(#noUsersRow)');
    const tbody = document.querySelector('tbody');

    function toggleClearButton() {
        if (!clearSearchBtn || !searchInput) {
            return;
        }

        if (searchInput.value.trim().length > 0) {
            clearSearchBtn.classList.remove('hidden');
            clearSearchBtn.classList.add('flex');
        } else {
            clearSearchBtn.classList.add('hidden');
            clearSearchBtn.classList.remove('flex');
        }
    }
    
    // Create "No users found" row if it doesn't exist
    let noUsersRow = document.getElementById('noUsersRow');
    if (!noUsersRow) {
        noUsersRow = document.createElement('tr');
        noUsersRow.id = 'noUsersRow';
        noUsersRow.innerHTML = '<td colspan="7" class="p-4 text-center text-gray-500">No users found</td>';
        noUsersRow.style.display = 'none';
        tbody.appendChild(noUsersRow);
    }

    searchInput.addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        let visibleCount = 0;

        toggleClearButton();

        tableRows.forEach(row => {
            const id = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
            const employeeId = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
            const name = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

            if (id.includes(searchValue) || employeeId.includes(searchValue) || name.includes(searchValue)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide "No users found" message
        noUsersRow.style.display = visibleCount === 0 ? '' : 'none';
    });

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function () {
            searchInput.value = '';
            toggleClearButton();
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && searchInput.value.trim().length > 0) {
                searchInput.value = '';
                toggleClearButton();
                searchInput.dispatchEvent(new Event('input'));
            }
        });
    }

    toggleClearButton();

    // Reopen modal if there are validation errors
    @if($errors->any())
        openModal();
    @endif

    // Auto-hide success message after 5 seconds
    @if(session('success'))
        setTimeout(function() {
            const successMsg = document.getElementById('successMessage');
            if (successMsg) {
                successMsg.style.opacity = '0';
                setTimeout(function() {
                    successMsg.remove();
                }, 500);
            }
        }, 5000);
    @endif
</script>
