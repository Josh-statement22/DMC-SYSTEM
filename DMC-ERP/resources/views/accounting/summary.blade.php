@extends('layouts.accounting')
@section('title', 'Accounting - Summary')

@section('content')

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
				<div class="flex flex-col gap-4">
					<div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
						<div class="md:col-span-1">
							<label class="block text-sm font-semibold text-gray-700 mb-2">Employee</label>
							<select id="summaryEmployeeFilter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white">
								<option value="">All Employees</option>
								@foreach($employees as $employee)
									<option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})</option>
								@endforeach
							</select>
						</div>

						<div class="md:col-span-1">
							<label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
							<select id="summaryMonthFilter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white">
								<option value="" selected>Select Month</option>
								@php
									for ($i = 0; $i < 12; $i++) {
										$date = now()->subMonths($i);
										$value = $date->format('Y-m');
										$display = $date->format('F Y');
										echo "<option value=\"$value\">$display</option>";
									}
								@endphp
							</select>
						</div>

						<div class="md:col-span-1">
							<button id="summaryThisWeekBtn" type="button" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-blue-100 text-blue-700 font-semibold hover:bg-blue-200 transition-all duration-200">
								<i data-feather="calendar" class="w-4 h-4"></i>
								<span class="hidden sm:inline">This Week</span>
								<span class="sm:hidden">Week</span>
							</button>
						</div>

						<div class="md:col-span-1">
							<button id="summaryThisMonthBtn" type="button" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-emerald-100 text-emerald-700 font-semibold hover:bg-emerald-200 transition-all duration-200">
								<i data-feather="grid" class="w-4 h-4"></i>
								<span class="hidden sm:inline">This Month</span>
								<span class="sm:hidden">Month</span>
							</button>
						</div>

						<div class="md:col-span-2 flex gap-2">
							<button id="summaryFilterBtn" type="button" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold hover:shadow-lg transition-all duration-300">
								<i data-feather="filter" class="w-4 h-4"></i>
								<span class="hidden sm:inline">Filter</span>
							</button>
							<button id="summaryClearBtn" type="button" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all duration-200">
								<i data-feather="x" class="w-4 h-4"></i>
								<span class="hidden sm:inline">Clear</span>
							</button>
						</div>
					</div>
				</div>
			</div>



			<!-- Expenses Table -->
			<div class="rounded-3xl bg-white p-6 shadow-lg border border-gray-100 overflow-hidden">
				<div class="mb-6">
					<h3 class="text-xl font-bold text-gray-800">Liquidation Expenses</h3>
					<p class="text-sm text-gray-500 mt-1"><span id="summaryExpenseCount">0</span> records</p>
				</div>

				<div class="overflow-x-auto">
					<table class="w-full">
						<thead>
							<tr class="border-b border-gray-200 text-left text-sm font-semibold text-gray-600 bg-gray-50">
								<th class="py-4 px-4">Date</th>
								<th class="py-4 px-4">Employee Name</th>
								<th class="py-4 px-4">Category</th>
								<th class="py-4 px-4">Particulars</th>
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
	let openingBalance = 0;
	let endingBalance = 0;

	function updateBalanceDisplay() {
		document.getElementById('displayOpeningBalance').textContent = 'PHP ' + openingBalance.toFixed(2);
		document.getElementById('displayEndingBalance').textContent = 'PHP ' + endingBalance.toFixed(2);
	}

	// Get date range based on period selection
	let currentPeriod = 'this-month';

	function getDateRange() {
		const selectedMonth = document.getElementById('summaryMonthFilter').value;
		const now = new Date();
		let fromDate, toDate;

		if (currentPeriod === 'this-month') {
			fromDate = new Date(now.getFullYear(), now.getMonth(), 1);
			toDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
		} else if (currentPeriod === 'this-week') {
			const day = now.getDate();
			const diff = now.getDay() === 0 ? -6 : now.getDay() - 1; // Monday = 1
			fromDate = new Date(now);
			fromDate.setDate(day - diff);
			toDate = new Date(fromDate);
			toDate.setDate(toDate.getDate() + 6);
		} else if (currentPeriod === 'custom' && selectedMonth) {
			const [year, month] = selectedMonth.split('-');
			fromDate = new Date(year, month - 1, 1);
			toDate = new Date(year, month, 0);
		} else {
			// Default to this month
			fromDate = new Date(now.getFullYear(), now.getMonth(), 1);
			toDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
		}

		return {
			from_date: fromDate.toISOString().split('T')[0],
			to_date: toDate.toISOString().split('T')[0]
		};
	}

	// Period button handlers
	document.getElementById('summaryThisWeekBtn').addEventListener('click', () => {
		currentPeriod = 'this-week';
		loadExpenses(1);
	});

	document.getElementById('summaryThisMonthBtn').addEventListener('click', () => {
		currentPeriod = 'this-month';
		loadExpenses(1);
	});

	// Load expenses data
	async function loadExpenses(page = 1) {
		const employeeId = document.getElementById('summaryEmployeeFilter').value;
		const dateRange = getDateRange();

		if (!dateRange) return;

		try {
			const response = await fetch(`/accounting/summary/data?page=${page}${employeeId ? '&employee_id=' + employeeId : ''}&from_date=${dateRange.from_date}&to_date=${dateRange.to_date}`);
			const data = await response.json();

			renderTable(data.expenses);
			updateSummaryCards(data);
			updatePagination(data.pagination);
		} catch (error) {
			console.error('Error loading expenses:', error);
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
						${expense.transaction_type}
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
			'Expense': 'bg-rose-100 text-rose-700 border border-rose-200',
			'Refund': 'bg-blue-100 text-blue-700 border border-blue-200',
			'Advance': 'bg-emerald-100 text-emerald-700 border border-emerald-200',
			'Adjustment': 'bg-amber-100 text-amber-700 border border-amber-200',
		};
		return badgeMap[type] || 'bg-gray-100 text-gray-700 border border-gray-200';
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
		const selectedMonth = document.getElementById('summaryMonthFilter').value;
		if (selectedMonth) {
			currentPeriod = 'custom';
		} else {
			currentPeriod = 'this-month';
		}
		loadExpenses(1);
	});

	document.getElementById('summaryClearBtn').addEventListener('click', () => {
		document.getElementById('summaryEmployeeFilter').value = '';
		document.getElementById('summaryMonthFilter').value = '';
		currentPeriod = 'this-month';
		loadExpenses(1);
	});

	// Initial load
	currentPeriod = 'this-month';
	loadExpenses(1);
</script>

@endsection
