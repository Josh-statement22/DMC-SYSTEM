<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Category Expense Matrix</title>
    <style>
        body {
            color: #111827;
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 24px;
        }

        h1 {
            font-size: 20px;
            margin: 0 0 4px;
        }

        p {
            color: #4b5563;
            margin: 0 0 18px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }

        th {
            background: #f3f4f6;
            text-align: left;
        }

        td.amount,
        th.amount {
            text-align: right;
            white-space: nowrap;
        }

        tfoot th,
        tfoot td {
            background: #111827;
            color: #ffffff;
            font-weight: 700;
        }

        .empty {
            border: 1px solid #d1d5db;
            color: #6b7280;
            font-weight: 700;
            padding: 24px;
            text-align: center;
        }

        @media print {
            body {
                margin: 12mm;
            }
        }
    </style>
</head>
<body @if(($format ?? '') === 'pdf') onload="window.print()" @endif>
    <h1>Category Expense Matrix</h1>
    <p>
        Debit spending from {{ \Carbon\Carbon::parse($categoryExpenseMatrix['filters']['effective_start_date'] ?? now())->format('M d, Y') }}
        to {{ \Carbon\Carbon::parse($categoryExpenseMatrix['filters']['effective_end_date'] ?? now())->format('M d, Y') }}
    </p>

    @if(!($categoryExpenseMatrix['has_data'] ?? false))
        <div class="empty">No category spending data available.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    @foreach($categoryExpenseMatrix['months'] ?? [] as $matrixMonth)
                        <th class="amount">{{ $matrixMonth['label'] }}</th>
                    @endforeach
                    <th class="amount">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryExpenseMatrix['rows'] ?? [] as $matrixRow)
                    <tr>
                        <th>{{ $matrixRow['category_name'] }}</th>
                        @foreach($categoryExpenseMatrix['months'] ?? [] as $matrixMonth)
                            <td class="amount">PHP {{ number_format((float) ($matrixRow['amounts'][$matrixMonth['key']] ?? 0), 2) }}</td>
                        @endforeach
                        <td class="amount">PHP {{ number_format((float) ($matrixRow['total'] ?? 0), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>TOTAL</th>
                    @foreach($categoryExpenseMatrix['months'] ?? [] as $matrixMonth)
                        <td class="amount">PHP {{ number_format((float) ($categoryExpenseMatrix['column_totals'][$matrixMonth['key']] ?? 0), 2) }}</td>
                    @endforeach
                    <td class="amount">PHP {{ number_format((float) ($categoryExpenseMatrix['grand_total'] ?? 0), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    @endif
</body>
</html>
