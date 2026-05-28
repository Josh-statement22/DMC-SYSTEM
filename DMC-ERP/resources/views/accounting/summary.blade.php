@extends('layouts.accounting')
@section('title', 'Accounting - Summary')

@section('content')
@php
	$pageOpeningBalance = (float) ($accountingBudgetBalance['opening_balance'] ?? 0);
	$pageRemainingBalance = (float) ($accountingBudgetBalance['remaining_balance'] ?? 0);
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

	<!-- Main Content with Balance Card on Right -->
	<div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
		<!-- Table Section (takes 3 columns) -->
		<div class="xl:col-span-3 space-y-6">
			<!-- Filter Section -->
			<div class="rounded-3xl bg-white p-6 shadow-lg border border-gray-100">
				<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
					<div class="w-full sm:max-w-xs">
						<label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
						<select id="summaryMonthFilter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white">
							@php
								for ($i = 0; $i < 12; $i++) {
									$date = now()->subMonths($i);
									$value = $date->format('Y-m');
									$display = $date->format('F Y');
									$selected = $i === 0 ? ' selected' : '';
									echo "<option value=\"$value\"$selected>$display</option>";
								}
							@endphp
						</select>
					</div>

					<div class="w-full sm:w-64">
						<button id="summaryFilterBtn" type="button" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold hover:shadow-lg transition-all duration-300">
							<i data-feather="filter" class="w-4 h-4"></i>
							<span>Category</span>
						</button>
					</div>
				</div>
			</div>

			<!-- Filter Modal -->
			<div id="summaryFilterModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 px-4">
				<div class="w-full max-w-md rounded-3xl bg-white shadow-2xl border border-gray-100 overflow-hidden">
					<div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
						<div>
							<h3 class="text-xl font-bold text-gray-800">Category Filter</h3>
							<p class="text-sm text-gray-500 mt-1">Choose a category to update the table and print view.</p>
						</div>
						<button id="summaryCloseModalBtn" type="button" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
							<i data-feather="x" class="w-5 h-5"></i>
						</button>
					</div>
					<div class="p-6 space-y-5">
						<div>
							<label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
							<select id="summaryCategoryFilter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white">
								<option value="">All Categories</option>
								@foreach($categories as $category)
									<option value="{{ $category->id }}">{{ $category->particulars_category }}</option>
								@endforeach
							</select>
						</div>
						<div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
							<button id="summaryResetModalBtn" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all duration-200">
								<i data-feather="rotate-ccw" class="w-4 h-4"></i>
								Reset
							</button>
							<button id="summarySaveFiltersBtn" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold hover:shadow-lg transition-all duration-300">
								<i data-feather="check" class="w-4 h-4"></i>
								Apply Category
							</button>
						</div>
					</div>
				</div>
			</div>



			<!-- Expenses Table -->
			<div class="rounded-3xl bg-white p-6 shadow-lg border border-gray-100 overflow-hidden">
				<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
					<div>
						<h3 class="text-xl font-bold text-gray-800">Liquidation Expenses</h3>
						<p class="text-sm text-gray-500 mt-1"><span id="summaryExpenseCount">0</span> records</p>
					</div>
					<button id="summaryPrintBtn" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-all duration-200 shadow-sm">
						<i data-feather="printer" class="w-4 h-4"></i>
						<span>Print</span>
					</button>
				</div>

				<div class="overflow-x-auto">
					<table class="w-full">
						<thead>
							<tr class="border-b border-gray-200 text-left text-sm font-semibold text-gray-600 bg-gray-50">
								<th class="py-4 px-4">Date</th>
								<th class="py-4 px-4">Employee Name</th>
								<th class="py-4 px-4">Category</th>
								<th class="py-4 px-4">Particulars</th>
								<th class="py-4 px-4">Type</th>
								<th class="py-4 px-4 text-right">Credit</th>
								<th class="py-4 px-4 text-right">Debit</th>
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

				<!-- Pagination -->
				<div id="summaryPaginationContainer" class="mt-6 hidden">
					<div class="flex items-center justify-between">
						<p class="text-sm text-gray-600">Page <span id="currentPage">1</span> of <span id="totalPages">1</span></p>
						<div class="flex gap-2">
							<button id="summaryPrevBtn" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50 transition">
								<i data-feather="chevron-left" class="w-4 h-4"></i>
								Previous
							</button>
							<button id="summaryNextBtn" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50 transition">
								Next
								<i data-feather="chevron-right" class="w-4 h-4"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Balance Card Section (takes 1 column) -->
		<div class="xl:col-span-1 h-fit">
			<div class="rounded-3xl bg-gradient-to-br from-slate-800 to-slate-900 text-white p-7 shadow-2xl relative overflow-hidden">
				<div class="absolute -top-12 -right-12 w-40 h-40 bg-emerald-300/20 rounded-full blur-3xl"></div>
				<div class="relative z-10 space-y-6">
					<div>
						<p class="text-sm uppercase tracking-wide text-emerald-200 font-semibold">Period Summary</p>
						<p id="summaryPeriodLabel" class="text-xs text-emerald-100 mt-2">Current Period</p>
					</div>

					<div class="space-y-4">
						<div class="rounded-2xl bg-emerald-500/15 border border-emerald-300/30 p-4">
							<p class="text-xs text-emerald-200 uppercase tracking-wide font-semibold">Opening Balance</p>
							<p id="displayOpeningBalance" class="text-2xl font-bold text-emerald-100 mt-2">PHP 0.00</p>
						</div>

						<div class="rounded-2xl bg-cyan-500/15 border border-cyan-300/30 p-4">
							<p class="text-xs text-cyan-200 uppercase tracking-wide font-semibold">Ending Balance</p>
							<p id="displayEndingBalance" class="text-2xl font-bold text-cyan-100 mt-2">PHP 0.00</p>
						</div>

						<div class="rounded-2xl bg-emerald-500/15 border border-emerald-300/30 p-4">
							<p class="text-xs text-emerald-200 uppercase tracking-wide font-semibold">Total Debits</p>
							<p id="summaryTotalDebits" class="text-2xl font-bold text-emerald-100 mt-2">PHP 0.00</p>
						</div>

						<div class="rounded-2xl bg-rose-500/15 border border-rose-300/30 p-4">
							<p class="text-xs text-rose-200 uppercase tracking-wide font-semibold">Total Credits</p>
							<p id="summaryTotalCredits" class="text-2xl font-bold text-rose-100 mt-2">PHP 0.00</p>
						</div>
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
	let appliedCategoryId = '';
	const summaryPrintedByName = @json(Auth::user()->name ?? Auth::user()->email ?? 'Current User');

	function updateBalanceDisplay() {
		document.getElementById('displayOpeningBalance').textContent = formatCurrencyValue(openingBalance);
		document.getElementById('displayEndingBalance').textContent = formatCurrencyValue(endingBalance);
	}

	function formatCurrencyValue(amount) {
		return 'PHP ' + Number(amount || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function getAppliedFilters() {
		return {
			categoryId: appliedCategoryId,
		};
	}

	function syncFilterModalFields() {
		document.getElementById('summaryCategoryFilter').value = appliedCategoryId;
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
		appliedCategoryId = document.getElementById('summaryCategoryFilter').value;
		closeFilterModal();
		loadExpenses(1);
	}

	function resetFilterSelections() {
		document.getElementById('summaryCategoryFilter').value = '';
		appliedCategoryId = '';
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
		const { categoryId } = getAppliedFilters();
		const dateRange = getDateRange();

		if (!dateRange) return;

		try {
			const params = new URLSearchParams();
			params.set('page', page);
			params.set('from_date', dateRange.from_date);
			params.set('to_date', dateRange.to_date);

			if (categoryId) {
				params.set('category_id', categoryId);
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
		const { categoryId } = getAppliedFilters();
		const dateRange = getDateRange();

		return { categoryId, dateRange };
	}

	async function fetchAllExpenses() {
		const { categoryId, dateRange } = getSummaryFilters();
		const params = new URLSearchParams();
		params.set('all', '1');
		params.set('from_date', dateRange.from_date);
		params.set('to_date', dateRange.to_date);

		if (categoryId) {
			params.set('category_id', categoryId);
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

	function buildPrintHtml(expenses, summary) {
		const periodLabel = document.getElementById('summaryPeriodLabel').textContent || 'Current Period';
		const categoryLabel = document.getElementById('summaryCategoryFilter').selectedOptions[0]?.textContent || 'All Categories';
		const printedAt = new Date().toLocaleString();
		const rows = expenses.length
			? expenses.map(expense => `
				<tr>
					<td>${escapeHtml(formatDate(expense.expense_date))}</td>
					<td>${escapeHtml(expense.employee_name || '-')}</td>
					<td>${escapeHtml(expense.category_name || '-')}</td>
					<td>${escapeHtml(expense.particular_name || '-')}</td>
					<td>${escapeHtml(formatTransactionType(expense.transaction_type) || '-')}</td>
					<td class="text-right">${Number(expense.credit || 0) > 0 ? escapeHtml(formatCurrencyValue(expense.credit)) : '-'}</td>
					<td class="text-right">${Number(expense.debit || 0) > 0 ? escapeHtml(formatCurrencyValue(expense.debit)) : '-'}</td>
				</tr>
			`).join('')
			: '<tr><td colspan="7" class="empty-state">No data available</td></tr>';

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
		.text-right { text-align: right; }
		.empty-state { text-align: center; padding: 16px 10px; color: #6b7280; }
		.summary { margin-top: 18px; padding-top: 14px; border-top: 2px solid #d1d5db; display: flex; justify-content: space-between; gap: 28px; font-size: 14px; color: #111827; }
		.summary-item { flex: 1; font-weight: 700; white-space: nowrap; }
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
				<div>Printed: ${escapeHtml(printedAt)}</div>
				<div>Printed By: ${escapeHtml(summaryPrintedByName)}</div>
			</div>
		</div>
	</div>
	<table>
		<thead>
			<tr>
				<th>Date</th>
				<th>Employee</th>
				<th>Category</th>
				<th>Particulars</th>
				<th>Type</th>
				<th class="text-right">Credit</th>
				<th class="text-right">Debit</th>
			</tr>
		</thead>
		<tbody>${rows}</tbody>
	</table>
	<div class="summary">
		<div class="summary-item">Total Records: <span class="summary-value">${summary.total_count ?? 0}</span></div>
		<div class="summary-item">Total Credits: <span class="summary-value">${formatCurrencyValue(summary.total_credits ?? 0)}</span></div>
		<div class="summary-item">Total Debits: <span class="summary-value">${formatCurrencyValue(summary.total_debits ?? 0)}</span></div>
	</div>
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
			printWindow.document.write(buildPrintHtml(data.expenses || [], data.summary || {}));
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
				<td class="py-4 px-4 text-sm text-gray-700">${formatDate(expense.expense_date)}</td>
				<td class="py-4 px-4 text-sm text-gray-700">${expense.employee_name}</td>
				<td class="py-4 px-4 text-sm text-gray-700">${expense.category_name || '-'}</td>
				<td class="py-4 px-4 text-sm text-gray-700">${expense.particular_name || '-'}</td>
				<td class="py-4 px-4">
					<span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ${getTransactionTypeBadgeClass(expense.transaction_type)}">
						${formatTransactionType(expense.transaction_type)}
					</span>
				</td>
				<td class="py-4 px-4 text-sm font-semibold text-right text-rose-600">${expense.credit > 0 ? 'PHP ' + parseFloat(expense.credit).toFixed(2) : '-'}</td>
				<td class="py-4 px-4 text-sm font-semibold text-right text-emerald-600">${expense.debit > 0 ? 'PHP ' + parseFloat(expense.debit).toFixed(2) : '-'}</td>
			</tr>
		`).join('');
	}

	function updateSummaryCards(data) {
		document.getElementById('summaryExpenseCount').textContent = data.summary.total_count;
		document.getElementById('summaryTotalDebits').textContent = 'PHP ' + parseFloat(data.summary.total_debits).toFixed(2);
		document.getElementById('summaryTotalCredits').textContent = 'PHP ' + parseFloat(data.summary.total_credits).toFixed(2);
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
	document.getElementById('summaryFilterModal').addEventListener('click', (event) => {
		if (event.target.id === 'summaryFilterModal') {
			closeFilterModal();
		}
	});

	document.getElementById('summaryPrintBtn').addEventListener('click', printSummary);

	// Initial load
	updateBalanceDisplay();
	loadExpenses(1);
</script>

@endsection
