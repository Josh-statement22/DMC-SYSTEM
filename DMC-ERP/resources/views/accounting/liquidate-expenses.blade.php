@extends('layouts.accounting')
@section('title', 'Accounting - Liquidate Expenses')

@section('content')
<div class="space-y-6">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 rounded-full border border-teal-100 bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">
            <i data-feather="receipt" class="w-3.5 h-3.5"></i>
            <span>Liquidate Expenses</span>
        </div>
        <p class="max-w-3xl text-sm text-gray-500">Record and track liquidation expenses. Enter debit transactions for cash advances sent to employees and credit transactions for money received.</p>
    </div>

    <!-- Balance Display -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">
            <p class="text-sm font-semibold text-emerald-700">Opening Balance</p>
            <p class="mt-2 text-3xl font-bold text-emerald-900" id="openingBalance">PHP 0.00</p>
        </div>
        <div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-6">
            <p class="text-sm font-semibold text-cyan-700">Ending Balance</p>
            <p class="mt-2 text-3xl font-bold text-cyan-900" id="endingBalance">PHP 0.00</p>
        </div>
    </div>

    <!-- Expense Entry Form -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Record Expense Transaction</h3>
        
        <form id="expenseForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Date Input -->
                <div>
                    <label for="expense_date" class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <input 
                        type="date" 
                        id="expense_date" 
                        name="expense_date" 
                        value="{{ date('Y-m-d') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                        required
                    >
                </div>

                <!-- Employee Name Input -->
                <div>
                    <label for="employee_name" class="block text-sm font-semibold text-gray-700 mb-2">Employee Name</label>
                    <select 
                        id="employee_name" 
                        name="employee_name" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                        required
                    >
                        <option value="">Select Employee</option>
                        @forelse($employees ?? [] as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>

                <!-- Transaction Type -->
                <div>
                    <label for="transaction_type" class="block text-sm font-semibold text-gray-700 mb-2">Transaction Type</label>
                    <select 
                        id="transaction_type" 
                        name="transaction_type" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                        required
                    >
                        <option value="">Select Type</option>
                        <option value="debit">Debit (Cash Advance Out)</option>
                        <option value="credit">Credit (Money Received)</option>
                    </select>
                </div>

                <!-- Amount Input -->
                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">Amount (PHP)</label>
                    <input 
                        type="number" 
                        id="amount" 
                        name="amount" 
                        step="0.01" 
                        min="0"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                        required
                    >
                </div>
            </div>

            <!-- Particulars/Transaction Details Input -->
            <div>
                <label for="transaction_details" class="block text-sm font-semibold text-gray-700 mb-2">Particulars</label>
                <input 
                    type="text" 
                    id="transaction_details" 
                    name="transaction_details" 
                    placeholder="Enter particulars (e.g., Office Supplies, Utilities, Meals)"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                    required
                >
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="3"
                    placeholder="Enter transaction description"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                ></textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 justify-end">
                <button 
                    type="reset" 
                    class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition"
                >
                    Clear
                </button>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-lg bg-teal-600 text-white font-semibold hover:bg-teal-700 transition"
                >
                    Add Transaction
                </button>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recorded Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Particulars</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Amount</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="transactionsTableBody">
                    <tr class="border-b border-gray-200">
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">No transactions recorded yet</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Submit Liquidation -->
    <div class="flex gap-3 justify-end">
        <button 
            type="button"
            id="submitLiquidationBtn"
            class="px-6 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
            disabled
        >
            Submit Liquidation
        </button>
    </div>
</div>

<script>
    let transactions = [];
    let currentBalance = 0;
    const openingBalance = 0;

    // Update particulars section visibility based on transaction type
    document.getElementById('transaction_type').addEventListener('change', function() {
        const particularsSection = document.getElementById('particularsSection');
        if (this.value === 'debit') {
            particularsSection.style.display = 'grid';
            document.getElementById('category').setAttribute('required', 'required');
        } else {
            particularsSection.style.display = 'none';
            document.getElementById('category').removeAttribute('required');
        }
    });

    // Handle form submission
    document.getElementById('expenseForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const categoryId = formData.get('category');
        const particularText = formData.get('particular');
        
        let categoryName = '-';
        let particularName = particularText || '-';

        if (categoryId) {
            const category = document.querySelector(`#category option[value="${categoryId}"]`);
            categoryName = category ? category.text : '-';
        }

        const transaction = {
            id: transactions.length + 1,
            date: formData.get('expense_date'),
            employee: formData.get('employee_name'),
            type: formData.get('transaction_type'),
            category: categoryName,
            particular: particularName,
            description: formData.get('description'),
            amount: parseFloat(formData.get('amount'))
        };

        // Update balance
        if (transaction.type === 'debit') {
            currentBalance -= transaction.amount;
        } else {
            currentBalance += transaction.amount;
        }

        transactions.push(transaction);
        updateTransactionsTable();
        updateBalances();
        this.reset();
        document.getElementById('transaction_type').dispatchEvent(new Event('change'));

        // Enable submit button if we have transactions
        document.getElementById('submitLiquidationBtn').disabled = transactions.length === 0;
    });

    function updateTransactionsTable() {
        const tbody = document.getElementById('transactionsTableBody');
        
        if (transactions.length === 0) {
            tbody.innerHTML = '<tr class="border-b border-gray-200"><td colspan="8" class="px-6 py-8 text-center text-gray-500">No transactions recorded yet</td></tr>';
            return;
        }

        tbody.innerHTML = transactions.map(t => `
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-6 py-3 text-sm text-gray-900">${t.date}</td>
                <td class="px-6 py-3 text-sm text-gray-900">${t.employee}</td>
                <td class="px-6 py-3 text-sm">
                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 ${t.type === 'debit' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}">
                        ${t.type === 'debit' ? 'Debit' : 'Credit'}
                    </span>
                </td>
                <td class="px-6 py-3 text-sm text-gray-900">${t.category}</td>
                <td class="px-6 py-3 text-sm text-gray-900">${t.particular}</td>
                <td class="px-6 py-3 text-sm text-gray-600">${t.description}</td>
                <td class="px-6 py-3 text-sm text-right font-semibold ${t.type === 'debit' ? 'text-red-600' : 'text-green-600'}">
                    ${t.type === 'debit' ? '-' : '+'}PHP ${t.amount.toFixed(2)}
                </td>
                <td class="px-6 py-3 text-center">
                    <button type="button" class="text-red-600 hover:text-red-800 font-semibold" onclick="deleteTransaction(${t.id})">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    function updateBalances() {
        document.getElementById('openingBalance').textContent = `PHP ${openingBalance.toFixed(2)}`;
        document.getElementById('endingBalance').textContent = `PHP ${currentBalance.toFixed(2)}`;
    }

    function deleteTransaction(id) {
        const transaction = transactions.find(t => t.id === id);
        if (transaction && confirm('Are you sure you want to delete this transaction?')) {
            // Reverse the balance update
            if (transaction.type === 'debit') {
                currentBalance += transaction.amount;
            } else {
                currentBalance -= transaction.amount;
            }
            transactions = transactions.filter(t => t.id !== id);
            updateTransactionsTable();
            updateBalances();
            document.getElementById('submitLiquidationBtn').disabled = transactions.length === 0;
        }
    }

    // Submit liquidation
    document.getElementById('submitLiquidationBtn').addEventListener('click', function() {
        if (transactions.length === 0) {
            alert('Please add at least one transaction');
            return;
        }

        console.log('Submitting liquidation with transactions:', transactions);
        alert('Liquidation submitted successfully!');
        transactions = [];
        currentBalance = 0;
        updateTransactionsTable();
        updateBalances();
        this.disabled = true;
    });

    // Initialize
    updateBalances();
</script>
@endsection
