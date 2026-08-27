<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'designation',
        'salary',
        'joining_date',
        'status',
        'profile_image',
        'department_id',
        'manager_id'
    ];

    protected $dates = ['deleted_at'];
    
    // Relationships

    /**
     * Get the user associated with the employee.
     * One-to-One relationship with User model
     */
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    /**
     * Get the department that the employee belongs to.
     * Many-to-One relationship with Department model
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the attendances for the employee.
     * One-to-Many relationship with Attendance model
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the manager of the employee.
     * Self-referencing relationship
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Get the subordinates (employees) under this manager.
     * Self-referencing relationship
     */
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    /**
     * Get the leave requests for the employee.
     * One-to-Many relationship with LeaveRequest model
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Get the leave balances for the employee.
     * One-to-Many relationship with LeaveBalance model
     */
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    /**
     * Get the assets assigned to the employee.
     * One-to-Many relationship with AssetIssue model
     */
    public function assetIssues()
    {
        return $this->hasMany(AssetIssue::class);
    }

    /**
     * Get the asset returns for the employee.
     * One-to-Many relationship with AssetReturn model
     */
    public function assetReturns()
    {
        return $this->hasMany(AssetReturn::class);
    }

    // Scopes
    /**
     * Scope a query to only include active employees.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
    /**
     * Scope a query to only include inactive employees.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }
    /**
     * Scope a query to search employees by name or email.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'LIKE', "%{$search}%")
              ->orWhere('last_name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('employee_code', 'LIKE', "%{$search}%");
        });
    }
    // Accessors & Mutators
    /**
     * Get the employee's profile image URL.
     * Returns stored image or generates avatar from name.
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image && Storage::disk('public')->exists($this->profile_image)) {
            return asset('storage/' . $this->profile_image);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->first_name . '+' . $this->last_name) . '&background=0D6EFD&color=fff&size=100';
    }

    /**
     * Get the employee's full name.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the employee's full name with employee code.
     */
    public function getFullNameWithCodeAttribute()
    {
        return $this->employee_code . ' - ' . $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Check if employee has a user account.
     */
    public function getHasUserAccountAttribute()
    {
        return $this->user()->exists();
    }

    /**
     * Get team members (subordinates) count.
     */
    public function getTeamCountAttribute()
    {
        return $this->subordinates()->count();
    }

    /**
     * Check if employee is a manager.
     */
    public function getIsManagerAttribute()
    {
        return $this->subordinates()->exists();
    }

    /**
     * Get the role of the employee's user account.
     */
    public function getRoleAttribute()
    {
        if ($this->user) {
            return $this->user->role;
        }
        return null;
    }

    /**
     * Get the user's name with role.
     */
    public function getNameWithRoleAttribute()
    {
        $role = $this->role ? "({$this->role})" : '';
        return $this->full_name . ' ' . $role;
    }

    /**
     * Get active leave balance for all leave types.
     */
    public function getActiveLeaveBalancesAttribute()
    {
        return $this->leaveBalances()
            ->where('year', now()->year)
            ->with('leaveType')
            ->get();
    }

    // Helper Methods

    /**
     * Get today's attendance for the employee.
     */
    public function todayAttendance()
    {
        return $this->attendances()->whereDate('date', now()->toDateString())->first();
    }

    /**
     * Get the employee's attendance for a specific date.
     */
    public function attendanceOn($date)
    {
        return $this->attendances()->whereDate('date', $date)->first();
    }

    /**
     * Check if employee is present today.
     */
    public function isPresentToday()
    {
        $attendance = $this->todayAttendance();
        return $attendance && $attendance->status === 'Present';
    }

    /**
     * Check if employee is active.
     */
    public function isActive()
    {
        return $this->status === 'Active';
    }

    /**
     * Check if employee is inactive.
     */
    public function isInactive()
    {
        return $this->status === 'Inactive';
    }

    /**
     * Get leave balance for a specific leave type.
     */
    public function getLeaveBalance($leaveTypeId, $year = null)
    {
        $year = $year ?? now()->year;
        return $this->leaveBalances()
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->first();
    }

    /**
     * Check if employee has sufficient leave balance.
     */
    public function hasSufficientLeaveBalance($leaveTypeId, $days, $year = null)
    {
        $balance = $this->getLeaveBalance($leaveTypeId, $year);
        if (!$balance) {
            return false;
        }
        return $balance->remaining_days >= $days;
    }

    /**
     * Get total leave days taken for a specific leave type in a year.
     */
    public function getTotalLeaveTaken($leaveTypeId, $year = null)
    {
        $year = $year ?? now()->year;
        return $this->leaveRequests()
            ->where('leave_type_id', $leaveTypeId)
            ->whereYear('from_date', $year)
            ->where('final_status', 'approved')
            ->sum('total_days');
    }

    /**
     * Get pending leave requests.
     */
    public function pendingLeaveRequests()
    {
        return $this->leaveRequests()
            ->where('final_status', 'pending')
            ->latest()
            ->get();
    }

    /**
     * Get approved leave requests.
     */
    public function approvedLeaveRequests()
    {
        return $this->leaveRequests()
            ->where('final_status', 'approved')
            ->latest()
            ->get();
    }

    /**
     * Get rejected leave requests.
     */
    public function rejectedLeaveRequests()
    {
        return $this->leaveRequests()
            ->where('final_status', 'rejected')
            ->latest()
            ->get();
    }

    /**
     * Check if employee has pending leave requests.
     */
    public function hasPendingLeaveRequests()
    {
        return $this->leaveRequests()
            ->where('final_status', 'pending')
            ->exists();
    }

    /**
     * Check if employee has a specific role.
     */
    public function hasRole($role)
    {
        return $this->user && $this->user->role === $role;
    }

    /**
     * Check if employee is Admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    /**
     * Check if employee is HR.
     */
    public function isHR()
    {
        return $this->hasRole('HR');
    }

    /**
     * Check if employee is Manager.
     */
    public function isManager()
    {
        return $this->hasRole('Manager');
    }

    /**
     * Check if employee is Employee.
     */
    public function isEmployee()
    {
        return $this->hasRole('Employee');
    }

    /**
     * Check if employee is on leave today.
     */
    public function isOnLeaveToday()
    {
        $today = now()->toDateString();
        return $this->leaveRequests()
            ->where('final_status', 'approved')
            ->where('from_date', '<=', $today)
            ->where('to_date', '>=', $today)
            ->exists();
    }

    /**
     * Get current leave request if on leave.
     */
    public function currentLeave()
    {
        $today = now()->toDateString();
        return $this->leaveRequests()
            ->where('final_status', 'approved')
            ->where('from_date', '<=', $today)
            ->where('to_date', '>=', $today)
            ->first();
    }
}