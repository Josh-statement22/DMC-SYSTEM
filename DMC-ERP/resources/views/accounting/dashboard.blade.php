@extends('layouts.accounting')
@section('title', 'Accounting - Dashboard')

@section('content')
@php
	$formatCurrency = fn ($amount) => 'PHP ' . number_format((float) ($amount ?? 0), 2);
	$openingBalance = (float) ($currentMonthlyBalance->opening_balance ?? 0);
	$totalDebits = (float) ($monthlyTransactionSummary->total_debits ?? 0);
	$totalCredits = (float) ($monthlyTransactionSummary->total_credits ?? 0);
	$totalTransactions = (int) ($monthlyTransactionSummary->transaction_count ?? 0);
	$maxCategoryAmount = max(1, (float) ($categorySummaries->max('total_amount') ?? 0));
	$distributionColors = ['#0f766e', '#0891b2', '#e11d48', '#7c3aed', '#64748b'];
	$conicStart = 0;
	$conicSegments = [];

	foreach ($distribution as $index => $segment) {
		$end = $conicStart + (float) ($segment['percentage'] ?? 0);
		$conicSegments[] = ($distributionColors[$index % count($distributionColors)] ?? '#64748b') . ' ' . $conicStart . '% ' . $end . '%';
		$conicStart = $end;
	}

	$pieBackground = count($conicSegments) > 0
		? 'conic-gradient(' . implode(', ', $conicSegments) . ')'
		: 'conic-gradient(#e5e7eb 0% 100%)';
	$categoryPrintRows = $categorySummaries->map(fn ($category) => [
		'category_name' => $category->category_name,
		'total_amount' => (float) $category->total_amount,
	])->values();
@endphp

<div class="space-y-8">
	<div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
		<div class="flex items-center space-x-4">
			<div class="w-14 h-14 bg-gradient-to-br from-emerald-700 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg">
				<i data-feather="bar-chart-2" class="w-7 h-7 text-white"></i>
			</div>
			<div>
				<h2 class="text-3xl font-bold text-gray-800">Accounting Analytics Dashboard</h2>
				<p class="text-gray-500">Monthly financial insights, category allocation, and spending trends</p>
			</div>
		</div>

		<div class="w-full sm:w-auto">
			<label for="dashboardMonthFilter" class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
			<div class="flex flex-col gap-3 sm:flex-row">
				<form method="GET" action="{{ route('accounting.dashboard') }}" class="w-full sm:w-72">
					<select id="dashboardMonthFilter" name="month" onchange="this.form.submit()" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200">
						@foreach($availableMonths as $month)
							<option value="{{ is_array($month) ? $month['value'] : $month->value }}" {{ (is_array($month) ? $month['value'] : $month->value) === $selectedMonthKey ? 'selected' : '' }}>
								{{ is_array($month) ? $month['label'] : $month->label }}
							</option>
						@endforeach
					</select>
				</form>
				<button id="printCategorySummaryBtn" type="button" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
					<i data-feather="printer" class="w-4 h-4"></i>
					Print Category Summary
				</button>
			</div>
		</div>
	</div>

	<div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
		<div class="rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
			<div class="flex items-center justify-between gap-4">
				<p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Opening Balance</p>
				<i data-feather="wallet" class="w-5 h-5 text-emerald-600"></i>
			</div>
			<p class="mt-3 text-3xl font-bold text-emerald-700">{{ $formatCurrency($openingBalance) }}</p>
		</div>

		<div class="rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
			<div class="flex items-center justify-between gap-4">
				<p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Total Debits</p>
				<i data-feather="arrow-up-right" class="w-5 h-5 text-rose-600"></i>
			</div>
			<p class="mt-3 text-3xl font-bold text-rose-600">{{ $formatCurrency($totalDebits) }}</p>
		</div>

		<div class="rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
			<div class="flex items-center justify-between gap-4">
				<p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Total Credits</p>
				<i data-feather="arrow-down-left" class="w-5 h-5 text-cyan-700"></i>
			</div>
			<p class="mt-3 text-3xl font-bold text-cyan-700">{{ $formatCurrency($totalCredits) }}</p>
		</div>

		<div class="rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
			<div class="flex items-center justify-between gap-4">
				<p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Total Transactions</p>
				<i data-feather="list" class="w-5 h-5 text-slate-700"></i>
			</div>
			<p class="mt-3 text-3xl font-bold text-slate-800">{{ number_format($totalTransactions) }}</p>
		</div>
	</div>

	<div class="rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
		<div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
			<div>
				<h3 class="text-xl font-bold text-gray-800">Category Analytics</h3>
				<p class="text-sm text-gray-500 mt-1">{{ $selectedMonth->format('F Y') }} category spending based on transaction categories and breakdown allocations</p>
			</div>
			<p class="text-sm font-semibold text-emerald-700">Total Category Spend: {{ $formatCurrency($totalCategoryAmount) }}</p>
		</div>

		<div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
			@forelse($categorySummaries as $category)
				<div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
					<p class="text-sm font-semibold text-gray-700">{{ $category->category_name }}</p>
					<p class="mt-2 text-xl font-bold text-gray-900">{{ $formatCurrency($category->total_amount) }}</p>
					<p class="mt-1 text-xs text-gray-500">{{ number_format((int) $category->transaction_count) }} transactions</p>
				</div>
			@empty
				<div class="rounded-xl border border-dashed border-gray-300 p-6 text-sm text-gray-500">
					No category activity for this month.
				</div>
			@endforelse
		</div>
	</div>

	<div class="grid grid-cols-1 gap-8 xl:grid-cols-5">
		<div class="xl:col-span-3 rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
			<div class="mb-6">
				<h3 class="text-xl font-bold text-gray-800">Category Expense Chart</h3>
				<p class="text-sm text-gray-500 mt-1">Highest to lowest category amounts</p>
			</div>

			<div class="space-y-5">
				@forelse($categorySummaries as $category)
					@php
						$barWidth = max(2, ((float) $category->total_amount / $maxCategoryAmount) * 100);
					@endphp
					<div class="grid grid-cols-1 gap-2 md:grid-cols-[12rem_1fr_9rem] md:items-center">
						<p class="text-sm font-semibold text-gray-700 truncate">{{ $category->category_name }}</p>
						<div class="h-4 overflow-hidden rounded-full bg-gray-100">
							<div class="h-full rounded-full bg-emerald-600" style="width: {{ $barWidth }}%"></div>
						</div>
						<p class="text-sm font-bold text-gray-800 md:text-right">{{ $formatCurrency($category->total_amount) }}</p>
					</div>
				@empty
					<div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
						No category chart data available.
					</div>
				@endforelse
			</div>
		</div>

		<div class="xl:col-span-2 rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
			<div class="mb-6">
				<h3 class="text-xl font-bold text-gray-800">Category Distribution</h3>
				<p class="text-sm text-gray-500 mt-1">Percentage allocation by category</p>
			</div>

			<div class="flex flex-col items-center gap-6">
				<div class="relative h-56 w-56 rounded-full" style="background: {{ $pieBackground }}">
					<div class="absolute inset-12 rounded-full bg-white flex items-center justify-center text-center">
						<div>
							<p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total</p>
							<p class="text-sm font-bold text-gray-800">{{ $formatCurrency($totalCategoryAmount) }}</p>
						</div>
					</div>
				</div>

				<div class="w-full space-y-3">
					@forelse($distribution as $index => $segment)
						<div class="flex items-center justify-between gap-4 text-sm">
							<div class="flex min-w-0 items-center gap-2">
								<span class="h-3 w-3 shrink-0 rounded-full" style="background: {{ $distributionColors[$index % count($distributionColors)] }}"></span>
								<span class="truncate font-semibold text-gray-700">{{ $segment['category_name'] }}</span>
							</div>
							<span class="font-bold text-gray-900">{{ number_format((float) $segment['percentage'], 1) }}%</span>
						</div>
					@empty
						<p class="text-center text-sm text-gray-500">No distribution data available.</p>
					@endforelse
				</div>
			</div>
		</div>
	</div>

	<div class="grid grid-cols-1 gap-8 xl:grid-cols-3">
		<div class="rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
			<div class="mb-6">
				<h3 class="text-xl font-bold text-gray-800">Top Categories</h3>
				<p class="text-sm text-gray-500 mt-1">Top 5 by selected month amount</p>
			</div>

			<div class="space-y-4">
				@forelse($topCategories as $index => $category)
					<div class="flex items-center justify-between gap-4 rounded-xl border border-gray-100 bg-gray-50 p-4">
						<div class="flex min-w-0 items-center gap-3">
							<span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">{{ $index + 1 }}</span>
							<p class="truncate font-semibold text-gray-800">{{ $category->category_name }}</p>
						</div>
						<p class="shrink-0 text-sm font-bold text-gray-900">{{ $formatCurrency($category->total_amount) }}</p>
					</div>
				@empty
					<div class="rounded-xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">
						No top categories for this month.
					</div>
				@endforelse
			</div>
		</div>

		<div class="xl:col-span-2 rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
			<div class="mb-6 flex flex-col gap-4 2xl:flex-row 2xl:items-end 2xl:justify-between">
				<div>
					<h3 class="text-xl font-bold text-gray-800">Category Expense Matrix</h3>
					<p class="text-sm text-gray-500 mt-1">
						Debit spending from {{ \Carbon\Carbon::parse($categoryExpenseMatrix['filters']['effective_start_date'] ?? now())->format('M d, Y') }}
						to {{ \Carbon\Carbon::parse($categoryExpenseMatrix['filters']['effective_end_date'] ?? now())->format('M d, Y') }}
					</p>
					<p class="mt-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">2026 months only</p>
				</div>
				<div class="flex w-full flex-col gap-3 2xl:w-auto 2xl:items-end">
					<form method="GET" action="{{ route('accounting.dashboard') }}" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end 2xl:justify-end">
						<input type="hidden" name="month" value="{{ $selectedMonthKey }}">
						<div class="w-full sm:w-44">
							<label for="categoryMatrixPeriod" class="block text-sm font-semibold text-gray-700 mb-2">Period</label>
							<select id="categoryMatrixPeriod" name="matrix_period" class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
								<option value="last_3" {{ ($categoryExpenseMatrix['filters']['period'] ?? 'last_6') === 'last_3' ? 'selected' : '' }}>Last 3 Months</option>
								<option value="last_6" {{ ($categoryExpenseMatrix['filters']['period'] ?? 'last_6') === 'last_6' ? 'selected' : '' }}>Last 6 Months</option>
								<option value="last_12" {{ ($categoryExpenseMatrix['filters']['period'] ?? 'last_6') === 'last_12' ? 'selected' : '' }}>Last 12 Months</option>
								<option value="custom" {{ ($categoryExpenseMatrix['filters']['period'] ?? 'last_6') === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
							</select>
						</div>
						<div class="categoryMatrixCustomField w-full sm:w-40">
							<label for="categoryMatrixStartDate" class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
							<input id="categoryMatrixStartDate" name="matrix_start_date" type="date" min="2026-01-01" max="2026-12-31" value="{{ $categoryExpenseMatrix['filters']['start_date'] ?? '' }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
						</div>
						<div class="categoryMatrixCustomField w-full sm:w-40">
							<label for="categoryMatrixEndDate" class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
							<input id="categoryMatrixEndDate" name="matrix_end_date" type="date" min="2026-01-01" max="2026-12-31" value="{{ $categoryExpenseMatrix['filters']['end_date'] ?? '' }}" class="h-10 w-full rounded-lg border border-gray-300 px-3 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
						</div>
						<button type="submit" class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-lg bg-emerald-700 px-4 text-sm font-semibold text-white transition hover:bg-emerald-800 sm:w-auto">
							<i data-feather="filter" class="w-4 h-4"></i>
							Apply
						</button>
					</form>
					<div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap 2xl:justify-end">
						<a href="{{ route('accounting.category-expense-matrix.export', array_merge(['format' => 'excel'], $categoryMatrixExportQuery ?? [])) }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
							<i data-feather="download" class="w-4 h-4"></i>
							Export Excel
						</a>
						<a href="{{ route('accounting.category-expense-matrix.export', array_merge(['format' => 'pdf'], $categoryMatrixExportQuery ?? [])) }}" target="_blank" rel="noopener" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-4 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
							<i data-feather="file-text" class="w-4 h-4"></i>
							Export PDF
						</a>
					</div>
				</div>
			</div>

			@if(!($categoryExpenseMatrix['has_data'] ?? false))
				<div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-sm font-semibold text-gray-500">
					No category spending data available.
				</div>
			@else
				<div class="overflow-x-auto rounded-xl border border-gray-200">
					<table class="w-full min-w-[820px] border-separate border-spacing-0 text-sm">
						<thead>
							<tr class="bg-gray-50 text-xs font-bold uppercase tracking-wide text-gray-500">
								<th class="sticky left-0 z-20 border-b border-gray-200 bg-gray-50 px-4 py-3 text-left">Category</th>
								@foreach($categoryExpenseMatrix['months'] ?? [] as $matrixMonth)
									<th class="border-b border-gray-200 px-4 py-3 text-right">{{ $matrixMonth['label'] }}</th>
								@endforeach
								<th class="border-b border-gray-200 bg-slate-100 px-4 py-3 text-right text-slate-700">Total</th>
							</tr>
						</thead>
						<tbody>
							@foreach($categoryExpenseMatrix['rows'] ?? [] as $matrixRow)
								<tr class="group hover:bg-emerald-50/40">
									<th class="sticky left-0 z-10 border-b border-gray-100 bg-white px-4 py-3 text-left font-semibold text-gray-800 group-hover:bg-emerald-50">
										<a href="{{ $matrixRow['transactions_url'] }}" class="inline-flex items-center gap-2 text-emerald-700 hover:text-emerald-900">
											<span>{{ $matrixRow['category_name'] }}</span>
											<i data-feather="arrow-up-right" class="w-3.5 h-3.5"></i>
										</a>
									</th>
									@foreach($categoryExpenseMatrix['months'] ?? [] as $matrixMonth)
										@php
											$matrixAmount = (float) ($matrixRow['amounts'][$matrixMonth['key']] ?? 0);
											$isHighest = $matrixAmount > 0 && round($matrixAmount, 2) === round((float) ($categoryExpenseMatrix['highest_by_month'][$matrixMonth['key']] ?? 0), 2);
										@endphp
										<td class="border-b border-gray-100 px-4 py-3 text-right font-medium {{ $isHighest ? 'bg-amber-50 text-amber-900' : 'text-gray-700' }}">
											{{ $formatCurrency($matrixAmount) }}
										</td>
									@endforeach
									<td class="border-b border-gray-100 bg-slate-50 px-4 py-3 text-right font-bold text-slate-900">
										{{ $formatCurrency($matrixRow['total'] ?? 0) }}
									</td>
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr class="bg-slate-900 text-white">
								<th class="sticky left-0 z-20 bg-slate-900 px-4 py-3 text-left font-bold">TOTAL</th>
								@foreach($categoryExpenseMatrix['months'] ?? [] as $matrixMonth)
									<td class="px-4 py-3 text-right font-bold">{{ $formatCurrency($categoryExpenseMatrix['column_totals'][$matrixMonth['key']] ?? 0) }}</td>
								@endforeach
								<td class="px-4 py-3 text-right font-bold">{{ $formatCurrency($categoryExpenseMatrix['grand_total'] ?? 0) }}</td>
							</tr>
						</tfoot>
					</table>
				</div>
			@endif
		</div>
	</div>
</div>

<script>
	const dashboardCategoryRows = @json($categoryPrintRows);
	const dashboardGrandTotal = @json((float) $totalCategoryAmount);
	const dashboardPeriodLabel = @json($selectedMonth->format('F Y'));
	const dashboardPeriodRange = @json($selectedMonth->format('F j, Y') . ' - ' . $selectedMonth->copy()->endOfMonth()->format('F j, Y'));
	const dashboardPrintedByName = @json(Auth::user()->name ?? Auth::user()->email ?? 'Accounting');
	const dashboardCompanyName = 'DMC ERP';
	const categoryMatrixPeriod = document.getElementById('categoryMatrixPeriod');
	const categoryMatrixCustomFields = document.querySelectorAll('.categoryMatrixCustomField');

	function syncCategoryMatrixCustomFields() {
		const showCustomFields = categoryMatrixPeriod?.value === 'custom';
		categoryMatrixCustomFields.forEach(field => {
			field.classList.toggle('hidden', !showCustomFields);
		});
	}

	categoryMatrixPeriod?.addEventListener('change', syncCategoryMatrixCustomFields);
	syncCategoryMatrixCustomFields();

	function formatDashboardCurrency(amount) {
		return 'PHP ' + Number(amount || 0).toLocaleString('en-US', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2,
		});
	}

	function escapeDashboardHtml(value) {
		return String(value ?? '')
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#39;');
	}

	function buildCategorySummaryPrintHtml() {
		const printedAt = new Date().toLocaleString('en-US', {
			month: '2-digit',
			day: '2-digit',
			year: 'numeric',
			hour: 'numeric',
			minute: '2-digit',
		}).replace(',', '');
		const rows = dashboardCategoryRows.length
			? dashboardCategoryRows.map(row => `
				<tr>
					<td>${escapeDashboardHtml(row.category_name || '-')}</td>
					<td class="text-right">${formatDashboardCurrency(row.total_amount)}</td>
				</tr>
			`).join('')
			: '<tr><td colspan="2" class="empty-state">No category expenses for the selected month.</td></tr>';

		return `<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Category Expense Summary - ${escapeDashboardHtml(dashboardPeriodLabel)}</title>
	<style>
		@page { size: A4 landscape; margin: 14mm; }
		* { box-sizing: border-box; }
		body { font-family: Arial, sans-serif; color: #111827; margin: 0; }
		.report-page { min-height: 100vh; padding-bottom: 24px; }
		.header { display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; margin-bottom: 18px; border-bottom: 2px solid #111827; padding-bottom: 14px; }
		.company { font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 8px; }
		.title { font-size: 24px; font-weight: 800; letter-spacing: .04em; margin: 0; color: #0f172a; text-transform: uppercase; }
		.meta { font-size: 12px; color: #374151; line-height: 1.7; text-align: right; }
		table { width: 100%; border-collapse: collapse; table-layout: fixed; }
		th, td { border: 1px solid #d1d5db; padding: 10px 12px; font-size: 12px; vertical-align: top; }
		th { background: #f3f4f6; color: #111827; font-weight: 800; text-align: left; }
		.amount-col { width: 32%; }
		.text-right { text-align: right; }
		.empty-state { text-align: center; color: #6b7280; padding: 18px 12px; }
		.grand-total td { border-top: 2px solid #111827; font-size: 13px; font-weight: 800; background: #f9fafb; }
		@media print {
			.grand-total {
				position: static !important;
				page-break-inside: avoid;
				break-inside: avoid;
				margin-top: 20px;
			}
		}
	</style>
</head>
<body>
	<div class="report-page">
		<div class="header">
			<div>
				<p class="company">${escapeDashboardHtml(dashboardCompanyName)}</p>
				<p class="title">Category Expense Summary</p>
				<div class="meta" style="text-align: left; margin-top: 8px;">
					<div>Selected Month: ${escapeDashboardHtml(dashboardPeriodLabel)}</div>
					<div>Period: ${escapeDashboardHtml(dashboardPeriodRange)}</div>
				</div>
			</div>
			<div class="meta">
				<div>Printed: ${escapeDashboardHtml(printedAt)}</div>
				<div>Printed By: ${escapeDashboardHtml(dashboardPrintedByName)}</div>
			</div>
		</div>
		<table>
			<tbody>
				<tr class="table-heading">
					<th>Category</th>
					<th class="amount-col text-right">Total Amount</th>
				</tr>
				${rows}
				<tr class="grand-total">
					<td>Grand Total</td>
					<td class="text-right">${formatDashboardCurrency(dashboardGrandTotal)}</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>`;
	}

	document.getElementById('printCategorySummaryBtn')?.addEventListener('click', function () {
		const printWindow = window.open('', '_blank', 'width=1200,height=900');
		if (!printWindow) {
			alert('Popup blocked. Please allow popups to print the category summary.');
			return;
		}

		printWindow.document.write(buildCategorySummaryPrintHtml());
		printWindow.document.close();
		setTimeout(() => {
			printWindow.focus();
			printWindow.print();
		}, 250);
	});
</script>
@endsection
