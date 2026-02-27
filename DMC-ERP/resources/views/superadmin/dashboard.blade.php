<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Super Admin - DMC ERP</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

        <!-- Header + Search -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by ID, Employee ID, or Name"
                        class="w-64 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600">
                    <i class="fa fa-search absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                </div>

                <button onclick="openModal()"
                    class="bg-[#001BB7] text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Add User
                </button>
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
    const tableRows = document.querySelectorAll('tbody tr:not(#noUsersRow)');
    const tbody = document.querySelector('tbody');
    
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