<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 18px;
            margin: 0;
        }
        .date-range {
            font-size: 14px;
            margin: 5px 0 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary {
            margin-top: 20px;
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Tradicare Sales Report</h1>
        <p class="date-range">Period: {{ $dateLabel }}</p>
    </div>
    
    <div>
        <h3>Sales Summary</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Sales Amount (RM)</th>
            </tr>
            @foreach($salesByDate as $date => $amount)
            <tr>
                <td>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                <td>RM {{ number_format($amount, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <th>Total</th>
                <th>RM {{ number_format($totalSales, 2) }}</th>
            </tr>
        </table>
    </div>
    
    <div class="summary">
        <p><strong>Total Sales:</strong> RM {{ number_format($totalSales, 2) }}</p>
        <p><strong>Order Sales:</strong> RM {{ number_format($orderSales->sum('total_amount'), 2) }}</p>
        <p><strong>Appointment Sales:</strong> RM {{ number_format($appointmentSales->sum('fee'), 2) }}</p>
    </div>
    
    <div class="footer">
        <p>Generated on {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
        <p>Tradicare - Your Traditional Healthcare Partner</p>
    </div>
</body>
</html>