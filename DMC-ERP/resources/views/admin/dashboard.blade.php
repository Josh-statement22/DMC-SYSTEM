@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

@php
    $formatCurrency = fn ($amount) => 'PHP ' . number_format((float) ($amount ?? 0), 2);
    $totalReceived = (float) ($monthlyTransactionSummary->total_received ?? 0);
    $totalLiquidated = (float) ($monthlyTransactionSummary->total_liquidated ?? 0);
    $totalTransactions = (int) ($monthlyTransactionSummary->transaction_count ?? 0);
    $maxCategoryAmount = max(1, (float) ($categorySummaries->max('total_amount') ?? 0));
    $distributionColors = ['#1C446D', '#255EC7', '#6999F1', '#10b981', '#0ea5a4'];
    $trendColors = ['#1C446D', '#255EC7', '#6999F1', '#10b981', '#0ea5a4'];
    $conicStart = 0;
    $conicSegments = [];

    foreach ($distribution as $index => $segment) {
        $end = $conicStart + (float) ($segment['percentage'] ?? 0);
        $conicSegments[] = ($distributionColors[$index % count($distributionColors)] ?? '#6999F1') . ' ' . $conicStart . '% ' . $end . '%';
        $conicStart = $end;
    }

    $pieBackground = count($conicSegments) > 0
        ? 'conic-gradient(' . implode(', ', $conicSegments) . ')'
        : 'conic-gradient(#e5e7eb 0% 100%)';
    $maxTrendAmount = max(1, (float) collect($monthlyTrend)->max('total_amount'));
    $categoryPrintRows = $categorySummaries->map(fn ($category) => [
        'category_name' => $category->category_name,
        'total_amount' => (float) $category->total_amount,
    ])->values();
@endphp

<div class="space-y-8">
    <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 bg-gradient-to-br from-[#1C446D] to-blue-700 rounded-2xl flex items-center justify-center shadow-lg">
                <i data-feather="bar-chart-2" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Admin Transaction Summary</h2>
                <p class="text-gray-500">Monthly summary of all cash advances received and liquidated</p>
            </div>
        </div>

        <div class="w-full sm:w-auto">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="w-full sm:w-72">
                    <select id="dashboardMonthFilter" name="month" onchange="this.form.submit()" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @foreach($availableMonths as $month)
                            <option value="{{ $month['value'] }}" {{ $month['value'] === $selectedMonthKey ? 'selected' : '' }}>
                                {{ $month['label'] }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <button id="printCategorySummaryBtn" type="button" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#1C446D] to-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:from-[#255EC7] hover:to-[#1C446D]">
                    <i data-feather="printer" class="w-4 h-4"></i>
                    Print Category Summary
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between gap-4">
                <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Total Cash Advance Received</p>
                <i data-feather="wallet" class="w-5 h-5 text-blue-600"></i>
            </div>
            <p class="mt-3 text-3xl font-bold text-blue-700">{{ $formatCurrency($totalReceived) }}</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between gap-4">
                <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Total Liquidated</p>
                <i data-feather="arrow-up-right" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="mt-3 text-3xl font-bold text-emerald-700">{{ $formatCurrency($totalLiquidated) }}</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between gap-4">
                <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Liquidation Count</p>
                <i data-feather="list" class="w-5 h-5 text-blue-700"></i>
            </div>
            <p class="mt-3 text-3xl font-bold text-blue-800">{{ number_format($totalTransactions) }}</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-lg border border-gray-100">
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Category Analytics</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $selectedMonth->format('F Y') }} category spending for your account</p>
            </div>
            <p class="text-sm font-semibold text-blue-700">Total Category Spend: {{ $formatCurrency($totalCategoryAmount) }}</p>
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
                            <div class="h-full rounded-full bg-blue-600" style="width: {{ $barWidth }}%"></div>
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
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">{{ $index + 1 }}</span>
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
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-800">Monthly Category Trend</h3>
                <p class="text-sm text-gray-500 mt-1">Stacked category expenses over the last six months</p>
            </div>

            <div class="space-y-5">
                @foreach($monthlyTrend as $month)
                    @php
                        $totalAmount = (float) ($month['total_amount'] ?? 0);
                        $rowWidth = max(2, ($totalAmount / $maxTrendAmount) * 100);
                    @endphp
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-[7rem_1fr_9rem] md:items-center">
                        <p class="text-sm font-semibold text-gray-700">{{ $month['label'] }}</p>
                        <div class="h-5 overflow-hidden rounded-full bg-gray-100" style="width: {{ $rowWidth }}%">
                            <div class="flex h-full w-full">
                                @foreach($month['categories'] as $categoryIndex => $category)
                                    @php
                                        $segmentWidth = $totalAmount > 0 ? (((float) $category['total_amount'] / $totalAmount) * 100) : 0;
                                    @endphp
                                    @if($segmentWidth > 0)
                                        <div class="h-full" title="{{ $category['category_name'] }} - {{ $formatCurrency($category['total_amount']) }}" style="width: {{ $segmentWidth }}%; background: {{ $trendColors[$categoryIndex % count($trendColors)] }}"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <p class="text-sm font-bold text-gray-800 md:text-right">{{ $formatCurrency($totalAmount) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex flex-wrap gap-4 text-xs font-semibold text-gray-500">
                @foreach($topCategories->take(4)->values() as $index => $category)
                    <span class="inline-flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $trendColors[$index % count($trendColors)] }}"></span>
                        {{ $category->category_name }}
                    </span>
                @endforeach
                @if($categorySummaries->count() > 4)
                    <span class="inline-flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $trendColors[4] }}"></span>
                        Others
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    const dashboardCategoryRows = @json($categoryPrintRows);
    const dashboardGrandTotal = @json((float) $totalCategoryAmount);
    const dashboardPeriodLabel = @json($selectedMonth->format('F Y'));
    const dashboardPeriodRange = @json($selectedMonth->format('F j, Y') . ' - ' . $selectedMonth->copy()->endOfMonth()->format('F j, Y'));
    const dashboardPrintedByName = @json(Auth::user()->name ?? Auth::user()->email ?? 'Admin');
    const dashboardCompanyName = 'DMC ERP';

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
        .report-page { position: relative; min-height: 100vh; padding-bottom: 24px; }
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
        tfoot td { font-size: 13px; font-weight: 800; background: #f9fafb; }
        .footer { position: fixed; right: 14mm; bottom: 8mm; font-size: 10px; color: #6b7280; }
        .footer::after { content: "Page " counter(page) " of " counter(pages); }
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
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="amount-col text-right">Total Amount</th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>
            <tfoot>
                <tr>
                    <td>Grand Total</td>
                    <td class="text-right">${formatDashboardCurrency(dashboardGrandTotal)}</td>
                </tr>
            </tfoot>
        </table>
        <div class="footer"></div>
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