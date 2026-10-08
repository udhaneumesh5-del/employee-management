<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->month_name }} {{ $payroll->payroll_year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            padding: 20px;
            color: #333;
            line-height: 1.4;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #333;
            padding-bottom: 12px;
        }
        
        .header h1 {
            font-size: 20px;
            color: #1a1a1a;
            margin-bottom: 4px;
            letter-spacing: 1px;
        }
        
        .header h2 {
            font-size: 14px;
            color: #555;
            font-weight: normal;
            margin-bottom: 6px;
        }
        
        .header p {
            font-size: 11px;
            color: #666;
        }
        
        /* Info Section */
        .info-section {
            width: 100%;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 12px;
            border-radius: 4px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 4px 8px;
            border: none;
            font-size: 11px;
            vertical-align: top;
        }
        
        .info-table td strong {
            color: #555;
            display: inline-block;
            min-width: 110px;
        }
        
        /* Attendance Table */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .attendance-table th {
            background: #17a2b8;
            color: #fff;
            padding: 6px;
            border: 1px solid #117a8b;
            text-align: center;
            font-size: 11px;
        }
        
        .attendance-table td {
            padding: 6px;
            border: 1px solid #ccc;
            text-align: center;
            font-size: 11px;
        }
        
        .attendance-table td strong {
            color: #333;
            font-size: 12px;
        }
        
        /* Salary Table */
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .salary-table th {
            background: #343a40;
            color: #fff;
            padding: 8px;
            border: 1px solid #1d2124;
            text-align: left;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        
        .salary-table td {
            padding: 6px 10px;
            border: 1px solid #ccc;
            font-size: 11px;
        }
        
        .salary-table td.amount {
            text-align: right;
            font-family: DejaVu Sans Mono, monospace;
            min-width: 90px;
        }
        
        .salary-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .salary-table tfoot td {
            background: #e9ecef;
            font-weight: bold;
            font-size: 12px;
        }
        
        /* Net Salary */
        .net-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .net-table td {
            padding: 8px 12px;
            border: 1px solid #ccc;
            font-size: 12px;
        }
        
        .net-table td.amount {
            text-align: right;
            font-family: DejaVu Sans Mono, monospace;
            min-width: 100px;
        }
        
        .net-table tr.net-row {
            background: #d4edda;
            color: #155724;
        }
        
        .net-table tr.net-row td {
            font-size: 14px;
            font-weight: bold;
            padding: 10px 12px;
        }
        
        .net-table tr.total-row {
            background: #cce5ff;
            color: #004085;
        }
        
        .net-table tr.total-row td {
            font-size: 15px;
            font-weight: bold;
            padding: 10px 12px;
        }
        
        /* Payment Info */
        .payment-info {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .payment-info h4 {
            font-size: 12px;
            color: #856404;
            margin-bottom: 6px;
        }
        
        .payment-info p {
            font-size: 11px;
            color: #856404;
            margin: 3px 0;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 12px;
        }
        
        .footer p {
            margin: 2px 0;
        }
        
        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(40, 167, 69, 0.06);
            font-weight: bold;
            z-index: -1;
            letter-spacing: 5px;
        }
    </style>
</head>
<body>

    <!-- Watermark -->
    <div class="watermark">PAID</div>
 
    <!-- HEADER -->
    <div class="header">
        <h1>EMPLOYEE MANAGEMENT SYSTEM</h1>
        <h2>PAYSLIP</h2>
        <p><strong>Pay Period:</strong> {{ $payroll->month_name }} {{ $payroll->payroll_year }}</p>
    </div>

    <!-- EMPLOYEE INFORMATION -->
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td width="50%"><strong>Employee Name:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                <td width="50%"><strong>Employee Code:</strong> {{ $payroll->employee->employee_code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Department:</strong> {{ $payroll->employee->department->department_name ?? 'N/A' }}</td>
                <td><strong>Designation:</strong> {{ $payroll->employee->designation ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Joining Date:</strong> 
                    {{ $payroll->employee->joining_date ? date('d-m-Y', strtotime($payroll->employee->joining_date)) : 'N/A' }}
                </td>
                <td><strong>Payment Date:</strong> {{ $payroll->paid_at ? $payroll->paid_at->format('d-m-Y') : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- ATTENDANCE SUMMARY -->
    <table class="attendance-table">
        <thead>
            <tr>
                <th colspan="5">ATTENDANCE SUMMARY</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Working Days<br><strong>{{ $payroll->total_working_days }}</strong></td>
                <td>Present<br><strong style="color: #28a745;">{{ $payroll->present_days }}</strong></td>
                <td>Absent<br><strong style="color: #dc3545;">{{ $payroll->absent_days }}</strong></td>
                <td>Paid Leave<br><strong>{{ $payroll->paid_leave_days }}</strong></td>
                <td>Unpaid Leave<br><strong style="color: #ffc107;">{{ $payroll->unpaid_leave_days }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- EARNINGS & DEDUCTIONS -->
    <table class="salary-table">
        <thead>
            <tr>
                <th width="35%">EARNINGS</th>
                <th width="15%" style="text-align: right;">AMOUNT</th>
                <th width="35%">DEDUCTIONS</th>
                <th width="15%" style="text-align: right;">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td class="amount">₹{{ number_format($payroll->basic_salary, 2) }}</td>
                <td>Absent Deduction</td>
                <td class="amount">₹{{ number_format($payroll->absent_deduction, 2) }}</td>
            </tr>
            <tr>
                <td>HRA (House Rent Allowance)</td>
                <td class="amount">₹{{ number_format($payroll->hra, 2) }}</td>
                <td>Unpaid Leave Deduction</td>
                <td class="amount">₹{{ number_format($payroll->unpaid_leave_deduction, 2) }}</td>
            </tr>
            <tr>
                <td>Conveyance Allowance</td>
                <td class="amount">₹{{ number_format($payroll->conveyance_allowance, 2) }}</td>
                <td>Provident Fund (PF)</td>
                <td class="amount">₹{{ number_format($payroll->pf_deduction, 2) }}</td>
            </tr>
            <tr>
                <td>Medical Allowance</td>
                <td class="amount">₹{{ number_format($payroll->medical_allowance, 2) }}</td>
                <td>Professional Tax</td>
                <td class="amount">₹{{ number_format($payroll->professional_tax, 2) }}</td>
            </tr>
            <tr>
                <td>Special Allowance</td>
                <td class="amount">₹{{ number_format($payroll->special_allowance, 2) }}</td>
                <td>Other Deduction</td>
                <td class="amount">₹{{ number_format($payroll->other_deduction, 2) }}</td>
            </tr>
            @if($payroll->other_allowance > 0)
            <tr>
                <td>Other Allowance</td>
                <td class="amount">₹{{ number_format($payroll->other_allowance, 2) }}</td>
                <td></td>
                <td class="amount"></td>
            </tr>
            @endif
            @if($payroll->bonus > 0)
            <tr>
                <td>Bonus</td>
                <td class="amount">₹{{ number_format($payroll->bonus, 2) }}</td>
                <td></td>
                <td class="amount"></td>
            </tr>
            @endif
            @if($payroll->incentive > 0)
            <tr>
                <td>Incentive</td>
                <td class="amount">₹{{ number_format($payroll->incentive, 2) }}</td>
                <td></td>
                <td class="amount"></td>
            </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Gross Salary</strong></td>
                <td class="amount"><strong>₹{{ number_format($payroll->gross_salary, 2) }}</strong></td>
                <td><strong>Total Deductions</strong></td>
                <td class="amount"><strong>₹{{ number_format($payroll->total_deductions, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- NET SALARY -->
    <table class="net-table">
        <tr class="net-row">
            <td><strong>NET SALARY</strong></td>
            <td class="amount"><strong>₹{{ number_format($payroll->net_salary, 2) }}</strong></td>
        </tr>
        @if($payroll->reimbursement_amount > 0)
        <tr>
            <td>Reimbursement Amount</td>
            <td class="amount">₹{{ number_format($payroll->reimbursement_amount, 2) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td><strong>TOTAL PAYABLE</strong></td>
            <td class="amount"><strong>₹{{ number_format($payroll->total_payable, 2) }}</strong></td>
        </tr>
    </table>

    <!-- PAYMENT DETAILS -->
    @if($payroll->status == 'Paid')
    <div class="payment-info">
        <h4>✓ PAYMENT DETAILS</h4>
        <p><strong>Payment Status:</strong> PAID</p>
        <p><strong>Payment Date:</strong> {{ $payroll->paid_at ? $payroll->paid_at->format('d-m-Y') : 'N/A' }}</p>
        <p><strong>Payment Method:</strong> {{ $payroll->payment_method ?? 'N/A' }}</p>
        @if($payroll->payment_reference)
        <p><strong>Reference No:</strong> {{ $payroll->payment_reference }}</p>
        @endif
        @if($payroll->paidBy)
        <p><strong>Paid By:</strong> {{ $payroll->paidBy->name ?? 'N/A' }}</p>
        @endif
    </div>
    @endif

    <!-- FOOTER -->
    <div class="footer">
        <p><strong>This is a computer-generated payslip. No signature required.</strong></p>
        <p>Generated on {{ date('d-m-Y H:i A') }}</p>
        <p>Employee Management System © {{ date('Y') }}</p>
    </div>

</body>
</html>