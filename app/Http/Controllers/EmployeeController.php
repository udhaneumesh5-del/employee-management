<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees with role-based restrictions
     */
    public function index(Request $request)
    {
        $query = Employee::with('department');
        $user = auth()->user();

        // Role-based restrictions
        if ($user->isManager()) {
            $manager = Employee::where('email', $user->email)->first();
            if ($manager) {
                $teamIds = Employee::where('manager_id', $manager->id)->pluck('id');
                $query->whereIn('id', $teamIds);
            } else {
                $query->where('id', 0);
            }
        } elseif ($user->isEmployee()) {
            $employee = Employee::where('email', $user->email)->first();
            if ($employee) {
                $query->where('id', $employee->id);
            } else {
                $query->where('id', 0);
            }
        }

        // Filters
        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'LIKE', '%' . $request->name . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
            });
        }

        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('joining_from')) {
            $query->whereDate('joining_date', '>=', $request->joining_from);
        }
        if ($request->filled('joining_to')) {
            $query->whereDate('joining_date', '<=', $request->joining_to);
        }
        if ($request->filled('salary_min')) {
            $query->where('salary', '>=', $request->salary_min);
        }
        if ($request->filled('salary_max')) {
            $query->where('salary', '<=', $request->salary_max);
        }

        // Sorting
        if ($request->sort_by == 'name') {
            $query->orderBy('first_name', $request->sort_order ?? 'asc');
        } elseif ($request->sort_by == 'email') {
            $query->orderBy('email', $request->sort_order ?? 'asc');
        } elseif ($request->sort_by == 'joining_date') {
            $query->orderBy('joining_date', $request->sort_order ?? 'asc');
        } elseif ($request->sort_by == 'status') {
            $query->orderBy('status', $request->sort_order ?? 'asc');
        } elseif ($request->sort_by == 'department') {
            $query->orderBy('department_id', $request->sort_order ?? 'asc');
        } else {
            $query->latest();
        }

        $employees = $query->paginate(10);
        $departments = Department::where('status', 'Active')->get();
        
        return view('employees.index', compact('employees', 'departments'));
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        $departments = Department::where('status', 'Active')->get();
        
        // Get managers for dropdown
        $managers = User::where('role', 'Manager')
            ->join('employees', 'users.email', '=', 'employees.email')
            ->select('employees.id', 'employees.first_name', 'employees.last_name')
            ->get();
        
        // If no managers found in employees table, get from users table
        if ($managers->isEmpty()) {
            $managers = User::where('role', 'Manager')
                ->select('id', 'name as first_name', DB::raw("'' as last_name"))
                ->get();
        }
        
        return view('employees.create', compact('departments', 'managers'));
    }

    /**
     * Store a newly created employee in storage
     */
    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('employees', 'public');
            $data['profile_image'] = $imagePath;
        }

        $employee = Employee::create($data);

        DB::table('activity_logs')->insert([
            'employee_name' => $employee->first_name . ' ' . $employee->last_name,
            'action' => 'Created',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully!');
    }

    /**
     * Display the specified employee profile
     */
    public function show($id)
    {
        $employee = Employee::with('department')->findOrFail($id);
        
        $user = auth()->user();
        if ($user->isEmployee()) {
            $currentEmployee = Employee::where('email', $user->email)->first();
            if ($currentEmployee && $currentEmployee->id != $id) {
                abort(403, 'You can only view your own profile.');
            }
        } elseif ($user->isManager()) {
            $manager = Employee::where('email', $user->email)->first();
            if ($manager) {
                $teamIds = Employee::where('manager_id', $manager->id)->pluck('id');
                if (!in_array($id, $teamIds->toArray()) && $id != $manager->id) {
                    abort(403, 'You can only view your team members.');
                }
            }
        }
        
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::where('status', 'Active')->get();
        
        // Get managers for dropdown
        $managers = User::where('role', 'Manager')
            ->join('employees', 'users.email', '=', 'employees.email')
            ->select('employees.id', 'employees.first_name', 'employees.last_name')
            ->get();
        
        if ($managers->isEmpty()) {
            $managers = User::where('role', 'Manager')
                ->select('id', 'name as first_name', DB::raw("'' as last_name"))
                ->get();
        }
        
        return view('employees.edit', compact('employee', 'departments', 'managers'));
    }

    /**
     * Update the specified employee in storage
     */
    public function update(EmployeeRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            if ($employee->profile_image) {
                Storage::disk('public')->delete($employee->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('employees', 'public');
            $data['profile_image'] = $imagePath;
        }

        $employee->update($data);

        DB::table('activity_logs')->insert([
            'employee_name' => $employee->first_name . ' ' . $employee->last_name,
            'action' => 'Updated',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    /**
     * Remove the specified employee from storage (Soft Delete)
     */
    public function destroy(Employee $employee)
    {
        $employeeName = $employee->first_name . ' ' . $employee->last_name;
        $employee->delete();

        DB::table('activity_logs')->insert([
            'employee_name' => $employeeName,
            'action' => 'Deleted',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee moved to trash successfully!');
    }

    /**
     * Display a listing of trashed employees
     */
    public function trash()
    {
        $employees = Employee::onlyTrashed()
                            ->with('department')
                            ->latest('deleted_at')
                            ->paginate(10);
        
        return view('employees.trash', compact('employees'));
    }

    /**
     * Restore a trashed employee
     */
    public function restore($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->restore();

        DB::table('activity_logs')->insert([
            'employee_name' => $employee->first_name . ' ' . $employee->last_name,
            'action' => 'Restored',
            'performed_by' => auth()->user()->name,
            'performed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('employees.trash')
            ->with('success', 'Employee restored successfully!');
    }

    /**
     * Permanently delete an employee
     */
    public function forceDelete($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        
        if ($employee->profile_image) {
            Storage::disk('public')->delete($employee->profile_image);
        }
        
        $employee->forceDelete();
        
        return redirect()->route('employees.trash')
            ->with('success', 'Employee deleted permanently!');
    }

    /**
     * Export employees data to CSV
     */
    public function exportCSV()
    {
        $user = auth()->user();
        
        $query = Employee::with('department');
        
        if ($user->isManager()) {
            $manager = Employee::where('email', $user->email)->first();
            if ($manager) {
                $teamIds = Employee::where('manager_id', $manager->id)->pluck('id');
                $query->whereIn('id', $teamIds);
            } else {
                $query->where('id', 0);
            }
        } elseif ($user->isEmployee()) {
            $employee = Employee::where('email', $user->email)->first();
            if ($employee) {
                $query->where('id', $employee->id);
            } else {
                $query->where('id', 0);
            }
        }
        
        $employees = $query->get();

        $filename = 'employees_' . date('Y_m_d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Employee Code', 'Name', 'Email', 'Department', 'Salary', 'Joining Date', 'Status'
            ]);

            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->employee_code,
                    $employee->first_name . ' ' . $employee->last_name,
                    $employee->email,
                    $employee->department ? $employee->department->department_name : 'N/A',
                    $employee->salary,
                    date('d-m-Y', strtotime($employee->joining_date)),
                    $employee->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get employee details for AJAX requests
     */
    public function getEmployeeDetails($id)
    {
        $employee = Employee::with('department')->findOrFail($id);
        return response()->json([
            'id' => $employee->id,
            'name' => $employee->first_name . ' ' . $employee->last_name,
            'email' => $employee->email,
            'department' => $employee->department ? $employee->department->department_name : 'N/A',
            'designation' => $employee->designation,
            'salary' => $employee->salary,
            'joining_date' => $employee->joining_date,
            'profile_image' => $employee->profile_image ? asset('storage/' . $employee->profile_image) : null
        ]);
    }

    /**
     * Get team members for manager
     */
    public function getTeamMembers()
    {
        $user = auth()->user();
        if (!$user->isManager()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $manager = Employee::where('email', $user->email)->first();
        if (!$manager) {
            return response()->json([]);
        }

        $teamMembers = Employee::where('manager_id', $manager->id)
            ->select('id', 'first_name', 'last_name', 'email')
            ->get();

        return response()->json($teamMembers);
    }
}