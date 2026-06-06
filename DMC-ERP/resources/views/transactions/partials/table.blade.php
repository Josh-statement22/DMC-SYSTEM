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
                <tr
                    class="transaction-row border-b border-gray-200 hover:bg-gray-50"
                    data-id="{{ $expense->id }}"
                    data-transaction-date="{{ $expense->expense_date->format('Y-m-d') }}"
                    data-transaction-type="{{ $expense->transaction_type }}"
                    data-original-date="{{ $expense->expense_date->format('Y-m-d') }}"
                    data-original-employee-id="{{ $expense->employee_id ?? '' }}"
                    data-original-type="{{ $expense->transaction_type }}"
                    data-original-purpose="{{ $expense->transaction_details ?? '' }}"
                    data-original-category-id="{{ $expense->category_id ?? '' }}"
                    data-original-category-name="{{ $expense->category ?? '' }}"
                    data-original-remarks="{{ $expense->description ?? '' }}"
                    data-original-amount="{{ number_format((float) $expense->amount, 2, '.', '') }}"
                >
                    <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->expense_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->employee_name ?? 'Unassigned' }}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 {{ $expense->transaction_type === 'debit' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($expense->transaction_type) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->transaction_details ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm text-gray-900">{{ $expense->category ?? 'Uncategorized' }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $expense->description ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm text-right font-semibold {{ $expense->transaction_type === 'debit' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $expense->transaction_type === 'debit' ? '-' : '+' }}PHP {{ number_format($expense->amount, 2) }}
                    </td>
                    <td class="px-6 py-3 text-center">
                        <div class="inline-flex flex-wrap items-center justify-center gap-2">
                            <button
                                type="button"
                                class="transactionRowAction transactionEditBtn inline-flex items-center gap-1 rounded-md border border-indigo-200 bg-indigo-50 px-2.5 py-1.5 text-xs font-semibold text-indigo-700 transition hover:border-indigo-300 hover:bg-indigo-100"
                                data-id="{{ $expense->id }}"
                                title="Edit transaction data"
                            >
                                <i data-feather="edit-3" class="w-3.5 h-3.5"></i>
                                <span>Edit</span>
                            </button>
                            @if($expense->transaction_type === 'debit')
                                <button
                                    type="button"
                                    class="transactionRowAction btn-edit breakdownBtn inline-flex items-center gap-1 rounded-md border border-teal-200 bg-teal-50 px-2.5 py-1.5 text-xs font-semibold text-teal-700 transition hover:border-teal-300 hover:bg-teal-100"
                                    data-id="{{ $expense->id }}"
                                    data-employee-id="{{ $expense->employee_id ?? '' }}"
                                    data-name="{{ $expense->employee_name ?? 'Unassigned' }}"
                                    data-date="{{ $expense->expense_date->format('Y-m-d') }}"
                                    data-amount="{{ number_format((float) $expense->amount, 2, '.', '') }}"
                                    title="Add breakdown"
                                >
                                    <i data-feather="plus-circle" class="w-3.5 h-3.5"></i>
                                    <span>Add Breakdown</span>
                                </button>
                            @else
                                <button
                                    type="button"
                                    class="transactionRowAction inline-flex cursor-not-allowed items-center gap-1 rounded-md border border-gray-200 bg-gray-50 px-2.5 py-1.5 text-xs font-semibold text-gray-400"
                                    title="Breakdown is only available for Debit transactions"
                                    disabled
                                >
                                    <i data-feather="plus-circle" class="w-3.5 h-3.5"></i>
                                    <span>Add Breakdown</span>
                                </button>
                            @endif
                            <button type="button" class="transactionRowAction btn-view viewBreakdownBtn inline-flex items-center gap-1 rounded-md border border-cyan-200 bg-cyan-50 px-2.5 py-1.5 text-xs font-semibold text-cyan-700 transition hover:border-cyan-300 hover:bg-cyan-100" data-id="{{ $expense->id }}" title="View breakdown">
                                <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                <span>View Breakdown</span>
                            </button>
                            <button type="button" class="transactionRowAction btn-delete deleteBtn inline-flex items-center gap-1 rounded-md border border-red-200 bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-700 transition hover:border-red-300 hover:bg-red-100" data-id="{{ $expense->id }}" title="Delete">
                                <i data-feather="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Delete</span>
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
