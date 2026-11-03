<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 25px;
        }

        .summary {
            margin-bottom: 20px;
            width: 100%;
        }

        .summary td {
            padding: 10px;
            background: #f1f5f9;
            text-align: center;
        }

        table.details {
            width: 100%;
            border-collapse: collapse;
        }

        .details th,
        .details td {
            border: 1px solid #ddd;
            padding: 9px;
            text-align: center;
        }

        .details th {
            background: #1e3a8a;
            color: white;
        }

        .total {
            font-weight: bold;
            background: #f1f5f9;
        }
    </style>
</head>

<body>

    <h2>Store Management - Sales Report</h2>

    <table class="summary">
        <tr>
            <td>
                <strong>Total Sales</strong><br>
                {{ number_format($totalSales, 2) }}
            </td>

            <td>
                <strong>Number of Sales</strong><br>
                {{ $salesCount }}
            </td>
        </tr>
    </table>

    <table class="details">
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Customer</th>
                <th>Employee</th>
                <th>Date</th>
                <th>Total Amount</th>
            </tr>
        </thead>

        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                <td>{{ $sale->employee->name ?? 'N/A' }}</td>
                <td>{{ $sale->sales_time }}</td>
                <td>{{ number_format($sale->total_amount, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No sales found.</td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr class="total">
                <td colspan="4">Total</td>
                <td>{{ number_format($totalSales, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</body>

</html>