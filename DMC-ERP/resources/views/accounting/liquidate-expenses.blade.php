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
    <div class="rounded-2xl border border-gray-200 bg-white px-4 py-3 shadow-sm">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex w-full flex-col gap-3 md:flex-row md:items-center">
                <div class="w-full md:w-[340px]">
                    <label for="monthSelector" class="sr-only">Select Month</label>
                    <select id="monthSelector" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 transition focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                        @foreach($months ?? [] as $m)
                            <option value="{{ $m['value'] }}" {{ $m['year'] == $year && $m['month'] == $month ? 'selected' : '' }}>
                                {{ $m['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="inline-flex min-h-[42px] w-full items-center justify-between gap-3 rounded-xl border border-teal-100 bg-teal-50 px-4 py-2 text-sm md:w-auto md:justify-start">
                    <span class="font-semibold text-teal-700">Current Period</span>
                    <span class="rounded-full bg-white px-3 py-1 text-sm font-bold text-teal-900 shadow-sm" id="currentPeriod">{{ now()->format('F Y') }}</span>
                </div>
            </div>

            <div class="w-full md:w-auto">
                <input id="liquidateExcelInput" type="file" class="hidden" accept=".xlsx,.xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                <button id="liquidateImportExcelBtn" type="button" class="inline-flex w-full min-w-[170px] items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 md:w-[170px]">
                    <i data-feather="upload" class="w-4 h-4"></i>
                    <span>Import Excel</span>
                </button>
            </div>
        </div>
    </div>
    <!-- Balance Display -->
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-emerald-700">Opening Balance</p>
            <p class="mt-2 text-3xl font-bold text-emerald-900" id="openingBalance">PHP {{ number_format($monthlyBalance->opening_balance ?? 0, 2) }}</p>
        </div>
        <button 
            type="button"
            id="setOpeningBalanceBtn"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold hover:shadow-lg transition"
        >
            <i data-feather="edit-3" class="w-4 h-4"></i>
            {{ ($monthlyBalance->opening_balance ?? 0) == 0 ? 'Set Opening Balance' : 'Edit Opening Balance' }}
        </button>
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
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Record Expense Transaction</h3>
        </div>
        
        <form id="expenseForm" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
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

                <!-- Category -->
                <div>
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

            <div id="purposeSection">
                <label for="purpose" class="block text-sm font-semibold text-gray-700 mb-2">Purpose</label>
                <input
                    type="text"
                    id="purpose"
                    name="purpose"
                    placeholder="Enter purpose"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                    required
                >
            </div>

            <!-- Accounting Remarks (auto-set to 'Manually Recorded' for liquidation entries) -->
            <input type="hidden" id="accounting_remarks" name="accounting_remarks" value="Manually Recorded">

            <!-- Hidden fields for year and month -->
            <input type="hidden" id="year" name="year" value="{{ $year }}">
            <input type="hidden" id="month" name="month" value="{{ $month }}">

            <!-- Form Actions -->
            <div class="flex gap-3 justify-end">
                <button 
                    type="reset" 
                    class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition"
                >
                    Clear Form
                </button>
                <button 
                    type="button" 
                    id="clearMonthBtn"
                    class="px-6 py-2.5 rounded-lg border border-red-300 text-red-700 font-semibold hover:bg-red-50 transition"
                >
                    <i data-feather="trash-2" class="w-4 h-4 inline mr-1"></i>
                    Clear Month
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
                        <h3 class="text-xl font-bold text-white">Opening Balance</h3>
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

    <!-- Excel Import Modal -->
    <div id="liquidateImportModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 px-4" role="dialog" aria-modal="true" aria-labelledby="liquidateImportTitle">
        <div class="flex max-h-[90vh] w-full max-w-6xl flex-col overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-2xl">
            <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-6 py-5">
                <div>
                    <h3 id="liquidateImportTitle" class="text-xl font-bold text-gray-800">Import Excel</h3>
                    <p id="liquidateImportFileName" class="mt-1 text-sm text-gray-500">No file selected</p>
                </div>
                <button id="liquidateImportCloseBtn" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-600 transition hover:bg-gray-200" aria-label="Close import preview">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-4 overflow-y-auto p-6">
                <div id="liquidateImportStatus" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700">
                    Waiting for file...
                </div>

                <div id="liquidateImportProgressWrap" class="hidden h-2 overflow-hidden rounded-full bg-gray-100">
                    <div id="liquidateImportProgress" class="h-full w-1/3 rounded-full bg-teal-500 transition-all duration-300"></div>
                </div>

                <div id="liquidateImportSummary" class="hidden grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Imported Rows</p>
                        <p id="liquidateImportSuccessCount" class="mt-2 text-2xl font-bold text-emerald-900">0</p>
                    </div>
                    <div class="rounded-2xl border border-rose-100 bg-rose-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-rose-700">Failed Rows</p>
                        <p id="liquidateImportFailedCount" class="mt-2 text-2xl font-bold text-rose-900">0</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-gray-200">
                    <table class="w-full min-w-[980px] text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                                <th class="px-4 py-3">Row</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Employee</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Purpose</th>
                                <th class="px-4 py-3">Category</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                                <th class="px-4 py-3">Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="liquidateImportPreviewBody">
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">Select an Excel file to preview rows.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-end">
                <button id="liquidateImportCancelBtn" type="button" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-100 px-4 py-3 font-semibold text-gray-700 transition hover:bg-gray-200">
                    <i data-feather="x-circle" class="w-4 h-4"></i>
                    Cancel
                </button>
                <button id="liquidateImportConfirmBtn" type="button" disabled class="inline-flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-4 py-3 font-semibold text-white transition hover:bg-teal-700 disabled:cursor-not-allowed disabled:bg-gray-300">
                    <i data-feather="check-circle" class="w-4 h-4"></i>
                    Confirm Import
                </button>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div id="transactionsContainer" class="rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Recorded Transactions</h3>
                    <p class="mt-1 text-sm text-gray-500"><span id="transactionsVisibleCount">{{ isset($expenses) && method_exists($expenses, 'total') ? $expenses->total() : ($expenses ?? collect())->count() }}</span> transaction(s) shown</p>
                </div>
                <form id="transactionFilterForm" method="GET" action="{{ route('accounting.liquidate-expenses') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-[150px_150px_150px_190px_220px_auto] xl:items-end">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <div>
                        <label for="transactionTypeFilter" class="block text-sm font-semibold text-gray-700 mb-2">Transaction Type</label>
                        <select id="transactionTypeFilter" name="type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                            <option value="" {{ ($transactionFilters['type'] ?? '') === '' ? 'selected' : '' }}>All Types</option>
                            <option value="credit" {{ ($transactionFilters['type'] ?? '') === 'credit' ? 'selected' : '' }}>Credit</option>
                            <option value="debit" {{ ($transactionFilters['type'] ?? '') === 'debit' ? 'selected' : '' }}>Debit</option>
                        </select>
                    </div>
                    <div>
                        <label for="transactionStartDateFilter" class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                        <input
                            id="transactionStartDateFilter"
                            name="start_date"
                            type="date"
                            value="{{ $transactionFilters['start_date'] ?? '' }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                        >
                    </div>
                    <div>
                        <label for="transactionEndDateFilter" class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                        <input
                            id="transactionEndDateFilter"
                            name="end_date"
                            type="date"
                            value="{{ $transactionFilters['end_date'] ?? '' }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                        >
                    </div>
                    <div>
                        <label for="transactionCategoryFilter" class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                        <select id="transactionCategoryFilter" name="category" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                            <option value="" {{ ($transactionFilters['category'] ?? '') === '' ? 'selected' : '' }}>All categories</option>
                            @forelse($categories ?? [] as $category)
                                <option value="{{ $category->particulars_category }}" {{ ($transactionFilters['category'] ?? '') === $category->particulars_category ? 'selected' : '' }}>
                                    {{ $category->particulars_category }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label for="transactionSearchFilter" class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                        <input
                            id="transactionSearchFilter"
                            name="search"
                            type="search"
                            value="{{ $transactionFilters['search'] ?? '' }}"
                            placeholder="Employee, purpose, remarks"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                        >
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row xl:justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-700"
                        >
                            <i data-feather="filter" class="w-4 h-4"></i>
                            Apply
                        </button>
                        <a
                            id="resetTransactionFilters"
                            href="{{ route('accounting.liquidate-expenses', ['year' => $year, 'month' => $month]) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            <i data-feather="rotate-ccw" class="w-4 h-4"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <div id="transactions-table-container" class="relative">
            @include('transactions.partials.table', [
                'categories' => $categories,
                'expenses' => $expenses,
                'hasTransactionFilters' => $hasTransactionFilters,
            ])
        </div>
    </div>

    <!-- Breakdown Modal -->
    <div id="breakdownModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="flex max-h-[90vh] w-full max-w-6xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="shrink-0 bg-gradient-to-r from-teal-600 to-cyan-600 p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-white">Transaction Breakdown</h3>
                        <p class="text-cyan-100 text-sm mt-1">Review the saved details for this entry</p>
                    </div>
                    <button id="closeBreakdownBtn" type="button" class="text-white hover:text-cyan-100 transition">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <form id="breakdownForm" class="flex-1 overflow-y-auto p-6 space-y-5">
                @csrf
                <input type="hidden" id="breakdownRecordSource" name="record_source" value="breakdown">
                <input type="hidden" id="breakdownCashAdvanceRequestId" name="cash_advance_request_id">
                <input type="hidden" id="breakdownEmployeeId" name="employee_id">
                <div class="grid grid-cols-1 gap-3 rounded-2xl border border-cyan-100 bg-cyan-50 p-4 text-sm md:grid-cols-4">
                    <div>
                        <p class="font-semibold text-cyan-700">Parent Budget</p>
                        <p id="breakdownParentTotal" class="mt-1 text-lg font-bold text-cyan-950">PHP 0.00</p>
                    </div>
                    <div>
                        <p class="font-semibold text-cyan-700">Budget Used</p>
                        <p id="breakdownAllocatedTotal" class="mt-1 text-lg font-bold text-cyan-950">PHP 0.00</p>
                    </div>
                    <div>
                        <p class="font-semibold text-cyan-700">Budget Remaining</p>
                        <p id="breakdownRemainingTotal" class="mt-1 text-lg font-bold text-cyan-950">PHP 0.00</p>
                    </div>
                    <div>
                        <p class="font-semibold text-cyan-700">Overspent Amount</p>
                        <p id="breakdownOverspentTotal" class="mt-1 text-lg font-bold text-cyan-950">PHP 0.00</p>
                    </div>
                </div>
                <p id="breakdownBudgetStatus" class="rounded-xl bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700">
                    Budget status: Available
                </p>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                        <input id="breakdownName" type="text" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-gray-50 text-gray-900" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                        <input id="breakdownDate" type="date" name="expense_date" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white text-gray-900" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                        <select id="breakdownCategory" name="category_id" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white text-gray-900" required>
                            <option value="">Select Category</option>
                            @forelse($categories ?? [] as $category)
                                <option value="{{ $category->id }}" data-category-name="{{ $category->particulars_category }}">{{ $category->particulars_category }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
                        <input id="breakdownAmount" name="amount" type="number" step="0.01" min="0" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white text-gray-900" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 items-start gap-5 lg:grid-cols-[minmax(0,1fr)_390px]">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction Details</label>
                            <textarea id="breakdownDetails" name="transaction_details" rows="4" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white text-gray-900"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="breakdownDescription" name="description" rows="4" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white text-gray-900"></textarea>
                        </div>
                    </div>
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label class="block text-sm font-semibold text-gray-700">Attachments <span id="breakdownAttachmentCounter">(0)</span></label>
                            <p class="text-xs font-semibold text-gray-500">PDF, JPG, JPEG, PNG, DOCX, XLSX. Max 10 files, 10MB each.</p>
                        </div>
                        <div id="breakdownAttachmentDropzone" class="rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-5 text-center transition hover:border-teal-400 hover:bg-teal-50/40">
                            <input id="breakdownAttachments" name="attachments[]" type="file" class="sr-only" multiple accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx,application/pdf,image/jpeg,image/png,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            <p class="text-sm font-semibold text-gray-700">Drag & Drop Files Here</p>
                            <p class="my-2 text-xs font-semibold uppercase tracking-wide text-gray-400">or</p>
                            <button id="breakdownSelectFilesBtn" type="button" class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-700">
                                <i data-feather="upload" class="w-4 h-4"></i>
                                Select Files
                            </button>
                        </div>
                        <div id="breakdownAttachmentPreview" class="mt-3 rounded-2xl border border-gray-200 bg-white p-3">
                            <p class="text-center text-sm text-gray-500">No files selected</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <div class="flex items-center gap-3">
                        <button id="closeBreakdownFooterBtn" type="button" class="px-6 py-2.5 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition">
                            Close
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 text-white font-semibold hover:bg-teal-700 transition">
                            Save Breakdown
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- View Breakdown Modal -->
    <div id="viewBreakdownModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-600 to-teal-600 p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-white">Debit Breakdown View</h3>
                        <p id="viewBreakdownSubtitle" class="text-cyan-100 text-sm mt-1"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="printViewBreakdownBtn" type="button" class="inline-flex items-center gap-2 rounded-lg bg-white/15 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/25">
                            <i data-feather="printer" class="w-4 h-4"></i>
                            Print
                        </button>
                        <button id="pdfViewBreakdownBtn" type="button" class="inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-teal-700 transition hover:bg-cyan-50">
                            <i data-feather="download" class="w-4 h-4"></i>
                            Export PDF
                        </button>
                        <button id="closeViewBreakdownBtn" type="button" class="text-white hover:text-cyan-100 transition">
                            <i data-feather="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                        <input id="viewBreakdownName" type="text" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-gray-50 text-gray-900" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                        <input id="viewBreakdownDate" type="text" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-gray-50 text-gray-900" readonly>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-gray-200">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Transaction Details</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Description</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="viewBreakdownTableBody">
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No breakdown loaded yet</td>
                            </tr>
                        </tbody>
                        <tfoot class="border-t border-gray-200 bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-bold text-gray-900">TOTAL</td>
                                <td id="viewBreakdownTotal" class="px-4 py-3 text-right text-sm font-bold text-teal-700">PHP 0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <h4 id="viewBreakdownAttachmentCount" class="text-sm font-bold text-gray-800">Attachments (0)</h4>
                        <p class="text-xs font-semibold text-gray-500">Supporting documents for this debit breakdown</p>
                    </div>
                    <div id="viewBreakdownAttachmentsList" class="space-y-2">
                        <p class="text-sm text-gray-500">No attachments found.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button id="closeViewBreakdownFooterBtn" type="button" class="px-6 py-2.5 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-white">Success</h3>
                        <p class="text-emerald-100 text-sm mt-1">Your changes have been saved.</p>
                    </div>
                    <button id="closeSuccessModalBtn" type="button" class="text-white hover:text-emerald-100 transition">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <p id="successModalMessage" class="text-sm text-gray-700"></p>
            </div>
        </div>
    </div>

    <!-- Warning Modal -->
    <div id="warningModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-white">Warning</h3>
                        <p class="text-amber-100 text-sm mt-1">Please review this carefully.</p>
                    </div>
                    <button id="closeWarningModalBtn" type="button" class="text-white hover:text-amber-100 transition">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <p id="warningModalMessage" class="text-sm text-gray-700"></p>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-rose-600 p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-white">Error</h3>
                        <p class="text-red-100 text-sm mt-1">Something went wrong.</p>
                    </div>
                    <button id="closeErrorModalBtn" type="button" class="text-white hover:text-red-100 transition">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <p id="errorModalMessage" class="text-sm text-gray-700"></p>
            </div>
        </div>
    </div>

    <div id="attachmentImagePreviewModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-[70] p-4">
        <div class="w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="flex items-center justify-between gap-4 border-b border-gray-200 px-5 py-4">
                <h3 id="attachmentImagePreviewTitle" class="text-lg font-bold text-gray-900">Attachment Preview</h3>
                <button id="closeAttachmentImagePreviewBtn" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-600 transition hover:bg-gray-200">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="max-h-[75vh] overflow-auto bg-gray-100 p-4">
                <img id="attachmentImagePreviewImg" src="" alt="Attachment preview" class="mx-auto max-h-[70vh] max-w-full rounded-xl bg-white object-contain shadow">
            </div>
        </div>
    </div>

    <!-- End of Main Content -->
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.4/dist/jspdf.plugin.autotable.min.js"></script>
<script>
    const currentYear = {{ $year }};
    const currentMonth = {{ $month }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;
    const storeExpenseRoute = @json(route('accounting.store-expense'));
    const importExpensesRoute = @json(route('accounting.import-expenses'));
    const updateOpeningBalanceRoute = @json(route('accounting.update-opening-balance'));
    const deleteExpenseBaseUrl = @json(url('/accounting/liquidate-expenses/expense'));
    const updateExpenseCategoryBaseUrl = @json(url('/accounting/liquidate-expenses/expense'));
    const viewBreakdownBaseUrl = @json(url('/accounting/liquidate-expenses/expense'));
    const deleteBreakdownAttachmentBaseUrl = @json(url('/accounting/liquidate-expenses/breakdown-attachment'));
    const reportCompanyName = 'DMC Enterprises';
    const reportLogoUrl = @json(asset('images/logo.png'));
    const reportGeneratedBy = @json(optional(Auth::user())->name ?? 'Accounting User');
    let openingBalanceValue = @json((float) ($monthlyBalance->opening_balance ?? 0));
    // No external cash-advance selection — form records manual liquidation entries
    const breakdownModal = document.getElementById('breakdownModal');
    const closeBreakdownBtn = document.getElementById('closeBreakdownBtn');
    const closeBreakdownFooterBtn = document.getElementById('closeBreakdownFooterBtn');
    const breakdownForm = document.getElementById('breakdownForm');
    const breakdownCategory = document.getElementById('breakdownCategory');
    const breakdownAmount = document.getElementById('breakdownAmount');
    const breakdownParentTotal = document.getElementById('breakdownParentTotal');
    const breakdownAllocatedTotal = document.getElementById('breakdownAllocatedTotal');
    const breakdownRemainingTotal = document.getElementById('breakdownRemainingTotal');
    const breakdownOverspentTotal = document.getElementById('breakdownOverspentTotal');
    const breakdownBudgetStatus = document.getElementById('breakdownBudgetStatus');
    const breakdownAttachments = document.getElementById('breakdownAttachments');
    const breakdownAttachmentDropzone = document.getElementById('breakdownAttachmentDropzone');
    const breakdownSelectFilesBtn = document.getElementById('breakdownSelectFilesBtn');
    const breakdownAttachmentPreview = document.getElementById('breakdownAttachmentPreview');
    const breakdownAttachmentCounter = document.getElementById('breakdownAttachmentCounter');
    const viewBreakdownModal = document.getElementById('viewBreakdownModal');
    const closeViewBreakdownBtn = document.getElementById('closeViewBreakdownBtn');
    const closeViewBreakdownFooterBtn = document.getElementById('closeViewBreakdownFooterBtn');
    const printViewBreakdownBtn = document.getElementById('printViewBreakdownBtn');
    const pdfViewBreakdownBtn = document.getElementById('pdfViewBreakdownBtn');
    const transactionFilterForm = document.getElementById('transactionFilterForm');
    const transactionsTableContainer = document.getElementById('transactions-table-container');
    const transactionsVisibleCount = document.getElementById('transactionsVisibleCount');
    const successModal = document.getElementById('successModal');
    const closeSuccessModalBtn = document.getElementById('closeSuccessModalBtn');
    const successModalMessage = document.getElementById('successModalMessage');
    const warningModal = document.getElementById('warningModal');
    const closeWarningModalBtn = document.getElementById('closeWarningModalBtn');
    const warningModalMessage = document.getElementById('warningModalMessage');
    const errorModal = document.getElementById('errorModal');
    const closeErrorModalBtn = document.getElementById('closeErrorModalBtn');
    const errorModalMessage = document.getElementById('errorModalMessage');
    const attachmentImagePreviewModal = document.getElementById('attachmentImagePreviewModal');
    const attachmentImagePreviewTitle = document.getElementById('attachmentImagePreviewTitle');
    const attachmentImagePreviewImg = document.getElementById('attachmentImagePreviewImg');
    const closeAttachmentImagePreviewBtn = document.getElementById('closeAttachmentImagePreviewBtn');
    const allowedBreakdownAttachmentExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'docx', 'xlsx'];
    const maxBreakdownAttachmentFiles = 10;
    const maxBreakdownAttachmentBytes = 10 * 1024 * 1024;
    let selectedBreakdownAttachmentFiles = [];
    let successModalTimer = null;
    let warningModalTimer = null;
    let errorModalTimer = null;
    let importPreviewRows = [];
    let importResultByRow = new Map();
    let currentViewBreakdownReport = null;
    let currentBreakdownAllocation = {
        parentAmount: 0,
        allocatedAmount: 0,
        overspentAmount: 0,
        status: 'AVAILABLE',
        remainingAmount: 0,
    };

    function showSuccessModal(message) {
        if (!successModal || !successModalMessage) {
            return;
        }

        successModalMessage.textContent = message;
        successModal.classList.remove('hidden');
        successModal.style.display = 'flex';

        if (window.feather) {
            feather.replace();
        }

        if (successModalTimer) {
            clearTimeout(successModalTimer);
        }

        successModalTimer = setTimeout(() => {
            closeSuccessModal();
        }, 5000);
    }

    function closeSuccessModal() {
        if (!successModal) {
            return;
        }

        successModal.classList.add('hidden');
        successModal.style.display = 'none';

        if (successModalTimer) {
            clearTimeout(successModalTimer);
            successModalTimer = null;
        }
    }

    function showWarningModal(message) {
        if (!warningModal || !warningModalMessage) {
            return;
        }

        warningModalMessage.textContent = message;
        warningModal.classList.remove('hidden');
        warningModal.style.display = 'flex';

        if (window.feather) {
            feather.replace();
        }

        if (warningModalTimer) {
            clearTimeout(warningModalTimer);
        }

        warningModalTimer = setTimeout(() => {
            closeWarningModal();
        }, 5000);
    }

    function closeWarningModal() {
        if (!warningModal) {
            return;
        }

        warningModal.classList.add('hidden');
        warningModal.style.display = 'none';

        if (warningModalTimer) {
            clearTimeout(warningModalTimer);
            warningModalTimer = null;
        }
    }

    function showErrorModal(message) {
        if (!errorModal || !errorModalMessage) {
            return;
        }

        errorModalMessage.textContent = message;
        errorModal.classList.remove('hidden');
        errorModal.style.display = 'flex';

        if (window.feather) {
            feather.replace();
        }

        if (errorModalTimer) {
            clearTimeout(errorModalTimer);
        }

        errorModalTimer = setTimeout(() => {
            closeErrorModal();
        }, 5000);
    }

    function closeErrorModal() {
        if (!errorModal) {
            return;
        }

        errorModal.classList.add('hidden');
        errorModal.style.display = 'none';

        if (errorModalTimer) {
            clearTimeout(errorModalTimer);
            errorModalTimer = null;
        }
    }

    function formatCurrencyValue(amount) {
        return 'PHP ' + Number(amount || 0).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function formatFileSize(bytes) {
        const size = Number(bytes || 0);

        if (size >= 1024 * 1024) {
            return (size / (1024 * 1024)).toFixed(2) + ' MB';
        }

        if (size >= 1024) {
            return (size / 1024).toFixed(2) + ' KB';
        }

        return size + ' B';
    }

    function getFileExtension(fileName) {
        return String(fileName || '').split('.').pop().toLowerCase();
    }

    function isImageAttachment(fileName, fileType = '') {
        const extension = getFileExtension(fileName);
        return ['jpg', 'jpeg', 'png'].includes(extension) || String(fileType || '').startsWith('image/');
    }

    function getAttachmentIcon(fileName, fileType = '') {
        const extension = getFileExtension(fileName);

        if (isImageAttachment(fileName, fileType)) {
            return '🖼';
        }

        if (extension === 'xlsx') {
            return '📊';
        }

        return '📄';
    }

    function syncBreakdownAttachmentInput() {
        if (!breakdownAttachments) {
            return;
        }

        const dataTransfer = new DataTransfer();
        selectedBreakdownAttachmentFiles.forEach(file => dataTransfer.items.add(file));
        breakdownAttachments.files = dataTransfer.files;
    }

    function renderBreakdownAttachmentPreview() {
        if (!breakdownAttachmentPreview || !breakdownAttachmentCounter) {
            return;
        }

        breakdownAttachmentCounter.textContent = `(${selectedBreakdownAttachmentFiles.length})`;

        if (!selectedBreakdownAttachmentFiles.length) {
            breakdownAttachmentPreview.innerHTML = '<p class="text-center text-sm text-gray-500">No files selected</p>';
            return;
        }

        breakdownAttachmentPreview.innerHTML = selectedBreakdownAttachmentFiles.map((file, index) => `
            <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-gray-800">${getAttachmentIcon(file.name, file.type)} ${escapeHtml(file.name)}</p>
                    <p class="text-xs text-gray-500">${escapeHtml(formatFileSize(file.size))}</p>
                </div>
                <button type="button" class="removeBreakdownAttachmentBtn rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50" data-index="${index}">Remove</button>
            </div>
        `).join('');
    }

    function addBreakdownAttachmentFiles(fileList) {
        const files = Array.from(fileList || []);

        if (!files.length) {
            return;
        }

        const accepted = [...selectedBreakdownAttachmentFiles];
        const errors = [];

        files.forEach(file => {
            const extension = getFileExtension(file.name);

            if (!allowedBreakdownAttachmentExtensions.includes(extension)) {
                errors.push(`${file.name} is not a supported file type.`);
                return;
            }

            if (file.size > maxBreakdownAttachmentBytes) {
                errors.push(`${file.name} is larger than 10MB.`);
                return;
            }

            if (accepted.length >= maxBreakdownAttachmentFiles) {
                errors.push('Maximum of 10 files per transaction reached.');
                return;
            }

            accepted.push(file);
        });

        selectedBreakdownAttachmentFiles = accepted;
        syncBreakdownAttachmentInput();
        renderBreakdownAttachmentPreview();

        if (errors.length) {
            showWarningModal(errors.join(' '));
        }
    }

    function resetBreakdownAttachments() {
        selectedBreakdownAttachmentFiles = [];

        if (breakdownAttachments) {
            breakdownAttachments.value = '';
        }

        syncBreakdownAttachmentInput();
        renderBreakdownAttachmentPreview();
    }

    if (breakdownSelectFilesBtn && breakdownAttachments) {
        breakdownSelectFilesBtn.addEventListener('click', () => breakdownAttachments.click());
    }

    if (breakdownAttachments) {
        breakdownAttachments.addEventListener('change', function() {
            addBreakdownAttachmentFiles(this.files);
        });
    }

    if (breakdownAttachmentDropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            breakdownAttachmentDropzone.addEventListener(eventName, function(event) {
                event.preventDefault();
                breakdownAttachmentDropzone.classList.add('border-teal-500', 'bg-teal-50');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            breakdownAttachmentDropzone.addEventListener(eventName, function(event) {
                event.preventDefault();
                breakdownAttachmentDropzone.classList.remove('border-teal-500', 'bg-teal-50');
            });
        });

        breakdownAttachmentDropzone.addEventListener('drop', function(event) {
            addBreakdownAttachmentFiles(event.dataTransfer?.files || []);
        });
    }

    if (breakdownAttachmentPreview) {
        breakdownAttachmentPreview.addEventListener('click', function(event) {
            const removeButton = event.target.closest('.removeBreakdownAttachmentBtn');

            if (!removeButton) {
                return;
            }

            selectedBreakdownAttachmentFiles.splice(Number(removeButton.dataset.index), 1);
            syncBreakdownAttachmentInput();
            renderBreakdownAttachmentPreview();
        });
    }

    function parseLocalDate(value) {
        if (value instanceof Date && !Number.isNaN(value.getTime())) {
            return value;
        }

        const raw = String(value ?? '').trim();
        const matchedDate = raw.match(/^(\d{4})-(\d{2})-(\d{2})$/);

        if (matchedDate) {
            return new Date(Number(matchedDate[1]), Number(matchedDate[2]) - 1, Number(matchedDate[3]));
        }

        const parsedDate = new Date(raw);
        return Number.isNaN(parsedDate.getTime()) ? null : parsedDate;
    }

    function formatReportDate(value) {
        const date = parseLocalDate(value);

        if (!date) {
            return value || '-';
        }

        return date.toLocaleDateString('en-US', {
            month: 'long',
            day: 'numeric',
            year: 'numeric',
        });
    }

    function formatReportTimestamp(date = new Date()) {
        return date.toLocaleString('en-US', {
            month: 'long',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
        });
    }

    function absoluteReportUrl(url) {
        if (!url) {
            return '';
        }

        try {
            return new URL(url, window.location.origin).href;
        } catch (error) {
            return url;
        }
    }

    function getBreakdownAttachmentLinks(report) {
        const requestAttachments = (report.attachments || [])
            .filter(attachment => attachment?.url)
            .map((attachment, index) => ({
                name: attachment.name || `Attachment ${index + 1}`,
                url: absoluteReportUrl(attachment.url),
            }));

        const receiptAttachments = (report.breakdowns || [])
            .filter(row => row?.receipt_url)
            .map((row, index) => ({
                name: `${row.category_name || 'Receipt'} Receipt ${index + 1}`,
                url: absoluteReportUrl(row.receipt_url),
            }));

        const transactionAttachments = (report.breakdowns || [])
            .flatMap(row => Array.isArray(row.attachments) ? row.attachments : [])
            .filter(attachment => attachment?.url)
            .map((attachment, index) => ({
                name: attachment.name || `Supporting Document ${index + 1}`,
                url: absoluteReportUrl(attachment.url),
            }));

        return [...requestAttachments, ...receiptAttachments, ...transactionAttachments];
    }

    function normalizeBreakdownReport(data) {
        const breakdowns = Array.isArray(data?.breakdowns) ? data.breakdowns : [];
        const total = breakdowns.reduce((sum, row) => sum + Number(row.amount || 0), 0);

        return {
            debit: data?.debit || {},
            breakdowns,
            attachments: Array.isArray(data?.attachments) ? data.attachments : [],
            attachment_count: Number(data?.attachment_count || 0),
            total,
        };
    }

    function getReportAttachmentCount(report) {
        return Number(report.attachment_count || 0);
    }

    function renderViewBreakdownAttachments(report) {
        const list = document.getElementById('viewBreakdownAttachmentsList');
        const counter = document.getElementById('viewBreakdownAttachmentCount');

        if (!list || !counter) {
            return;
        }

        const rowAttachments = (report.breakdowns || []).flatMap(row => {
            const label = row.category_name || formatReportDate(row.expense_date) || 'Breakdown';

            return (row.attachments || []).map(attachment => ({
                ...attachment,
                label,
            }));
        });

        const requestAttachments = (report.attachments || []).map(attachment => ({
            ...attachment,
            label: 'Debit Request',
            readonly: true,
        }));

        const receiptAttachments = (report.breakdowns || [])
            .filter(row => row.receipt_url)
            .map((row, index) => ({
                id: `receipt-${row.id || index}`,
                name: `${row.category_name || 'Receipt'} Receipt`,
                url: row.receipt_url,
                download_url: row.receipt_url,
                size: 0,
                type: '',
                label: row.category_name || 'Breakdown',
                readonly: true,
            }));

        const attachments = [...requestAttachments, ...receiptAttachments, ...rowAttachments];
        counter.textContent = `Attachments (${attachments.length})`;

        if (!attachments.length) {
            list.innerHTML = '<p class="text-sm text-gray-500">No attachments found.</p>';
            return;
        }

        list.innerHTML = attachments.map(attachment => {
            const canPreviewImage = isImageAttachment(attachment.name, attachment.type);
            const viewLabel = canPreviewImage ? 'View' : 'Open';
            const deleteButton = attachment.readonly ? '' : `
                <button type="button" class="deleteBreakdownAttachmentBtn rounded-lg px-2.5 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-50" data-id="${escapeHtml(attachment.id)}">Delete</button>
            `;

            return `
                <div class="flex flex-col gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-gray-800">${getAttachmentIcon(attachment.name, attachment.type)} ${escapeHtml(attachment.name || 'Attachment')}</p>
                        <p class="text-xs text-gray-500">${escapeHtml(attachment.label || 'Breakdown')}${attachment.size ? ' • ' + escapeHtml(formatFileSize(attachment.size)) : ''}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <button type="button" class="viewBreakdownAttachmentBtn rounded-lg px-2.5 py-1.5 text-xs font-semibold text-teal-700 transition hover:bg-teal-50" data-url="${escapeHtml(attachment.url || '')}" data-name="${escapeHtml(attachment.name || 'Attachment')}" data-type="${escapeHtml(attachment.type || '')}">${viewLabel}</button>
                        <a href="${escapeHtml(attachment.download_url || attachment.url || '#')}" download class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50">Download</a>
                        ${deleteButton}
                    </div>
                </div>
            `;
        }).join('');
    }

    function openAttachmentImagePreview(url, name) {
        if (!attachmentImagePreviewModal || !attachmentImagePreviewImg) {
            window.open(url, '_blank', 'noopener');
            return;
        }

        attachmentImagePreviewImg.onerror = function () {
            attachmentImagePreviewImg.onerror = null;
            closeAttachmentImagePreview();
            showWarningModal('Unable to load the image preview. Opening the file in a new tab instead.');
            window.open(url, '_blank', 'noopener');
        };
        attachmentImagePreviewImg.src = url;

        if (attachmentImagePreviewTitle) {
            attachmentImagePreviewTitle.textContent = name || 'Attachment Preview';
        }

        attachmentImagePreviewModal.classList.remove('hidden');
        attachmentImagePreviewModal.style.display = 'flex';

        if (window.feather) {
            feather.replace();
        }
    }

    function closeAttachmentImagePreview() {
        if (!attachmentImagePreviewModal || !attachmentImagePreviewImg) {
            return;
        }

        attachmentImagePreviewModal.classList.add('hidden');
        attachmentImagePreviewModal.style.display = 'none';
        attachmentImagePreviewImg.onerror = null;
        attachmentImagePreviewImg.src = '';
    }

    function removeAttachmentFromCurrentReport(attachmentId) {
        if (!currentViewBreakdownReport) {
            return;
        }

        currentViewBreakdownReport.breakdowns = (currentViewBreakdownReport.breakdowns || []).map(row => ({
            ...row,
            attachments: (row.attachments || []).filter(attachment => String(attachment.id) !== String(attachmentId)),
        }));
        currentViewBreakdownReport.attachment_count = Math.max(0, getReportAttachmentCount(currentViewBreakdownReport) - 1);
        renderViewBreakdownAttachments(currentViewBreakdownReport);
    }

    document.getElementById('viewBreakdownAttachmentsList')?.addEventListener('click', function(event) {
        const viewButton = event.target.closest('.viewBreakdownAttachmentBtn');
        const deleteButton = event.target.closest('.deleteBreakdownAttachmentBtn');

        if (viewButton) {
            const url = viewButton.dataset.url;
            const name = viewButton.dataset.name || 'Attachment';
            const type = viewButton.dataset.type || '';

            if (!url) {
                return;
            }

            if (isImageAttachment(name, type)) {
                openAttachmentImagePreview(url, name);
            } else {
                window.open(url, '_blank', 'noopener');
            }

            return;
        }

        if (!deleteButton) {
            return;
        }

        const attachmentId = deleteButton.dataset.id;

        if (!attachmentId || !confirm('Delete this attachment?')) {
            return;
        }

        deleteButton.disabled = true;
        deleteButton.classList.add('opacity-60');

        fetch(`${deleteBreakdownAttachmentBaseUrl}/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(response => response.json().then(data => ({ ok: response.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || !data.success) {
                showErrorModal(data.message || 'Unable to delete attachment.');
                return;
            }

            removeAttachmentFromCurrentReport(attachmentId);
            showSuccessModal(data.message || 'Attachment deleted successfully.');
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('An error occurred while deleting the attachment.');
        })
        .finally(() => {
            deleteButton.disabled = false;
            deleteButton.classList.remove('opacity-60');
        });
    });

    if (closeAttachmentImagePreviewBtn) {
        closeAttachmentImagePreviewBtn.addEventListener('click', closeAttachmentImagePreview);
    }

    if (attachmentImagePreviewModal) {
        attachmentImagePreviewModal.addEventListener('click', function(event) {
            if (event.target === attachmentImagePreviewModal) {
                closeAttachmentImagePreview();
            }
        });
    }

    function getCurrentBreakdownReport() {
        if (!currentViewBreakdownReport) {
            showWarningModal('Please open a debit breakdown first.');
            return null;
        }

        return currentViewBreakdownReport;
    }

    function buildBreakdownReportRows(report, html = true) {
        const rows = report.breakdowns || [];

        if (!rows.length) {
            return html
                ? '<tr><td colspan="5" class="empty">No breakdown entries found</td></tr>'
                : [];
        }

        if (!html) {
            return rows.map(row => [
                formatReportDate(row.expense_date),
                row.category_name || '-',
                row.transaction_details || '-',
                row.description || '-',
                formatCurrencyValue(row.amount),
            ]);
        }

        return rows.map(row => `
            <tr>
                <td>${escapeHtml(formatReportDate(row.expense_date))}</td>
                <td>${escapeHtml(row.category_name || '-')}</td>
                <td>${escapeHtml(row.transaction_details || '-')}</td>
                <td>${escapeHtml(row.description || '-')}</td>
                <td class="amount">${escapeHtml(formatCurrencyValue(row.amount))}</td>
            </tr>
        `).join('');
    }

    function buildBreakdownPrintHtml(report) {
        const printedAt = formatReportTimestamp();
        const attachmentLinks = getBreakdownAttachmentLinks(report);

        return `<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Debit Breakdown ${escapeHtml(report.debit.id || '')}</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: Arial, sans-serif; margin: 0; padding: 32px; }
        .company { font-size: 18px; font-weight: 800; margin: 0 0 4px; text-align: center; }
        h1 { font-size: 24px; margin: 0 0 22px; text-align: center; }
        .meta { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px 24px; margin-bottom: 22px; }
        .meta div, .footer div { font-size: 13px; }
        .label { color: #4b5563; font-weight: 700; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #d1d5db; font-size: 12px; padding: 9px 10px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; color: #374151; font-weight: 800; }
        .amount { text-align: right; white-space: nowrap; }
        tfoot td { border-top: 2px solid #111827; font-weight: 800; }
        .empty { color: #6b7280; padding: 24px; text-align: center; }
        .footer { border-top: 1px solid #d1d5db; display: grid; gap: 7px; margin-top: 24px; padding-top: 14px; }
        .attachments { margin-top: 18px; }
        .attachments h2 { font-size: 13px; margin: 0 0 8px; }
        .attachments a { color: #0f766e; display: block; font-size: 12px; margin-bottom: 5px; overflow-wrap: anywhere; }
        @media print {
            body { padding: 20mm 14mm; }
        }
    </style>
</head>
<body>
    <p class="company">${escapeHtml(reportCompanyName)}</p>
    <h1>Debit Breakdown Report</h1>
    <section class="meta">
        <div><span class="label">Debit ID:</span> ${escapeHtml(report.debit.id || '-')}</div>
        <div><span class="label">Employee Name:</span> ${escapeHtml(report.debit.employee_name || '-')}</div>
        <div><span class="label">Date:</span> ${escapeHtml(formatReportDate(report.debit.expense_date))}</div>
        <div><span class="label">Attachment Count:</span> ${escapeHtml(report.attachment_count || 0)}</div>
    </section>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Transaction Details</th>
                <th>Description</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>${buildBreakdownReportRows(report)}</tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="amount">TOTAL</td>
                <td class="amount">${escapeHtml(formatCurrencyValue(report.total))}</td>
            </tr>
        </tfoot>
    </table>
    ${attachmentLinks.length ? `
        <section class="attachments">
            <h2>Supporting Documents:</h2>
            ${attachmentLinks.map(link => `<a href="${escapeHtml(link.url)}">${escapeHtml(link.name)}</a>`).join('')}
        </section>
    ` : ''}
    <section class="footer">
        <div><span class="label">Total Amount:</span> ${escapeHtml(formatCurrencyValue(report.total))}</div>
        <div><span class="label">Attachment Count:</span> ${escapeHtml(attachmentLinks.length)}</div>
        <div><span class="label">Printed By:</span> ${escapeHtml(reportGeneratedBy)}</div>
        <div><span class="label">Print Date & Time:</span> ${escapeHtml(printedAt)}</div>
    </section>
</body>
</html>`;
    }

    function printCurrentBreakdownReport() {
        const report = getCurrentBreakdownReport();

        if (!report) {
            return;
        }

        const printWindow = window.open('', '_blank', 'width=1100,height=850');

        if (!printWindow) {
            showWarningModal('Popup blocked. Please allow popups to print the debit breakdown.');
            return;
        }

        printWindow.document.write(buildBreakdownPrintHtml(report));
        printWindow.document.close();
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
        }, 250);
    }

    function sanitizePdfFilenamePart(value) {
        return String(value || 'Report').replace(/[^a-zA-Z0-9_-]/g, '');
    }

    function loadImageDataUrl(url) {
        return new Promise(resolve => {
            if (!url) {
                resolve(null);
                return;
            }

            const image = new Image();
            image.crossOrigin = 'anonymous';
            image.onload = function () {
                try {
                    const canvas = document.createElement('canvas');
                    canvas.width = image.naturalWidth;
                    canvas.height = image.naturalHeight;
                    const context = canvas.getContext('2d');
                    context.drawImage(image, 0, 0);
                    resolve(canvas.toDataURL('image/png'));
                } catch (error) {
                    resolve(null);
                }
            };
            image.onerror = function () {
                resolve(null);
            };
            image.src = url;
        });
    }

    async function exportCurrentBreakdownPdf() {
        const report = getCurrentBreakdownReport();

        if (!report) {
            return;
        }

        if (!window.jspdf?.jsPDF || typeof window.jspdf.jsPDF.API.autoTable !== 'function') {
            showWarningModal('PDF export is still loading. Please try again in a moment.');
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'portrait', unit: 'pt', format: 'a4' });
        const pageWidth = doc.internal.pageSize.getWidth();
        const marginX = 40;
        let cursorY = 42;
        const generatedAt = formatReportTimestamp();
        const logoDataUrl = await loadImageDataUrl(reportLogoUrl);

        if (logoDataUrl) {
            doc.addImage(logoDataUrl, 'PNG', marginX, cursorY - 8, 42, 42);
        }

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(15);
        doc.text(reportCompanyName, logoDataUrl ? marginX + 54 : marginX, cursorY + 5);
        doc.setFontSize(18);
        doc.text('Debit Breakdown Report', marginX, cursorY + 58);

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        doc.text(`Debit ID: ${report.debit.id || '-'}`, marginX, cursorY + 82);
        doc.text(`Employee Name: ${report.debit.employee_name || '-'}`, marginX, cursorY + 98);
        doc.text(`Date: ${formatReportDate(report.debit.expense_date)}`, marginX, cursorY + 114);
        doc.text(`Attachment Count: ${report.attachment_count || 0}`, marginX, cursorY + 130);
        doc.text(`Generated: ${generatedAt}`, pageWidth - marginX, cursorY + 82, { align: 'right' });

        doc.autoTable({
            startY: cursorY + 154,
            head: [['Date', 'Category', 'Transaction Details', 'Description', 'Amount']],
            body: buildBreakdownReportRows(report, false),
            foot: [['', '', '', 'TOTAL', formatCurrencyValue(report.total)]],
            theme: 'grid',
            styles: {
                font: 'helvetica',
                fontSize: 8,
                cellPadding: 5,
                valign: 'top',
            },
            headStyles: {
                fillColor: [15, 118, 110],
                textColor: 255,
                fontStyle: 'bold',
            },
            footStyles: {
                fillColor: [241, 245, 249],
                textColor: [17, 24, 39],
                fontStyle: 'bold',
            },
            columnStyles: {
                0: { cellWidth: 72 },
                1: { cellWidth: 88 },
                2: { cellWidth: 130 },
                3: { cellWidth: 140 },
                4: { halign: 'right', cellWidth: 88 },
            },
        });

        cursorY = doc.lastAutoTable.finalY + 24;
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(11);
        doc.text(`Grand Total: ${formatCurrencyValue(report.total)}`, pageWidth - marginX, cursorY, { align: 'right' });

        const attachmentLinks = getBreakdownAttachmentLinks(report);

        if (attachmentLinks.length) {
            cursorY += 28;
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(10);
            doc.text('Supporting Documents:', marginX, cursorY);
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(9);

            attachmentLinks.forEach((link, index) => {
                cursorY += 14;

                if (cursorY > doc.internal.pageSize.getHeight() - 40) {
                    doc.addPage();
                    cursorY = 42;
                }

                const label = `${index + 1}. ${link.name}`;
                doc.setTextColor(15, 118, 110);
                doc.textWithLink(label, marginX, cursorY, { url: link.url });
                doc.setTextColor(17, 24, 39);
            });
        }

        const pageCount = doc.internal.getNumberOfPages();

        for (let pageNumber = 1; pageNumber <= pageCount; pageNumber += 1) {
            doc.setPage(pageNumber);
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(8);
            doc.setTextColor(107, 114, 128);
            doc.text(`Generated by ${reportGeneratedBy} on ${generatedAt} | Attachments: ${attachmentLinks.length}`, marginX, doc.internal.pageSize.getHeight() - 24);
            doc.text(`Page ${pageNumber} of ${pageCount}`, pageWidth - marginX, doc.internal.pageSize.getHeight() - 24, { align: 'right' });
            doc.setTextColor(17, 24, 39);
        }

        doc.save(`DebitBreakdown_${sanitizePdfFilenamePart(report.debit.id)}.pdf`);
    }

    function setBreakdownAllocation(allocation = {}) {
        currentBreakdownAllocation = {
            parentAmount: Number(allocation.parent_amount || allocation.parentAmount || 0),
            allocatedAmount: Number(allocation.allocated_amount || allocation.allocatedAmount || 0),
            overspentAmount: Number(allocation.overspent_amount || allocation.overspentAmount || 0),
            status: String(allocation.status || 'AVAILABLE'),
            remainingAmount: Number(allocation.remaining_amount || allocation.remainingAmount || 0),
        };

        if (breakdownParentTotal) {
            breakdownParentTotal.textContent = formatCurrencyValue(currentBreakdownAllocation.parentAmount);
        }

        if (breakdownAllocatedTotal) {
            breakdownAllocatedTotal.textContent = formatCurrencyValue(currentBreakdownAllocation.allocatedAmount);
        }

        if (breakdownRemainingTotal) {
            breakdownRemainingTotal.textContent = formatCurrencyValue(currentBreakdownAllocation.remainingAmount);
            breakdownRemainingTotal.classList.toggle('text-emerald-700', currentBreakdownAllocation.remainingAmount === 0);
            breakdownRemainingTotal.classList.toggle('text-red-700', currentBreakdownAllocation.remainingAmount < 0);
            breakdownRemainingTotal.classList.toggle('text-cyan-950', currentBreakdownAllocation.remainingAmount > 0);
        }

        if (breakdownOverspentTotal) {
            breakdownOverspentTotal.textContent = formatCurrencyValue(currentBreakdownAllocation.overspentAmount);
            breakdownOverspentTotal.classList.toggle('text-red-700', currentBreakdownAllocation.overspentAmount > 0);
            breakdownOverspentTotal.classList.toggle('text-cyan-950', currentBreakdownAllocation.overspentAmount <= 0);
        }

        if (breakdownBudgetStatus) {
            const isOverspent = currentBreakdownAllocation.status === 'OVERSPENT';
            breakdownBudgetStatus.textContent = isOverspent
                ? `Budget status: Overspent by ${formatCurrencyValue(currentBreakdownAllocation.overspentAmount)}. No additional cash deduction was recorded.`
                : currentBreakdownAllocation.status === 'FULLY_USED'
                    ? 'Budget status: Fully used'
                    : 'Budget status: Available';
            breakdownBudgetStatus.classList.toggle('bg-red-50', isOverspent);
            breakdownBudgetStatus.classList.toggle('text-red-700', isOverspent);
            breakdownBudgetStatus.classList.toggle('bg-gray-50', !isOverspent);
            breakdownBudgetStatus.classList.toggle('text-gray-700', !isOverspent);
        }
    }

    async function loadBreakdownAllocation(transactionId) {
        if (!transactionId) {
            return;
        }

        try {
            const response = await fetch(`${viewBreakdownBaseUrl}/${transactionId}/breakdown`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await response.json();

            if (response.ok && data.success && data.allocation) {
                setBreakdownAllocation(data.allocation);
            }
        } catch (error) {
            console.error('Error loading breakdown allocation:', error);
        }
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatDateValue(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    function setImportStatus(message, tone = 'slate') {
        const status = document.getElementById('liquidateImportStatus');
        const toneClasses = {
            slate: 'border-slate-200 bg-slate-50 text-slate-700',
            emerald: 'border-emerald-200 bg-emerald-50 text-emerald-800',
            rose: 'border-rose-200 bg-rose-50 text-rose-800',
            amber: 'border-amber-200 bg-amber-50 text-amber-800',
        };

        status.className = `rounded-2xl border px-4 py-3 text-sm font-semibold ${toneClasses[tone] || toneClasses.slate}`;
        status.textContent = message;
    }

    function setImportBusy(isBusy) {
        document.getElementById('liquidateImportProgressWrap').classList.toggle('hidden', !isBusy);
        document.getElementById('liquidateImportProgress').classList.toggle('animate-pulse', isBusy);
        document.getElementById('liquidateImportConfirmBtn').disabled = isBusy || importPreviewRows.length === 0;
    }

    function openImportModal() {
        const modal = document.getElementById('liquidateImportModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (window.feather) {
            feather.replace();
        }
    }

    function closeImportModal() {
        const modal = document.getElementById('liquidateImportModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function resetImportPreview() {
        importPreviewRows = [];
        importResultByRow = new Map();
        document.getElementById('liquidateExcelInput').value = '';
        document.getElementById('liquidateImportFileName').textContent = 'No file selected';
        document.getElementById('liquidateImportSummary').classList.add('hidden');
        document.getElementById('liquidateImportSuccessCount').textContent = '0';
        document.getElementById('liquidateImportFailedCount').textContent = '0';
        document.getElementById('liquidateImportConfirmBtn').disabled = true;
        setImportBusy(false);
        setImportStatus('Waiting for file...');
        renderImportPreview();
    }

    function normalizeImportHeader(value) {
        return String(value ?? '').trim().toLowerCase().replace(/[^a-z]/g, '');
    }

    function getHeaderIndex(headers, aliases) {
        const normalizedHeaders = headers.map(normalizeImportHeader);
        return aliases.map(normalizeImportHeader)
            .map(alias => normalizedHeaders.indexOf(alias))
            .find(index => index >= 0);
    }

    function formatImportDate(value) {
        if (value instanceof Date && !Number.isNaN(value.getTime())) {
            return formatDateValue(value);
        }

        if (typeof value === 'number' && window.XLSX?.SSF) {
            const parsed = XLSX.SSF.parse_date_code(value);
            if (parsed) {
                return formatDateValue(new Date(parsed.y, parsed.m - 1, parsed.d));
            }
        }

        const raw = String(value ?? '').trim();
        if (!raw) {
            return '';
        }

        const parsedDate = new Date(raw);
        return Number.isNaN(parsedDate.getTime()) ? raw : formatDateValue(parsedDate);
    }

    function formatImportAmount(value) {
        if (value === null || value === undefined || value === '') {
            return '';
        }

        // If it's already a number, convert to string with proper precision
        if (typeof value === 'number') {
            return String(Number(value).toFixed(2));
        }

        const raw = String(value).trim();
        if (!raw) {
            return '';
        }

        // Check if amount is in parentheses (negative format)
        const isParenthesizedNegative = /^\(.*\)$/.test(raw);
        
        // Remove currency symbols, spaces, and parentheses
        let normalized = raw
            .replace(/[$€¥₱]/g, '')  // Remove currency symbols
            .replace(/\s/g, '')      // Remove spaces
            .replace(/[()]/g, '');   // Remove parentheses

        // Remove commas (thousand separators)
        normalized = normalized.replace(/,/g, '');

        // Keep only valid number characters (digits, single dot, single minus)
        // Remove multiple dots/minus signs
        const parts = normalized.split('.');
        if (parts.length > 2) {
            normalized = parts[0] + '.' + parts.slice(1).join('');
        }

        // Keep only digits and one dot
        normalized = normalized.replace(/[^\d.-]/g, '');

        if (!normalized || normalized === '.' || normalized === '-' || normalized === '-.') {
            return '';
        }

        return isParenthesizedNegative ? `-${normalized}` : normalized;
    }

    function parseExcelRows(workbook) {
        const sheetName = workbook.SheetNames[0];
        const sheet = workbook.Sheets[sheetName];
        const rows = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '', blankrows: false });

        if (rows.length < 2) {
            throw new Error('The selected file does not contain import rows.');
        }

        const headers = rows[0];
        const columns = {
            date: getHeaderIndex(headers, ['Date']),
            employee: getHeaderIndex(headers, ['Employee', 'Employee Name', 'Employee ID']),
            type: getHeaderIndex(headers, ['Type', 'Transaction Type']),
            purpose: getHeaderIndex(headers, ['Purpose', 'Particulars']),
            category: getHeaderIndex(headers, ['Category']),
            remarks: getHeaderIndex(headers, ['Remarks', 'Description']),
            amount: getHeaderIndex(headers, ['Amount']),
        };

        const missingColumns = Object.entries(columns)
            .filter(([key, index]) => !['remarks', 'category'].includes(key) && typeof index === 'undefined')
            .map(([key]) => key.charAt(0).toUpperCase() + key.slice(1));

        if (missingColumns.length > 0) {
            throw new Error(`Missing column(s): ${missingColumns.join(', ')}.`);
        }

        return rows.slice(1)
            .map((row, index) => {
                const parsedAmount = formatImportAmount(row[columns.amount]);
                // Log for debugging
                console.log(`Row ${index + 2} - Raw amount: "${row[columns.amount]}", Parsed: "${parsedAmount}"`);
                
                return {
                    row_number: index + 2,
                    date: formatImportDate(row[columns.date]),
                    employee: String(row[columns.employee] ?? '').trim(),
                    type: String(row[columns.type] ?? '').trim(),
                    purpose: String(row[columns.purpose] ?? '').trim(),
                    category: typeof columns.category === 'undefined' ? '' : String(row[columns.category] ?? '').trim(),
                    remarks: typeof columns.remarks === 'undefined' ? '' : String(row[columns.remarks] ?? '').trim(),
                    amount: parsedAmount,
                };
            })
            .filter(row => ['date', 'employee', 'type', 'purpose', 'category', 'remarks', 'amount']
                .some(key => String(row[key] ?? '').trim() !== ''));
    }

    function renderImportPreview() {
        const tbody = document.getElementById('liquidateImportPreviewBody');

        if (importPreviewRows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="px-4 py-8 text-center text-gray-500">Select an Excel file to preview rows.</td></tr>';
            return;
        }

        tbody.innerHTML = importPreviewRows.map(row => {
            const result = importResultByRow.get(row.row_number) || { status: 'Pending', errors: [] };
            const statusClass = result.status === 'Imported'
                ? 'bg-emerald-100 text-emerald-700 border-emerald-200'
                : result.status === 'Failed'
                    ? 'bg-rose-100 text-rose-700 border-rose-200'
                    : 'bg-slate-100 text-slate-700 border-slate-200';
            const statusTitle = (result.errors || []).join(' ');

            return `
                <tr class="border-t border-gray-100 align-top">
                    <td class="px-4 py-3 font-semibold text-gray-700">${row.row_number}</td>
                    <td class="px-4 py-3">
                        <span title="${escapeHtml(statusTitle)}" class="inline-flex rounded-full border px-2.5 py-1 text-xs font-bold ${statusClass}">
                            ${escapeHtml(result.status)}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">${escapeHtml(row.date)}</td>
                    <td class="px-4 py-3 text-gray-700">${escapeHtml(row.employee)}</td>
                    <td class="px-4 py-3 text-gray-700">${escapeHtml(row.type)}</td>
                    <td class="px-4 py-3 text-gray-700">${escapeHtml(row.purpose)}</td>
                    <td class="px-4 py-3 text-gray-700">${escapeHtml(row.category)}</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-800">${escapeHtml(row.amount)}</td>
                    <td class="px-4 py-3 text-gray-600">${escapeHtml(result.status === 'Failed' ? statusTitle : row.remarks)}</td>
                </tr>
            `;
        }).join('');
    }

    async function handleExcelFileSelection(event) {
        const file = event.target.files?.[0];
        if (!file) {
            return;
        }

        openImportModal();
        document.getElementById('liquidateImportFileName').textContent = file.name;
        document.getElementById('liquidateImportSummary').classList.add('hidden');
        importResultByRow = new Map();

        if (!/\.(xlsx|xls)$/i.test(file.name)) {
            importPreviewRows = [];
            renderImportPreview();
            setImportStatus('Please select a .xlsx or .xls file.', 'rose');
            return;
        }

        if (!window.XLSX) {
            importPreviewRows = [];
            renderImportPreview();
            setImportStatus('Excel parser is unavailable. Check your connection and reload the page.', 'rose');
            return;
        }

        try {
            setImportBusy(true);
            setImportStatus('Reading workbook...', 'amber');
            const buffer = await file.arrayBuffer();
            const workbook = XLSX.read(buffer, { type: 'array', cellDates: true });
            importPreviewRows = parseExcelRows(workbook);
            renderImportPreview();
            setImportStatus(`${importPreviewRows.length} row(s) ready for validation.`, 'emerald');
            document.getElementById('liquidateImportConfirmBtn').disabled = importPreviewRows.length === 0;
        } catch (error) {
            importPreviewRows = [];
            renderImportPreview();
            setImportStatus(error.message || 'Unable to read the selected Excel file.', 'rose');
        } finally {
            setImportBusy(false);
        }
    }

    async function confirmExcelImport() {
        if (importPreviewRows.length === 0) {
            return;
        }

        // Validate that all rows have amounts
        const rowsWithEmptyAmounts = importPreviewRows.filter(row => !row.amount || String(row.amount).trim() === '');
        if (rowsWithEmptyAmounts.length > 0) {
            showErrorModal(`${rowsWithEmptyAmounts.length} row(s) have empty amounts and cannot be imported.`);
            return;
        }

        try {
            setImportBusy(true);
            setImportStatus('Validating and saving rows...', 'amber');
            
            // Log all rows being sent for debugging
            console.log('Rows being sent to backend:', importPreviewRows);
            
            const response = await fetch(importExpensesRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ rows: importPreviewRows }),
            });
            const data = await response.json();

            importResultByRow = new Map();
            (data.imported || []).forEach(row => {
                importResultByRow.set(Number(row.row_number), { status: 'Imported', errors: [] });
            });
            (data.failures || []).forEach(row => {
                importResultByRow.set(Number(row.row_number), { status: 'Failed', errors: row.errors || [] });
            });
            renderImportPreview();

            document.getElementById('liquidateImportSummary').classList.remove('hidden');
            document.getElementById('liquidateImportSuccessCount').textContent = data.imported_rows || 0;
            document.getElementById('liquidateImportFailedCount').textContent = data.failed_rows || 0;

            if ((data.imported_rows || 0) > 0) {
                if ((data.failed_rows || 0) > 0) {
                    setImportStatus('Import completed with failed rows. Review the rows marked Failed before refreshing.', 'amber');
                } else {
                    setImportStatus('Import completed. Refreshing transactions...', 'emerald');
                    setTimeout(() => {
                        location.reload();
                    }, 2500);
                }
            } else {
                setImportStatus(data.message || 'No rows were imported.', 'rose');
            }
        } catch (error) {
            setImportStatus('Import failed. Please try again.', 'rose');
            console.error('Error importing Excel rows:', error);
        } finally {
            setImportBusy(false);
            document.getElementById('liquidateImportConfirmBtn').disabled = true;
        }
    }

    // Month selector change
    document.getElementById('monthSelector').addEventListener('change', function() {
        const [year, month] = this.value.split('-');
        const url = new URL(@json(route('accounting.liquidate-expenses')), window.location.origin);
        const params = new URLSearchParams(window.location.search);

        params.set('year', year);
        params.set('month', month);
        params.delete('page');
        url.search = params.toString();
        window.location.href = url.toString();
    });

    document.getElementById('liquidateImportExcelBtn').addEventListener('click', () => {
        resetImportPreview();
        document.getElementById('liquidateExcelInput').click();
    });
    document.getElementById('liquidateExcelInput').addEventListener('change', handleExcelFileSelection);
    document.getElementById('liquidateImportConfirmBtn').addEventListener('click', confirmExcelImport);
    document.getElementById('liquidateImportCloseBtn').addEventListener('click', closeImportModal);
    document.getElementById('liquidateImportCancelBtn').addEventListener('click', closeImportModal);
    document.getElementById('liquidateImportModal').addEventListener('click', function(event) {
        if (event.target.id === 'liquidateImportModal') {
            closeImportModal();
        }
    });

    // Update current period display
    function updatePeriodDisplay() {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
        const period = monthNames[currentMonth - 1] + ' ' + currentYear;
        document.getElementById('currentPeriod').textContent = period;
    }

    // Purpose is always visible and required for manual entries.

    function setTransactionsLoading(isLoading) {
        if (!transactionsTableContainer) {
            return;
        }

        const controls = [
            ...Array.from(transactionFilterForm?.querySelectorAll('input, select, button') || []),
            ...Array.from(transactionsTableContainer.querySelectorAll('.pagination a, nav a, button, select'))
        ];

        controls.forEach(control => {
            control.disabled = isLoading;
            control.classList.toggle('pointer-events-none', isLoading);
            control.classList.toggle('opacity-60', isLoading);
        });

        let overlay = document.getElementById('transactionsLoadingOverlay');
        if (isLoading && !overlay) {
            overlay = document.createElement('div');
            overlay.id = 'transactionsLoadingOverlay';
            overlay.className = 'absolute inset-0 z-10 flex items-center justify-center bg-white/70';
            overlay.innerHTML = '<div class="h-10 w-10 animate-spin rounded-full border-4 border-teal-100 border-t-teal-600"></div>';
            transactionsTableContainer.appendChild(overlay);
        }

        if (!isLoading) {
            document.querySelectorAll('#transactionsLoadingOverlay').forEach(staleOverlay => staleOverlay.remove());
        }
    }

    function syncTransactionFiltersFromUrl(url) {
        if (!transactionFilterForm) {
            return;
        }

        const params = new URL(url, window.location.origin).searchParams;
        ['type', 'start_date', 'end_date', 'category', 'search'].forEach(name => {
            const input = transactionFilterForm.querySelector(`[name="${name}"]`);
            if (input) {
                input.value = params.get(name) || '';
            }
        });
    }

    function updateTransactionsCount() {
        const meta = document.getElementById('transactionsTableMeta');
        if (meta && transactionsVisibleCount) {
            transactionsVisibleCount.textContent = meta.dataset.total || '0';
        }
    }

    async function loadTransactionsTable(url, options = {}) {
        if (!transactionsTableContainer) {
            window.location.href = url;
            return;
        }

        setTransactionsLoading(true);

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Unable to load transactions.');
            }

            transactionsTableContainer.innerHTML = await response.text();
            updateTransactionsCount();

            if (options.pushState !== false) {
                window.history.pushState({}, '', url);
            }

            if (options.syncFilters) {
                syncTransactionFiltersFromUrl(url);
            }

            if (window.feather) {
                feather.replace();
            }
        } catch (error) {
            console.error('Error:', error);
            window.location.href = url;
        } finally {
            setTransactionsLoading(false);
        }
    }

    function buildTransactionFilterUrl() {
        const formData = new FormData(transactionFilterForm);
        const url = new URL(transactionFilterForm.action, window.location.origin);

        formData.forEach((value, key) => {
            if (value) {
                url.searchParams.set(key, value);
            }
        });
        url.searchParams.delete('page');

        return url.toString();
    }

    if (transactionFilterForm) {
        transactionFilterForm.addEventListener('submit', function(event) {
            event.preventDefault();
            loadTransactionsTable(buildTransactionFilterUrl());
        });
    }

    document.addEventListener('click', function(event) {
        const paginationLink = event.target.closest('#transactions-table-container .pagination a, #transactions-table-container nav a');
        if (paginationLink) {
            event.preventDefault();
            loadTransactionsTable(paginationLink.href);
            return;
        }

        const resetLink = event.target.closest('#resetTransactionFilters');
        if (resetLink) {
            event.preventDefault();
            if (transactionFilterForm) {
                transactionFilterForm.reset();
                ['type', 'start_date', 'end_date', 'category', 'search'].forEach(name => {
                    const input = transactionFilterForm.querySelector(`[name="${name}"]`);
                    if (input) {
                        input.value = '';
                    }
                });
            }
            loadTransactionsTable(resetLink.href, { syncFilters: true });
        }
    });

    window.addEventListener('popstate', function() {
        loadTransactionsTable(window.location.href, {
            pushState: false,
            syncFilters: true
        });
    });

    function updateTransactionCategory(select) {
        const transactionId = select.dataset.id;
        const previousCategoryId = select.dataset.originalCategoryId || '';

        if (!transactionId || select.value === previousCategoryId) {
            return;
        }

        select.disabled = true;
        select.classList.add('opacity-70');

        fetch(`${updateExpenseCategoryBaseUrl}/${transactionId}/category`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                category_id: select.value || null
            })
        })
        .then(response => response.json().then(data => ({ ok: response.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || !data.success) {
                throw new Error(data.message || 'Unable to update category');
            }

            select.dataset.originalCategoryId = data.transaction?.category_id || '';
        })
        .catch(error => {
            console.error('Error:', error);
            select.value = previousCategoryId;
            showErrorModal('An error occurred while updating the category');
        })
        .finally(() => {
            select.disabled = false;
            select.classList.remove('opacity-70');
        });
    }

    document.addEventListener('change', function(event) {
        const select = event.target.closest('.transactionCategorySelect');

        if (!select) {
            return;
        }

        updateTransactionCategory(select);
    });

    // Breakdown modal layout
    function openBreakdownModalFromButton(button) {
        const parentAmount = Number(button.dataset.amount || 0);

        document.getElementById('breakdownName').value = button.dataset.name || '';
        document.getElementById('breakdownDate').value = button.dataset.date || '';
        document.getElementById('breakdownCashAdvanceRequestId').value = button.dataset.id || '';
        document.getElementById('breakdownEmployeeId').value = button.dataset.employeeId || '';
        document.getElementById('breakdownAmount').value = '';
        document.getElementById('breakdownDetails').value = '';
        document.getElementById('breakdownDescription').value = '';
        document.getElementById('breakdownCategory').value = '';
        resetBreakdownAttachments();
        setBreakdownAllocation({
            parentAmount,
            allocatedAmount: 0,
            overspentAmount: 0,
            status: 'AVAILABLE',
            remainingAmount: parentAmount,
        });
        loadBreakdownAllocation(button.dataset.id || '');

        breakdownModal.classList.remove('hidden');
        breakdownModal.style.display = 'flex';

        if (window.feather) {
            feather.replace();
        }
    }

    function closeBreakdownModal() {
        breakdownModal.classList.add('hidden');
        breakdownModal.style.display = 'none';
    }

    if (closeBreakdownBtn) {
        closeBreakdownBtn.addEventListener('click', closeBreakdownModal);
    }

    if (closeBreakdownFooterBtn) {
        closeBreakdownFooterBtn.addEventListener('click', closeBreakdownModal);
    }

    if (breakdownModal) {
        breakdownModal.addEventListener('click', function(e) {
            if (e.target === breakdownModal) {
                closeBreakdownModal();
            }
        });
    }

    if (breakdownForm) {
        breakdownForm.addEventListener('submit', function(e) {
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
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                if (!ok) {
                    const errors = data.errors ? Object.values(data.errors).flat().join(' ') : '';
                    showErrorModal(errors || data.message || 'Unable to save breakdown.');
                    return;
                }

                if (data.success) {
                    if (data.allocation) {
                        setBreakdownAllocation(data.allocation);
                    }

                    const remaining = Number(data.allocation?.remaining_amount ?? 0);
                    const overspentAmount = Number(data.allocation?.overspent_amount ?? 0);
                    const message = overspentAmount > 0
                        ? `Breakdown saved. Budget is overspent by ${formatCurrencyValue(overspentAmount)}. Ending Balance was not deducted again.`
                        : remaining > 0
                            ? `Breakdown saved. Remaining balance: ${formatCurrencyValue(remaining)}.`
                            : 'Breakdown saved successfully!';

                    showSuccessModal(message);
                    resetBreakdownAttachments();
                    closeBreakdownModal();
                } else {
                    showErrorModal('Error: ' + (data.message || 'Unable to save breakdown.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorModal('An error occurred while saving the breakdown');
            });
        });
    }

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
            document.getElementById('openingBalanceInput').value = Number(openingBalanceValue || 0).toFixed(2);
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
            showErrorModal('Please enter a valid opening balance');
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
                showSuccessModal('Opening balance saved successfully!');
                openingBalanceModal.classList.add('hidden');
                openingBalanceModal.style.display = 'none';
                openingBalanceValue = openingBalance;
                document.getElementById('openingBalance').textContent = formatCurrencyValue(openingBalance);
                if (setOpeningBalanceBtn) {
                    setOpeningBalanceBtn.innerHTML = '<i data-feather="edit-3" class="w-4 h-4"></i> Edit Opening Balance';
                    if (window.feather) {
                        feather.replace();
                    }
                }

                updateBalances();
            } else {
                showErrorModal('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('An error occurred while saving');
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
    const expenseForm = document.getElementById('expenseForm');
    const transactionTypeInput = document.getElementById('transaction_type');
    const categoryInput = document.getElementById('category_id');

    function syncCategoryRequirement() {
        if (!categoryInput || !transactionTypeInput) {
            return;
        }

        categoryInput.required = transactionTypeInput.value === 'debit';
    }

    if (transactionTypeInput) {
        transactionTypeInput.addEventListener('change', syncCategoryRequirement);
    }

    syncCategoryRequirement();

    expenseForm.addEventListener('submit', function(e) {
        e.preventDefault();

        syncCategoryRequirement();
        if (transactionTypeInput?.value === 'debit' && !categoryInput?.value) {
            showErrorModal('Category is required for Debit transactions.');
            categoryInput?.focus();
            return;
        }

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
                    showSuccessModal('Expense recorded successfully!');
                // Reload the page to refresh the table
                    setTimeout(() => {
                        location.reload();
                    }, 5000);
            } else {
                showErrorModal('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('An error occurred while recording the expense');
        });

        this.reset();
        syncCategoryRequirement();
    });

    function deleteTransactionFromButton(button) {
        const expenseId = button.dataset.id;

        if (!expenseId || !confirm('Are you sure you want to delete this transaction?')) {
            return;
        }

        button.disabled = true;
        button.classList.add('pointer-events-none', 'opacity-60');

        fetch(`${deleteExpenseBaseUrl}/${expenseId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json().then(data => ({ ok: response.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || !data.success) {
                showErrorModal('Error: ' + (data.message || 'Unable to delete the expense.'));
                return;
            }

            showSuccessModal('Expense deleted successfully!');
            loadTransactionsTable(window.location.href, {
                pushState: false,
                syncFilters: true,
            });
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('An error occurred while deleting the expense');
        })
        .finally(() => {
            button.disabled = false;
            button.classList.remove('pointer-events-none', 'opacity-60');
        });
    }

    // Clear all transactions for the month
    document.getElementById('clearMonthBtn').addEventListener('click', function() {
        const year = document.getElementById('year').value;
        const month = document.getElementById('month').value;
        const monthName = document.getElementById('monthSelector').options[document.getElementById('monthSelector').selectedIndex].text;
        
        const confirmMessage = `⚠️ WARNING!\n\nThis will DELETE ALL transactions for ${monthName}.\n\nAre you absolutely sure? This cannot be undone.`;
        
        if (confirm(confirmMessage)) {
            const secondConfirm = confirm('This is your last chance to cancel. Click OK to permanently delete all transactions for this month.');
            
            if (secondConfirm) {
                const clearMonthUrl = @json(url('/accounting/liquidate-expenses/month')) + '/' + year + '/' + month;
                
                fetch(clearMonthUrl, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessModal(data.message);
                        setTimeout(() => location.reload(), 5000);
                    } else {
                        showErrorModal('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorModal('An error occurred while clearing the month');
                });
            }
        }
    });

    function viewBreakdownFromButton(button) {
        const expenseId = button.dataset.id;

        if (!expenseId) {
            return;
        }

        button.disabled = true;
        button.classList.add('pointer-events-none', 'opacity-60');

        fetch(`${viewBreakdownBaseUrl}/${expenseId}/breakdown`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json().then(data => ({ ok: response.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || !data.success) {
                showErrorModal('Error: ' + (data.message || 'Unable to load the breakdown.'));
                return;
            }

            currentViewBreakdownReport = normalizeBreakdownReport(data);
            document.getElementById('viewBreakdownName').value = currentViewBreakdownReport.debit.employee_name || '';
            document.getElementById('viewBreakdownDate').value = formatReportDate(currentViewBreakdownReport.debit.expense_date);
            document.getElementById('viewBreakdownSubtitle').textContent = `Debit ID: ${currentViewBreakdownReport.debit.id}`;

            const tbody = document.getElementById('viewBreakdownTableBody');
            tbody.innerHTML = '';

            if (!currentViewBreakdownReport.breakdowns.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No breakdown entries found</td></tr>';
            } else {
                currentViewBreakdownReport.breakdowns.forEach(row => {
                    tbody.insertAdjacentHTML('beforeend', `
                        <tr class="border-b border-gray-200 last:border-b-0">
                            <td class="px-4 py-3 text-sm text-gray-900">${escapeHtml(formatReportDate(row.expense_date))}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">${escapeHtml(row.category_name ?? '-')}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">${escapeHtml(row.transaction_details ?? '-')}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">${escapeHtml(row.description ?? '-')}</td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-teal-700">${escapeHtml(formatCurrencyValue(row.amount))}</td>
                        </tr>
                    `);
                });
            }

            document.getElementById('viewBreakdownTotal').textContent = formatCurrencyValue(currentViewBreakdownReport.total);
            renderViewBreakdownAttachments(currentViewBreakdownReport);

            viewBreakdownModal.classList.remove('hidden');
            viewBreakdownModal.style.display = 'flex';

            if (window.feather) {
                feather.replace();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('An error occurred while loading the breakdown');
        })
        .finally(() => {
            button.disabled = false;
            button.classList.remove('pointer-events-none', 'opacity-60');
        });
    }

    // Native delegated equivalent of $(document).on('click', '.btn-*', ...)
    document.addEventListener('click', function(event) {
        const editButton = event.target.closest('.btn-edit, .breakdownBtn');
        if (editButton && transactionsTableContainer?.contains(editButton)) {
            event.preventDefault();
            openBreakdownModalFromButton(editButton);
            return;
        }

        const viewButton = event.target.closest('.btn-view, .viewBreakdownBtn');
        if (viewButton && transactionsTableContainer?.contains(viewButton)) {
            event.preventDefault();
            viewBreakdownFromButton(viewButton);
            return;
        }

        const deleteButton = event.target.closest('.btn-delete, .deleteBtn');
        if (deleteButton && transactionsTableContainer?.contains(deleteButton)) {
            event.preventDefault();
            deleteTransactionFromButton(deleteButton);
        }
    });

    function closeViewBreakdownModal() {
        viewBreakdownModal.classList.add('hidden');
        viewBreakdownModal.style.display = 'none';
    }

    if (closeViewBreakdownBtn) {
        closeViewBreakdownBtn.addEventListener('click', closeViewBreakdownModal);
    }

    if (closeViewBreakdownFooterBtn) {
        closeViewBreakdownFooterBtn.addEventListener('click', closeViewBreakdownModal);
    }

    if (printViewBreakdownBtn) {
        printViewBreakdownBtn.addEventListener('click', printCurrentBreakdownReport);
    }

    if (pdfViewBreakdownBtn) {
        pdfViewBreakdownBtn.addEventListener('click', exportCurrentBreakdownPdf);
    }

    if (closeSuccessModalBtn) {
        closeSuccessModalBtn.addEventListener('click', closeSuccessModal);
    }

    if (successModal) {
        successModal.addEventListener('click', function(e) {
            if (e.target === successModal) {
                closeSuccessModal();
            }
        });
    }

    if (closeWarningModalBtn) {
        closeWarningModalBtn.addEventListener('click', closeWarningModal);
    }

    if (warningModal) {
        warningModal.addEventListener('click', function(e) {
            if (e.target === warningModal) {
                closeWarningModal();
            }
        });
    }

    if (closeErrorModalBtn) {
        closeErrorModalBtn.addEventListener('click', closeErrorModal);
    }

    if (errorModal) {
        errorModal.addEventListener('click', function(e) {
            if (e.target === errorModal) {
                closeErrorModal();
            }
        });
    }

    if (viewBreakdownModal) {
        viewBreakdownModal.addEventListener('click', function(e) {
            if (e.target === viewBreakdownModal) {
                closeViewBreakdownModal();
            }
        });
    }

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

</script>
@endsection
