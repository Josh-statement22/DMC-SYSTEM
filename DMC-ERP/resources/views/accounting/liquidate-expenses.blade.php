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
                <button id="liquidateImportExcelBtn" type="button" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 md:w-auto">
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

            <!-- Category (dropdown) -->
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

    <!-- Transactions Table (Hidden by default) -->
    <div id="transactionsContainer" class="rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm" style="display: none;">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Recorded Transactions</h3>
                    <p class="mt-1 text-sm text-gray-500"><span id="transactionsVisibleCount">{{ ($expenses ?? collect())->count() }}</span> transaction(s) shown</p>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:items-end">
                    <div>
                        <label for="transactionDateFilter" class="block text-sm font-semibold text-gray-700 mb-2">Filter Date</label>
                        <select id="transactionDateFilter" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                            <option value="">All dates</option>
                        </select>
                    </div>
                    <button
                        type="button"
                        id="resetTransactionDateFilter"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        <i data-feather="rotate-ccw" class="w-4 h-4"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Remarks</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Amount</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="transactionsTableBody">
                    @forelse($expenses ?? [] as $expense)
                        <tr class="transaction-row border-b border-gray-200 hover:bg-gray-50" data-transaction-date="{{ $expense->expense_date->format('Y-m-d') }}">
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->expense_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->employee_name ?? 'Unassigned' }}</td>
                            <td class="px-6 py-3 text-sm">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 {{ $expense->transaction_type === 'debit' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($expense->transaction_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->transaction_details ?? '-' }}</td>
                            <td class="px-6 py-2 text-sm text-gray-900">
                                <select
                                    class="transactionCategorySelect w-44 rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-700 transition focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500 disabled:cursor-wait disabled:bg-gray-50"
                                    data-id="{{ $expense->id }}"
                                    data-original-category="{{ $expense->category ?? '' }}"
                                    aria-label="Transaction category"
                                >
                                    <option value="">{{ ($expense->category ?? null) ? 'Uncategorized' : 'Select category' }}</option>
                                    @forelse($categories ?? [] as $category)
                                        <option value="{{ $category->particulars_category }}" {{ ($expense->category ?? '') === $category->particulars_category ? 'selected' : '' }}>
                                            {{ $category->particulars_category }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ $expense->description ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm text-right font-semibold {{ $expense->transaction_type === 'debit' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $expense->transaction_type === 'debit' ? '-' : '+' }}PHP {{ number_format($expense->amount, 2) }}
                            </td>
                            <td class="px-6 py-3 text-center">
                                <div class="inline-flex items-center gap-3">
                                    <button
                                        type="button"
                                        class="text-teal-600 hover:text-teal-800 font-semibold breakdownBtn"
                                        data-id="{{ $expense->id }}"
                                        data-employee-id="{{ $expense->employee_id ?? '' }}"
                                        data-name="{{ $expense->employee_name ?? 'Unassigned' }}"
                                        data-date="{{ $expense->expense_date->format('Y-m-d') }}"
                                    >
                                        <i data-feather="edit-3" class="w-4 h-4"></i>
                                        <span class="sr-only">Breakdown</span>
                                    </button>
                                    <button type="button" class="text-cyan-600 hover:text-cyan-800 font-semibold viewBreakdownBtn" data-id="{{ $expense->id }}">
                                        <i data-feather="eye" class="w-4 h-4"></i>
                                        <span class="sr-only">View</span>
                                    </button>
                                    <button type="button" class="text-red-600 hover:text-red-800 font-semibold deleteBtn" data-id="{{ $expense->id }}">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                        <span class="sr-only">Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-b border-gray-200">
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">No transactions recorded yet</td>
                        </tr>
                    @endforelse
                    <tr id="transactionsFilterEmptyRow" class="hidden border-b border-gray-200">
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">No transactions found for the selected date</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="transactionsPaginationContainer" class="hidden border-t border-gray-200 px-6 py-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-600">Page <span id="transactionsCurrentPageLabel">1</span> of <span id="transactionsTotalPagesLabel">1</span></p>
                <div class="flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        id="transactionsPrevPageBtn"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Previous
                    </button>
                    <div id="transactionsPageNumbers" class="flex flex-wrap items-center gap-2"></div>
                    <button
                        type="button"
                        id="transactionsNextPageBtn"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Breakdown Modal -->
    <div id="breakdownModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-teal-600 to-cyan-600 p-5">
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

            <form id="breakdownForm" class="p-6 space-y-6">
                @csrf
                <input type="hidden" id="breakdownRecordSource" name="record_source" value="breakdown">
                <input type="hidden" id="breakdownEmployeeId" name="employee_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                <option value="{{ $category->id }}">{{ $category->particulars_category }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
                        <input id="breakdownAmount" name="amount" type="number" step="0.01" min="0" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white text-gray-900" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction Details</label>
                        <textarea id="breakdownDetails" name="transaction_details" rows="3" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white text-gray-900"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="breakdownDescription" name="description" rows="3" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white text-gray-900"></textarea>
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
                    <button id="closeViewBreakdownBtn" type="button" class="text-white hover:text-cyan-100 transition">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
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
                    </table>
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

    <!-- End of Main Content -->
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
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
    // No external cash-advance selection — form records manual liquidation entries
    const breakdownModal = document.getElementById('breakdownModal');
    const closeBreakdownBtn = document.getElementById('closeBreakdownBtn');
    const closeBreakdownFooterBtn = document.getElementById('closeBreakdownFooterBtn');
    const breakdownForm = document.getElementById('breakdownForm');
    const viewBreakdownModal = document.getElementById('viewBreakdownModal');
    const closeViewBreakdownBtn = document.getElementById('closeViewBreakdownBtn');
    const closeViewBreakdownFooterBtn = document.getElementById('closeViewBreakdownFooterBtn');
    const transactionDateFilter = document.getElementById('transactionDateFilter');
    const resetTransactionDateFilter = document.getElementById('resetTransactionDateFilter');
    const transactionsVisibleCount = document.getElementById('transactionsVisibleCount');
    const transactionsFilterEmptyRow = document.getElementById('transactionsFilterEmptyRow');
    const transactionsPaginationContainer = document.getElementById('transactionsPaginationContainer');
    const transactionsPrevPageBtn = document.getElementById('transactionsPrevPageBtn');
    const transactionsNextPageBtn = document.getElementById('transactionsNextPageBtn');
    const transactionsPageNumbers = document.getElementById('transactionsPageNumbers');
    const transactionsCurrentPageLabel = document.getElementById('transactionsCurrentPageLabel');
    const transactionsTotalPagesLabel = document.getElementById('transactionsTotalPagesLabel');
    const successModal = document.getElementById('successModal');
    const closeSuccessModalBtn = document.getElementById('closeSuccessModalBtn');
    const successModalMessage = document.getElementById('successModalMessage');
    const transactionsPerPage = 15;
    let transactionsCurrentPage = 1;
    let successModalTimer = null;
    let importPreviewRows = [];
    let importResultByRow = new Map();

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
            .filter(([key, index]) => key !== 'remarks' && typeof index === 'undefined')
            .map(([key]) => key.charAt(0).toUpperCase() + key.slice(1));

        if (missingColumns.length > 0) {
            throw new Error(`Missing column(s): ${missingColumns.join(', ')}.`);
        }

        return rows.slice(1)
            .map((row, index) => ({
                row_number: index + 2,
                date: formatImportDate(row[columns.date]),
                employee: String(row[columns.employee] ?? '').trim(),
                type: String(row[columns.type] ?? '').trim(),
                purpose: String(row[columns.purpose] ?? '').trim(),
                category: String(row[columns.category] ?? '').trim(),
                remarks: typeof columns.remarks === 'undefined' ? '' : String(row[columns.remarks] ?? '').trim(),
                amount: String(row[columns.amount] ?? '').trim(),
            }))
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

        try {
            setImportBusy(true);
            setImportStatus('Validating and saving rows...', 'amber');
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
                setImportStatus('Import completed. Refreshing transactions...', (data.failed_rows || 0) > 0 ? 'amber' : 'emerald');
                setTimeout(() => {
                    location.reload();
                }, 2500);
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
        window.location.href = `{{ route('accounting.liquidate-expenses') }}?year=${year}&month=${month}`;
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

    function getMonthDateValue(day) {
        return `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    }

    function formatTransactionFilterDate(dateValue) {
        return new Date(`${dateValue}T00:00:00`).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    function populateTransactionDateFilter() {
        if (!transactionDateFilter) {
            return;
        }

        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
        let options = '<option value="">All dates</option>';

        for (let day = 1; day <= daysInMonth; day += 1) {
            const dateValue = getMonthDateValue(day);
            options += `<option value="${dateValue}">${formatTransactionFilterDate(dateValue)}</option>`;
        }

        transactionDateFilter.innerHTML = options;
    }

    function renderTransactionsPagination(totalRows) {
        if (!transactionsPaginationContainer || !transactionsPageNumbers) {
            return;
        }

        const totalPages = Math.max(1, Math.ceil(totalRows / transactionsPerPage));
        transactionsPaginationContainer.classList.toggle('hidden', totalRows <= transactionsPerPage);

        if (transactionsCurrentPage > totalPages) {
            transactionsCurrentPage = totalPages;
        }

        if (transactionsCurrentPageLabel) {
            transactionsCurrentPageLabel.textContent = transactionsCurrentPage;
        }

        if (transactionsTotalPagesLabel) {
            transactionsTotalPagesLabel.textContent = totalPages;
        }

        if (transactionsPrevPageBtn) {
            transactionsPrevPageBtn.disabled = transactionsCurrentPage <= 1;
        }

        if (transactionsNextPageBtn) {
            transactionsNextPageBtn.disabled = transactionsCurrentPage >= totalPages;
        }

        transactionsPageNumbers.innerHTML = '';

        for (let page = 1; page <= totalPages; page += 1) {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = page;
            button.className = page === transactionsCurrentPage
                ? 'inline-flex h-10 min-w-10 items-center justify-center rounded-lg bg-teal-600 px-3 text-sm font-bold text-white shadow-sm'
                : 'inline-flex h-10 min-w-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50';
            button.addEventListener('click', () => {
                transactionsCurrentPage = page;
                applyTransactionDateFilter();
            });
            transactionsPageNumbers.appendChild(button);
        }
    }

    function applyTransactionDateFilter(options = {}) {
        if (options.resetPage) {
            transactionsCurrentPage = 1;
        }

        const selectedDate = transactionDateFilter?.value || '';
        const rows = Array.from(document.querySelectorAll('.transaction-row'));
        const filteredRows = rows.filter(row => !selectedDate || row.dataset.transactionDate === selectedDate);
        const totalPages = Math.max(1, Math.ceil(filteredRows.length / transactionsPerPage));

        if (transactionsCurrentPage > totalPages) {
            transactionsCurrentPage = totalPages;
        }

        const pageStart = (transactionsCurrentPage - 1) * transactionsPerPage;
        const pageRows = filteredRows.slice(pageStart, pageStart + transactionsPerPage);

        rows.forEach(row => {
            row.classList.add('hidden');
        });

        pageRows.forEach(row => {
            row.classList.remove('hidden');
        });

        if (transactionsVisibleCount) {
            transactionsVisibleCount.textContent = filteredRows.length;
        }

        if (transactionsFilterEmptyRow) {
            transactionsFilterEmptyRow.classList.toggle('hidden', filteredRows.length > 0 || rows.length === 0);
        }

        renderTransactionsPagination(filteredRows.length);
    }

    if (transactionDateFilter) {
        transactionDateFilter.addEventListener('change', () => applyTransactionDateFilter({ resetPage: true }));
    }

    if (resetTransactionDateFilter) {
        resetTransactionDateFilter.addEventListener('click', function() {
            transactionDateFilter.value = '';
            applyTransactionDateFilter({ resetPage: true });
        });
    }

    if (transactionsPrevPageBtn) {
        transactionsPrevPageBtn.addEventListener('click', function() {
            if (transactionsCurrentPage > 1) {
                transactionsCurrentPage -= 1;
                applyTransactionDateFilter();
            }
        });
    }

    if (transactionsNextPageBtn) {
        transactionsNextPageBtn.addEventListener('click', function() {
            transactionsCurrentPage += 1;
            applyTransactionDateFilter();
        });
    }

    function updateTransactionCategory(select) {
        const transactionId = select.dataset.id;
        const previousCategory = select.dataset.originalCategory || '';

        if (!transactionId || select.value === previousCategory) {
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
                category: select.value || null
            })
        })
        .then(response => response.json().then(data => ({ ok: response.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || !data.success) {
                throw new Error(data.message || 'Unable to update category');
            }

            select.dataset.originalCategory = data.transaction?.category || '';
        })
        .catch(error => {
            console.error('Error:', error);
            select.value = previousCategory;
            alert('An error occurred while updating the category');
        })
        .finally(() => {
            select.disabled = false;
            select.classList.remove('opacity-70');
        });
    }

    document.querySelectorAll('.transactionCategorySelect').forEach(select => {
        select.addEventListener('change', function() {
            updateTransactionCategory(this);
        });
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

    // Breakdown modal layout
    document.querySelectorAll('.breakdownBtn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('breakdownName').value = this.dataset.name || '';
            document.getElementById('breakdownDate').value = this.dataset.date || '';
            document.getElementById('breakdownEmployeeId').value = this.dataset.employeeId || '';
            document.getElementById('breakdownAmount').value = '';
            document.getElementById('breakdownDetails').value = '';
            document.getElementById('breakdownDescription').value = '';
            document.getElementById('breakdownCategory').value = '';

            breakdownModal.classList.remove('hidden');
            breakdownModal.style.display = 'flex';

            if (window.feather) {
                feather.replace();
            }
        });
    });

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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessModal('Breakdown saved successfully!');
                    closeBreakdownModal();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the breakdown');
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
                    showSuccessModal('Expense recorded successfully!');
                // Reload the page to refresh the table
                    setTimeout(() => {
                        location.reload();
                    }, 5000);
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

    document.querySelectorAll('.viewBreakdownBtn').forEach(button => {
        button.addEventListener('click', function() {
            const expenseId = this.getAttribute('data-id');

            fetch(`${viewBreakdownBaseUrl}/${expenseId}/breakdown`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Error: ' + data.message);
                    return;
                }

                document.getElementById('viewBreakdownName').value = data.debit.employee_name || '';
                document.getElementById('viewBreakdownDate').value = data.debit.expense_date || '';
                document.getElementById('viewBreakdownSubtitle').textContent = `Debit ID: ${data.debit.id}`;

                const tbody = document.getElementById('viewBreakdownTableBody');
                tbody.innerHTML = '';

                if (!data.breakdowns.length) {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No breakdown entries found</td></tr>';
                } else {
                    data.breakdowns.forEach(row => {
                        tbody.insertAdjacentHTML('beforeend', `
                            <tr class="border-b border-gray-200 last:border-b-0">
                                <td class="px-4 py-3 text-sm text-gray-900">${row.expense_date ?? '-'}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">${row.category_name ?? '-'}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">${row.transaction_details ?? '-'}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">${row.description ?? '-'}</td>
                                <td class="px-4 py-3 text-sm text-right font-semibold text-teal-700">PHP ${Number(row.amount || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            </tr>
                        `);
                    });
                }

                viewBreakdownModal.classList.remove('hidden');
                viewBreakdownModal.style.display = 'flex';

                if (window.feather) {
                    feather.replace();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while loading the breakdown');
            });
        });
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
    populateTransactionDateFilter();
    applyTransactionDateFilter();
    setDateToFirstOfMonth();

</script>
@endsection
