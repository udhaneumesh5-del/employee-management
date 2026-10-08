<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payroll Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10px; 
            padding: 15px;
            color: #333;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 { font-size: 18px; margin-bottom: 5px; }
        .header h4 { font-size: 14px; margin-bottom: 5px; color: #555; }
        .header p { font-size: 10px; color: #666; }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
            font-size: 9px;
        }
        th, td { 
            border: 1px solid #999; 
            padding: 5px; 
            text-align: left;
        }
        th { 
            background: #343a40; 
            color: #fff; 
            font-weight: bold;
            text-align: center;
        }
        td { text-align: left; }
        td.amount { text-align: right; }
        
        tfoot td {
            font-weight: bold;
            background: #f0f0f0;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>EMPLOYEE MANAGEMENT SYSTEM</h2>
        <h4>Payroll Report</h4>
        <p>
            Period: 
            @if(request('month'))
                {{ date('F', mktime(0, 0, 0, request('month'), 1)) }}
            @else
                All Months
            @endif
            {{ request('year') ?? '' }}
        </p>
        <p>Generated on: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Code</th>
                <th>Department</th>
                <th>Role</th>
                <th>Month/Year</th>
                <th>Gross</th>
                <th>Deductions</th>
                <th>Net Salary</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $index => $payroll)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                <td>{{ $payroll->employee->employee_code }}</td>
                <td>{{ $payroll->employee->department->department_name ?? 'N/A' }}</td>
                <td>{{ $payroll->employee->user->role ?? 'N/A' }}</td>
                <td>{{ $payroll->month_name }} {{ $payroll->payroll_year }}</td>
                <td class="amount">₹{{ number_format($payroll->gross_salary, 2) }}</td>
                <td class="amount">₹{{ number_format($payroll->total_deductions, 2) }}</td>
                <td class="amount">₹{{ number_format($payroll->net_salary, 2) }}</td>
                <td style="text-align: center;">{{ $payroll->status }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right; font-weight: bold;">TOTAL:</td>
                <td class="amount">₹{{ number_format($stats['total_gross'], 2) }}</td>
                <td class="amount">₹{{ number_format($stats['total_deductions'], 2) }}</td>
                <td class="amount">₹{{ number_format($stats['total_net'], 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>This is a computer-generated report. No signature required.</p>
        <p>Total Records: {{ $stats['total'] }}</p>
    </div>
</body>
</html>