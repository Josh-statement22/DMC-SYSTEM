@extends('layouts.accounting')
@section('title', 'Accounting - Cash Disbursement')

@section('content')

<div class="space-y-8">
	<div id="accountingToast" class="hidden fixed top-6 right-6 z-50 p-4 rounded-xl shadow-xl flex items-start gap-3 transition-opacity duration-500">
		<i id="accountingToastIcon" data-feather="check-circle" class="w-5 h-5 mt-0.5"></i>
		<p id="accountingToastText" class="text-sm font-medium">Notification</p>
	</div>

	<div id="cashAdvanceTab" class="space-y-8">

	<div class="flex items-center justify-between">
		<div class="flex items-center space-x-4">
			<div class="w-14 h-14 bg-gradient-to-br from-emerald-700 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg">
				<i data-feather="send" class="w-7 h-7 text-white"></i>
			</div>
			<div>
				<h2 class="text-3xl font-bold text-gray-800">Employee Cash Disbursement</h2>
				<p class="text-gray-500">Send funds with a clear purpose statement</p>
			</div>
		</div>
		<button id="openQueueBtn" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-sm border border-emerald-200 hover:bg-emerald-100 transition-all duration-200">
			<i data-feather="inbox" class="w-4 h-4"></i>
			<span id="queueBadgeText">Accounting Queue: 0 Pending</span>
		</button>
	</div>

	<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
		<div class="xl:col-span-2 rounded-3xl bg-white p-8 shadow-2xl border border-gray-100">
			<div class="mb-6">
				<h3 class="text-2xl font-bold text-gray-800">Send New Cash Advance</h3>
				<p class="text-gray-500 mt-1">Submission now saves the monthly balance to the database.</p>
			</div>

			<form id="sendCashAdvanceForm" class="space-y-6">
				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<div>
						<label class="block text-sm font-semibold text-gray-700 mb-2">Employee <span class="text-red-500">*</span></label>
						<select id="sendEmployeeId" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 bg-white" required>
							<option value="" selected disabled>Select employee</option>
							@foreach($employees as $employee)
							<option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})</option>
							@endforeach
						</select>
					</div>

					<div>
						<label class="block text-sm font-semibold text-gray-700 mb-2">Disbursement Date <span class="text-red-500">*</span></label>
						<input id="sendReleaseDate" type="date" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200" required>
					</div>
				</div>

				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-2">Amount <span class="text-red-500">*</span></label>
					<div class="relative">
						<span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-700 font-semibold">PHP</span>
						<input id="sendAmount" type="number" min="0" step="0.01" placeholder="0.00" class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200" required>
					</div>
				</div>

				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-2">Purpose <span class="text-red-500">*</span></label>
					<textarea id="sendPurpose" rows="3" placeholder="Example: Cash advance for project site materials and transportation." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 resize-none" required></textarea>
				</div>

				<div>
					<label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
					<textarea id="sendNotes" rows="2" placeholder="Optional internal note" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 resize-none"></textarea>
				</div>

				<div class="pt-2 flex items-center justify-end gap-3">
					<button type="button" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all duration-200">
						Save Draft
					</button>
					<button type="submit" class="inline-flex items-center gap-2 px-7 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
						<i data-feather="send" class="w-4 h-4"></i>
						Send Money
					</button>
				</div>
			</form>
		</div>

		<div class="rounded-3xl bg-gradient-to-br from-slate-800 to-slate-900 text-white p-7 shadow-2xl relative overflow-hidden">
			<div class="absolute -top-12 -right-12 w-40 h-40 bg-emerald-300/20 rounded-full blur-3xl"></div>
			<div class="relative z-10 space-y-6">
				<div>
					<p class="text-sm uppercase tracking-wide text-emerald-200">Budget Window</p>
					<p id="todayBudgetDate" class="text-xs text-emerald-100 mt-1">Today: --</p>
					<p id="budgetMonthLabel" class="text-xs text-emerald-100 mt-1">Opening Balance for Current Month</p>
					<p id="budgetWindowLimit" class="text-4xl font-extrabold mt-1">PHP 0.00</p>
					<button id="openMonthlyBudgetBtn" type="button" class="mt-3 inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/15 hover:bg-white/25 text-emerald-50 text-xs font-semibold border border-white/20 transition-all duration-200">
						<i data-feather="edit-3" class="w-3.5 h-3.5"></i>
						<span>Set Monthly Balance</span>
					</button>
				</div>
				<div class="grid grid-cols-2 gap-4">
					<div class="rounded-2xl bg-white/10 p-4">
						<p class="text-xs text-gray-200">Sent This Month</p>
						<p id="budgetSentToday" class="text-xl font-bold mt-1">PHP 0.00</p>
					</div>
					<div class="rounded-2xl bg-white/10 p-4">
						<p class="text-xs text-gray-200">Remaining This Month</p>
						<p id="budgetRemainingToday" class="text-xl font-bold mt-1">PHP 0.00</p>
					</div>
				</div>
				<div class="rounded-2xl bg-emerald-500/20 border border-emerald-300/30 p-4">
					<p class="text-sm text-emerald-100">Tip</p>
					<p class="text-sm mt-1 leading-relaxed text-emerald-50">Always include a specific project or activity in the purpose to speed up approval and liquidation.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="rounded-3xl bg-white p-8 shadow-2xl border border-gray-100">
		<div class="flex items-center justify-between mb-5">
			<h3 class="text-xl font-bold text-gray-800">Recent Disbursements</h3>
			<button class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">View All</button>
		</div>
		<div class="overflow-x-auto">
			<table class="w-full">
				<thead>
					<tr class="border-b border-gray-200">
						<th class="text-left py-3 px-3 text-sm font-semibold text-gray-600">Date</th>
						<th class="text-left py-3 px-3 text-sm font-semibold text-gray-600">Employee</th>
						<th class="text-left py-3 px-3 text-sm font-semibold text-gray-600">Purpose</th>
						<th class="text-left py-3 px-3 text-sm font-semibold text-gray-600">Processed By</th>
						<th class="text-right py-3 px-3 text-sm font-semibold text-gray-600">Amount</th>
						<th class="text-center py-3 px-3 text-sm font-semibold text-gray-600">Status</th>
					</tr>
				</thead>
				<tbody id="recentDisbursementBody"></tbody>
			</table>
		</div>
			<p id="recentDisbursementEmpty" class="hidden text-center text-sm text-gray-500 py-8">
				No transactions yet. Submitted requests and accounting decisions will appear here in real time.
			</p>
	</div>
	</div>

</div>

<!-- ACCOUNTING QUEUE MODAL -->
<div id="queueModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
	<div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden">
		<div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-5 sm:p-6">
			<div class="flex items-center justify-between gap-4">
				<div>
					<h3 class="text-xl sm:text-2xl font-bold text-white">Cash Advance Request Queue</h3>
					<p class="text-emerald-100 text-sm mt-1">All employee requests awaiting accounting review</p>
				</div>
				<button id="closeQueueBtn" type="button" class="text-white hover:text-emerald-100 transition">
					<i data-feather="x" class="w-6 h-6"></i>
				</button>
			</div>
		</div>

		<div class="p-4 sm:p-6 overflow-y-auto max-h-[calc(90vh-110px)]">
			<div class="mb-4 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3">
				<p class="text-xs text-amber-700 font-semibold uppercase tracking-wide">Workflow</p>
				<p class="text-sm text-amber-800 mt-1">Approve or reject pending requests to update employee liquidation status in real time.</p>
			</div>
			<div id="queueList" class="space-y-4"></div>
			<div id="queueEmptyState" class="hidden rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
				<i data-feather="inbox" class="w-8 h-8 text-gray-400 mx-auto"></i>
				<p class="text-gray-600 font-semibold mt-3">No cash advance requests yet.</p>
				<p class="text-sm text-gray-500 mt-1">Submitted requests from employees/admin will appear here.</p>
			</div>

			<div class="mt-8">
				<h4 class="text-sm uppercase tracking-wide text-gray-500 font-semibold mb-3">Recently Processed</h4>
				<div id="queueProcessedList" class="space-y-3"></div>
				<p id="queueProcessedEmpty" class="hidden text-sm text-gray-500">No approved or rejected requests yet.</p>
			</div>
		</div>
	</div>
</div>

<!-- MONTHLY BUDGET MODAL -->
<div id="monthlyBudgetModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
	<div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
		<div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-5">
			<div class="flex items-center justify-between">
				<div>
					<h3 class="text-xl font-bold text-white">Set Monthly Opening Balance</h3>
					<p id="monthlyBudgetModalMonth" class="text-emerald-100 text-sm mt-1"></p>
				</div>
				<button id="closeMonthlyBudgetBtn" type="button" class="text-white hover:text-emerald-100 transition">
					<i data-feather="x" class="w-6 h-6"></i>
				</button>
			</div>
		</div>

		<form id="monthlyBudgetForm" class="p-6 space-y-4">
			<div>
				<label class="block text-sm font-semibold text-gray-700 mb-2">Opening Balance</label>
				<div class="relative">
					<span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-600 text-sm">PHP</span>
					<input id="monthlyOpeningInput" type="number" min="0" step="0.01" class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required>
				</div>
			</div>



			<div class="pt-2 flex items-center gap-3">
				<button type="button" id="cancelMonthlyBudgetBtn" class="flex-1 px-4 py-2.5 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition-all duration-200">Cancel</button>
				<button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold hover:shadow-lg transition-all duration-200">Save Balance</button>
			</div>
		</form>
	</div>
</div>

@endsection

@push('scripts')
<script>
	@php
		$initialMonthlyBalance = null;
		if ($currentMonthlyBalance) {
			$initialMonthlyBalance = [
				'id' => $currentMonthlyBalance->id,
				'year' => (int) $currentMonthlyBalance->year,
				'month' => (int) $currentMonthlyBalance->month,
				'month_key' => sprintf('%04d-%02d', $currentMonthlyBalance->year, $currentMonthlyBalance->month),
				'opening_balance' => (float) $currentMonthlyBalance->opening_balance,
				'released_total' => (float) $currentMonthlyBalance->released_total,
				'expense_total' => (float) $currentMonthlyBalance->expense_total,
				'remaining_balance' => (float) $currentMonthlyBalance->remaining_balance,
			];
		}
	@endphp
	const CASH_ADVANCE_INDEX_ROUTE = @json(route('accounting.cash-advance.requests.index'));
	const CASH_ADVANCE_STREAM_ROUTE = @json(route('accounting.cash-advance.requests.stream'));
	const CASH_ADVANCE_DECISION_BASE_URL = @json(url('/accounting/cash-advance/requests'));
	const CASH_ADVANCE_DIRECT_SEND_ROUTE = @json(route('accounting.cash-advance.requests.send'));
	const CASH_ADVANCE_MONTHLY_BALANCE_ROUTE = @json(route('accounting.cash-advance.monthly-balance.store'));
	const CASH_ADVANCE_CSRF = @json(csrf_token());
	const INITIAL_MONTHLY_BALANCE = @json($initialMonthlyBalance);
	let currentMonthlyBalance = INITIAL_MONTHLY_BALANCE;
	let cashAdvanceRequestsCache = [];
	let accountingToastTimeout;
	let cashAdvanceStreamSource = null;
	let lastCashAdvanceStreamSignature = null;
	let hasReceivedInitialCashAdvanceStreamEvent = false;

	async function fetchCashAdvanceRequests() {
		const response = await fetch(CASH_ADVANCE_INDEX_ROUTE, {
			headers: {
				'Accept': 'application/json',
				'X-Requested-With': 'XMLHttpRequest'
			}
		});

		if (!response.ok) {
			throw new Error('Failed to load cash advance requests.');
		}

		const payload = await response.json();
		cashAdvanceRequestsCache = Array.isArray(payload?.requests) ? payload.requests : [];
		return cashAdvanceRequestsCache;
	}

	async function refreshCashAdvanceDashboardFromRealtime(payload) {
		if (payload?.signature && payload.signature === lastCashAdvanceStreamSignature) {
			return;
		}

		lastCashAdvanceStreamSignature = payload?.signature || lastCashAdvanceStreamSignature;

		try {
			await fetchCashAdvanceRequests();
			renderQueue();
			renderBudgetWindowAmounts();
			renderRecentDisbursements();

			const latestRequest = payload?.latest_request;
			const latestStatus = (latestRequest?.status || '').toLowerCase();
			if (hasReceivedInitialCashAdvanceStreamEvent && latestStatus === 'pending') {
				const employeeName = latestRequest?.employee_name || 'an employee';
				const amount = formatCurrency(latestRequest?.requested_amount || 0);
				showAccountingToast(`New cash advance request from ${employeeName}: ${amount}`, 'success');
			}

			hasReceivedInitialCashAdvanceStreamEvent = true;
		} catch (error) {
			showAccountingToast('Realtime update arrived, but the queue failed to refresh.', 'error');
		}
	}

	function initializeCashAdvanceRealtimeStream() {
		if (!window.EventSource || cashAdvanceStreamSource) {
			return;
		}

		cashAdvanceStreamSource = new EventSource(CASH_ADVANCE_STREAM_ROUTE);

		cashAdvanceStreamSource.addEventListener('cash-advance-requests-updated', function(event) {
			const payload = JSON.parse(event.data || '{}');
			refreshCashAdvanceDashboardFromRealtime(payload);
		});

		cashAdvanceStreamSource.addEventListener('error', function() {
			hasReceivedInitialCashAdvanceStreamEvent = false;
		});

		window.addEventListener('beforeunload', function() {
			if (cashAdvanceStreamSource) {
				cashAdvanceStreamSource.close();
			}
		});
	}

	function formatCurrency(amount) {
		const numericAmount = Number(amount || 0);
		return `PHP ${numericAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
	}

	function formatDate(dateString) {
		if (!dateString) return '-';
		const date = new Date(dateString);
		if (Number.isNaN(date.getTime())) return '-';
		return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
	}

	function getMonthKey(date) {
		const month = String(date.getMonth() + 1).padStart(2, '0');
		return `${date.getFullYear()}-${month}`;
	}

	function getMonthLabel(date) {
		return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
	}

	function getApprovedTotalForMonth(monthKey) {
		const approvedRequests = cashAdvanceRequestsCache.filter(request => (request.status || '').toLowerCase() === 'approved');
		return approvedRequests.reduce((sum, request) => {
			const sourceDate = new Date(request.reviewed_at || request.released_at || request.request_date || 0);
			if (Number.isNaN(sourceDate.getTime()) || getMonthKey(sourceDate) !== monthKey) {
				return sum;
			}

			return sum + Number(request.approved_amount || request.requested_amount || 0);
		}, 0);
	}

	function getCurrentMonthBudget() {
		const today = new Date();
		const currentMonthKey = getMonthKey(today);
		if (currentMonthlyBalance && currentMonthlyBalance.month_key === currentMonthKey) {
			return {
				monthKey: currentMonthKey,
				monthLabel: getMonthLabel(today),
				opening: Number(currentMonthlyBalance.opening_balance || 0),
			};
		}

		return {
			monthKey: currentMonthKey,
			monthLabel: getMonthLabel(today),
			opening: 0,
		};
	}

	function renderBudgetWindowAmounts() {
		const budgetWindowLimit = document.getElementById('budgetWindowLimit');
		const budgetSentToday = document.getElementById('budgetSentToday');
		const budgetRemainingToday = document.getElementById('budgetRemainingToday');
		const budgetMonthLabel = document.getElementById('budgetMonthLabel');
		if (!budgetWindowLimit || !budgetSentToday || !budgetRemainingToday || !budgetMonthLabel) return;

		const currentMonthBudget = getCurrentMonthBudget();
		const spentThisMonth = getApprovedTotalForMonth(currentMonthBudget.monthKey);
		const remainingThisMonth = currentMonthBudget.opening - spentThisMonth;

		budgetMonthLabel.textContent = `Opening Balance for ${currentMonthBudget.monthLabel}`;
		budgetWindowLimit.textContent = formatCurrency(currentMonthBudget.opening);
		budgetSentToday.textContent = formatCurrency(spentThisMonth);
		budgetRemainingToday.textContent = formatCurrency(remainingThisMonth);
	}

	function openMonthlyBudgetModal() {
		const modal = document.getElementById('monthlyBudgetModal');
		const monthLabel = document.getElementById('monthlyBudgetModalMonth');
		const openingInput = document.getElementById('monthlyOpeningInput');

		const currentMonthBudget = getCurrentMonthBudget();
		monthLabel.textContent = currentMonthBudget.monthLabel;
		openingInput.value = currentMonthBudget.opening;

		modal.classList.remove('hidden');
		modal.classList.add('flex');
		setTimeout(() => feather.replace(), 10);
	}

	function closeMonthlyBudgetModal() {
		const modal = document.getElementById('monthlyBudgetModal');
		modal.classList.add('hidden');
		modal.classList.remove('flex');
	}



	function showAccountingToast(message, type = 'success') {
		const toast = document.getElementById('accountingToast');
		const toastText = document.getElementById('accountingToastText');
		const toastIcon = document.getElementById('accountingToastIcon');
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

		clearTimeout(accountingToastTimeout);
		accountingToastTimeout = setTimeout(function() {
			toast.style.opacity = '0';
			setTimeout(function() {
				toast.classList.add('hidden');
				toast.style.opacity = '1';
			}, 500);
		}, 5000);
	}

	async function processRequestDecision(requestId, decision) {
		let remarks = '';
		if (decision === 'rejected') {
			const current = cashAdvanceRequestsCache.find(request => String(request.id) === String(requestId));
			remarks = prompt('Please provide rejection remarks:', current?.accounting_remarks || '');
			if (remarks === null) return;
			remarks = remarks.trim();
			if (!remarks) {
				showAccountingToast('Rejection remarks are required.', 'error');
				return;
			}
		} else {
			const current = cashAdvanceRequestsCache.find(request => String(request.id) === String(requestId));
			const approveRemarks = prompt('Optional approval remarks:', current?.accounting_remarks || '');
			if (approveRemarks !== null) {
				remarks = approveRemarks.trim();
			}
		}

		try {
			const response = await fetch(`${CASH_ADVANCE_DECISION_BASE_URL}/${requestId}/decision`, {
				method: 'PATCH',
				headers: {
					'Content-Type': 'application/json',
					'Accept': 'application/json',
					'X-CSRF-TOKEN': CASH_ADVANCE_CSRF,
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: JSON.stringify({
					decision,
					accounting_remarks: remarks,
				})
			});

			const payload = await response.json().catch(() => ({}));
			if (!response.ok) {
				showAccountingToast(payload?.message || 'Failed to update request.', 'error');
				return;
			}

			await fetchCashAdvanceRequests();
			renderQueue();
			renderBudgetWindowAmounts();
			showAccountingToast(payload?.message || 'Request updated successfully.', 'success');
		} catch (error) {
			showAccountingToast('Failed to update request. Please try again.', 'error');
		}
	}

	function getStatusPill(status) {
		const normalized = (status || '').toLowerCase();
		if (normalized === 'approved') {
			return '<span class="inline-flex rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1">Approved</span>';
		}
		if (normalized === 'rejected') {
			return '<span class="inline-flex rounded-full bg-red-100 text-red-700 text-xs font-semibold px-3 py-1">Rejected</span>';
		}
		return '<span class="inline-flex rounded-full bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1">Pending</span>';
	}

	function renderQueue() {
		const allRequests = cashAdvanceRequestsCache;
		const requests = allRequests.filter(request => (request.status || '').toLowerCase() === 'pending');
		const processedRequests = allRequests
			.filter(request => {
				const status = (request.status || '').toLowerCase();
				return status === 'approved' || status === 'rejected';
			})
			.sort((a, b) => new Date(b.reviewed_at || 0) - new Date(a.reviewed_at || 0));

		const queueList = document.getElementById('queueList');
		const queueEmptyState = document.getElementById('queueEmptyState');
		const queueBadgeText = document.getElementById('queueBadgeText');
		const queueProcessedList = document.getElementById('queueProcessedList');
		const queueProcessedEmpty = document.getElementById('queueProcessedEmpty');

		queueBadgeText.textContent = `Accounting Queue: ${requests.length} Pending`;

		if (requests.length === 0) {
			queueList.innerHTML = '';
			queueEmptyState.classList.remove('hidden');
		} else {
			queueEmptyState.classList.add('hidden');
			queueList.innerHTML = requests.map((request, index) => {
				return `
					<div class="rounded-2xl border border-gray-200 p-4 sm:p-5 hover:shadow-md transition-all duration-200">
						<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
							<div>
								<p class="text-xs uppercase tracking-wide text-gray-500">Request #${requests.length - index}</p>
								<h4 class="text-lg font-bold text-gray-800">${request.employee_name || 'Unknown Employee'}</h4>
							</div>
							<span class="inline-flex self-start rounded-full bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1">Pending Review</span>
						</div>

						<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
							<div class="rounded-xl bg-gray-50 p-3">
								<p class="text-[11px] uppercase tracking-wide text-gray-500">Amount</p>
								<p class="text-sm font-bold text-gray-800 mt-1">${formatCurrency(request.requested_amount || 0)}</p>
							</div>
							<div class="rounded-xl bg-gray-50 p-3">
								<p class="text-[11px] uppercase tracking-wide text-gray-500">Request Date</p>
								<p class="text-sm font-semibold text-gray-800 mt-1">${formatDate(request.request_date)}</p>
							</div>
							<div class="rounded-xl bg-gray-50 p-3">
								<p class="text-[11px] uppercase tracking-wide text-gray-500">Date Needed</p>
								<p class="text-sm font-semibold text-gray-800 mt-1">${formatDate(request.date_needed)}</p>
							</div>
							<div class="rounded-xl bg-gray-50 p-3">
								<p class="text-[11px] uppercase tracking-wide text-gray-500">Status</p>
								<p class="text-sm font-semibold text-amber-700 mt-1">For Accounting Review</p>
							</div>
						</div>

						<div class="mt-4">
							<p class="text-xs uppercase tracking-wide text-gray-500">Purpose</p>
							<p class="text-sm text-gray-700 mt-1 leading-relaxed">${request.purpose || '-'}</p>
						</div>

						<div class="mt-5 flex flex-col sm:flex-row gap-2 sm:justify-end">
							<button type="button" onclick="processRequestDecision('${request.id}', 'rejected')"
								class="px-4 py-2 rounded-xl text-sm font-semibold bg-red-50 text-red-700 hover:bg-red-100 transition-all duration-200">
								Reject
							</button>
							<button type="button" onclick="processRequestDecision('${request.id}', 'approved')"
								class="px-4 py-2 rounded-xl text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition-all duration-200">
								Approve and Release
							</button>
						</div>
					</div>
				`;
			}).join('');
		}

		if (processedRequests.length === 0) {
			queueProcessedList.innerHTML = '';
			queueProcessedEmpty.classList.remove('hidden');
		} else {
			queueProcessedEmpty.classList.add('hidden');
			queueProcessedList.innerHTML = processedRequests.slice(0, 8).map(request => `
				<div class="rounded-xl border border-gray-200 p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
					<div>
						<p class="text-sm font-semibold text-gray-800">${request.employee_name || 'Unknown Employee'} • ${formatCurrency(request.approved_amount || request.requested_amount || 0)}</p>
						<p class="text-xs text-gray-500 mt-1">${formatDate(request.reviewed_at)} • Reviewed by ${request.reviewer_name || 'Accounting Staff'} • ${request.accounting_remarks || 'No remarks'}</p>
					</div>
					<div>${getStatusPill(request.status)}</div>
				</div>
			`).join('');
		}

		feather.replace();
		renderRecentDisbursements();
	}

	function renderRecentDisbursements() {
		const allRequests = [...cashAdvanceRequestsCache]
			.sort((a, b) => new Date(b.reviewed_at || b.request_date || 0) - new Date(a.reviewed_at || a.request_date || 0));

		const tbody = document.getElementById('recentDisbursementBody');
		const empty = document.getElementById('recentDisbursementEmpty');
		if (!tbody || !empty) return;

		if (allRequests.length === 0) {
			tbody.innerHTML = '';
			empty.classList.remove('hidden');
			return;
		}

		empty.classList.add('hidden');
		tbody.innerHTML = allRequests.slice(0, 12).map(request => {
			const status = (request.status || 'pending').toLowerCase();
			const statusPill = getStatusPill(status);
			const dateLabel = status === 'approved' ? (request.reviewed_at || request.request_date) : request.request_date;
			const processedBy = request.reviewer_name || '-';

			return `
				<tr class="border-b border-gray-100">
					<td class="py-3 px-3 text-sm text-gray-700">${formatDate(dateLabel)}</td>
					<td class="py-3 px-3 text-sm text-gray-800 font-medium">${request.employee_name || 'Unknown Employee'}</td>
					<td class="py-3 px-3 text-sm text-gray-700">${request.purpose || '-'}</td>
					<td class="py-3 px-3 text-sm text-gray-700">${processedBy}</td>
					<td class="py-3 px-3 text-sm text-right font-semibold text-gray-900">${formatCurrency(request.approved_amount || request.requested_amount || 0)}</td>
					<td class="py-3 px-3 text-center">${statusPill}</td>
				</tr>
			`;
		}).join('');
	}

	async function openQueueModal() {
		const queueModal = document.getElementById('queueModal');
		queueModal.classList.remove('hidden');
		queueModal.classList.add('flex');
		try {
			await fetchCashAdvanceRequests();
		} catch (error) {
			showAccountingToast('Unable to refresh queue records.', 'error');
		}
		renderQueue();
	}

	function closeQueueModal() {
		const queueModal = document.getElementById('queueModal');
		queueModal.classList.add('hidden');
		queueModal.classList.remove('flex');
	}

	document.getElementById('openQueueBtn').addEventListener('click', openQueueModal);
	document.getElementById('closeQueueBtn').addEventListener('click', closeQueueModal);

	document.getElementById('queueModal').addEventListener('click', function(event) {
		if (event.target === this) {
			closeQueueModal();
		}
	});

	document.addEventListener('keydown', function(event) {
		if (event.key === 'Escape') {
			closeQueueModal();
		}
	});

	const sendCashAdvanceForm = document.getElementById('sendCashAdvanceForm');
	if (sendCashAdvanceForm) {
		sendCashAdvanceForm.addEventListener('submit', async function(event) {
			event.preventDefault();

			const requesterId = document.getElementById('sendEmployeeId')?.value;
			const amount = Number(document.getElementById('sendAmount')?.value || 0);
			const purpose = (document.getElementById('sendPurpose')?.value || '').trim();
				const accountingRemarks = (document.getElementById('sendNotes')?.value || '').trim();
			const releaseDate = document.getElementById('sendReleaseDate')?.value;

			if (!requesterId || !purpose || amount <= 0 || !releaseDate) {
				showAccountingToast('Please complete all required fields.', 'error');
				return;
			}

			try {
				const response = await fetch(CASH_ADVANCE_DIRECT_SEND_ROUTE, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'Accept': 'application/json',
						'X-CSRF-TOKEN': CASH_ADVANCE_CSRF,
						'X-Requested-With': 'XMLHttpRequest'
					},
					body: JSON.stringify({
						requester_id: requesterId,
						amount,
						purpose,
						accounting_remarks: accountingRemarks,
						release_date: releaseDate,
					})
				});

				const payload = await response.json().catch(() => ({}));
				if (!response.ok) {
					showAccountingToast(payload?.message || 'Failed to send cash advance.', 'error');
					return;
				}

				sendCashAdvanceForm.reset();
				document.getElementById('sendReleaseDate').value = new Date().toISOString().split('T')[0];
				await fetchCashAdvanceRequests();
				renderQueue();
				renderBudgetWindowAmounts();
				showAccountingToast(payload?.message || 'Cash advance sent successfully.', 'success');
			} catch (error) {
				showAccountingToast('Failed to send cash advance. Please try again.', 'error');
			}
		});
	}

	function renderTodayBudgetDate() {
		const todayBudgetDate = document.getElementById('todayBudgetDate');
		if (!todayBudgetDate) return;

		const today = new Date();
		todayBudgetDate.textContent = `Today: ${today.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`;
	}

	document.getElementById('openMonthlyBudgetBtn').addEventListener('click', openMonthlyBudgetModal);
	document.getElementById('closeMonthlyBudgetBtn').addEventListener('click', closeMonthlyBudgetModal);
	document.getElementById('cancelMonthlyBudgetBtn').addEventListener('click', closeMonthlyBudgetModal);

	document.getElementById('monthlyBudgetForm').addEventListener('submit', function(event) {
		event.preventDefault();

		const opening = Number(document.getElementById('monthlyOpeningInput').value || 0);
		if (opening < 0) {
			showAccountingToast('Opening balance must be zero or greater.', 'error');
			return;
		}

		fetch(CASH_ADVANCE_MONTHLY_BALANCE_ROUTE, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'X-CSRF-TOKEN': CASH_ADVANCE_CSRF,
				'X-Requested-With': 'XMLHttpRequest'
			},
			body: JSON.stringify({
				opening_balance: opening,
			})
		}).then(async response => {
			const payload = await response.json().catch(() => ({}));
			if (!response.ok) {
				throw new Error(payload?.message || 'Failed to save monthly opening balance.');
			}

			currentMonthlyBalance = payload?.balance || currentMonthlyBalance;
			renderBudgetWindowAmounts();
			closeMonthlyBudgetModal();
			showAccountingToast(payload?.message || 'Monthly opening balance updated successfully.', 'success');
		}).catch(() => {
			showAccountingToast('Failed to save monthly opening balance. Please try again.', 'error');
		});
	});

	document.getElementById('monthlyBudgetModal').addEventListener('click', function(event) {
		if (event.target === this) {
			closeMonthlyBudgetModal();
		}
	});

	async function initializeAccountingDashboard() {
		renderTodayBudgetDate();
		document.getElementById('sendReleaseDate').value = new Date().toISOString().split('T')[0];
		try {
			await fetchCashAdvanceRequests();
		} catch (error) {
			showAccountingToast('Unable to load cash advance records from database.', 'error');
		}
		renderBudgetWindowAmounts();
		renderQueue();
		renderRecentDisbursements();
		initializeCashAdvanceRealtimeStream();
		feather.replace();
	}

	initializeAccountingDashboard();
</script>
@endpush
