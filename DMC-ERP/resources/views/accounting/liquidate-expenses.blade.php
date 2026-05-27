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

    <!-- Month Selector -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="monthSelector" class="block text-sm font-semibold text-gray-700 mb-2">Select Month</label>
                <select id="monthSelector" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    @foreach($months ?? [] as $m)
                        <option value="{{ $m['value'] }}" {{ $m['year'] == $year && $m['month'] == $month ? 'selected' : '' }}>
                            {{ $m['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <p class="text-sm text-gray-600">Current Period: <span class="font-semibold text-gray-900" id="currentPeriod">{{ now()->format('F Y') }}</span></p>
            </div>
        </div>
    </div>

    <!-- Balance Display -->
    <!-- Opening Balance -->
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-emerald-700">Opening Balance</p>
            <p class="mt-2 text-3xl font-bold text-emerald-900" id="openingBalance">PHP {{ number_format($monthlyBalance->opening_balance ?? 0, 2) }}</p>
        </div>
        @if(($monthlyBalance->opening_balance ?? 0) == 0)
        <button 
            type="button"
            id="setOpeningBalanceBtn"
            class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold hover:shadow-lg transition"
        >
            Set Opening Balance
        </button>
        @endif
    </div>

    <!-- Debit and Credit -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="rounded-2xl border border-orange-200 bg-orange-50 p-6">
            <p class="text-sm font-semibold text-orange-700">Debit (Cash Out)</p>
            <p class="mt-2 text-3xl font-bold text-orange-900" id="debitTotal">PHP {{ number_format($debitTotal ?? 0, 2) }}</p>
        </div>
        <div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-6">
            <p class="text-sm font-semibold text-cyan-700">Credit (Cash In)</p>
            <p class="mt-2 text-3xl font-bold text-cyan-900" id="creditTotal">PHP {{ number_format($creditTotal ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- Ending Balance -->
    <div class="rounded-2xl border border-violet-200 bg-violet-50 p-6">
        <p class="text-sm font-semibold text-violet-700">Ending Balance</p>
        <p class="mt-2 text-3xl font-bold text-violet-900" id="endingBalance">PHP {{ number_format(($monthlyBalance->remaining_balance ?? 0), 2) }}</p>
    </div>

    <!-- Expense Entry Form -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Record Expense Transaction</h3>
            <button
                type="button"
                id="toggleTransactionsBtn"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition"
            >
                <i data-feather="eye" class="w-4 h-4"></i>
                <span>Show Transactions</span>
            </button>
        </div>
        
        <form id="expenseForm" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Date Input -->
                <div>
                    <label for="expense_date" class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <input 
                        type="date" 
                        id="expense_date" 
                        name="expense_date" 
                        value="{{ sprintf('%04d-%02d-01', $year, $month) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                        required
                    >
                </div>

                <!-- Employee Name Input -->
                <div>
                    <label for="employee_id" class="block text-sm font-semibold text-gray-700 mb-2">Employee Name</label>
                    <select 
                        id="employee_id" 
                        name="employee_id" 
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

            <!-- Category (for debit transactions) -->
            <div id="categorySection" style="display: none;">
                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                <select 
                    id="category_id" 
                    name="category_id" 
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                >
                    <option value="">Select Category</option>
                    @forelse($categories ?? [] as $category)
                        <option value="{{ $category->id }}">{{ $category->particulars_category }}</option>
                    @empty
                    @endforelse
                </select>
            </div>

            <!-- Transaction Details/Particulars Input -->
            <div>
                <label for="transaction_details" class="block text-sm font-semibold text-gray-700 mb-2">Particulars/Details</label>
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

            <!-- Hidden fields for year and month -->
            <input type="hidden" id="year" name="year" value="{{ $year }}">
            <input type="hidden" id="month" name="month" value="{{ $month }}">

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

    <!-- Opening Balance Modal -->
    <div id="openingBalanceModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Set Opening Balance</h3>
                        <p id="openingBalanceModalMonth" class="text-emerald-100 text-sm mt-1"></p>
                    </div>
                    <button id="closeOpeningBalanceBtn" type="button" class="text-white hover:text-emerald-100 transition">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <form id="openingBalanceForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Opening Balance (PHP)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">PHP</span>
                        <input id="openingBalanceInput" type="number" min="0" step="0.01" placeholder="0.00" class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-xl text-gray-900 font-semibold focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required>
                    </div>
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <button type="button" id="cancelOpeningBalanceBtn" class="flex-1 px-4 py-2.5 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition-all duration-200">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold hover:shadow-lg transition-all duration-200">Save Balance</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table (Hidden by default) -->
    <div id="transactionsContainer" class="rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm" style="display: none;">
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Particulars</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Amount</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="transactionsTableBody">
                    @forelse($expenses ?? [] as $expense)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->expense_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->employee_name }}</td>
                            <td class="px-6 py-3 text-sm">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 {{ $expense->transaction_type === 'debit' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($expense->transaction_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->category_name ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->transaction_details ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ $expense->description ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm text-right font-semibold {{ $expense->transaction_type === 'debit' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $expense->transaction_type === 'debit' ? '-' : '+' }}PHP {{ number_format($expense->amount, 2) }}
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if($expense->transaction_type === 'debit')
                                    <div class="flex items-center gap-2 justify-center">
                                        <button type="button" class="text-blue-600 hover:text-blue-800 font-semibold breakdownBtn" data-id="{{ $expense->id }}" data-employee="{{ $expense->employee_name }}" data-date="{{ $expense->expense_date->format('Y-m-d') }}" data-amount="{{ $expense->amount }}">Breakdown</button>
                                        <button type="button" class="text-red-600 hover:text-red-800 font-semibold deleteBtn" data-id="{{ $expense->id }}">Delete</button>
                                    </div>
                                @else
                                    <button type="button" class="text-red-600 hover:text-red-800 font-semibold deleteBtn" data-id="{{ $expense->id }}">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="border-b border-gray-200">
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">No transactions recorded yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Breakdown Modal -->
    <div id="breakdownModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-teal-600 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Expense Breakdown</h3>
                        <p class="text-blue-100 text-sm mt-1">Add transaction details for <span id="breakdownEmployeeName" class="font-semibold"></span></p>
                    </div>
                    <button id="closeBreakdownBtn" type="button" class="text-white hover:text-blue-100 transition">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <form id="breakdownForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="breakdownTransactionId" name="transaction_id">

                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <input type="date" id="breakdownDate" name="date" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                    <select id="breakdownCategory" name="category_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                        <option value="">Select Category</option>
                        @forelse($categories ?? [] as $category)
                            <option value="{{ $category->id }}">{{ $category->particulars_category }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>

                <!-- Transaction Details -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction Details</label>
                    <input type="text" id="breakdownDetails" name="transaction_details" placeholder="e.g., Office Supplies, Utilities, Meals" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea id="breakdownDescription" name="description" rows="3" placeholder="Enter additional details" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Amount (PHP)</label>
                    <input type="number" id="breakdownAmount" name="amount" step="0.01" min="0" placeholder="0.00" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="button" id="cancelBreakdownBtn" class="flex-1 px-4 py-2.5 rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 rounded-lg bg-gradient-to-r from-blue-600 to-teal-600 text-white font-semibold hover:shadow-lg transition">Save Breakdown</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const currentYear = {{ $year }};
    const currentMonth = {{ $month }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;
    const storeExpenseRoute = @json(route('accounting.store-expense'));
    const updateOpeningBalanceRoute = @json(route('accounting.update-opening-balance'));
    const deleteExpenseBaseUrl = @json(url('/accounting/liquidate-expenses/expense'));

    // Month selector change
    document.getElementById('monthSelector').addEventListener('change', function() {
        const [year, month] = this.value.split('-');
        window.location.href = `{{ route('accounting.liquidate-expenses') }}?year=${year}&month=${month}`;
    });

    // Update current period display
    function updatePeriodDisplay() {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
        const period = monthNames[currentMonth - 1] + ' ' + currentYear;
        document.getElementById('currentPeriod').textContent = period;
    }

    // Update transaction type change - show/hide category section
    document.getElementById('transaction_type').addEventListener('change', function() {
        const categorySection = document.getElementById('categorySection');
        if (this.value === 'debit') {
            categorySection.style.display = 'block';
            document.getElementById('category_id').setAttribute('required', 'required');
        } else {
            categorySection.style.display = 'none';
            document.getElementById('category_id').removeAttribute('required');
        }
    });

    // Toggle transactions visibility
    let transactionsVisible = false;
    document.getElementById('toggleTransactionsBtn').addEventListener('click', function() {
        const container = document.getElementById('transactionsContainer');
        const btn = this;
        const icon = btn.querySelector('i');
        const text = btn.querySelector('span');

        transactionsVisible = !transactionsVisible;

        if (transactionsVisible) {
            container.style.display = 'block';
            text.textContent = 'Hide Transactions';
            // Update icon to eye-off
            icon.setAttribute('data-feather', 'eye-off');
        } else {
            container.style.display = 'none';
            text.textContent = 'Show Transactions';
            // Update icon to eye
            icon.setAttribute('data-feather', 'eye');
        }

        // Reinitialize feather icons
        if (window.feather) {
            feather.replace();
        }
    });

    // Opening Balance Modal
    const openingBalanceModal = document.getElementById('openingBalanceModal');
    const setOpeningBalanceBtn = document.getElementById('setOpeningBalanceBtn');
    const closeOpeningBalanceBtn = document.getElementById('closeOpeningBalanceBtn');
    const cancelOpeningBalanceBtn = document.getElementById('cancelOpeningBalanceBtn');
    const openingBalanceForm = document.getElementById('openingBalanceForm');

    if (setOpeningBalanceBtn) {
        setOpeningBalanceBtn.addEventListener('click', function() {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                               'July', 'August', 'September', 'October', 'November', 'December'];
            const period = monthNames[currentMonth - 1] + ' ' + currentYear;
            document.getElementById('openingBalanceModalMonth').textContent = period;
            document.getElementById('openingBalanceInput').value = '';
            openingBalanceModal.classList.remove('hidden');
            openingBalanceModal.style.display = 'flex';
        });
    }

    closeOpeningBalanceBtn.addEventListener('click', function() {
        openingBalanceModal.classList.add('hidden');
        openingBalanceModal.style.display = 'none';
    });

    cancelOpeningBalanceBtn.addEventListener('click', function() {
        openingBalanceModal.classList.add('hidden');
        openingBalanceModal.style.display = 'none';
    });

    openingBalanceForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const openingBalance = parseFloat(document.getElementById('openingBalanceInput').value);
        
        if (isNaN(openingBalance) || openingBalance < 0) {
            alert('Please enter a valid opening balance');
            return;
        }

        fetch(updateOpeningBalanceRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                year: currentYear,
                month: currentMonth,
                opening_balance: openingBalance
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Opening balance saved successfully!');
                openingBalanceModal.classList.add('hidden');
                openingBalanceModal.style.display = 'none';
                document.getElementById('openingBalance').textContent = 'PHP ' + openingBalance.toFixed(2);
                
                // Hide the button after saving
                if (setOpeningBalanceBtn) {
                    setOpeningBalanceBtn.style.display = 'none';
                }
                
                updateBalances();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving');
        });
    });

    // Close modal when clicking outside
    openingBalanceModal.addEventListener('click', function(e) {
        if (e.target === openingBalanceModal) {
            openingBalanceModal.classList.add('hidden');
            openingBalanceModal.style.display = 'none';
        }
    });

    // Handle form submission
    document.getElementById('expenseForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        
        fetch(storeExpenseRoute, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Expense recorded successfully!');
                // Reload the page to refresh the table
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while recording the expense');
        });

        this.reset();
    });

    // Delete transaction
    document.querySelectorAll('.deleteBtn').forEach(button => {
        button.addEventListener('click', function() {
            const expenseId = this.getAttribute('data-id');
            
            if (confirm('Are you sure you want to delete this transaction?')) {
                fetch(`${deleteExpenseBaseUrl}/${expenseId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Expense deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the expense');
                });
            }
        });
    });

    function updateBalances() {
        // The page will reload after API calls, so this is mainly for visual feedback
        // Real balances are calculated on the server
    }

    // Set date input to first day of selected month when page loads
    function setDateToFirstOfMonth() {
        const firstDay = new Date(currentYear, currentMonth - 1, 1);
        const year = firstDay.getFullYear();
        const month = String(firstDay.getMonth() + 1).padStart(2, '0');
        const day = String(firstDay.getDate()).padStart(2, '0');
        document.getElementById('expense_date').value = `${year}-${month}-${day}`;
    }

    // Initialize
    updatePeriodDisplay();
    setDateToFirstOfMonth();

    // Breakdown Modal
    const breakdownModal = document.getElementById('breakdownModal');
    const closeBreakdownBtn = document.getElementById('closeBreakdownBtn');
    const cancelBreakdownBtn = document.getElementById('cancelBreakdownBtn');
    const breakdownForm = document.getElementById('breakdownForm');

    // Open breakdown modal
    document.querySelectorAll('.breakdownBtn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-id');
            const employeeName = this.getAttribute('data-employee');
            const date = this.getAttribute('data-date');
            const amount = this.getAttribute('data-amount');

            document.getElementById('breakdownEmployeeName').textContent = employeeName;
            document.getElementById('breakdownTransactionId').value = transactionId;
            document.getElementById('breakdownDate').value = date;
            document.getElementById('breakdownAmount').value = amount;
            document.getElementById('breakdownDetails').value = '';
            document.getElementById('breakdownDescription').value = '';
            document.getElementById('breakdownCategory').value = '';

            breakdownModal.classList.remove('hidden');
            breakdownModal.style.display = 'flex';
        });
    });

    closeBreakdownBtn.addEventListener('click', function() {
        breakdownModal.classList.add('hidden');
        breakdownModal.style.display = 'none';
    });

    cancelBreakdownBtn.addEventListener('click', function() {
        breakdownModal.classList.add('hidden');
        breakdownModal.style.display = 'none';
    });

    // Close modal when clicking outside
    breakdownModal.addEventListener('click', function(e) {
        if (e.target === breakdownModal) {
            breakdownModal.classList.add('hidden');
            breakdownModal.style.display = 'none';
        }
    });

    // Breakdown form submission disabled - route not implemented
    breakdownForm.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Breakdown feature is not yet available');
    });
</script>
@endsection
