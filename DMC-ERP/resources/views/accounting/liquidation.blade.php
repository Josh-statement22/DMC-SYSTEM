@extends('layouts.accounting')
@section('title', 'Accounting - Liquidation Tracking')

@section('content')
@php
    $selectedEmployeeData = $selectedEmployee ?? null;
    $selectedMonthKey = request('month', now()->format('Y-m'));
    $liquidationMonths = collect(range(1, 12))->map(function (int $month) {
        $date = \Carbon\Carbon::createFromDate(2026, $month, 1);

        return [
            'value' => $date->format('Y-m'),
            'label' => $date->format('F Y'),
        ];
    });
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 rounded-full border border-teal-100 bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">
                <i data-feather="clipboard" class="w-3.5 h-3.5"></i>
                <span>Liquidation Tracking</span>
            </div>

            @if($selectedEmployeeData)
                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500">
                    <a href="{{ route('accounting.liquidation') }}" class="font-medium text-gray-600 hover:text-teal-700 transition">Liquidation Tracking</a>
                    <i data-feather="chevron-right" class="w-4 h-4"></i>
                    <span class="font-semibold text-gray-700">{{ $selectedEmployeeData->name }}</span>
                </div>
                <p class="max-w-3xl text-sm text-gray-500">Review this employee's liquidation records for the selected month.</p>
            @else
                <p class="max-w-3xl text-sm text-gray-500">Track which employees submitted liquidations during the selected month and open their liquidation records.</p>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($selectedEmployeeData)
                <a id="backToEmployeeListLink" href="{{ route('accounting.liquidation', $selectedMonthKey ? ['month' => $selectedMonthKey] : []) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-teal-200 hover:text-teal-700 hover:shadow-md">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    Back to employee list
                </a>
            @endif
        </div>
    </div>

    <div id="liquidationReviewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="flex max-h-[90vh] w-full max-w-5xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="border-b border-gray-100 bg-gradient-to-r from-emerald-600 to-teal-600 p-5 text-white">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-100">Liquidation Review</p>
                        <h3 id="liquidationReviewTitle" class="mt-1 text-2xl font-bold">Submitted liquidation</h3>
                        <p id="liquidationReviewSubtitle" class="mt-1 text-sm text-emerald-50"></p>
                    </div>
                    <button id="closeLiquidationReviewBtn" type="button" class="rounded-xl p-2 text-white transition hover:bg-white/15">
                        <i data-feather="x" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>

            <div class="overflow-y-auto p-5">
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Submitted By</p>
                        <p id="liquidationReviewSender" class="mt-1 text-sm font-bold text-gray-900">-</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Balance Sent</p>
                        <p id="liquidationReviewBalance" class="mt-1 text-sm font-bold text-gray-900">PHP 0.00</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Expenses</p>
                        <p id="liquidationReviewExpenses" class="mt-1 text-sm font-bold text-gray-900">PHP 0.00</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Remaining</p>
                        <p id="liquidationReviewRemaining" class="mt-1 text-sm font-bold text-gray-900">PHP 0.00</p>
                    </div>
                </div>

                <div class="mt-5 overflow-hidden rounded-2xl border border-gray-200">
                    <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                        <p class="text-sm font-bold text-gray-900">Expense Breakdown</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 bg-white text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="px-4 py-3">Particulars</th>
                                    <th class="px-4 py-3">Description</th>
                                    <th class="px-4 py-3 text-right">Amount</th>
                                    <th class="px-4 py-3 text-center">Receipt</th>
                                </tr>
                            </thead>
                            <tbody id="liquidationReviewExpenseBody"></tbody>
                        </table>
                    </div>
                    <p id="liquidationReviewEmpty" class="hidden px-4 py-8 text-center text-sm text-gray-500">No expenses attached to this liquidation.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2 border-t border-gray-100 bg-gray-50 p-4">
                <button id="liquidationReviewRejectBtn" type="button" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-700">Reject</button>
                <button id="liquidationReviewApproveBtn" type="button" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-700">Approve</button>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <div class="w-full sm:w-72">
                    <label for="liquidationMonthFilter" class="block text-sm font-semibold text-gray-700">Month</label>
                    <select id="liquidationMonthFilter" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm outline-none transition focus:border-teal-300 focus:ring-4 focus:ring-teal-100">
                        @foreach($liquidationMonths as $month)
                            <option value="{{ $month['value'] }}" {{ $month['value'] === $selectedMonthKey ? 'selected' : '' }}>{{ $month['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="relative" id="liquidationQueueWrapper">
                <button id="liquidationQueueBtn" type="button" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-bold text-emerald-700 shadow-sm transition hover:border-emerald-300 hover:bg-emerald-100 sm:w-auto">
                    <i data-feather="inbox" class="w-4 h-4"></i>
                    <span id="liquidationQueueLabel">Liquidation Queue</span>
                    <span id="liquidationQueueBadge" class="hidden min-w-6 rounded-full bg-emerald-600 px-2 py-0.5 text-center text-xs font-bold text-white">0</span>
                </button>

                <div id="liquidationQueuePanel" class="hidden absolute right-0 top-14 z-40 w-[min(28rem,calc(100vw-3rem))] overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl">
                    <div class="border-b border-gray-100 px-4 py-3">
                        <p class="text-sm font-bold text-gray-900">Liquidation Review Queue</p>
                        <p class="text-xs font-medium text-gray-500">Submitted liquidations waiting for accounting decision</p>
                    </div>
                    <div id="liquidationQueueList" class="max-h-96 space-y-3 overflow-y-auto p-4"></div>
                    <p id="liquidationQueueEmpty" class="hidden px-4 py-8 text-center text-sm text-gray-500">No submitted liquidations for review.</p>
                </div>
            </div>
        </div>

    </div>

    @if($selectedEmployeeData)
        <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-lg sm:p-6">
            <div class="flex flex-col gap-4 border-b border-gray-100 pb-5 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Employee detail</p>
                    <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $selectedEmployeeData->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $selectedEmployeeData->employee_id }} · {{ $selectedEmployeeData->role_name }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Scope</p>
                    <p id="employeeRecordCount" class="mt-1 text-lg font-bold text-slate-900">0 records</p>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-sm font-semibold text-gray-500">
                            <th class="py-3 px-3">Expense date</th>
                            <th class="py-3 px-3">Category</th>
                            <th class="py-3 px-3">Transaction details</th>
                            <th class="py-3 px-3">Description</th>
                            <th class="py-3 px-3 text-center">Proof</th>
                            <th class="py-3 px-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="liquidationRecordTableBody"></tbody>
                </table>
            </div>

            <p id="liquidationRecordEmptyState" class="hidden py-10 text-center text-sm text-gray-500">No liquidation records found for the selected period.</p>
        </div>
    @else
        <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-lg sm:p-6">
            <div class="flex flex-col gap-4 border-b border-gray-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Employee overview</h3>
                    <p class="mt-1 text-sm text-gray-500">Search by name, employee ID, or role. Click a name to open the employee detail view.</p>
                </div>

                <div class="relative w-full sm:max-w-md">
                    <i data-feather="search" class="pointer-events-none absolute left-4 top-1/2 w-4 h-4 -translate-y-1/2 text-gray-400"></i>
                    <input id="liquidationSearchInput" type="search" placeholder="Search employees" class="w-full rounded-2xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-teal-300 focus:bg-white focus:ring-4 focus:ring-teal-100">
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200 text-left text-sm font-semibold text-gray-600">
                            <th class="py-3 px-3">
                                <button type="button" class="sort-trigger inline-flex items-center gap-2" data-sort-field="name">
                                    <span>Employee name</span>
                                    <span class="sort-indicator text-xs text-gray-400"></span>
                                </button>
                            </th>
                            <th class="py-3 px-3">
                                <button type="button" class="sort-trigger inline-flex items-center gap-2" data-sort-field="employee_id">
                                    <span>Employee ID</span>
                                    <span class="sort-indicator text-xs text-gray-400"></span>
                                </button>
                            </th>
                            <th class="py-3 px-3">
                                <button type="button" class="sort-trigger inline-flex items-center gap-2" data-sort-field="role_name">
                                    <span>Role</span>
                                    <span class="sort-indicator text-xs text-gray-400"></span>
                                </button>
                            </th>
                            <th class="py-3 px-3 text-right">
                                <button type="button" class="sort-trigger inline-flex items-center gap-2 justify-end w-full" data-sort-field="total_liquidations">
                                    <span>Total liquidations</span>
                                    <span class="sort-indicator text-xs text-gray-400"></span>
                                </button>
                            </th>
                            <th class="py-3 px-3 text-right">
                                <button type="button" class="sort-trigger inline-flex items-center gap-2 justify-end w-full" data-sort-field="total_amount">
                                    <span>Total amount</span>
                                    <span class="sort-indicator text-xs text-gray-400"></span>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="liquidationEmployeeTableBody"></tbody>
                </table>
            </div>

            <p id="liquidationEmployeeEmptyState" class="hidden py-10 text-center text-sm text-gray-500">No employee liquidation records match the selected period.</p>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    feather.replace();

    function showAccountingToast(message, type = 'success') {
        try {
            // Simple fallback: use alert for now
            if (type === 'error') {
                alert('Error: ' + message);
            } else {
                // small non-blocking notification could be added here later
                console.log('Notice: ' + message);
            }
        } catch (e) {
            // no-op
        }
    }

    const liquidationRecords = @json($liquidationRecords ?? []);
    const selectedEmployee = @json($selectedEmployeeData);
    const isDetailView = Boolean(selectedEmployee);
    const initialMonthKey = @json($selectedMonthKey);
    const liquidationIndexRoute = @json(route('accounting.liquidation'));
    const employeeRouteTemplate = @json(route('accounting.liquidation.employee', ['employee' => '__EMPLOYEE__']));
    const liquidationSubmittedRoute = @json(route('accounting.liquidation.submitted'));
    const liquidationDecisionRouteTemplate = @json(route('accounting.liquidation.decision', ['liquidation' => '__LIQUIDATION__']));

    let currentViewMode = 'month';
    let currentPeriodStart = startOfDay(getMonthStart(monthKeyToDate(initialMonthKey) || new Date()));
    let currentPeriodEnd = endOfDay(getMonthEnd(currentPeriodStart));
    let sortState = { field: 'total_amount', direction: 'desc' };
    let liquidationQueueRecords = liquidationRecords.filter((record) => Boolean(record.queue_record)
        && (String(record.status || '').toLowerCase() === 'submitted'
            || (record.submitted_at && String(record.status || '').toLowerCase() === 'pending')));
    let liquidationQueuePollingInterval = null;
    let liquidationQueueFetchInProgress = false;

    const weekBreakdown = document.getElementById('liquidationWeekBreakdown');
    const prevPeriodBtn = document.getElementById('liquidationPrevPeriodBtn');
    const nextPeriodBtn = document.getElementById('liquidationNextPeriodBtn');
    const currentWeekBtn = document.getElementById('liquidationCurrentWeekBtn');
    const currentMonthBtn = document.getElementById('liquidationCurrentMonthBtn');
    const weekToggleBtn = document.getElementById('liquidationWeekToggle');
    const monthToggleBtn = document.getElementById('liquidationMonthToggle');
    const monthFilter = document.getElementById('liquidationMonthFilter');
    const backToEmployeeListLink = document.getElementById('backToEmployeeListLink');
    const searchInput = document.getElementById('liquidationSearchInput');
    const employeeTableBody = document.getElementById('liquidationEmployeeTableBody');
    const employeeEmptyState = document.getElementById('liquidationEmployeeEmptyState');
    const recordTableBody = document.getElementById('liquidationRecordTableBody');
    const recordEmptyState = document.getElementById('liquidationRecordEmptyState');
    const employeeRecordCount = document.getElementById('employeeRecordCount');

    const liquidationQueueWrapper = document.getElementById('liquidationQueueWrapper');
    const liquidationQueueBtn = document.getElementById('liquidationQueueBtn');
    const liquidationQueuePanel = document.getElementById('liquidationQueuePanel');
    const liquidationQueueLabel = document.getElementById('liquidationQueueLabel');
    const liquidationQueueBadge = document.getElementById('liquidationQueueBadge');
    const liquidationQueueList = document.getElementById('liquidationQueueList');
    const liquidationQueueEmpty = document.getElementById('liquidationQueueEmpty');
    const liquidationReviewModal = document.getElementById('liquidationReviewModal');
    const liquidationReviewTitle = document.getElementById('liquidationReviewTitle');
    const liquidationReviewSubtitle = document.getElementById('liquidationReviewSubtitle');
    const liquidationReviewSender = document.getElementById('liquidationReviewSender');
    const liquidationReviewBalance = document.getElementById('liquidationReviewBalance');
    const liquidationReviewExpenses = document.getElementById('liquidationReviewExpenses');
    const liquidationReviewRemaining = document.getElementById('liquidationReviewRemaining');
    const liquidationReviewExpenseBody = document.getElementById('liquidationReviewExpenseBody');
    const liquidationReviewEmpty = document.getElementById('liquidationReviewEmpty');
    const closeLiquidationReviewBtn = document.getElementById('closeLiquidationReviewBtn');
    const liquidationReviewRejectBtn = document.getElementById('liquidationReviewRejectBtn');
    const liquidationReviewApproveBtn = document.getElementById('liquidationReviewApproveBtn');
    let activeLiquidationReviewId = null;

    function formatMonthKey(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        return `${y}-${m}`;
    }

    function normalizeMonthKey(monthKey) {
        return String(monthKey || '').slice(0, 7);
    }

    function monthKeyToDate(monthKey) {
        const normalized = normalizeMonthKey(monthKey);
        const match = normalized.match(/^(\d{4})-(\d{2})$/);
        if (!match) {
            return null;
        }

        return new Date(Number(match[1]), Number(match[2]) - 1, 1);
    }

    function parseDate(value) {
        if (!value) {
            return new Date();
        }

        const dateString = String(value);
        const isoDateMatch = dateString.match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (isoDateMatch) {
            return new Date(Number(isoDateMatch[1]), Number(isoDateMatch[2]) - 1, Number(isoDateMatch[3]));
        }

        const [year, month, day] = dateString.split('-').map(Number);
        return new Date(year, month - 1, day);
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

    function formatCurrency(amount) {
        const numericAmount = Number(amount || 0);
        return `PHP ${numericAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatDate(dateValue) {
        const date = dateValue instanceof Date ? dateValue : parseDate(dateValue);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
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

    function formatPeriodLabel(start, end, viewMode) {
        if (viewMode === 'month') {
            return formatMonthLabel(start);
        }

        const yearPart = start.getFullYear() === end.getFullYear() ? `, ${start.getFullYear()}` : '';
        return `${formatShortDay(start)}–${formatShortDay(end)}${yearPart}`;
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

    function isWithinRange(recordDate, start, end) {
        const target = startOfDay(parseDate(recordDate));
        return target >= startOfDay(start) && target <= endOfDay(end);
    }

    function applyRange(start, end, mode) {
        currentPeriodStart = startOfDay(start);
        currentPeriodEnd = endOfDay(end);
        currentViewMode = mode;
        renderLiquidationDashboard();
    }

    function setMonthView(date) {
        const monthStart = startOfDay(getMonthStart(date));
        const monthEnd = endOfDay(getMonthEnd(date));
        applyRange(monthStart, monthEnd, 'month');
    }

    function setWeekView(date) {
        const range = getCurrentWeekRange();
        if (date) {
            const selected = startOfDay(date);
            const mondayOffset = (selected.getDay() + 6) % 7;
            range.start = startOfDay(addDays(selected, -mondayOffset));
            range.end = endOfDay(addDays(range.start, 6));
        }

        applyRange(range.start, range.end, 'week');
    }

    function shiftPeriod(direction) {
        if (currentViewMode === 'month') {
            setMonthView(addMonths(currentPeriodStart, direction));
            return;
        }

        applyRange(addDays(currentPeriodStart, direction * 7), addDays(currentPeriodEnd, direction * 7), 'week');
    }

    function getVisibleRecords() {
        return liquidationRecords.filter((record) => !record.queue_record && isWithinRange(record.record_date, currentPeriodStart, currentPeriodEnd));
    }

    function normalizeLiquidationStatus(status) {
        return String(status || 'pending').toLowerCase();
    }

    function getSubmittedLiquidations() {
        return liquidationQueueRecords
            .filter((record) => normalizeLiquidationStatus(record.status) === 'submitted'
                || (record.submitted_at && normalizeLiquidationStatus(record.status) === 'pending'))
            .sort((left, right) => new Date(right.submitted_at || right.record_timestamp || 0) - new Date(left.submitted_at || left.record_timestamp || 0));
    }

    function syncLiquidationQueueRecords(records) {
        liquidationQueueRecords = Array.isArray(records) ? records : [];

        liquidationQueueRecords.forEach((incomingRecord) => {
            const existingIndex = liquidationRecords.findIndex((record) => Number(record.id) === Number(incomingRecord.id));

            if (existingIndex === -1) {
                liquidationRecords.push(incomingRecord);
                return;
            }

            liquidationRecords[existingIndex] = {
                ...liquidationRecords[existingIndex],
                ...incomingRecord,
            };
        });
    }

    async function fetchSubmittedLiquidationQueue() {
        if (liquidationQueueFetchInProgress) {
            return;
        }

        liquidationQueueFetchInProgress = true;

        try {
            const response = await fetch(liquidationSubmittedRoute, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json().catch(() => ({}));
            syncLiquidationQueueRecords(payload?.liquidations || []);
            renderLiquidationQueue();
        } catch (error) {
            // Keep the existing queue visible if polling fails.
        } finally {
            liquidationQueueFetchInProgress = false;
        }
    }

    function renderLiquidationQueue() {
        if (!liquidationQueueLabel || !liquidationQueueList || !liquidationQueueEmpty) {
            return;
        }

        const submittedRecords = getSubmittedLiquidations();
        liquidationQueueLabel.textContent = 'Liquidation Queue';

        if (liquidationQueueBadge) {
            liquidationQueueBadge.textContent = submittedRecords.length.toLocaleString('en-US');
            liquidationQueueBadge.classList.toggle('hidden', submittedRecords.length === 0);
        }

        if (submittedRecords.length === 0) {
            liquidationQueueList.innerHTML = '';
            liquidationQueueEmpty.classList.remove('hidden');
            return;
        }

        liquidationQueueEmpty.classList.add('hidden');
        liquidationQueueList.innerHTML = submittedRecords.map((record) => `
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-emerald-950">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold">Submitted by: ${escapeHtml(record.name || 'Employee')}</p>
                        <p class="mt-1 text-xs font-semibold text-emerald-800">${escapeHtml(record.period_month_label || record.cutoff_period || 'Liquidation')}</p>
                        <p class="mt-1 text-xs font-medium">Submitted: ${formatDate(record.submitted_at || record.record_date)}</p>
                        <p class="mt-1 text-xs font-medium">Amount: ${formatCurrency(record.balance_sent || 0)} | Expenses: ${formatCurrency(record.total_expenses || 0)}</p>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <button type="button" class="liquidation-review-btn rounded-lg bg-white px-3 py-2 text-xs font-bold text-emerald-700 shadow-sm ring-1 ring-emerald-200 transition hover:bg-emerald-100" data-liquidation-id="${record.id}">View for approval</button>
                </div>
            </div>
        `).join('');

        document.querySelectorAll('.liquidation-review-btn').forEach((button) => {
            button.addEventListener('click', () => openLiquidationReviewModal(button.dataset.liquidationId));
        });
    }

    function findLiquidationRecord(liquidationId) {
        return liquidationRecords.find((item) => Number(item.id) === Number(liquidationId))
            || liquidationQueueRecords.find((item) => Number(item.id) === Number(liquidationId));
    }

    function openLiquidationReviewModal(liquidationId) {
        const record = findLiquidationRecord(liquidationId);
        if (!record || !liquidationReviewModal) {
            return;
        }

        activeLiquidationReviewId = record.id;
        const senderLabel = `${record.name || 'Employee'}${record.employee_id ? ' (' + record.employee_id + ')' : ''}`;
        const periodLabel = record.period_month_label || record.cutoff_period || 'Liquidation';
        const expenseLines = Array.isArray(record.expense_breakdown) ? record.expense_breakdown : [];

        liquidationReviewTitle.textContent = `${record.name || 'Employee'} - ${periodLabel}`;
        liquidationReviewSubtitle.textContent = `Submitted ${formatDate(record.submitted_at || record.record_date)} for accounting review`;
        liquidationReviewSender.textContent = senderLabel;
        liquidationReviewBalance.textContent = formatCurrency(record.balance_sent || 0);
        liquidationReviewExpenses.textContent = formatCurrency(record.total_expenses || 0);
        liquidationReviewRemaining.textContent = formatCurrency(record.remaining_balance || 0);

        if (expenseLines.length === 0) {
            liquidationReviewExpenseBody.innerHTML = '';
            liquidationReviewEmpty.classList.remove('hidden');
        } else {
            liquidationReviewEmpty.classList.add('hidden');
            liquidationReviewExpenseBody.innerHTML = expenseLines.map((line) => `
                <tr class="border-b border-gray-100">
                    <td class="px-4 py-3 text-sm text-gray-700">${formatDate(line.expense_date || record.record_date)}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(line.category || '-')}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">${escapeHtml(line.details || '-')}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${escapeHtml(line.description || '-')}</td>
                    <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">${formatCurrency(line.amount || 0)}</td>
                    <td class="px-4 py-3 text-center">
                        ${line.receipt_url ? `<a href="${escapeHtml(line.receipt_url)}" target="_blank" class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100">View Receipt</a>` : '<span class="text-xs text-gray-400">None</span>'}
                    </td>
                </tr>
            `).join('');
        }

        liquidationReviewModal.classList.remove('hidden');
        liquidationReviewModal.classList.add('flex');
        setTimeout(() => feather.replace(), 10);
    }

    function closeLiquidationReviewModal() {
        if (!liquidationReviewModal) {
            return;
        }

        activeLiquidationReviewId = null;
        liquidationReviewModal.classList.add('hidden');
        liquidationReviewModal.classList.remove('flex');
    }

    async function submitLiquidationDecision(liquidationId, decision) {
        if (!liquidationId || !decision) {
            return;
        }

        const promptedRemarks = decision === 'rejected' ? window.prompt('Reason for rejection?', '') : '';
        if (decision === 'rejected' && promptedRemarks === null) {
            return;
        }

        const remarks = promptedRemarks || '';
        const decisionUrl = liquidationDecisionRouteTemplate.replace('__LIQUIDATION__', liquidationId);

        try {
            const response = await fetch(decisionUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ decision, remarks }),
            });

            const payload = await response.json().catch(() => ({}));
            if (!response.ok) {
                throw new Error(payload?.message || 'Failed to update liquidation decision.');
            }

            const record = liquidationRecords.find((item) => Number(item.id) === Number(liquidationId));
            if (record) {
                record.status = payload.status || decision;
                record.remarks = payload.remarks || remarks;
                record.approved_at = payload.approved_at || null;
            }

            liquidationQueueRecords = liquidationQueueRecords.filter((item) => Number(item.id) !== Number(liquidationId));
            closeLiquidationReviewModal();
            renderLiquidationQueue();
            renderLiquidationDashboard();
            showAccountingToast(payload?.message || 'Liquidation decision saved.', 'success');
        } catch (error) {
            showAccountingToast(error.message || 'Failed to update liquidation decision.', 'error');
        }
    }

    function getEmployeeSummaries(records) {
        const grouped = new Map();

        records.forEach((record) => {
            const key = record.user_id;
            if (!grouped.has(key)) {
                grouped.set(key, {
                    user_id: record.user_id,
                    employee_id: record.employee_id,
                    name: record.name,
                    role_name: record.role_name || 'Employee',
                    total_liquidations: 0,
                    total_amount: 0,
                    search_text: `${record.name} ${record.employee_id} ${record.role_name || 'Employee'}`.toLowerCase(),
                });
            }

            const summary = grouped.get(key);
            summary.total_liquidations += 1;
            summary.total_amount += Number(record.liquidation_amount ?? record.balance_sent ?? 0);
        });

        return Array.from(grouped.values());
    }

    function getSortIcon(field) {
        if (sortState.field !== field) {
            return '↕';
        }

        return sortState.direction === 'asc' ? '↑' : '↓';
    }

    function compareValues(left, right, field) {
        const leftValue = left[field];
        const rightValue = right[field];

        if (typeof leftValue === 'number' || typeof rightValue === 'number') {
            return Number(leftValue || 0) - Number(rightValue || 0);
        }

        return String(leftValue || '').localeCompare(String(rightValue || ''), 'en', { sensitivity: 'base' });
    }

    function renderEmployeeRows(records) {
        if (!employeeTableBody || !employeeEmptyState) {
            return;
        }

        const searchTerm = (searchInput?.value || '').trim().toLowerCase();
        let summaries = getEmployeeSummaries(records);

        if (searchTerm) {
            summaries = summaries.filter((summary) => summary.search_text.includes(searchTerm));
        }

        summaries.sort((left, right) => {
            const result = compareValues(left, right, sortState.field);
            return sortState.direction === 'asc' ? result : -result;
        });

        employeeTableBody.innerHTML = summaries.map((summary) => {
            const employeeUrl = new URL(employeeRouteTemplate.replace('__EMPLOYEE__', summary.user_id), window.location.origin);
            employeeUrl.searchParams.set('month', formatMonthKey(currentPeriodStart));
            return `
                <tr class="border-b border-gray-100 transition hover:bg-gray-50/80">
                    <td class="py-4 px-3">
                        <a href="${employeeUrl.toString()}" class="font-semibold text-slate-900 transition hover:text-teal-700">${summary.name}</a>
                    </td>
                    <td class="py-4 px-3 text-sm text-gray-600">${summary.employee_id || '-'}</td>
                    <td class="py-4 px-3 text-sm text-gray-600">${summary.role_name}</td>
                    <td class="py-4 px-3 text-right text-sm font-semibold text-slate-900">${summary.total_liquidations.toLocaleString('en-US')}</td>
                    <td class="py-4 px-3 text-right text-sm font-semibold text-teal-700">${formatCurrency(summary.total_amount)}</td>
                </tr>
            `;
        }).join('');

        employeeEmptyState.classList.toggle('hidden', summaries.length !== 0);
    }

    function renderRecordRows(records) {
        if (!recordTableBody || !recordEmptyState) {
            return;
        }

        const expenseRows = records
            .flatMap((record) => {
                const lines = Array.isArray(record.expense_breakdown) ? record.expense_breakdown : [];
                return lines.map((line) => ({
                    expense_date: line.expense_date || record.record_date,
                    category: line.category || '-',
                    details: line.details || '-',
                    description: line.description || '-',
                    receipt_url: line.receipt_url || null,
                    amount: Number(line.amount || 0),
                }));
            })
            .sort((left, right) => parseDate(right.expense_date) - parseDate(left.expense_date));

        recordTableBody.innerHTML = expenseRows.map((line) => `
            <tr class="border-b border-gray-100 transition hover:bg-gray-50/80">
                <td class="py-4 px-3 text-sm text-gray-700">${formatDate(line.expense_date)}</td>
                <td class="py-4 px-3 text-sm text-gray-700">${line.category}</td>
                <td class="py-4 px-3 text-sm text-gray-700">${line.details}</td>
                <td class="py-4 px-3 text-sm text-gray-700">${line.description}</td>
                <td class="py-4 px-3 text-center text-sm">
                    ${line.receipt_url ? `<a href="${line.receipt_url}" target="_blank" class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100">View</a>` : '<span class="text-xs text-gray-400">None</span>'}
                </td>
                <td class="py-4 px-3 text-right text-sm font-semibold text-rose-700">${formatCurrency(line.amount)}</td>
            </tr>
        `).join('');

        if (employeeRecordCount) {
            employeeRecordCount.textContent = `${expenseRows.length.toLocaleString('en-US')} expense${expenseRows.length === 1 ? '' : 's'}`;
        }

        recordEmptyState.classList.toggle('hidden', expenseRows.length !== 0);
    }

    function renderWeekBreakdown(records) {
        if (!weekBreakdown) {
            return;
        }

        if (currentViewMode !== 'month') {
            weekBreakdown.innerHTML = '';
            return;
        }

        const ranges = getMonthRanges(currentPeriodStart);
        weekBreakdown.innerHTML = ranges.map((range) => {
            const rangeCount = records.filter((record) => isWithinRange(record.record_date, range.start, range.end)).length;
            const isActive = range.start.getTime() === currentPeriodStart.getTime() && range.end.getTime() === currentPeriodEnd.getTime();
            const label = `${formatShortDay(range.start)}–${formatShortDay(range.end)}`;
            return `
                <button type="button" class="liquidation-week-chip inline-flex min-w-[140px] flex-col rounded-2xl border px-4 py-3 text-left text-sm font-semibold transition ${isActive ? 'border-teal-300 bg-teal-500 text-white shadow-lg' : 'border-white/10 bg-white/5 text-slate-200 hover:border-white/20 hover:bg-white/10'}" data-range-start="${formatDateKey(range.start)}" data-range-end="${formatDateKey(range.end)}">
                    <span>${label}</span>
                    <span class="text-xs font-medium ${isActive ? 'text-teal-50' : 'text-slate-400'}">${rangeCount.toLocaleString('en-US')} liquidation${rangeCount === 1 ? '' : 's'}</span>
                </button>
            `;
        }).join('');

        document.querySelectorAll('.liquidation-week-chip').forEach((button) => {
            button.addEventListener('click', () => {
                currentViewMode = 'week';
                currentPeriodStart = startOfDay(parseDate(button.dataset.rangeStart));
                currentPeriodEnd = endOfDay(parseDate(button.dataset.rangeEnd));
                renderLiquidationDashboard();
            });
        });
    }

    function updatePeriodToggles() {
        if (weekToggleBtn) {
            weekToggleBtn.classList.toggle('bg-white', currentViewMode === 'week');
            weekToggleBtn.classList.toggle('text-slate-900', currentViewMode === 'week');
            weekToggleBtn.classList.toggle('text-slate-200', currentViewMode !== 'week');
            weekToggleBtn.classList.toggle('shadow-lg', currentViewMode === 'week');
        }

        if (monthToggleBtn) {
            monthToggleBtn.classList.toggle('bg-white', currentViewMode === 'month');
            monthToggleBtn.classList.toggle('text-slate-900', currentViewMode === 'month');
            monthToggleBtn.classList.toggle('text-slate-200', currentViewMode !== 'month');
            monthToggleBtn.classList.toggle('shadow-lg', currentViewMode === 'month');
        }
    }

    function syncSelectedMonthNavigation() {
        const monthKey = formatMonthKey(currentPeriodStart);

        if (monthFilter && monthFilter.value !== monthKey) {
            monthFilter.value = monthKey;
        }

        if (window.history?.replaceState) {
            const pageUrl = new URL(window.location.href);
            pageUrl.searchParams.set('month', monthKey);
            window.history.replaceState({}, '', pageUrl.toString());
        }

        if (backToEmployeeListLink) {
            const backUrl = new URL(liquidationIndexRoute, window.location.origin);
            backUrl.searchParams.set('month', monthKey);
            backToEmployeeListLink.href = backUrl.toString();
        }
    }

    function renderLiquidationDashboard() {
        const visibleRecords = getVisibleRecords();
        updatePeriodToggles();
        syncSelectedMonthNavigation();
        renderLiquidationQueue();
        renderWeekBreakdown(visibleRecords);

        if (isDetailView) {
            renderRecordRows(visibleRecords);
            return;
        }

        renderEmployeeRows(visibleRecords);
    }

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

    if (monthFilter) {
        monthFilter.addEventListener('change', () => {
            const selectedMonth = monthKeyToDate(monthFilter.value);
            if (selectedMonth) {
                setMonthView(selectedMonth);
            }
        });
    }

    if (liquidationQueueBtn && liquidationQueuePanel) {
        liquidationQueueBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            liquidationQueuePanel.classList.toggle('hidden');
        });
    }

    if (closeLiquidationReviewBtn) {
        closeLiquidationReviewBtn.addEventListener('click', closeLiquidationReviewModal);
    }

    if (liquidationReviewRejectBtn) {
        liquidationReviewRejectBtn.addEventListener('click', () => {
            if (activeLiquidationReviewId) {
                submitLiquidationDecision(activeLiquidationReviewId, 'rejected');
            }
        });
    }

    if (liquidationReviewApproveBtn) {
        liquidationReviewApproveBtn.addEventListener('click', () => {
            if (activeLiquidationReviewId) {
                submitLiquidationDecision(activeLiquidationReviewId, 'approved');
            }
        });
    }

    if (liquidationReviewModal) {
        liquidationReviewModal.addEventListener('click', (event) => {
            if (event.target === liquidationReviewModal) {
                closeLiquidationReviewModal();
            }
        });
    }

    document.addEventListener('click', (event) => {
        if (
            liquidationQueueWrapper &&
            liquidationQueuePanel &&
            !liquidationQueueWrapper.contains(event.target)
        ) {
            liquidationQueuePanel.classList.add('hidden');
        }
    });

    if (searchInput) {
        searchInput.addEventListener('input', () => renderEmployeeRows(getVisibleRecords()));
    }

    document.querySelectorAll('.sort-trigger').forEach((button) => {
        button.addEventListener('click', () => {
            const field = button.dataset.sortField;

            if (sortState.field === field) {
                sortState.direction = sortState.direction === 'asc' ? 'desc' : 'asc';
            } else {
                sortState.field = field;
                sortState.direction = field === 'name' ? 'asc' : 'desc';
            }

            document.querySelectorAll('.sort-indicator').forEach((indicator) => {
                indicator.textContent = indicator.parentElement?.dataset?.sortField === sortState.field ? getSortIcon(sortState.field) : '↕';
            });

            renderEmployeeRows(getVisibleRecords());
        });
    });

    document.querySelectorAll('.sort-trigger .sort-indicator').forEach((indicator) => {
        indicator.textContent = '↕';
    });

    document.querySelectorAll('.sort-trigger').forEach((button) => {
        const indicator = button.querySelector('.sort-indicator');
        if (indicator && button.dataset.sortField === sortState.field) {
            indicator.textContent = getSortIcon(sortState.field);
        }
    });

    setMonthView(monthKeyToDate(monthFilter?.value || initialMonthKey) || new Date());
    fetchSubmittedLiquidationQueue();
    liquidationQueuePollingInterval = setInterval(fetchSubmittedLiquidationQueue, 5000);
    window.addEventListener('beforeunload', () => {
        if (liquidationQueuePollingInterval) {
            clearInterval(liquidationQueuePollingInterval);
        }
    });
</script>
@endpush
