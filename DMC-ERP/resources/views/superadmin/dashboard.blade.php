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

        <!-- Header + Search -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" placeholder="Search User"
                        class="w-64 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600">
                    <i class="fa fa-search absolute right-3 top-3 text-gray-400"></i>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                        <i class="fa fa-sign-out"></i> Logout
                    </button>
                </form>
            </div>

    <button onclick="openModal()"
        class="bg-[#001BB7] text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
        + Add User
    </button>

</div>
<div id="addUserModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center">

    <!-- Modal Box -->
    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">

        <h2 class="text-lg font-semibold mb-6">
            Add Employee Account
        </h2>

        <form>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Employee ID
                </label>
                <input type="text"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Full Name
                </label>
                <input type="text"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">
                    Password
                </label>
                <input type="password"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 rounded-lg">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-[#001BB7] text-black rounded-lg">
                    Save
                </button>
            </div>

        </form>

    </div>
</div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border">

            <table class="w-full table-fixed text-left">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="p-4 w-1/4 text-left text-sm font-semibold text-gray-600">
                            EmployeeID
                        </th>
                        <th class="p-4 w-1/4 text-center text-sm font-semibold text-gray-600">
                            Name
                        </th>
                        <th class="p-4 w-1/4 text-right text-sm font-semibold text-gray-600">
                            Password
                        </th>
                        <th class="p-4 w-1/4 text-center text-sm font-semibold text-gray-600">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-left font-medium text-gray-800">
                            201640215
                        </td>
                        <td class="p-4 text-center text-gray-600">
                            John Doe
                        </td>
                        <td class="p-4 text-right text-gray-500">
                            23456789
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-4 text-sm">
                                <button class="flex items-center gap-2 text-gray-600 hover:text-teal-700">
                                    <i class="fa fa-gear"></i> Edit
                                </button>
                                <button class="flex items-center gap-2 text-gray-600 hover:text-red-600">
                                    <i class="fa fa-times-circle"></i> Remove
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="flex items-center justify-end p-4 gap-2 text-sm text-gray-600">
                <button class="px-3 py-1 border rounded">First</button>
                <button class="px-3 py-1 border rounded bg-gray-100">10</button>
                <button class="px-3 py-1 border rounded bg-teal-600 text-white">11</button>
                <button class="px-3 py-1 border rounded">25</button>
                <button class="px-3 py-1 border rounded">26</button>
                <button class="px-3 py-1 border rounded">Last</button>
            </div>

        </div>
    </div>
</body>
</html>


<script>
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
</script>