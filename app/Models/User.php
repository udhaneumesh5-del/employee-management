<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'employee_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role Check Methods
    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    public function isHR()
    {
        return $this->role === 'HR';
    }

    public function isManager()
    {
        return $this->role === 'Manager';
    }

    public function isEmployee()
    {
        return $this->role === 'Employee';
    }

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'employee_id');
    }

    // Permission Methods - User Management
    public function canManageUsers()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canManageAllUsers()
    {
        return $this->role === 'Admin';
    }

    public function canManageEmployeesOnly()
    {
        return $this->role === 'HR';
    }

    public function canCreateUser()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canChangeRole()
    {
        return $this->role === 'Admin';
    }

    public function getAllowedRolesToCreate()
    {
        if ($this->isAdmin()) {
            return ['Admin', 'HR', 'Manager', 'Employee'];
        } elseif ($this->isHR()) {
            return ['Manager', 'Employee'];
        }
        return [];
    }

    public function canManageUser($targetUser)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isHR() && $targetUser->isAdmin()) {
            return false;
        }

        if ($this->isHR() && ($targetUser->isManager() || $targetUser->isEmployee())) {
            return true;
        }

        return false;
    }

    public function canDeleteUser($targetUser)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isHR() && $targetUser->isAdmin()) {
            return false;
        }

        if ($this->isHR() && ($targetUser->isManager() || $targetUser->isEmployee())) {
            return true;
        }

        return false;
    }

    // Permission Methods - Employee Management
    public function canManageEmployees()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canViewTeamEmployees()
    {
        return $this->role === 'Manager';
    }

    public function canViewOwnProfile()
    {
        return $this->role === 'Employee';
    }

    // Permission Methods - Department Management
    public function canManageDepartments()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canViewDepartments()
    {
        return in_array($this->role, ['Admin', 'HR', 'Manager', 'Employee']);
    }

    // Permission Methods - Attendance Management
    public function canManageAttendance()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canViewTeamAttendance()
    {
        return $this->role === 'Manager';
    }

    public function canViewOwnAttendance()
    {
        return $this->role === 'Employee';
    }

    // Permission Methods - Asset Management
    public function canManageAssets()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canViewTeamAssets()
    {
        return $this->role === 'Manager';
    }

    public function canViewOwnAssets()
    {
        return $this->role === 'Employee';
    }
    
    // Permission Methods - Leave Management

    public function canManageAllLeaves()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canApproveTeamLeaves()
    {
        return in_array($this->role, ['Manager', 'Admin']);
    }

    public function canApplyLeave()
    {
        return in_array($this->role, ['Admin', 'HR', 'Manager', 'Employee']);
    }

    // Permission Methods - Activity Logs
    public function canViewActivityLogs()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canViewFullActivityLogs()
    {
        return $this->role === 'Admin';
    }

    // Permission Methods - Reports
    public function canViewReports()
    {
        return in_array($this->role, ['Admin', 'HR', 'Manager', 'Employee']);
    }

    public function canViewTeamReports()
    {
        return $this->role === 'Manager';
    }

    public function canViewOwnReports()
    {
        return $this->role === 'Employee';
    }

    // Permission Methods - Settings
    public function canManageSettings()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canManageFullSettings()
    {
        return $this->role === 'Admin';
    }

    public function canManageLimitedSettings()
    {
        return $this->role === 'HR';
    }

    // Permission Methods - Reimbursement
    public function canCreateReimbursement()
    {
        return in_array($this->role, ['Employee', 'Manager', 'HR']);
    }

    public function canApproveReimbursement($reimbursement)
    {
        if ($this->isEmployee()) {
            return false;
        }

        if ($this->isManager()) {
            return $reimbursement->status === 'pending_manager' && 
                   $reimbursement->manager_id === $this->employee?->id;
        }

        if ($this->isHR()) {
            return $reimbursement->status === 'pending_hr' &&
                   $reimbursement->requester_id !== $this->employee?->id;
        }

        if ($this->isAdmin()) {
            return $reimbursement->status === 'pending_admin' &&
                   $reimbursement->requester_role === 'HR';
        }

        return false;
    }

    public function canManagePolicies()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canManageExpenseTypes()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canViewReimbursementReports()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    public function canManagePayments()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }
    // Permission Methods - Payroll Management
    /**
     * Check if user can manage payroll
     * HR can manage Employee/Manager payroll, Admin can manage HR payroll
     */
    public function canManagePayroll()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * FIXED: Check if user can process specific employee's payroll
     * 
     * HR → Employee + Manager payroll
     * Admin → HR payroll only
     */
    public function canProcessPayroll($payroll)
    {
        // Get the employee's user account role from users table
        $employeeUser = self::where('employee_id', $payroll->employee_id)->first();
        $employeeRole = $employeeUser->role ?? 'Employee';

        // HR can process Employee and Manager payroll
        if ($this->isHR()) {
            return in_array($employeeRole, ['Employee', 'Manager']);
        }

        // Admin can process HR payroll only
        if ($this->isAdmin()) {
            return $employeeRole === 'HR';
        }

        return false;
    }

    /**
     * Check if user can view own payslip
     */
    public function canViewOwnPayslip()
    {
        return in_array($this->role, ['Employee', 'Manager', 'HR']);
    }

    /**
     * Check if user can view all payrolls
     */
    public function canViewAllPayrolls()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can view team payrolls
     */
    public function canViewTeamPayrolls()
    {
        return $this->role === 'Manager';
    }

    /**
     * Check if user can process payroll (generate monthly payroll)
     */
    public function canProcessPayrollBatch()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can approve payroll
     */
    public function canApprovePayroll()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can delete payroll
     */
    public function canDeletePayroll()
    {
        return $this->role === 'Admin';
    }

    /**
     * Check if user can manage salary structures
     */
    public function canManageSalaryStructures()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can view salary structures
     */
    public function canViewSalaryStructures()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can manage employee loans
     */
    public function canManageLoans()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can approve loans
     */
    public function canApproveLoans()
    {
        return $this->role === 'Admin';
    }

    /**
     * Check if user can process loan EMI deductions
     */
    public function canProcessLoanDeductions()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can manage salary increments
     */
    public function canManageSalaryIncrements()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can view payroll reports
     */
    public function canViewPayrollReports()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can export bank file
     */
    public function canExportBankFile()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can download Form 16
     */
    public function canDownloadForm16()
    {
        return in_array($this->role, ['Admin', 'HR', 'Manager', 'Employee']);
    }

    /**
     * Check if user can mark payroll as paid
     */
    public function canMarkPayrollAsPaid()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can view payroll dashboard
     */
    public function canViewPayrollDashboard()
    {
        return in_array($this->role, ['Admin', 'HR', 'Manager', 'Employee']);
    }

    /**
     * NEW: Check if user can manage payroll adjustments
     */
    public function canManagePayrollAdjustments()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * NEW: Check if user can view payroll reports (alias)
     */
    public function canViewPayrollReport()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     *  NEW: Check if user can edit salary structure
     */
    public function canEditSalaryStructure()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * NEW: Get the role of the employee linked to a payroll
     * Returns the role from users table for the payroll's employee
     */
    public function getEmployeeRoleFromPayroll($payroll)
    {
        $employeeUser = self::where('employee_id', $payroll->employee_id)->first();
        return $employeeUser->role ?? 'Employee';
    }

    /**
     * NEW: Check if HR can process this specific employee's payroll
     */
    public function hrCanProcess($payroll)
    {
        if (!$this->isHR()) {
            return false;
        }
        
        $employeeRole = $this->getEmployeeRoleFromPayroll($payroll);
        return in_array($employeeRole, ['Employee', 'Manager']);
    }

    /**
     * NEW: Check if Admin can process this specific employee's payroll
     */
    public function adminCanProcess($payroll)
    {
        if (!$this->isAdmin()) {
            return false;
        }
        
        $employeeRole = $this->getEmployeeRoleFromPayroll($payroll);
        return $employeeRole === 'HR';
    }

    /**
     * Get payroll access level for the user
     */
    public function getPayrollAccessLevel()
    {
        return match($this->role) {
            'Admin' => 'full',
            'HR' => 'manage',
            'Manager' => 'team_view',
            'Employee' => 'self_view',
            default => 'none',
        };
    }

    // Status Methods
    public function isActive()
    {
        return $this->status === 'Active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }
    
    // Badge Color

    public function getRoleBadgeColor()
    {
        $colors = [
            'Admin' => 'bg-danger',
            'HR' => 'bg-primary',
            'Manager' => 'bg-warning',
            'Employee' => 'bg-success',
        ];
        return $colors[$this->role] ?? 'bg-secondary';
    }

    public function getStatusBadgeColor()
    {
        return $this->isActive() ? 'bg-success' : 'bg-danger';
    }
}