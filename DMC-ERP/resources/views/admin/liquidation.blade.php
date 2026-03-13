@extends('layouts.admin')
@section('title', 'Cash Advance Liquidation')

@section('content')

<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 bg-gradient-to-br from-[#1C446D] to-blue-700
                        rounded-2xl flex items-center justify-center shadow-lg">
                <i data-feather="credit-card" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Cash Advance Liquidation</h2>
                <p class="text-gray-500">Track and manage your cash advance expenses</p>
            </div>
        </div>
    </div>

    <!-- CASH ADVANCE SUMMARY CARD -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-8 shadow-2xl text-white">
        
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white opacity-5 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <!-- Duration Badge -->
            <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-md px-4 py-2 rounded-full mb-6">
                <i data-feather="calendar" class="w-4 h-4"></i>
                <span class="text-sm font-semibold">March 1, 2026 - March 15, 2026</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <p class="text-sm opacity-80 mb-1">Cash Advance</p>
                        <p class="text-4xl font-bold">₱5,000.00</p>
                    </div>
                    
                    <div>
                        <p class="text-sm opacity-80 mb-1">Purpose</p>
                        <p class="text-lg font-semibold">Project Materials</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                            <p class="text-xs opacity-80 mb-1">Spent</p>
                            <p class="text-2xl font-bold text-red-300">₱2,700.00</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                            <p class="text-xs opacity-80 mb-1">Remaining</p>
                            <p class="text-2xl font-bold text-green-300">₱2,300.00</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm opacity-80 mb-2">Status</p>
                        <span class="inline-flex items-center space-x-2 bg-yellow-400 text-yellow-900 px-4 py-2 rounded-full font-semibold">
                            <i data-feather="clock" class="w-4 h-4"></i>
                            <span>Ongoing</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="flex items-center justify-between">
        <h3 class="text-2xl font-bold text-gray-800">Expense Transactions</h3>
        <button onclick="openAddExpenseModal()" 
                class="inline-flex items-center space-x-2 px-6 py-3
                       bg-gradient-to-r from-[#0A3562] via-[#255EC7] to-[#6999F1]
                       text-white font-semibold rounded-xl
                       hover:shadow-xl hover:scale-[1.02]
                       transition-all duration-300">
            <i data-feather="plus" class="w-5 h-5"></i>
            <span>Add Expense</span>
        </button>
    </div>

    <!-- EXPENSES TABLE -->
    <div class="relative overflow-hidden rounded-3xl bg-white p-8 shadow-2xl">
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700">Date</th>
                        <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700">Transaction Details</th>
                        <th class="text-right py-4 px-4 text-sm font-semibold text-gray-700">Amount</th>
                        <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="expensesTableBody">
                    <!-- Sample Expenses -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-4 px-4 text-sm text-gray-700">March 2, 2026</td>
                        <td class="py-4 px-4 text-sm text-gray-800 font-medium">Hardware supplies - nails, screws, bolts</td>
                        <td class="py-4 px-4 text-sm text-right font-semibold text-red-600">₱850.00</td>
                        <td class="py-4 px-4 text-center">
                            <button class="text-red-500 hover:text-red-700 transition">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-4 px-4 text-sm text-gray-700">March 3, 2026</td>
                        <td class="py-4 px-4 text-sm text-gray-800 font-medium">Cement and sand delivery</td>
                        <td class="py-4 px-4 text-sm text-right font-semibold text-red-600">₱1,200.00</td>
                        <td class="py-4 px-4 text-center">
                            <button class="text-red-500 hover:text-red-700 transition">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-4 px-4 text-sm text-gray-700">March 5, 2026</td>
                        <td class="py-4 px-4 text-sm text-gray-800 font-medium">Paint and brushes</td>
                        <td class="py-4 px-4 text-sm text-right font-semibold text-red-600">₱650.00</td>
                        <td class="py-4 px-4 text-center">
                            <button class="text-red-500 hover:text-red-700 transition">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-300">
                        <td colspan="2" class="py-4 px-4 text-right text-lg font-bold text-gray-800">Total Expenses:</td>
                        <td class="py-4 px-4 text-right text-xl font-bold text-red-600">₱2,700.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 flex justify-end">
            <button class="inline-flex items-center space-x-2 px-8 py-3
                           bg-gradient-to-r from-green-600 to-green-700
                           text-white font-semibold rounded-xl
                           hover:shadow-xl hover:scale-[1.02]
                           transition-all duration-300">
                <i data-feather="send" class="w-5 h-5"></i>
                <span>Submit for Review</span>
            </button>
        </div>
    </div>

</div>

<!-- ADD EXPENSE MODAL -->
<div id="addExpenseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 transform transition-all">
        
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-[#0A3562] via-[#255EC7] to-[#6999F1] p-6 rounded-t-3xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">Add New Expense</h3>
                <button onclick="closeAddExpenseModal()" class="text-white hover:text-gray-200 transition">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="addExpenseForm" class="p-6 space-y-6">
            
            <!-- Date Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Date <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       id="expenseDate"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl
                              focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                              transition-all duration-200"
                       required>
            </div>

            <!-- Transaction Details Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Transaction Details <span class="text-red-500">*</span>
                </label>
                <textarea id="expenseDetails"
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                 focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                 transition-all duration-200 resize-none"
                          placeholder="e.g., Hardware supplies - nails, screws, bolts"
                          required></textarea>
            </div>

            <!-- Amount Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Amount <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-700 font-semibold">₱</span>
                    <input type="number" 
                           id="expenseAmount"
                           step="0.01"
                           min="0"
                           class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                  transition-all duration-200"
                           placeholder="0.00"
                           required>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center space-x-3 pt-4">
                <button type="button"
                        onclick="closeAddExpenseModal()"
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl
                               hover:bg-gray-300 transition-all duration-200">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-[#0A3562] via-[#255EC7] to-[#6999F1]
                               text-white font-semibold rounded-xl
                               hover:shadow-xl hover:scale-[1.02]
                               transition-all duration-300">
                    Add Expense
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    feather.replace();

    // Modal Functions
    function openAddExpenseModal() {
        const modal = document.getElementById('addExpenseModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Set today's date as default
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('expenseDate').value = today;
        
        // Refresh feather icons
        setTimeout(() => feather.replace(), 10);
    }

    function closeAddExpenseModal() {
        const modal = document.getElementById('addExpenseModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Reset form
        document.getElementById('addExpenseForm').reset();
    }

    // Handle form submission
    document.getElementById('addExpenseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const date = document.getElementById('expenseDate').value;
        const details = document.getElementById('expenseDetails').value;
        const amount = parseFloat(document.getElementById('expenseAmount').value);
        
        // Format date
        const dateObj = new Date(date);
        const formattedDate = dateObj.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        // Format amount
        const formattedAmount = amount.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        
        // Add row to table
        const tbody = document.getElementById('expensesTableBody');
        const newRow = document.createElement('tr');
        newRow.className = 'border-b border-gray-100 hover:bg-gray-50 transition';
        newRow.innerHTML = `
            <td class="py-4 px-4 text-sm text-gray-700">${formattedDate}</td>
            <td class="py-4 px-4 text-sm text-gray-800 font-medium">${details}</td>
            <td class="py-4 px-4 text-sm text-right font-semibold text-red-600">₱${formattedAmount}</td>
            <td class="py-4 px-4 text-center">
                <button onclick="deleteExpense(this)" class="text-red-500 hover:text-red-700 transition">
                    <i data-feather="trash-2" class="w-4 h-4"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(newRow);
        
        // Refresh feather icons
        feather.replace();
        
        // Close modal
        closeAddExpenseModal();
        
        // Show success message (you can enhance this)
        alert('Expense added successfully!');
    });

    // Delete expense function
    function deleteExpense(button) {
        if (confirm('Are you sure you want to delete this expense?')) {
            button.closest('tr').remove();
        }
    }

    // Close modal when clicking outside
    document.getElementById('addExpenseModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddExpenseModal();
        }
    });
</script>
@endpush
