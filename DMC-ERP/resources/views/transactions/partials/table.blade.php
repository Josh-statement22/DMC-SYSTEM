<div id="transactionsTableMeta" data-total="{{ isset($expenses) && method_exists($expenses, 'total') ? $expenses->total() : ($expenses ?? collect())->count() }}" class="hidden"></div>
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
                <tr class="transaction-row border-b border-gray-200 hover:bg-gray-50" data-transaction-date="{{ $expense->expense_date->format('Y-m-d') }}" data-transaction-type="{{ $expense->transaction_type }}">
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
                            data-original-category-id="{{ $expense->category_id ?? '' }}"
                            aria-label="Transaction category"
                        >
                            <option value="">{{ ($expense->category ?? null) ? 'Uncategorized' : 'Select category' }}</option>
                            @forelse($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ (string) ($expense->category_id ?? '') === (string) $category->id || (! ($expense->category_id ?? null) && ($expense->category ?? '') === $category->particulars_category) ? 'selected' : '' }}>
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
                                class="btn-edit text-teal-600 hover:text-teal-800 font-semibold breakdownBtn"
                                data-id="{{ $expense->id }}"
                                data-employee-id="{{ $expense->employee_id ?? '' }}"
                                data-name="{{ $expense->employee_name ?? 'Unassigned' }}"
                                data-date="{{ $expense->expense_date->format('Y-m-d') }}"
                                data-amount="{{ number_format((float) $expense->amount, 2, '.', '') }}"
                            >
                                <i data-feather="edit-3" class="w-4 h-4"></i>
                                <span class="sr-only">Breakdown</span>
                            </button>
                            <button type="button" class="btn-view text-cyan-600 hover:text-cyan-800 font-semibold viewBreakdownBtn" data-id="{{ $expense->id }}">
                                <i data-feather="eye" class="w-4 h-4"></i>
                                <span class="sr-only">View</span>
                            </button>
                            <button type="button" class="btn-delete text-red-600 hover:text-red-800 font-semibold deleteBtn" data-id="{{ $expense->id }}">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                                <span class="sr-only">Delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="border-b border-gray-200">
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        {{ $hasTransactionFilters ?? false ? 'No transactions found for the selected filters.' : 'No transactions recorded yet' }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if(isset($expenses) && method_exists($expenses, 'hasPages') && $expenses->hasPages())
    <div id="transactionsPaginationContainer" class="border-t border-gray-200 px-6 py-4">
        {{ $expenses->links() }}
    </div>
@endif
