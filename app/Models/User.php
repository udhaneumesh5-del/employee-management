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

    /**
     * Get allowed roles for creation
     */
    public function getAllowedRolesToCreate()
    {
        if ($this->isAdmin()) {
            return ['Admin', 'HR', 'Manager', 'Employee'];
        } elseif ($this->isHR()) {
            return ['Manager', 'Employee'];
        }
        return [];
    }

    /**
     * Check if user can manage another specific user
     */
    public function canManageUser($targetUser)
    {
        // Admin can manage everyone
        if ($this->isAdmin()) {
            return true;
        }

        // HR cannot manage Admin
        if ($this->isHR() && $targetUser->isAdmin()) {
            return false;
        }

        // HR can manage Manager and Employee
        if ($this->isHR() && ($targetUser->isManager() || $targetUser->isEmployee())) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can delete another specific user
     */
    public function canDeleteUser($targetUser)
    {
        // Admin can delete everyone
        if ($this->isAdmin()) {
            return true;
        }

        // HR cannot delete Admin
        if ($this->isHR() && $targetUser->isAdmin()) {
            return false;
        }

        // HR can delete Manager and Employee
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

    /**
     * Check if user can create reimbursement
     */
    public function canCreateReimbursement()
    {
        return in_array($this->role, ['Employee', 'Manager', 'HR', 'Admin']);
    }

    /**
     * Check if user can approve reimbursement
     */
    public function canApproveReimbursement($reimbursement)
    {
        if ($this->isEmployee()) {
            return false;
        }

        if ($this->isManager()) {
            // Manager can approve if request is pending_manager and belongs to their team
            return $reimbursement->status === 'pending_manager' && 
                   $reimbursement->manager_id === $this->employee->id;
        }

        if ($this->isHR()) {
            // HR can approve if request is pending_hr
            return $reimbursement->status === 'pending_hr' &&
                   $reimbursement->requester_id !== $this->employee->id;
        }

        if ($this->isAdmin()) {
            // Admin can approve if request is pending_admin (HR requests)
            return $reimbursement->status === 'pending_admin' &&
                   $reimbursement->requester_role === 'HR';
        }

        return false;
    }

    /**
     * Check if user can manage policies
     */
    public function canManagePolicies()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can manage expense types
     */
    public function canManageExpenseTypes()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can view reimbursement reports
     */
    public function canViewReimbursementReports()
    {
        return in_array($this->role, ['Admin', 'HR']);
    }

    /**
     * Check if user can manage payments
     */
    public function canManagePayments()
    {
        return in_array($this->role, ['Admin', 'HR']);
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

    // Badge Colors
    
    /**
     * Get user role badge color
     */
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

    /**
     * Get user status badge color
     */
    public function getStatusBadgeColor()
    {
        return $this->isActive() ? 'bg-success' : 'bg-danger';
    }
}