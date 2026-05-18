@extends('layouts.admin')
@section('title', 'Cash Advance Liquidation')

@section('content')

<div class="space-y-8">

    <div id="liquidationToast"
         class="hidden fixed top-6 right-6 z-50 p-4 rounded-xl shadow-xl flex items-start gap-3 transition-opacity duration-500">
        <i id="liquidationToastIcon" data-feather="check-circle" class="w-5 h-5 mt-0.5"></i>
        <p id="liquidationToastText" class="text-sm font-medium">Notification</p>
    </div>

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
        <button onclick="openRequestAdvanceModal()"
                class="inline-flex items-center space-x-2 px-6 py-3
                       bg-gradient-to-r from-amber-500 to-orange-600
                       text-white font-semibold rounded-xl
                       hover:shadow-xl hover:scale-[1.02]
                       transition-all duration-300">
            <i data-feather="wallet" class="w-5 h-5"></i>
            <span>Request Cash Advance</span>
        </button>
    </div>

    <!-- CASH ADVANCE SUMMARY CARD -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column - Current Balance -->
        <div class="xl:col-span-2 rounded-3xl bg-white p-8 shadow-2xl border border-gray-100">
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Cash Advance Balance</h3>
                <p class="text-gray-500 mt-1">View and manage your current cash advance status</p>
            </div>

            <div class="space-y-6">
                <!-- Period Selection -->
                <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-xl border border-gray-200">
                    <button id="prevPeriodBtn" onclick="prevPeriod()" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-600 hover:bg-gray-200 transition">
                        <i data-feather="chevron-left" class="w-4 h-4"></i>
                    </button>

                    <select id="liquidationFilter" onchange="applyLiquidationFilter()" class="bg-transparent text-gray-800 text-sm font-semibold border-none outline-none cursor-pointer">
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>

                    <button id="nextPeriodBtn" onclick="nextPeriod()" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-600 hover:bg-gray-200 transition">
                        <i data-feather="chevron-right" class="w-4 h-4"></i>
                    </button>

                    <div class="flex-1 flex items-center gap-2 text-gray-600 justify-end">
                        <i data-feather="calendar" class="w-4 h-4"></i>
                        <span id="liquidationPeriodLabel" class="text-sm font-semibold">Loading...</span>
                    </div>
                </div>

                <!-- Balance Information -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Current Balance</p>
                        <p id="currentBalanceAmount" class="text-4xl font-bold text-gray-900">₱0.00</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Purpose</p>
                        <p id="currentBalancePurpose" class="text-lg font-semibold text-gray-800">No approved request yet</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Summary Cards -->
        <div class="rounded-3xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white p-7 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-40 h-40 bg-blue-300/20 rounded-full blur-3xl"></div>
            <div class="relative z-10 space-y-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-blue-200">Liquidation Status</p>
                    <p id="budgetMonthLabel" class="text-xs text-blue-100 mt-2">Expense Summary</p>
                    <p id="summaryExpendedAmount" class="text-4xl font-extrabold mt-2">₱0.00</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-white/10 backdrop-blur-sm p-4">
                        <p class="text-xs text-gray-200">Total Expended</p>
                        <p id="totalExpensesDisplay" class="text-xl font-bold mt-1 text-red-300">₱0.00</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur-sm p-4">
                        <p class="text-xs text-gray-200">Remaining</p>
                        <p id="summaryRemainingAmount" class="text-xl font-bold mt-1 text-green-300">₱0.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="flex items-center justify-between">
        <h3 class="text-2xl font-bold text-gray-800">Expense Transactions</h3>
        <div class="flex items-center space-x-3">
            <button onclick="printLiquidationSummary()"
                    class="inline-flex items-center space-x-2 px-6 py-3
                           bg-gradient-to-r from-slate-600 to-slate-700
                           text-white font-semibold rounded-xl
                           hover:shadow-xl hover:scale-[1.02]
                           transition-all duration-300">
                <i data-feather="printer" class="w-5 h-5"></i>
                <span>Print</span>
            </button>

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
    </div>

    <div class="rounded-3xl bg-white p-8 shadow-2xl border border-gray-100">
        <div class="relative mb-5 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-800">My Cash Advance Requests</h3>
                <span class="text-sm font-semibold text-gray-500">Today's history</span>
            </div>

            <div class="relative" id="cashAdvanceNotificationWrapper">
                <button id="cashAdvanceNotificationBtn"
                        type="button"
                        class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-5 py-2.5 text-sm font-bold text-emerald-700 shadow-sm transition hover:border-emerald-300 hover:bg-emerald-100"
                        title="Cash advance notifications">
                    <i data-feather="inbox" class="w-4 h-4"></i>
                    <span id="cashAdvanceNotificationLabel">Cash Advance Notifications</span>
                </button>

                <div id="cashAdvanceNotificationPanel"
                     class="hidden absolute right-0 top-14 z-40 w-[min(24rem,calc(100vw-3rem))] overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl">
                    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
                        <div>
                            <p class="text-sm font-bold text-gray-900">Cash Advance Notifications</p>
                            <p class="text-xs font-medium text-gray-500">All recent request updates</p>
                        </div>
                    </div>

                    <div id="cashAdvanceNotifications" class="max-h-96 space-y-3 overflow-y-auto p-4"></div>
                    <p id="cashAdvanceNotificationsEmpty" class="hidden px-4 py-8 text-center text-sm text-gray-500">
                        No recent cash advance notifications yet.
                    </p>
                </div>
            </div>
        </div>

        <div class="mb-5 flex flex-wrap items-center gap-2">
            <button type="button"
                    class="request-status-filter-btn rounded-xl border px-4 py-2 text-sm font-semibold transition"
                    data-request-status-filter="all">
                All
            </button>
            <button type="button"
                    class="request-status-filter-btn rounded-xl border px-4 py-2 text-sm font-semibold transition"
                    data-request-status-filter="approved">
                Approved
            </button>
            <button type="button"
                    class="request-status-filter-btn rounded-xl border px-4 py-2 text-sm font-semibold transition"
                    data-request-status-filter="pending">
                Pending
            </button>
            <button type="button"
                    class="request-status-filter-btn rounded-xl border px-4 py-2 text-sm font-semibold transition"
                    data-request-status-filter="rejected">
                Rejected
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-3 text-sm font-semibold text-gray-600">Request Date</th>
                        <th class="text-left py-3 px-3 text-sm font-semibold text-gray-600">Date Approved</th>
                        <th class="text-left py-3 px-3 text-sm font-semibold text-gray-600">Purpose</th>
                        <th class="text-right py-3 px-3 text-sm font-semibold text-gray-600">Amount</th>
                        <th class="text-left py-3 px-3 text-sm font-semibold text-gray-600">Approved/Sent By</th>
                        <th class="text-center py-3 px-3 text-sm font-semibold text-gray-600">Status</th>
                        <th class="text-left py-3 px-3 text-sm font-semibold text-gray-600">Accounting Remarks</th>
                    </tr>
                </thead>
                <tbody id="employeeRequestsTableBody"></tbody>
            </table>
        </div>

        <p id="employeeRequestsEmpty" class="hidden text-center text-sm text-gray-500 py-8">
            No cash advance requests submitted today.
        </p>
    </div>

    <!-- EXPENSES TABLE -->
    <div class="relative overflow-hidden rounded-3xl bg-white p-8 shadow-2xl">
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700">Date</th>
                        <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700">
                            <div class="flex items-center gap-2">
                                <span>Category</span>
                                <div class="relative" id="categoryFilterWrapper">
                                    <button id="categoryFilterBtn"
                                            onclick="toggleCategoryFilter(event)"
                                            class="flex items-center justify-center w-6 h-6 rounded-md
                                                   text-gray-400 hover:text-[#1C446D] hover:bg-gray-100
                                                   transition-all duration-200"
                                            title="Filter by category">
                                        <i data-feather="filter" class="w-3.5 h-3.5" id="categoryFilterIcon"></i>
                                    </button>

                                    <!-- Dropdown -->
                                    <div id="categoryFilterDropdown"
                                         class="hidden absolute left-0 top-8 z-50 w-56 bg-white
                                                rounded-2xl shadow-2xl border border-gray-100
                                                py-2 font-normal">
                                        <!-- Header row -->
                                        <div class="flex items-center justify-between px-4 py-2 border-b border-gray-100">
                                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Filter</span>
                                            <button onclick="clearCategoryFilter()"
                                                    class="text-xs text-[#1C446D] hover:underline font-medium">
                                                Clear
                                            </button>
                                        </div>
                                        <!-- Options -->
                                        <div class="max-h-60 overflow-y-auto py-1" id="categoryFilterOptions">
                                            @foreach($particulars as $id => $name)
                                            <label class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 cursor-pointer transition">
                                                <input type="checkbox"
                                                       value="{{ $name }}"
                                                       onchange="applyCategoryFilter()"
                                                       class="category-filter-checkbox w-4 h-4 rounded
                                                              accent-[#1C446D] cursor-pointer">
                                                <span class="text-sm text-gray-700">{{ $name }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </th>
                        <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700">Particulars</th>
                        <th class="text-right py-4 px-4 text-sm font-semibold text-gray-700">Amount</th>
                        <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="expensesTableBody">
                    <tr id="emptyExpenseRow" class="border-b border-gray-100">
                        <td colspan="5" class="py-8 px-4 text-sm text-center text-gray-500">
                            No expense entries yet. Add expense records once funds are released.
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-300">
                        <td colspan="2" class="py-4 px-4 text-right text-lg font-bold text-gray-800">Total Expenses:</td>
                        <td id="totalExpensesAmount" class="py-4 px-4 text-right text-xl font-bold text-red-600">₱0.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 flex justify-end">
            <button id="submitLiquidationReviewBtn"
                    type="button"
                    class="inline-flex items-center space-x-2 px-8 py-3
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

<!-- REQUEST CASH ADVANCE MODAL -->
<div id="requestAdvanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 transform transition-all">

        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 p-6 rounded-t-3xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">Request Cash Advance</h3>
                <button onclick="closeRequestAdvanceModal()" class="text-white hover:text-gray-200 transition">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="requestAdvanceForm" class="p-6 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Date of Request
                </label>
                <input type="date"
                       id="requestDate"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl
                              bg-gray-100 text-gray-700
                              focus:ring-2 focus:ring-amber-500 focus:border-transparent
                              transition-all duration-200"
                       readonly>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Employee Name
                </label>
                <input type="text"
                       id="employeeName"
                       value="{{ auth()->user()->name ?? '' }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl
                              bg-gray-100 text-gray-700
                              focus:ring-2 focus:ring-amber-500 focus:border-transparent
                              transition-all duration-200"
                       placeholder="Employee name"
                       readonly>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Purpose <span class="text-red-500">*</span>
                </label>
                <textarea id="requestPurpose"
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                 focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                 transition-all duration-200 resize-none"
                          placeholder="State the purpose of your cash advance request"
                          required></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Amount Requested <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-700 font-semibold">₱</span>
                    <input type="number"
                           id="requestAmount"
                           step="0.01"
                           min="0"
                           class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                  transition-all duration-200"
                           placeholder="0.00"
                           required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Supporting Attachments
                </label>
                <input type="file"
                       id="requestAttachments"
                       multiple
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl
                              file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                              file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700
                              hover:file:bg-amber-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent
                              transition-all duration-200">
                <p class="text-xs text-gray-500 mt-2">Optional: upload receipts, quotations, or references.</p>
            </div>

            <div class="flex items-center space-x-3 pt-2">
                <button type="button"
                        onclick="closeRequestAdvanceModal()"
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl
                               hover:bg-gray-300 transition-all duration-200">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600
                               text-white font-semibold rounded-xl
                               hover:shadow-xl hover:scale-[1.02]
                               transition-all duration-300">
                    Submit Request
                </button>
            </div>
        </form>
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
            @csrf
            
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

            <!-- Category Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select id="expenseCategory"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                   focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                   transition-all duration-200 appearance-none bg-white"
                            required>
                        <option value="" disabled selected>Select a category</option>
                        @foreach($particulars as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center">
                        <i data-feather="chevron-down" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Transaction Details Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Particulars <span class="text-red-500">*</span>
                </label>
                <textarea id="expenseDetails"
                          rows="2"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                 focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                 transition-all duration-200 resize-none"
                          placeholder="e.g., Hardware supplies - nails, screws, bolts"
                          required></textarea>
            </div>

            <!-- Description Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Description
                </label>
                <textarea id="expenseDescription"
                          rows="2"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl
                                 focus:ring-2 focus:ring-[#1C446D] focus:border-transparent
                                 transition-all duration-200 resize-none"
                          placeholder="Optional additional notes..."></textarea>
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
    const CASH_ADVANCE_MY_REQUESTS_ROUTE = @json(route('cash-advance.requests.my'));
    const CASH_ADVANCE_STORE_ROUTE = @json(route('cash-advance.requests.store'));
    const LIQUIDATION_SUBMIT_ROUTE = @json(route('admin.liquidation.submit'));
    const CASH_ADVANCE_CSRF = @json(csrf_token());
    let myCashAdvanceRequestsCache = [];
    let liquidationToastTimeout;
    let requestStatusFilter = 'all';
    let currentViewMode = 'month';
    let currentPeriodStart = startOfDay(getMonthStart(new Date()));
    let currentPeriodEnd = endOfDay(getMonthEnd(new Date()));
    let currentOpeningBalance = 0;

    const periodLabel = document.getElementById('liquidationPeriodLabel');
    const periodSubLabel = document.getElementById('liquidationPeriodSubLabel');
    const weekBreakdown = document.getElementById('liquidationWeekBreakdown');
    const prevPeriodBtn = document.getElementById('liquidationPrevPeriodBtn');
    const nextPeriodBtn = document.getElementById('liquidationNextPeriodBtn');
    const currentWeekBtn = document.getElementById('liquidationCurrentWeekBtn');
    const currentMonthBtn = document.getElementById('liquidationCurrentMonthBtn');
    const weekToggleBtn = document.getElementById('liquidationWeekToggle');
    const monthToggleBtn = document.getElementById('liquidationMonthToggle');
    const submitLiquidationReviewBtn = document.getElementById('submitLiquidationReviewBtn');

    async function fetchMyCashAdvanceRequests(startDate = null, endDate = null) {
        let url = CASH_ADVANCE_MY_REQUESTS_ROUTE;
        const params = [];
        if (startDate) params.push('start_date=' + encodeURIComponent(startDate));
        if (endDate) params.push('end_date=' + encodeURIComponent(endDate));
        if (params.length) url += '?' + params.join('&');

        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load cash advance requests.');
        }

        const payload = await response.json();
        myCashAdvanceRequestsCache = Array.isArray(payload?.requests) ? payload.requests : [];
        return myCashAdvanceRequestsCache;
    }

    function showLiquidationToast(message, type = 'success') {
        const toast = document.getElementById('liquidationToast');
        const toastText = document.getElementById('liquidationToastText');
        const toastIcon = document.getElementById('liquidationToastIcon');
        if (!toast || !toastText || !toastIcon) return;

        toastText.textContent = message;
        toast.classList.remove('hidden', 'bg-green-50', 'border-green-300', 'bg-red-50', 'border-red-300', 'border');
        toastText.classList.remove('text-green-800', 'text-red-800');
        toastIcon.classList.remove('text-green-600', 'text-red-600');

        if (type === 'error') {
            toast.classList.add('bg-red-50', 'border-red-300', 'border');
            toastText.classList.add('text-red-800');
            toastIcon.classList.add('text-red-600');
            toastIcon.setAttribute('data-feather', 'alert-circle');
        } else {
            toast.classList.add('bg-green-50', 'border-green-300', 'border');
            toastText.classList.add('text-green-800');
            toastIcon.classList.add('text-green-600');
            toastIcon.setAttribute('data-feather', 'check-circle');
        }

        toast.style.opacity = '1';
        feather.replace();

        clearTimeout(liquidationToastTimeout);
        liquidationToastTimeout = setTimeout(function() {
            toast.style.opacity = '0';
            setTimeout(function() {
                toast.classList.add('hidden');
                toast.style.opacity = '1';
            }, 500);
        }, 5000);
    }

    function formatCurrency(amount) {
        const numericAmount = Number(amount || 0);
        return `₱${numericAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        if (Number.isNaN(date.getTime())) return '-';
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function getRequestActivityDate(request) {
        return request.released_at || request.reviewed_at || request.submitted_at || request.request_date;
    }

    function isTodayDate(dateValue) {
        if (!dateValue) return false;
        return formatDateKey(parseDate(dateValue)) === formatDateKey(new Date());
    }

    function parseDate(value) {
        if (!value) {
            return new Date();
        }

        if (value instanceof Date) {
            return new Date(value);
        }

        const dateString = String(value);
        const isoDateMatch = dateString.match(/^(\d{4})-(\d{2})-(\d{2})/);

        if (isoDateMatch) {
            return new Date(Number(isoDateMatch[1]), Number(isoDateMatch[2]) - 1, Number(isoDateMatch[3]));
        }

        return new Date(dateString);
    }

    function startOfDay(date) {
        const result = new Date(date);
        result.setHours(0, 0, 0, 0);
        return result;
    }

    function endOfDay(date) {
        const result = new Date(date);
        result.setHours(23, 59, 59, 999);
        return result;
    }

    function addDays(date, days) {
        const result = new Date(date);
        result.setDate(result.getDate() + days);
        return result;
    }

    function addMonths(date, months) {
        const result = new Date(date);
        result.setMonth(result.getMonth() + months);
        return result;
    }

    function getMonthStart(date) {
        return new Date(date.getFullYear(), date.getMonth(), 1);
    }

    function getMonthEnd(date) {
        return new Date(date.getFullYear(), date.getMonth() + 1, 0);
    }

    function formatMonthLabel(date) {
        return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    }

    function formatShortDay(date) {
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    function formatDateKey(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function formatMonthKey(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        return `${year}-${month}`;
    }

    function formatPeriodLabel(start, end, viewMode) {
        if (viewMode === 'month') {
            return formatMonthLabel(start);
        }

        const yearPart = start.getFullYear() === end.getFullYear() ? `, ${start.getFullYear()}` : '';
        return `${formatShortDay(start)} - ${formatShortDay(end)}${yearPart}`;
    }

    function getMonthRanges(date) {
        const ranges = [];
        let rangeStart = startOfDay(getMonthStart(date));
        const monthEnd = endOfDay(getMonthEnd(date));

        while (rangeStart <= monthEnd) {
            const rangeEnd = endOfDay(new Date(Math.min(addDays(rangeStart, 6).getTime(), monthEnd.getTime())));
            ranges.push({ start: rangeStart, end: rangeEnd });
            rangeStart = startOfDay(addDays(rangeEnd, 1));
        }

        return ranges;
    }

    function getCurrentWeekRange() {
        const now = startOfDay(new Date());
        const mondayOffset = (now.getDay() + 6) % 7;
        const start = startOfDay(addDays(now, -mondayOffset));
        return { start, end: endOfDay(addDays(start, 6)) };
    }

    function isWithinRange(dateValue, start, end) {
        const target = startOfDay(parseDate(dateValue));
        return target >= startOfDay(start) && target <= endOfDay(end);
    }

    function applyRange(start, end, mode) {
        currentPeriodStart = startOfDay(start);
        currentPeriodEnd = endOfDay(end);
        currentViewMode = mode;
        renderLiquidationDashboard();
    }

    function setMonthView(date) {
        applyRange(getMonthStart(date), getMonthEnd(date), 'month');
    }

    function setWeekView(date = null) {
        if (!date) {
            const range = getCurrentWeekRange();
            applyRange(range.start, range.end, 'week');
            return;
        }

        const selected = startOfDay(date);
        const mondayOffset = (selected.getDay() + 6) % 7;
        const start = startOfDay(addDays(selected, -mondayOffset));
        applyRange(start, endOfDay(addDays(start, 6)), 'week');
    }

    function shiftPeriod(direction) {
        if (currentViewMode === 'month') {
            setMonthView(addMonths(currentPeriodStart, direction));
            return;
        }

        applyRange(addDays(currentPeriodStart, direction * 7), addDays(currentPeriodEnd, direction * 7), 'week');
    }

    function getExpenseRows() {
        return [...document.querySelectorAll('#expensesTableBody tr')]
            .filter(row => row.id !== 'emptyExpenseRow' && row.id !== 'filteredExpenseEmptyRow');
    }

    function getSelectedCategories() {
        return [...document.querySelectorAll('.category-filter-checkbox:checked')]
            .map(cb => cb.value.toLowerCase());
    }

    function getExpenseRowDate(row) {
        if (row.dataset.expenseDate) {
            return row.dataset.expenseDate;
        }

        const dateCell = row.querySelectorAll('td')[0];
        return dateCell ? dateCell.textContent.trim() : null;
    }

    function getExpenseRowAmount(row) {
        if (row.dataset.expenseAmount) {
            return Number(row.dataset.expenseAmount || 0);
        }

        const amountCell = row.querySelectorAll('td')[3];
        return Number((amountCell?.textContent || '').replace(/[^0-9.-]+/g, '')) || 0;
    }

    function getExpenseRowCategory(row) {
        return (row.dataset.expenseCategory || row.querySelectorAll('td')[1]?.textContent || '').trim().toLowerCase();
    }

    function isExpenseRowInCurrentPeriod(row) {
        const expenseDate = getExpenseRowDate(row);
        return expenseDate ? isWithinRange(expenseDate, currentPeriodStart, currentPeriodEnd) : false;
    }

    function updatePeriodToggles() {
        if (weekToggleBtn) {
            weekToggleBtn.classList.toggle('bg-white', currentViewMode === 'week');
            weekToggleBtn.classList.toggle('text-slate-900', currentViewMode === 'week');
            weekToggleBtn.classList.toggle('text-blue-100', currentViewMode !== 'week');
            weekToggleBtn.classList.toggle('shadow-lg', currentViewMode === 'week');
        }

        if (monthToggleBtn) {
            monthToggleBtn.classList.toggle('bg-white', currentViewMode === 'month');
            monthToggleBtn.classList.toggle('text-slate-900', currentViewMode === 'month');
            monthToggleBtn.classList.toggle('text-blue-100', currentViewMode !== 'month');
            monthToggleBtn.classList.toggle('shadow-lg', currentViewMode === 'month');
        }
    }

    function updatePeriodLabels() {
        if (periodLabel) {
            periodLabel.textContent = formatPeriodLabel(currentPeriodStart, currentPeriodEnd, currentViewMode);
        }

        if (periodSubLabel) {
            periodSubLabel.textContent = currentViewMode === 'month' ? 'Month view' : 'Week view';
        }
    }

    function renderWeekBreakdown() {
        if (!weekBreakdown) {
            return;
        }

        if (currentViewMode !== 'month') {
            weekBreakdown.innerHTML = '';
            return;
        }

        const rows = getExpenseRows();
        const ranges = getMonthRanges(currentPeriodStart);

        weekBreakdown.innerHTML = ranges.map(range => {
            const rangeCount = rows.filter(row => isWithinRange(getExpenseRowDate(row), range.start, range.end)).length;
            const label = `${formatShortDay(range.start)} - ${formatShortDay(range.end)}`;

            return `
                <button type="button"
                        class="liquidation-week-chip inline-flex min-w-[140px] flex-col rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-left text-sm font-semibold text-blue-100 transition hover:border-white/20 hover:bg-white/10"
                        data-range-start="${formatDateKey(range.start)}"
                        data-range-end="${formatDateKey(range.end)}">
                    <span>${label}</span>
                    <span class="text-xs font-medium text-blue-100/80">${rangeCount.toLocaleString('en-US')} expense${rangeCount === 1 ? '' : 's'}</span>
                </button>
            `;
        }).join('');

        document.querySelectorAll('.liquidation-week-chip').forEach(button => {
            button.addEventListener('click', () => {
                applyRange(parseDate(button.dataset.rangeStart), parseDate(button.dataset.rangeEnd), 'week');
            });
        });
    }

    function getStatusBadge(status) {
        const normalized = normalizeRequestStatus(status);

        if (normalized === 'approved') {
            return '<span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Approved</span>';
        }

        if (normalized === 'rejected') {
            return '<span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Rejected</span>';
        }

        return '<span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Pending</span>';
    }

    function getMyRequests() {
        return myCashAdvanceRequestsCache;
    }

    function normalizeRequestStatus(status) {
        return (status || 'pending').toLowerCase();
    }

    function getRequestStatusLabel(status) {
        const normalized = normalizeRequestStatus(status);
        return normalized.charAt(0).toUpperCase() + normalized.slice(1);
    }

    function updateRequestStatusFilterButtons() {
        document.querySelectorAll('.request-status-filter-btn').forEach(button => {
            const isActive = button.dataset.requestStatusFilter === requestStatusFilter;
            button.classList.toggle('border-[#1C446D]', isActive);
            button.classList.toggle('bg-[#1C446D]', isActive);
            button.classList.toggle('text-white', isActive);
            button.classList.toggle('border-gray-200', !isActive);
            button.classList.toggle('bg-white', !isActive);
            button.classList.toggle('text-gray-600', !isActive);
            button.classList.toggle('hover:border-[#1C446D]', !isActive);
            button.classList.toggle('hover:text-[#1C446D]', !isActive);
        });
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function renderCashAdvanceNotifications(myRequests) {
        const notifications = document.getElementById('cashAdvanceNotifications');
        const empty = document.getElementById('cashAdvanceNotificationsEmpty');
        if (!notifications || !empty) return;

        const recentRequests = [...myRequests]
            .sort((a, b) => parseDate(getRequestActivityDate(b)) - parseDate(getRequestActivityDate(a)));

        if (recentRequests.length === 0) {
            notifications.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }

        empty.classList.add('hidden');
        notifications.innerHTML = recentRequests.map(request => {
            const status = normalizeRequestStatus(request.status);
            const actedBy = request.reviewer_name ? escapeHtml(request.reviewer_name) : 'Accounting Staff';
            const purpose = request.purpose ? escapeHtml(request.purpose) : '-';
            const remarks = request.accounting_remarks ? escapeHtml(request.accounting_remarks) : 'No notes provided';
            const amount = formatCurrency(request.approved_amount || request.requested_amount || 0);
            const activityDate = formatDate(getRequestActivityDate(request));
            const statusStyles = status === 'approved'
                ? 'border-emerald-200 bg-emerald-50 text-emerald-900'
                : status === 'rejected'
                    ? 'border-red-200 bg-red-50 text-red-900'
                    : 'border-amber-200 bg-amber-50 text-amber-900';

            return `
                <div class="rounded-xl border p-3 ${statusStyles}">
                    <p class="text-sm font-semibold">
                        ${amount} request is ${escapeHtml(getRequestStatusLabel(status))} as of ${activityDate}
                    </p>
                    <p class="text-sm mt-1">Purpose: ${purpose}</p>
                    <p class="text-sm mt-1">Updated by: ${actedBy}</p>
                    <p class="text-sm mt-1">Notes: ${remarks}</p>
                </div>
            `;
        }).join('');
    }

    function renderEmployeeRequests() {
        const myRequests = getMyRequests();
        const tbody = document.getElementById('employeeRequestsTableBody');
        const emptyState = document.getElementById('employeeRequestsEmpty');

        if (!tbody || !emptyState) return;

        updateRequestStatusFilterButtons();

        const todaysRequests = myRequests.filter(request => isTodayDate(request.request_date));
        const filteredRequests = requestStatusFilter === 'all'
            ? todaysRequests
            : todaysRequests.filter(request => normalizeRequestStatus(request.status) === requestStatusFilter);

        if (filteredRequests.length === 0) {
            tbody.innerHTML = '';
            emptyState.textContent = requestStatusFilter === 'all'
                ? 'No cash advance requests submitted today.'
                : `No ${requestStatusFilter} cash advance requests submitted today.`;
            emptyState.classList.remove('hidden');
            renderCashAdvanceNotifications(myRequests);
            renderApprovedSummary(myRequests);
            return;
        }

        emptyState.classList.add('hidden');
        renderCashAdvanceNotifications(myRequests);
        tbody.innerHTML = filteredRequests.map(request => `
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="py-3 px-3 text-sm text-gray-700">${formatDate(request.request_date)}</td>
                <td class="py-3 px-3 text-sm text-gray-700">${formatDate(request.reviewed_at)}</td>
                <td class="py-3 px-3 text-sm text-gray-700">${escapeHtml(request.purpose || '-')}</td>
                <td class="py-3 px-3 text-sm text-right font-semibold text-gray-900">${formatCurrency(request.approved_amount || request.requested_amount || 0)}</td>
                <td class="py-3 px-3 text-sm text-gray-700">${escapeHtml(request.reviewer_name || '-')}</td>
                <td class="py-3 px-3 text-sm text-center">${getStatusBadge(request.status)}</td>
                <td class="py-3 px-3 text-sm text-gray-600">${escapeHtml(request.accounting_remarks || '-')}</td>
            </tr>
        `).join('');

        renderApprovedSummary(myRequests);
    }

    function getRequestPeriodDate(request) {
        return request.released_at || request.reviewed_at || request.request_date;
    }

    function renderApprovedSummary(myRequests) {
        const approvedRequests = myRequests
            .filter(request => (request.status || '').toLowerCase() === 'approved')
            .filter(request => isWithinRange(getRequestPeriodDate(request), currentPeriodStart, currentPeriodEnd))
            .sort((a, b) => parseDate(getRequestPeriodDate(b)) - parseDate(getRequestPeriodDate(a)));

        const currentBalanceAmount = document.getElementById('currentBalanceAmount');
        const currentBalancePurpose = document.getElementById('currentBalancePurpose');
        const liquidationPeriodLabel = document.getElementById('liquidationPeriodLabel');

        if (!currentBalanceAmount || !currentBalancePurpose) return;

        if (approvedRequests.length === 0) {
            currentBalanceAmount.textContent = '₱0.00';
            currentBalancePurpose.textContent = 'No approved request yet';
            if (liquidationPeriodLabel) {
                liquidationPeriodLabel.textContent = 'Current cycle';
            }
            renderExpenseSummary(0);
            return;
        }

        const approvedAmount = approvedRequests.reduce((total, request) => {
            return total + Number(request.approved_amount || request.requested_amount || 0);
        }, 0);
        const latestApproved = approvedRequests[0];
        const approvedAmount = Number(latestApproved.approved_amount || latestApproved.requested_amount || 0);
        currentBalanceAmount.textContent = formatCurrency(approvedAmount);
        currentBalancePurpose.textContent = latestApproved.purpose || 'Approved cash advance';

        if (liquidationPeriodLabel) {
            const fromDate = formatDate(latestApproved.request_date);
            const toDate = formatDate(latestApproved.sent_date || latestApproved.reviewed_at);
            liquidationPeriodLabel.textContent = `${fromDate} - ${toDate}`;
        }

        renderExpenseSummary(approvedAmount);
    }

    function getCurrentExpenseTotal() {
        return getExpenseRows().reduce((total, row) => {
            if (!isExpenseRowInCurrentPeriod(row)) {
                return total;
            }

            // Skip hidden rows (filtered out by date range)
            if (row.style.display === 'none') {
                return;
            }

            const amountCell = row.querySelectorAll('td')[3];
            if (!amountCell) {
                return;
            }

            const parsedAmount = Number((amountCell.textContent || '').replace(/[^0-9.-]+/g, ''));
            if (!Number.isNaN(parsedAmount)) {
                total += parsedAmount;
            }
        });

        return total;
    }

    function renderExpenseSummary(openingBalance = null) {
        const totalExpensesAmount = document.getElementById('totalExpensesAmount');
        const summaryExpendedAmount = document.getElementById('summaryExpendedAmount');
        const totalExpensesDisplay = document.getElementById('totalExpensesDisplay');
        const summaryRemainingAmount = document.getElementById('summaryRemainingAmount');
        const currentBalanceAmount = document.getElementById('currentBalanceAmount');

        if (!summaryExpendedAmount || !summaryRemainingAmount || !currentBalanceAmount) {
            return;
        }

        const expenseTotal = getCurrentExpenseTotal();
        const resolvedOpeningBalance = openingBalance === null
            ? Number((currentBalanceAmount.textContent || '').replace(/[^0-9.-]+/g, '')) || 0
            : Number(openingBalance);
        const remaining = resolvedOpeningBalance - expenseTotal;

        if (totalExpensesAmount) {
            totalExpensesAmount.textContent = formatCurrency(expenseTotal);
        }
        summaryExpendedAmount.textContent = formatCurrency(expenseTotal);
        if (totalExpensesDisplay) {
            totalExpensesDisplay.textContent = formatCurrency(expenseTotal);
        }
        summaryRemainingAmount.textContent = formatCurrency(remaining);
    }

    // Week and Month navigation helpers
    let weekOffset = 0;
    let monthOffset = 0;
    let currentPeriodType = 'week'; // 'week' or 'month'

    function getWeekRange(offset) {
        const base = new Date();
        base.setDate(base.getDate() + (offset * 7));
        const dayIndex = (base.getDay() + 6) % 7; // Monday=0
        const monday = new Date(base);
        monday.setDate(base.getDate() - dayIndex);
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        return {
            start: monday.toISOString().split('T')[0],
            end: sunday.toISOString().split('T')[0],
            startObj: monday,
            endObj: sunday,
            label: `${formatDate(monday)} - ${formatDate(sunday)}`
        };
    }

    function getMonthRange(offset) {
        const today = new Date();
        const targetMonth = new Date(today.getFullYear(), today.getMonth() + offset, 1);
        const firstDay = new Date(targetMonth.getFullYear(), targetMonth.getMonth(), 1);
        const lastDay = new Date(targetMonth.getFullYear(), targetMonth.getMonth() + 1, 0);
        return {
            start: firstDay.toISOString().split('T')[0],
            end: lastDay.toISOString().split('T')[0],
            startObj: firstDay,
            endObj: lastDay,
            label: firstDay.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
        };
    }

    function updatePeriodLabel() {
        const label = document.getElementById('liquidationPeriodLabel');
        let range;

        if (currentPeriodType === 'week') {
            range = getWeekRange(weekOffset);
        } else {
            range = getMonthRange(monthOffset);
        }

        if (label) {
            label.textContent = range.label;
        }

        filterExpensesTable(range.start, range.end);
    }

    function prevPeriod() {
        if (currentPeriodType === 'week') {
            weekOffset -= 1;
        } else {
            monthOffset -= 1;
        }
        updatePeriodLabel();
    }

    function nextPeriod() {
        if (currentPeriodType === 'week') {
            weekOffset += 1;
        } else {
            monthOffset += 1;
        }
        updatePeriodLabel();
    }

    function applyLiquidationFilter() {
        const sel = document.getElementById('liquidationFilter');
        const value = sel ? sel.value : 'week';

        currentPeriodType = value;
        weekOffset = 0;
        monthOffset = 0;

        updatePeriodLabel();
    }

    function filterExpensesTable(startDate, endDate) {
        const rows = document.querySelectorAll('#expensesTableBody tr');
        if (!startDate && !endDate) {
            rows.forEach(r => r.style.display = '');
            updateWeekAndMonthTotals();
            return;
        }
        const s = startDate ? new Date(startDate) : null;
        const e = endDate ? new Date(endDate) : null;

        rows.forEach(row => {
            if (row.id === 'emptyExpenseRow') return;
            const dateCell = row.querySelectorAll('td')[0];
            const txt = dateCell ? dateCell.textContent.trim() : '';
            const rowDate = new Date(txt);
            if (Number.isNaN(rowDate.getTime())) {
                row.style.display = '';
                return;
            }
            let show = true;
            if (s && rowDate < s) show = false;
            if (e && rowDate > new Date(e.getFullYear(), e.getMonth(), e.getDate(), 23,59,59)) show = false;
            row.style.display = show ? '' : 'none';
        });

        updateWeekAndMonthTotals();
    }

    function getThisWeekTotal() {
        const today = new Date();
        const dayIndex = (today.getDay() + 6) % 7; // Monday=0
        const monday = new Date(today);
        monday.setDate(today.getDate() - dayIndex);
        
        const rows = document.querySelectorAll('#expensesTableBody tr');
        let total = 0;

        rows.forEach(row => {
            if (row.id === 'emptyExpenseRow') return;
            if (row.style.display === 'none') return;

            const dateCell = row.querySelectorAll('td')[0];
            const amountCell = row.querySelectorAll('td')[3];
            
            if (!dateCell || !amountCell) return;

            const rowDate = new Date(dateCell.textContent.trim());
            if (Number.isNaN(rowDate.getTime())) return;

            // Check if date is in this week (Monday to Sunday)
            if (rowDate >= monday && rowDate <= new Date(monday.getTime() + 6.5 * 24 * 60 * 60 * 1000)) {
                const parsedAmount = Number((amountCell.textContent || '').replace(/[^0-9.-]+/g, ''));
                if (!Number.isNaN(parsedAmount)) {
                    total += parsedAmount;
                }
            }
        });

        return total;
    }

    function getThisMonthTotal() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        const rows = document.querySelectorAll('#expensesTableBody tr');
        let total = 0;

        rows.forEach(row => {
            if (row.id === 'emptyExpenseRow') return;
            if (row.style.display === 'none') return;

            const dateCell = row.querySelectorAll('td')[0];
            const amountCell = row.querySelectorAll('td')[3];
            
            if (!dateCell || !amountCell) return;

            const rowDate = new Date(dateCell.textContent.trim());
            if (Number.isNaN(rowDate.getTime())) return;

            // Check if date is in this month
            if (rowDate >= firstDay && rowDate <= lastDay) {
                const parsedAmount = Number((amountCell.textContent || '').replace(/[^0-9.-]+/g, ''));
                if (!Number.isNaN(parsedAmount)) {
                    total += parsedAmount;
                }
            }
        });

        return total;
    }

    function updateWeekAndMonthTotals() {
        // Currently not displayed in the new design, but kept for reference
    }

    // Modal Functions
    function openRequestAdvanceModal() {
        const modal = document.getElementById('requestAdvanceModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        const today = new Date().toISOString().split('T')[0];
        document.getElementById('requestDate').value = today;

        setTimeout(() => feather.replace(), 10);
    }

    function closeRequestAdvanceModal() {
        const modal = document.getElementById('requestAdvanceModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.getElementById('requestAdvanceForm').reset();
    }

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

    function printLiquidationSummary() {
        window.print();
    }

    function closeAddExpenseModal() {
        const modal = document.getElementById('addExpenseModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Reset form
        document.getElementById('addExpenseForm').reset();
    }

    // Handle form submission
    document.getElementById('addExpenseForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const date        = document.getElementById('expenseDate').value;
        const particularSelect = document.getElementById('expenseCategory');
        const particularId = particularSelect.value;
        const particularName = particularSelect.options[particularSelect.selectedIndex]?.text || '';
        const details     = document.getElementById('expenseDetails').value;
        const description = document.getElementById('expenseDescription').value;
        const amount      = parseFloat(document.getElementById('expenseAmount').value);

        const formData = new FormData();
        formData.append('_token', document.querySelector('#addExpenseForm input[name="_token"]').value);
        formData.append('expense_date', date);
        formData.append('particular_id', particularId);
        formData.append('transaction_details', details);
        formData.append('description', description);
        formData.append('amount', amount);

        let savedExpense;
        try {
            const response = await fetch('{{ route('admin.liquidation.expenses.store') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const result = await response.json();
            if (!response.ok) {
                const errorMessage = result?.message || 'Failed to save expense.';
                showLiquidationToast(errorMessage, 'error');
                return;
            }

            savedExpense = result.expense;
        } catch (error) {
            showLiquidationToast('Failed to save expense. Please try again.', 'error');
            return;
        }
        
        // Format date
        const dateObj = new Date(savedExpense?.expense_date || date);
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
        const emptyExpenseRow = document.getElementById('emptyExpenseRow');
        if (emptyExpenseRow) {
            emptyExpenseRow.remove();
        }

        const newRow = document.createElement('tr');
        newRow.className = 'border-b border-gray-100 hover:bg-gray-50 transition';
        if (savedExpense?.id) {
            newRow.dataset.expenseId = savedExpense.id;
        }
        newRow.dataset.expenseDate = savedExpense?.expense_date || date;
        newRow.dataset.expenseAmount = String(savedExpense?.amount || amount);
        newRow.dataset.expenseCategory = savedExpense?.category_name || categoryName;
        newRow.innerHTML = `
            <td class="py-4 px-4 text-sm text-gray-700">${formattedDate}</td>
            <td class="py-4 px-4 text-sm text-gray-800 font-medium">${savedExpense?.category_name || categoryName}</td>
            <td class="py-4 px-4 text-sm text-gray-800 font-medium">${details}${description ? '<br><span class="text-xs text-gray-400">' + description + '</span>' : ''}</td>
            <td class="py-4 px-4 text-sm text-right font-semibold text-red-600">₱${formattedAmount}</td>
            <td class="py-4 px-4 text-center">
                <button onclick="deleteExpense(this)" class="text-red-500 hover:text-red-700 transition">
                    <i data-feather="trash-2" class="w-4 h-4"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(newRow);
        renderLiquidationDashboard();
        
        // Refresh feather icons
        feather.replace();
        
        // Close modal
        closeAddExpenseModal();
        
        showLiquidationToast('Expense added successfully!', 'success');
    });

    document.getElementById('requestAdvanceForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        const purpose = document.getElementById('requestPurpose').value.trim();
        const amount = parseFloat(document.getElementById('requestAmount').value);
        const dateNeeded = document.getElementById('requestDate').value;

        if (!purpose) {
            showLiquidationToast('Please enter a purpose for your request.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Request';
            return;
        }

        if (!amount || amount <= 0) {
            showLiquidationToast('Please enter a valid requested amount.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Request';
            return;
        }

        try {
            const response = await fetch(CASH_ADVANCE_STORE_ROUTE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CASH_ADVANCE_CSRF,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    purpose,
                    requested_amount: amount,
                    date_needed: dateNeeded,
                })
            });

            const payload = await response.json().catch(() => ({}));
            if (!response.ok) {
                showLiquidationToast(payload?.message || 'Failed to submit request.', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Request';
                return;
            }

            await fetchMyCashAdvanceRequests();
            renderEmployeeRequests();
            closeRequestAdvanceModal();
            showLiquidationToast(payload?.message || 'Cash advance request submitted successfully!', 'success');
        } catch (error) {
            console.error('Request error:', error);
            showLiquidationToast('Failed to submit request. Please try again.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Request';
        }
    });

    // Delete expense function
    async function deleteExpense(button) {
        if (confirm('Are you sure you want to delete this expense?')) {
            const row = button.closest('tr');
            const expenseId = row?.dataset?.expenseId;

            if (expenseId) {
                try {
                    const csrfToken = document.querySelector('#addExpenseForm input[name="_token"]').value;
                    const response = await fetch(`{{ url('/admin/liquidation/expenses') }}/${expenseId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        const result = await response.json().catch(() => ({}));
                        showLiquidationToast(result?.message || 'Failed to delete expense.', 'error');
                        return;
                    }
                } catch (error) {
                    showLiquidationToast('Failed to delete expense. Please try again.', 'error');
                    return;
                }
            }

            row.remove();

            const tbody = document.getElementById('expensesTableBody');
            const hasExpenseRows = getExpenseRows().length > 0;
            if (!hasExpenseRows) {
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'emptyExpenseRow';
                emptyRow.className = 'border-b border-gray-100';
                emptyRow.innerHTML = '<td colspan="5" class="py-8 px-4 text-sm text-center text-gray-500">No expense entries yet. Add expense records once funds are released.</td>';
                tbody.appendChild(emptyRow);
            }

            renderLiquidationDashboard();
            showLiquidationToast('Expense deleted successfully.', 'success');
        }
    }

    // Close modal when clicking outside
    document.getElementById('addExpenseModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddExpenseModal();
        }
    });

    document.getElementById('requestAdvanceModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRequestAdvanceModal();
        }
    });

    // Category filter
    function toggleCategoryFilter(e) {
        if (e) {
            e.stopPropagation();
        }
        const dropdown = document.getElementById('categoryFilterDropdown');
        dropdown.classList.toggle('hidden');
    }

    function applyCategoryFilter() {
        renderExpensesForCurrentFilters();
    }

    function clearCategoryFilter() {
        document.querySelectorAll('.category-filter-checkbox').forEach(cb => {
            cb.checked = false;
        });
        applyCategoryFilter();
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('categoryFilterWrapper');
        const dropdown = document.getElementById('categoryFilterDropdown');
        if (wrapper && dropdown && !wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    document.querySelectorAll('.request-status-filter-btn').forEach(button => {
        button.addEventListener('click', () => {
            requestStatusFilter = button.dataset.requestStatusFilter || 'all';
            renderEmployeeRequests();
        });
    });

    const cashAdvanceNotificationWrapper = document.getElementById('cashAdvanceNotificationWrapper');
    const cashAdvanceNotificationBtn = document.getElementById('cashAdvanceNotificationBtn');
    const cashAdvanceNotificationPanel = document.getElementById('cashAdvanceNotificationPanel');

    if (cashAdvanceNotificationBtn && cashAdvanceNotificationPanel) {
        cashAdvanceNotificationBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            cashAdvanceNotificationPanel.classList.toggle('hidden');
        });
    }

    document.addEventListener('click', function(event) {
        if (
            cashAdvanceNotificationWrapper &&
            cashAdvanceNotificationPanel &&
            !cashAdvanceNotificationWrapper.contains(event.target)
        ) {
            cashAdvanceNotificationPanel.classList.add('hidden');
        }
    });

    if (prevPeriodBtn) {
        prevPeriodBtn.addEventListener('click', () => shiftPeriod(-1));
    }

    if (nextPeriodBtn) {
        nextPeriodBtn.addEventListener('click', () => shiftPeriod(1));
    }

    if (currentWeekBtn) {
        currentWeekBtn.addEventListener('click', () => setWeekView());
    }

    if (currentMonthBtn) {
        currentMonthBtn.addEventListener('click', () => setMonthView(new Date()));
    }

    if (weekToggleBtn) {
        weekToggleBtn.addEventListener('click', () => setWeekView(currentPeriodStart));
    }

    if (monthToggleBtn) {
        monthToggleBtn.addEventListener('click', () => setMonthView(currentPeriodStart));
    }

    if (submitLiquidationReviewBtn) {
        submitLiquidationReviewBtn.addEventListener('click', submitLiquidationForReview);
    }

    // ===============================================
    // REAL-TIME UPDATES (Polling)
    // ===============================================
    let pollingInterval = null;
    let isPolling = false;
    let pollCheckInProgress = false;

    function createRequestMap(requests) {
        const map = {};
        requests.forEach(req => {
            map[req.id] = req;
        });
        return map;
    }

    async function checkForCashAdvanceUpdates() {
        if (pollCheckInProgress) return;
        pollCheckInProgress = true;

        try {
            const response = await fetch(CASH_ADVANCE_MY_REQUESTS_ROUTE, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                pollCheckInProgress = false;
                return;
            }

            const payload = await response.json();
            const newRequests = Array.isArray(payload?.requests) ? payload.requests : [];
            const oldRequests = myCashAdvanceRequestsCache;

            if (newRequests.length === oldRequests.length && newRequests.length === 0) {
                pollCheckInProgress = false;
                return;
            }

            const newMap = createRequestMap(newRequests);
            const oldMap = createRequestMap(oldRequests);
            let hasChanges = false;
            let changeMessage = '';

            if (newRequests.length > oldRequests.length) {
                changeMessage = '📬 New cash advance request received!';
                hasChanges = true;
            } else if (newRequests.length < oldRequests.length) {
                changeMessage = '🗑️ A request was removed';
                hasChanges = true;
            } else {
                for (const id in newMap) {
                    const newReq = newMap[id];
                    const oldReq = oldMap[id];

                    if (oldReq) {
                        if (newReq.status !== oldReq.status) {
                            changeMessage = `💬 Request status updated to: ${newReq.status}`;
                            hasChanges = true;
                            break;
                        }

                        if (newReq.approved_amount !== oldReq.approved_amount) {
                            changeMessage = '✅ Request amount was updated!';
                            hasChanges = true;
                            break;
                        }

                        if (newReq.accounting_remarks !== oldReq.accounting_remarks) {
                            changeMessage = '📝 Accounting added new remarks!';
                            hasChanges = true;
                            break;
                        }

                        if (newReq.reviewed_at !== oldReq.reviewed_at) {
                            changeMessage = '⏰ Request review date updated!';
                            hasChanges = true;
                            break;
                        }
                    }
                }
            }

            if (hasChanges) {
                showLiquidationToast(changeMessage, 'success');
                myCashAdvanceRequestsCache = newRequests;
                renderEmployeeRequests();
            }

        } catch (error) {
            console.error('Polling error:', error);
        } finally {
            pollCheckInProgress = false;
        }
    }

    function startPolling() {
        if (isPolling) return;
        isPolling = true;
        pollingInterval = setInterval(checkForCashAdvanceUpdates, 5000);
    }

    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            isPolling = false;
        }
    }

    // Initialize and start polling
    async function initializeMyCashAdvanceRequests() {
        try {
            await fetchMyCashAdvanceRequests();
            renderEmployeeRequests();
            renderLiquidationDashboard();
            startPolling();
        } catch (error) {
            showLiquidationToast('Unable to load cash advance requests.', 'error');
        }
    }

    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeMyCashAdvanceRequests);
    } else {
        initializeMyCashAdvanceRequests();
    }

    initializeMyCashAdvanceRequests();
    updatePeriodLabel();
    renderExpenseSummary();
</script>
@endpush
