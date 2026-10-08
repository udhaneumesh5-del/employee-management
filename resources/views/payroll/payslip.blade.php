<!DOCTYPE html>
<html>
<head>
    <title>Payslip - {{ $payroll->month_name }} {{ $payroll->payroll_year }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
        }
        .payslip-box { max-width: 800px; margin: 20px auto; padding: 30px; border: 1px solid #ddd; }
    </style>
</head>
<body>
<div class="container">
    <div class="no-print mt-3">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <div class="payslip-box">
        <div class="text-center mb-4">
            <h2>EMPLOYEE MANAGEMENT SYSTEM</h2>
            <h4>PAYSLIP</h4>
            <p><strong>Pay Period:</strong> {{ $payroll->month_name }} {{ $payroll->payroll_year }}</p>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Employee:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</p>
                <p><strong>Code:</strong> {{ $payroll->employee->employee_code }}</p>
                <p><strong>Designation:</strong> {{ $payroll->employee->designation }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Department:</strong> {{ $payroll->employee->department->department_name ?? 'N/A' }}</p>
                <p><strong>Joining Date:</strong> {{ $payroll->employee->joining_date }}</p>
                <p><strong>Payment Date:</strong> {{ $payroll->paid_at?->format('d-m-Y') ?? 'N/A' }}</p>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>EARNINGS</th>
                    <th class="text-end">Amount</th>
                    <th>DEDUCTIONS</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td class="text-end">₹{{ number_format($payroll->basic_salary, 2) }}</td>
                    <td>Absent Deduction</td>
                    <td class="text-end">₹{{ number_format($payroll->absent_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>HRA</td>
                    <td class="text-end">₹{{ number_format($payroll->hra, 2) }}</td>
                    <td>Unpaid Leave</td>
                    <td class="text-end">₹{{ number_format($payroll->unpaid_leave_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>Conveyance</td>
                    <td class="text-end">₹{{ number_format($payroll->conveyance_allowance, 2) }}</td>
                    <td>PF</td>
                    <td class="text-end">₹{{ number_format($payroll->pf_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>Medical</td>
                    <td class="text-end">₹{{ number_format($payroll->medical_allowance, 2) }}</td>
                    <td>Professional Tax</td>
                    <td class="text-end">₹{{ number_format($payroll->professional_tax, 2) }}</td>
                </tr>
                <tr>
                    <td>Special Allowance</td>
                    <td class="text-end">₹{{ number_format($payroll->special_allowance, 2) }}</td>
                    <td>Other</td>
                    <td class="text-end">₹{{ number_format($payroll->other_deduction, 2) }}</td>
                </tr>
                <tr class="table-info">
                    <td><strong>Gross Salary</strong></td>
                    <td class="text-end"><strong>₹{{ number_format($payroll->gross_salary, 2) }}</strong></td>
                    <td><strong>Total Deductions</strong></td>
                    <td class="text-end"><strong>₹{{ number_format($payroll->total_deductions, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <td><strong>Net Salary</strong></td>
                        <td class="text-end">₹{{ number_format($payroll->net_salary, 2) }}</td>
                    </tr>
                    @if($payroll->reimbursement_amount > 0)
                    <tr>
                        <td><strong>Reimbursement</strong></td>
                        <td class="text-end">₹{{ number_format($payroll->reimbursement_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="table-success">
                        <td><h5 class="mb-0"><strong>Total Payable</strong></h5></td>
                        <td class="text-end"><h5 class="mb-0"><strong>₹{{ number_format($payroll->total_payable, 2) }}</strong></h5></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="text-center mt-4">
            <p><small>This is a computer-generated payslip.</small></p>
        </div>
    </div>
</div>
</body>
</html>