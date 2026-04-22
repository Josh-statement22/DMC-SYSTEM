@extends('layouts.accounting')
@section('title', 'Accounting - Liquidation Tracking')

@section('content')
@php
    $selectedEmployeeData = $selectedEmployee ?? null;
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
                <p class="max-w-3xl text-sm text-gray-500">Review the liquidation records for this employee within the selected period. Use the period navigator to move across months or 7-day windows without leaving the page.</p>
            @else
                <p class="max-w-3xl text-sm text-gray-500">Track liquidation activity by month or week, compare totals at a glance, and drill into a single employee without expanding rows on the same page.</p>
            @endif
        </div>

        @if($selectedEmployeeData)
            <a href="{{ route('accounting.liquidation') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-teal-200 hover:text-teal-700 hover:shadow-md">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                Back to employee list
            </a>
        @endif
    </div>

    <div class="rounded-3xl border border-slate-800 bg-slate-950 p-5 text-white shadow-2xl md:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-3">
                <button id="liquidationPrevPeriodBtn" type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-white transition hover:bg-white/15 hover:scale-105">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                </button>

                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400">Selected period</p>
                    <h2 id="liquidationPeriodLabel" class="mt-1 text-2xl font-bold md:text-3xl">April 2026</h2>
                    <p id="liquidationPeriodSubLabel" class="mt-1 text-sm text-slate-300">Month view</p>
                </div>

                <button id="liquidationNextPeriodBtn" type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-white transition hover:bg-white/15 hover:scale-105">
                    <i data-feather="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="inline-flex rounded-2xl border border-white/10 bg-white/10 p-1">
                    <button id="liquidationWeekToggle" type="button" data-view-toggle="week" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-200 transition hover:text-white">Week</button>
                    <button id="liquidationMonthToggle" type="button" data-view-toggle="month" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-200 transition hover:text-white">Month</button>
                </div>

                <button id="liquidationCurrentWeekBtn" type="button" class="inline-flex items-center gap-2 rounded-xl border border-emerald-300/30 bg-emerald-500/15 px-4 py-2.5 text-sm font-semibold text-emerald-100 transition hover:bg-emerald-500/25">
                    <i data-feather="calendar" class="w-4 h-4"></i>
                    Current Week
                </button>

                <button id="liquidationCurrentMonthBtn" type="button" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/15">
                    <i data-feather="grid" class="w-4 h-4"></i>
                    Current Month
                </button>
            </div>
        </div>

        <div id="liquidationWeekBreakdown" class="mt-4 flex flex-wrap gap-2"></div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl border border-gray-100 bg-white p-5 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
            <p class="text-sm font-medium text-gray-500">Total liquidations</p>
            <p id="summaryLiquidationCount" class="mt-2 text-3xl font-bold text-slate-900">0</p>
            <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Selected period</p>
        </div>

        <div class="rounded-3xl border border-emerald-100 bg-emerald-50 p-5 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
            <p class="text-sm font-medium text-emerald-700">Total amount sent</p>
            <p id="summaryAmountSent" class="mt-2 text-3xl font-bold text-emerald-800">PHP 0.00</p>
            <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-emerald-700/70">Funds released</p>
        </div>

        <div class="rounded-3xl border border-rose-100 bg-rose-50 p-5 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
            <p class="text-sm font-medium text-rose-700">Total expenses</p>
            <p id="summaryTotalExpenses" class="mt-2 text-3xl font-bold text-rose-700">PHP 0.00</p>
            <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-rose-700/70">Expenses recorded</p>
        </div>

        <div class="rounded-3xl border border-teal-100 bg-teal-50 p-5 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
            <p class="text-sm font-medium text-teal-700">Remaining balance</p>
            <p id="summaryRemainingBalance" class="mt-2 text-3xl font-bold text-teal-800">PHP 0.00</p>
            <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-teal-700/70">After expenses</p>
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

    const liquidationRecords = @json($liquidationRecords ?? []);
    const selectedEmployee = @json($selectedEmployeeData);
    const isDetailView = Boolean(selectedEmployee);
    const employeeRouteTemplate = @json(route('accounting.liquidation.employee', ['employee' => '__EMPLOYEE__']));

    let currentViewMode = 'month';
    let currentPeriodStart = startOfDay(getMonthStart(new Date()));
    let currentPeriodEnd = endOfDay(getMonthEnd(new Date()));
    let sortState = { field: 'total_amount', direction: 'desc' };

    const periodLabel = document.getElementById('liquidationPeriodLabel');
    const periodSubLabel = document.getElementById('liquidationPeriodSubLabel');
    const weekBreakdown = document.getElementById('liquidationWeekBreakdown');
    const prevPeriodBtn = document.getElementById('liquidationPrevPeriodBtn');
    const nextPeriodBtn = document.getElementById('liquidationNextPeriodBtn');
    const currentWeekBtn = document.getElementById('liquidationCurrentWeekBtn');
    const currentMonthBtn = document.getElementById('liquidationCurrentMonthBtn');
    const weekToggleBtn = document.getElementById('liquidationWeekToggle');
    const monthToggleBtn = document.getElementById('liquidationMonthToggle');
    const searchInput = document.getElementById('liquidationSearchInput');
    const employeeTableBody = document.getElementById('liquidationEmployeeTableBody');
    const employeeEmptyState = document.getElementById('liquidationEmployeeEmptyState');
    const recordTableBody = document.getElementById('liquidationRecordTableBody');
    const recordEmptyState = document.getElementById('liquidationRecordEmptyState');
    const employeeRecordCount = document.getElementById('employeeRecordCount');

    const summaryLiquidationCount = document.getElementById('summaryLiquidationCount');
    const summaryAmountSent = document.getElementById('summaryAmountSent');
    const summaryTotalExpenses = document.getElementById('summaryTotalExpenses');
    const summaryRemainingBalance = document.getElementById('summaryRemainingBalance');

    function parseDate(value) {
        if (!value) {
            return new Date();
        }

        const [year, month, day] = String(value).split('-').map(Number);
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
        return liquidationRecords.filter((record) => isWithinRange(record.record_date, currentPeriodStart, currentPeriodEnd));
    }

    function getSummaryMetrics(records) {
        return records.reduce((totals, record) => {
            totals.liquidations += 1;
            totals.sent += Number(record.balance_sent || 0);
            totals.expenses += Number(record.total_expenses || 0);
            totals.remaining += Number(record.remaining_balance || 0);
            return totals;
        }, { liquidations: 0, sent: 0, expenses: 0, remaining: 0 });
    }

    function renderSummaryCards(records) {
        if (!summaryLiquidationCount || !summaryAmountSent || !summaryTotalExpenses || !summaryRemainingBalance) {
            return;
        }

        const totals = getSummaryMetrics(records);
        summaryLiquidationCount.textContent = totals.liquidations.toLocaleString('en-US');
        summaryAmountSent.textContent = formatCurrency(totals.sent);
        summaryTotalExpenses.textContent = formatCurrency(totals.expenses);
        summaryRemainingBalance.textContent = formatCurrency(totals.remaining);
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
                    total_expenses: 0,
                    remaining_balance: 0,
                    search_text: `${record.name} ${record.employee_id} ${record.role_name || 'Employee'}`.toLowerCase(),
                });
            }

            const summary = grouped.get(key);
            summary.total_liquidations += 1;
            summary.total_amount += Number(record.balance_sent || 0);
            summary.total_expenses += Number(record.total_expenses || 0);
            summary.remaining_balance += Number(record.remaining_balance || 0);
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
            const employeeUrl = employeeRouteTemplate.replace('__EMPLOYEE__', summary.user_id);
            return `
                <tr class="border-b border-gray-100 transition hover:bg-gray-50/80">
                    <td class="py-4 px-3">
                        <a href="${employeeUrl}" class="font-semibold text-slate-900 transition hover:text-teal-700">${summary.name}</a>
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

    function updatePeriodLabels() {
        if (periodLabel) {
            periodLabel.textContent = formatPeriodLabel(currentPeriodStart, currentPeriodEnd, currentViewMode);
        }

        if (periodSubLabel) {
            periodSubLabel.textContent = currentViewMode === 'month' ? 'Month view' : 'Week view';
        }
    }

    function renderLiquidationDashboard() {
        const visibleRecords = getVisibleRecords();
        updatePeriodToggles();
        updatePeriodLabels();
        renderSummaryCards(visibleRecords);
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

    setMonthView(new Date());
</script>
@endpush