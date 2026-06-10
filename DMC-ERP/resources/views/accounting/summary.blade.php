@extends('layouts.accounting')
@section('title', 'Accounting - Summary')

@section('content')
@php
	$pageOpeningBalance = (float) ($accountingBudgetBalance['opening_balance'] ?? 0);
	$pageRemainingBalance = (float) ($accountingBudgetBalance['remaining_balance'] ?? 0);
	$summaryEmployeeOptions = ($employees ?? collect())->map(fn ($employee) => [
		'id' => (int) $employee->id,
		'name' => $employee->name,
		'employee_id' => $employee->employee_id,
		'label' => $employee->name . ($employee->employee_id ? ' (' . $employee->employee_id . ')' : ''),
	])->values();
@endphp

<div class="space-y-8">
	<!-- Header Section -->
	<div class="flex items-center justify-between">
		<div class="flex items-center space-x-4">
			<div class="w-14 h-14 bg-gradient-to-br from-emerald-700 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg">
				<i data-feather="bar-chart-2" class="w-7 h-7 text-white"></i>
			</div>
			<div>
				<h2 class="text-3xl font-bold text-gray-800">Accounting Summary</h2>
				<p class="text-gray-500">View all liquidation expenses and balance summary</p>
			</div>
		</div>
	</div>

	<div class="space-y-6">
		<div class="rounded-2xl border border-gray-200 bg-white px-4 py-3 shadow-sm">
			<div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
				<div class="flex w-full flex-col gap-3 md:flex-row md:items-center">
					<div class="w-full md:w-[340px]">
						<label for="summaryMonthFilter" class="sr-only">Month</label>
						<select id="summaryMonthFilter" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 transition focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
							@foreach($summaryMonths ?? [] as $summaryMonth)
								<option value="{{ $summaryMonth['value'] }}" {{ $summaryMonth['value'] === ($defaultSummaryMonth ?? '2026-01') ? 'selected' : '' }}>
									{{ $summaryMonth['label'] }}
								</option>
							@endforeach
						</select>
					</div>

					<div class="inline-flex min-h-[42px] w-full items-center justify-between gap-3 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-2 text-sm md:w-auto md:justify-start">
						<span class="font-semibold text-emerald-700">Current Period</span>
						<span class="rounded-full bg-white px-3 py-1 text-sm font-bold text-emerald-900 shadow-sm" id="summaryPeriodLabel">Current Period</span>
					</div>
				</div>

				<button id="summaryFilterBtn" type="button" class="inline-flex w-full min-w-[170px] items-center justify-center gap-2 rounded-xl bg-teal-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 md:w-[170px]">
					<i data-feather="filter" class="w-4 h-4"></i>
					<span>Filters</span>
				</button>
			</div>
		</div>

		<div id="summaryFilterModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 px-4">
			<div class="w-full max-w-md overflow-visible rounded-2xl border border-gray-100 bg-white shadow-2xl">
				<div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
					<div>
						<h3 class="text-xl font-bold text-gray-800">Summary Filters</h3>
						<p class="text-sm text-gray-500 mt-1">Choose employees, category, and type.</p>
					</div>
					<button id="summaryCloseModalBtn" type="button" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
						<i data-feather="x" class="w-5 h-5"></i>
					</button>
				</div>
				<div class="p-6 space-y-5">
					<div>
						<label class="block text-sm font-semibold text-gray-700 mb-2">Employee</label>
						<div class="relative">
							<div id="summarySelectedEmployees" class="mb-2 flex min-h-0 flex-wrap gap-2"></div>
							<input
								id="summaryEmployeeSearch"
								type="text"
								autocomplete="off"
								placeholder="Type employee name or ID"
								class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white"
							>
							<div id="summaryEmployeeSuggestions" class="absolute z-[90] mt-2 hidden max-h-56 w-full overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-xl"></div>
						</div>
					</div>
					<div>
						<label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
						<select id="summaryCategoryFilter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white">
							<option value="">All Categories</option>
							@foreach($categories as $category)
								<option value="category:{{ $category->id }}">{{ $category->particulars_category }}</option>
							@endforeach
							<option value="borrow_returned">Borrow / Returned</option>
							<option value="borrow_not_yet_spent">Borrow / Not yet spent</option>
						</select>
					</div>
					<div>
						<label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
						<select id="summaryTypeFilter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white">
							<option value="">All Types</option>
							<option value="debit">Debit</option>
							<option value="credit">Credit</option>
						</select>
					</div>
					<div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
						<button id="summaryResetModalBtn" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all duration-200">
							<i data-feather="rotate-ccw" class="w-4 h-4"></i>
							Reset
						</button>
						<button id="summarySaveFiltersBtn" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-teal-600 text-white font-semibold hover:bg-teal-700 transition-all duration-200">
							<i data-feather="check" class="w-4 h-4"></i>
							Apply Filters
						</button>
					</div>
				</div>
			</div>
		</div>

		<div id="periodSummaryCards" class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
			<div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
				<p class="text-sm font-semibold text-emerald-700">Opening Balance</p>
				<p id="displayOpeningBalance" class="mt-2 text-2xl font-bold text-emerald-900">PHP 0.00</p>
			</div>
			<div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-5">
				<p class="text-sm font-semibold text-cyan-700">Ending Balance</p>
				<p id="displayEndingBalance" class="mt-2 text-2xl font-bold text-cyan-900">PHP 0.00</p>
			</div>
			<div class="rounded-2xl border border-orange-200 bg-orange-50 p-5">
				<p class="text-sm font-semibold text-orange-700">Total Debits</p>
				<p id="summaryTotalDebits" class="mt-2 text-2xl font-bold text-orange-900">PHP 0.00</p>
			</div>
			<div class="rounded-2xl border border-rose-200 bg-rose-50 p-5">
				<p class="text-sm font-semibold text-rose-700">Total Credits</p>
				<p id="summaryTotalCredits" class="mt-2 text-2xl font-bold text-rose-900">PHP 0.00</p>
			</div>
		</div>

		<div id="categorySummaryCards" class="hidden grid grid-cols-1 gap-4 md:grid-cols-2">
			<div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-5">
				<p class="text-sm font-semibold text-cyan-700">Category</p>
				<p id="summarySelectedCategory" class="mt-2 break-words text-xl font-bold text-cyan-900">All Categories</p>
			</div>
			<div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
				<p class="text-sm font-semibold text-emerald-700">Total Category Amount</p>
				<p id="summaryTotalCategoryAmount" class="mt-2 text-2xl font-bold text-emerald-900">PHP 0.00</p>
			</div>
		</div>

		<div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
			<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
				<div>
					<h3 class="text-lg font-semibold text-gray-900">Liquidation Expenses</h3>
					<p class="mt-1 text-sm text-gray-500">
						<span id="summaryExpenseCount">0</span> records
						<span id="summaryActiveFilters" class="block text-xs font-semibold text-emerald-700 mt-1"></span>
					</p>
				</div>
				<button id="summaryPrintBtn" type="button" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
					<i data-feather="printer" class="w-4 h-4"></i>
					<span>Print</span>
				</button>
			</div>

			<div class="overflow-x-auto">
				<table class="w-full min-w-[920px]">
					<thead>
						<tr class="border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
							<th class="py-3 px-4">Date</th>
							<th class="py-3 px-4">Employee Name</th>
							<th class="py-3 px-4">Category</th>
							<th class="py-3 px-4">Particulars</th>
							<th class="py-3 px-4">Type</th>
							<th class="py-3 px-4 text-right">Credit</th>
							<th class="py-3 px-4 text-right">Debit</th>
						</tr>
					</thead>
					<tbody id="summaryTableBody">
						<tr class="border-b border-gray-100 hover:bg-gray-50 transition">
							<td colspan="7" class="py-8 px-4 text-center text-gray-500">
								<p class="text-sm">No data available</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div id="summaryPaginationContainer" class="mt-6 hidden">
				<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
					<p class="text-sm text-gray-600">Page <span id="currentPage">1</span> of <span id="totalPages">1</span></p>
					<div class="flex gap-2">
						<button id="summaryPrevBtn" type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-700 transition hover:bg-gray-50">
							<i data-feather="chevron-left" class="w-4 h-4"></i>
							Previous
						</button>
						<button id="summaryNextBtn" type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-700 transition hover:bg-gray-50">
							Next
							<i data-feather="chevron-right" class="w-4 h-4"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// Initialize feather icons
	feather.replace();

	// Balance settings
	let openingBalance = @json($pageOpeningBalance);
	let endingBalance = @json($pageRemainingBalance);
	let appliedCategoryKey = '';
	let appliedEmployeeIds = [];
	let draftEmployeeIds = [];
	let appliedType = '';
	const summaryPrintedByName = @json(Auth::user()->name ?? Auth::user()->email ?? 'Current User');
	const summaryEmployees = @json($summaryEmployeeOptions);

	function updateBalanceDisplay() {
		document.getElementById('displayOpeningBalance').textContent = formatCurrencyValue(openingBalance);
		document.getElementById('displayEndingBalance').textContent = formatCurrencyValue(endingBalance);
	}

	function formatCurrencyValue(amount) {
		return 'PHP ' + Number(amount || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function getSelectedCategoryLabel() {
		const select = document.getElementById('summaryCategoryFilter');

		return select?.selectedOptions[0]?.textContent || 'All Categories';
	}

	function getEmployeeById(employeeId) {
		return summaryEmployees.find(employee => String(employee.id) === String(employeeId));
	}

	function getSelectedEmployeeLabel(employeeIds = appliedEmployeeIds) {
		if (!employeeIds.length) {
			return 'All Employees';
		}

		return employeeIds
			.map(employeeId => getEmployeeById(employeeId)?.label)
			.filter(Boolean)
			.join(', ') || 'All Employees';
	}

	function renderSelectedEmployees() {
		const container = document.getElementById('summarySelectedEmployees');
		if (!container) return;

		if (draftEmployeeIds.length === 0) {
			container.innerHTML = '<span class="text-xs font-semibold text-gray-500">All Employees</span>';
			return;
		}

		container.innerHTML = draftEmployeeIds.map(employeeId => {
			const employee = getEmployeeById(employeeId);
			if (!employee) return '';

			return `
				<span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100">
					${escapeHtml(employee.label)}
					<button type="button" class="summaryRemoveEmployee text-emerald-700 hover:text-emerald-900" data-id="${employee.id}" aria-label="Remove ${escapeHtml(employee.label)}">&times;</button>
				</span>
			`;
		}).join('');
	}

	function hideEmployeeSuggestions() {
		const suggestions = document.getElementById('summaryEmployeeSuggestions');
		suggestions?.classList.add('hidden');
	}

	function renderEmployeeSuggestions() {
		const input = document.getElementById('summaryEmployeeSearch');
		const suggestions = document.getElementById('summaryEmployeeSuggestions');
		if (!input || !suggestions) return;

		const search = input.value.trim().toLowerCase();
		const matches = summaryEmployees
			.filter(employee => !draftEmployeeIds.includes(employee.id))
			.filter(employee => {
				if (!search) return true;
				return employee.label.toLowerCase().includes(search)
					|| String(employee.employee_id || '').toLowerCase().includes(search);
			})
			.slice(0, 8);

		if (!matches.length) {
			suggestions.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">No employee found.</div>';
			suggestions.classList.remove('hidden');
			return;
		}

		suggestions.innerHTML = matches.map((employee, index) => `
			<button type="button" class="summaryEmployeeSuggestion flex w-full items-center justify-between px-4 py-3 text-left text-sm hover:bg-emerald-50 ${index === 0 ? 'bg-emerald-50' : ''}" data-id="${employee.id}">
				<span class="font-semibold text-gray-800">${escapeHtml(employee.name)}</span>
				<span class="text-xs text-gray-500">${escapeHtml(employee.employee_id || '')}</span>
			</button>
		`).join('');
		suggestions.classList.remove('hidden');
	}

	function addDraftEmployee(employeeId) {
		const normalizedId = Number(employeeId);
		if (!normalizedId || draftEmployeeIds.includes(normalizedId)) {
			return;
		}

		draftEmployeeIds.push(normalizedId);
		document.getElementById('summaryEmployeeSearch').value = '';
		renderSelectedEmployees();
		renderEmployeeSuggestions();
	}

	function getSelectedTypeLabel() {
		const select = document.getElementById('summaryTypeFilter');

		return select?.selectedOptions[0]?.textContent || 'All Types';
	}

	function getActiveFilterLabel(data = null) {
		const filters = [];

		if (appliedCategoryKey) {
			filters.push(data?.summary?.selected_category_name || getSelectedCategoryLabel());
		}

		if (appliedEmployeeIds.length) {
			filters.push(data?.summary?.selected_employee_name || getSelectedEmployeeLabel());
		}

		if (appliedType) {
			filters.push(getSelectedTypeLabel());
		}

		return filters.length ? `Filtered by ${filters.join(' / ')}` : '';
	}

	function getAppliedFilters() {
		return {
			categoryKey: appliedCategoryKey,
			employeeIds: [...appliedEmployeeIds],
			type: appliedType,
		};
	}

	function syncFilterModalFields() {
		document.getElementById('summaryCategoryFilter').value = appliedCategoryKey;
		draftEmployeeIds = [...appliedEmployeeIds];
		document.getElementById('summaryEmployeeSearch').value = '';
		renderSelectedEmployees();
		hideEmployeeSuggestions();
		document.getElementById('summaryTypeFilter').value = appliedType;
	}

	function openFilterModal() {
		syncFilterModalFields();
		const modal = document.getElementById('summaryFilterModal');
		modal.classList.remove('hidden');
		modal.classList.add('flex');
	}

	function closeFilterModal() {
		const modal = document.getElementById('summaryFilterModal');
		modal.classList.add('hidden');
		modal.classList.remove('flex');
	}

	function applyFilterSelections() {
		appliedCategoryKey = document.getElementById('summaryCategoryFilter').value;
		appliedEmployeeIds = [...draftEmployeeIds];
		appliedType = document.getElementById('summaryTypeFilter').value;
		closeFilterModal();
		loadExpenses(1);
	}

	function resetFilterSelections() {
		document.getElementById('summaryCategoryFilter').value = '';
		document.getElementById('summaryEmployeeSearch').value = '';
		document.getElementById('summaryTypeFilter').value = '';
		appliedCategoryKey = '';
		appliedEmployeeIds = [];
		draftEmployeeIds = [];
		appliedType = '';
		renderSelectedEmployees();
		hideEmployeeSuggestions();
		closeFilterModal();
		loadExpenses(1);
	}

	function formatDateValue(date) {
		const year = date.getFullYear();
		const month = String(date.getMonth() + 1).padStart(2, '0');
		const day = String(date.getDate()).padStart(2, '0');

		return `${year}-${month}-${day}`;
	}

	function updatePeriodLabel(fromDate, toDate) {
		document.getElementById('summaryPeriodLabel').textContent = `${fromDate.toLocaleDateString('en-US', {
			month: 'long',
			day: 'numeric',
			year: 'numeric',
		})} - ${toDate.toLocaleDateString('en-US', {
			month: 'long',
			day: 'numeric',
			year: 'numeric',
		})}`;
	}

	function getDateRange() {
		const selectedMonth = document.getElementById('summaryMonthFilter').value;
		const now = new Date();
		const [year, month] = selectedMonth
			? selectedMonth.split('-').map(Number)
			: [now.getFullYear(), now.getMonth() + 1];
		const fromDate = new Date(year, month - 1, 1);
		const toDate = new Date(year, month, 0);

		updatePeriodLabel(fromDate, toDate);

		return {
			from_date: formatDateValue(fromDate),
			to_date: formatDateValue(toDate)
		};
	}

	// Load expenses data
	async function loadExpenses(page = 1) {
		const { categoryKey, employeeIds, type } = getAppliedFilters();
		const dateRange = getDateRange();

		if (!dateRange) return;

		try {
			const params = new URLSearchParams();
			params.set('page', page);
			params.set('from_date', dateRange.from_date);
			params.set('to_date', dateRange.to_date);

			if (categoryKey) {
				params.set('category_key', categoryKey);
			}

			employeeIds.forEach(employeeId => params.append('employee_ids[]', employeeId));

			if (type) {
				params.set('type', type);
			}

			const response = await fetch(`/accounting/summary/data?${params.toString()}`);
			const data = await response.json();

			renderTable(data.expenses);
			updateSummaryCards(data);
			updatePagination(data.pagination);
		} catch (error) {
			console.error('Error loading expenses:', error);
		}
	}

	function getSummaryFilters() {
		const { categoryKey, employeeIds, type } = getAppliedFilters();
		const dateRange = getDateRange();

		return { categoryKey, employeeIds, type, dateRange };
	}

	async function fetchAllExpenses() {
		const { categoryKey, employeeIds, type, dateRange } = getSummaryFilters();
		const params = new URLSearchParams();
		params.set('all', '1');
		params.set('from_date', dateRange.from_date);
		params.set('to_date', dateRange.to_date);

		if (categoryKey) {
			params.set('category_key', categoryKey);
		}

		employeeIds.forEach(employeeId => params.append('employee_ids[]', employeeId));

		if (type) {
			params.set('type', type);
		}

		const response = await fetch(`/accounting/summary/data?${params.toString()}`);
		return response.json();
	}

	function escapeHtml(value) {
		return String(value ?? '')
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#39;');
	}

	function buildPrintHtml(expenses, summary, balance) {
		const periodLabel = document.getElementById('summaryPeriodLabel').textContent || 'Current Period';
		const categoryActive = Boolean(appliedCategoryKey);
		const employeeActive = appliedEmployeeIds.length > 0;
		const typeActive = Boolean(appliedType);
		const categoryLabel = getSelectedCategoryLabel();
		const employeeLabel = summary.selected_employee_name || getSelectedEmployeeLabel();
		const typeLabel = getSelectedTypeLabel();
		const totalCategoryAmount = Number(summary.total_category_amount ?? 0);
		const printedAt = new Date().toLocaleString();
		const rows = expenses.length
			? expenses.map(expense => `
				<tr>
					<td>${escapeHtml(formatDate(expense.expense_date))}</td>
					<td>${escapeHtml(expense.employee_name || '-')}</td>
					<td>${escapeHtml(expense.category_name || '-')}</td>
					<td>${escapeHtml(expense.particular_name || '-')}</td>
					<td class="text-right">${Number(expense.credit || 0) > 0 ? escapeHtml(formatCurrencyValue(expense.credit)) : '-'}</td>
					<td class="text-right">${Number(expense.debit || 0) > 0 ? escapeHtml(formatCurrencyValue(expense.debit)) : '-'}</td>
				</tr>
			`).join('')
			: '<tr><td colspan="6" class="empty-state">No data available</td></tr>';
		const summaryRows = categoryActive
			? `
				<div class="summary-item">Selected Category: <span class="summary-value">${escapeHtml(categoryLabel)}</span></div>
				${employeeActive ? `<div class="summary-item">Selected Employee: <span class="summary-value">${escapeHtml(employeeLabel)}</span></div>` : ''}
				${typeActive ? `<div class="summary-item">Selected Type: <span class="summary-value">${escapeHtml(typeLabel)}</span></div>` : ''}
				<div class="summary-item">Total Category Amount: <span class="summary-value">${formatCurrencyValue(totalCategoryAmount)}</span></div>
			`
			: `
				<div class="summary-item">Total Records: <span class="summary-value">${summary.total_count ?? 0}</span></div>
				${employeeActive ? `<div class="summary-item">Selected Employee: <span class="summary-value">${escapeHtml(employeeLabel)}</span></div>` : ''}
				${typeActive ? `<div class="summary-item">Selected Type: <span class="summary-value">${escapeHtml(typeLabel)}</span></div>` : ''}
				<div class="summary-item">Opening Balance: <span class="summary-value">${formatCurrencyValue(balance?.opening_balance ?? 0)}</span></div>
				<div class="summary-item">Ending Balance: <span class="summary-value">${formatCurrencyValue(balance?.remaining_balance ?? balance?.ending_balance ?? 0)}</span></div>
				<div class="summary-item">Total Credits: <span class="summary-value">${formatCurrencyValue(summary.total_credits ?? 0)}</span></div>
				<div class="summary-item">Total Debits: <span class="summary-value">${formatCurrencyValue(summary.total_debits ?? 0)}</span></div>
			`;

		return `<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Liquidation Summary Print</title>
	<style>
		@page { size: landscape; margin: 14mm; }
		body { font-family: Arial, sans-serif; color: #111827; margin: 0; }
		.header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; gap: 16px; }
		.title { font-size: 24px; font-weight: 700; margin: 0 0 8px; color: #0f172a; }
		.meta { font-size: 12px; color: #4b5563; line-height: 1.6; }
		table { width: 100%; border-collapse: collapse; }
		th, td { border: 1px solid #d1d5db; padding: 8px 10px; font-size: 12px; vertical-align: top; }
		th { background: #f3f4f6; color: #1f2937; font-weight: 700; text-align: left; }
		.col-date { width: 12%; }
		.col-employee { width: 20%; }
		.col-category { width: 18%; }
		.col-particulars { width: 26%; }
		.col-amount { width: 12%; }
		.text-right { text-align: right; }
		.empty-state { text-align: center; padding: 16px 10px; color: #6b7280; }
		.summary { margin-top: 18px; padding-top: 14px; border-top: 2px solid #d1d5db; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px 28px; font-size: 14px; color: #111827; }
		.summary-item { min-width: 190px; flex: 1; font-weight: 700; white-space: nowrap; }
		.summary-value { font-weight: 800; }
	</style>
</head>
<body>
	<div class="header">
		<div>
			<p class="title">Liquidation Summary</p>
			<div class="meta">
				<div>Period: ${escapeHtml(periodLabel)}</div>
				<div>Category: ${escapeHtml(categoryLabel)}</div>
				<div>Employee: ${escapeHtml(employeeLabel)}</div>
				<div>Type: ${escapeHtml(typeLabel)}</div>
				<div>Printed: ${escapeHtml(printedAt)}</div>
				<div>Printed By: ${escapeHtml(summaryPrintedByName)}</div>
			</div>
		</div>
	</div>
	<table>
		<thead>
			<tr>
				<th class="col-date">Date</th>
				<th class="col-employee">Employee</th>
				<th class="col-category">Category</th>
				<th class="col-particulars">Particulars</th>
				<th class="col-amount text-right">Credit</th>
				<th class="col-amount text-right">Debit</th>
			</tr>
		</thead>
		<tbody>${rows}</tbody>
	</table>
	<div class="summary">${summaryRows}</div>
</body>
</html>`;
	}

	async function printSummary() {
		const printWindow = window.open('', '_blank', 'width=1200,height=900');
		if (!printWindow) {
			alert('Popup blocked. Please allow popups to print the liquidation summary.');
			return;
		}

		printWindow.document.write('<!doctype html><html><head><title>Preparing Print...</title></head><body style="font-family: Arial, sans-serif; padding: 24px;">Preparing print layout...</body></html>');
		printWindow.document.close();

		try {
			const data = await fetchAllExpenses();
			printWindow.document.open();
			printWindow.document.write(buildPrintHtml(data.expenses || [], data.summary || {}, data.balance || {}));
			printWindow.document.close();
			setTimeout(() => {
				printWindow.focus();
				printWindow.print();
			}, 250);
		} catch (error) {
			printWindow.close();
			console.error('Error preparing print summary:', error);
			alert('Unable to prepare the print view. Please try again.');
		}
	}

	function renderTable(expenses) {
		const tbody = document.getElementById('summaryTableBody');
		
		if (expenses.length === 0) {
			tbody.innerHTML = '<tr class="border-b border-gray-100"><td colspan="7" class="py-8 px-4 text-center text-gray-500"><p class="text-sm">No data available</p></td></tr>';
			return;
		}

		tbody.innerHTML = expenses.map(expense => `
			<tr class="border-b border-gray-100 hover:bg-gray-50 transition">
				<td class="py-4 px-4 text-sm text-gray-700">${escapeHtml(formatDate(expense.expense_date))}</td>
				<td class="py-4 px-4 text-sm text-gray-700">${escapeHtml(expense.employee_name || '-')}</td>
				<td class="py-4 px-4 text-sm text-gray-700">${escapeHtml(expense.category_name || '-')}</td>
				<td class="py-4 px-4 text-sm text-gray-700">${escapeHtml(expense.particular_name || '-')}</td>
				<td class="py-4 px-4">
					<span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ${getTransactionTypeBadgeClass(expense.transaction_type)}">
						${escapeHtml(formatTransactionType(expense.transaction_type))}
					</span>
				</td>
				<td class="py-4 px-4 text-sm font-semibold text-right text-rose-600">${Number(expense.credit || 0) > 0 ? formatCurrencyValue(expense.credit) : '-'}</td>
				<td class="py-4 px-4 text-sm font-semibold text-right text-emerald-600">${Number(expense.debit || 0) > 0 ? formatCurrencyValue(expense.debit) : '-'}</td>
			</tr>
		`).join('');
	}

	function updateSummaryCards(data) {
		const categoryActive = Boolean(appliedCategoryKey);
		const totalCategoryAmount = Number(data.summary.total_category_amount ?? 0);

		document.getElementById('summaryExpenseCount').textContent = data.summary.total_count;
		document.getElementById('summaryActiveFilters').textContent = getActiveFilterLabel(data);
		document.getElementById('summaryTotalDebits').textContent = formatCurrencyValue(data.summary.total_debits);
		document.getElementById('summaryTotalCredits').textContent = formatCurrencyValue(data.summary.total_credits);
		document.getElementById('summarySelectedCategory').textContent = data.summary.selected_category_name || getSelectedCategoryLabel();
		document.getElementById('summaryTotalCategoryAmount').textContent = formatCurrencyValue(totalCategoryAmount);
		document.getElementById('periodSummaryCards').classList.toggle('hidden', categoryActive);
		document.getElementById('categorySummaryCards').classList.toggle('hidden', !categoryActive);

		if (data.balance) {
			openingBalance = Number(data.balance.opening_balance || 0);
			endingBalance = Number(data.balance.remaining_balance ?? data.balance.ending_balance ?? 0);
			updateBalanceDisplay();
		}
	}

	function updatePagination(pagination) {
		if (pagination.total_pages <= 1) {
			document.getElementById('summaryPaginationContainer').classList.add('hidden');
			return;
		}

		document.getElementById('summaryPaginationContainer').classList.remove('hidden');
		document.getElementById('currentPage').textContent = pagination.current_page;
		document.getElementById('totalPages').textContent = pagination.total_pages;

		document.getElementById('summaryPrevBtn').disabled = pagination.current_page === 1;
		document.getElementById('summaryNextBtn').disabled = pagination.current_page === pagination.total_pages;

		document.getElementById('summaryPrevBtn').onclick = () => loadExpenses(pagination.current_page - 1);
		document.getElementById('summaryNextBtn').onclick = () => loadExpenses(pagination.current_page + 1);
	}

	function getTransactionTypeBadgeClass(type) {
		const badgeMap = {
			'debit': 'bg-rose-100 text-rose-700 border border-rose-200',
			'credit': 'bg-emerald-100 text-emerald-700 border border-emerald-200',
		};
		return badgeMap[type] || 'bg-gray-100 text-gray-700 border border-gray-200';
	}

	function formatTransactionType(type) {
		const labelMap = {
			debit: 'Debit',
			credit: 'Credit',
		};

		return labelMap[type] || type || '-';
	}

	function formatDate(date) {
		return new Date(date).toLocaleDateString('en-US', { 
			year: 'numeric', 
			month: 'short', 
			day: 'numeric' 
		});
	}

	// Event listeners
	document.getElementById('summaryFilterBtn').addEventListener('click', () => {
		openFilterModal();
	});

	document.getElementById('summaryMonthFilter').addEventListener('change', () => {
		loadExpenses(1);
	});

	document.getElementById('summaryCloseModalBtn').addEventListener('click', closeFilterModal);
	document.getElementById('summarySaveFiltersBtn').addEventListener('click', applyFilterSelections);
	document.getElementById('summaryResetModalBtn').addEventListener('click', resetFilterSelections);
	document.getElementById('summaryEmployeeSearch').addEventListener('focus', renderEmployeeSuggestions);
	document.getElementById('summaryEmployeeSearch').addEventListener('input', renderEmployeeSuggestions);
	document.getElementById('summaryEmployeeSearch').addEventListener('keydown', (event) => {
		if (event.key !== 'Enter') {
			return;
		}

		event.preventDefault();
		const firstSuggestion = document.querySelector('#summaryEmployeeSuggestions .summaryEmployeeSuggestion');
		if (firstSuggestion) {
			addDraftEmployee(firstSuggestion.dataset.id);
		}
	});
	document.getElementById('summaryEmployeeSuggestions').addEventListener('click', (event) => {
		const suggestion = event.target.closest('.summaryEmployeeSuggestion');
		if (suggestion) {
			addDraftEmployee(suggestion.dataset.id);
		}
	});
	document.getElementById('summarySelectedEmployees').addEventListener('click', (event) => {
		const removeButton = event.target.closest('.summaryRemoveEmployee');
		if (!removeButton) {
			return;
		}

		draftEmployeeIds = draftEmployeeIds.filter(employeeId => String(employeeId) !== String(removeButton.dataset.id));
		renderSelectedEmployees();
		renderEmployeeSuggestions();
	});
	document.getElementById('summaryFilterModal').addEventListener('click', (event) => {
		if (event.target.id === 'summaryFilterModal') {
			closeFilterModal();
		}
	});
	document.addEventListener('click', (event) => {
		if (!event.target.closest('#summaryEmployeeSearch') && !event.target.closest('#summaryEmployeeSuggestions')) {
			hideEmployeeSuggestions();
		}
	});

	document.getElementById('summaryPrintBtn').addEventListener('click', printSummary);

	// Initial load
	renderSelectedEmployees();
	updateBalanceDisplay();
	loadExpenses(1);
</script>

@endsection
