<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('department');

        // Filter by Employee Name
        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'LIKE', '%' . $request->name . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
            });
        }

        // Filter by Email
        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }

        // Filter by Department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Joining Date Range
        if ($request->filled('joining_from')) {
            $query->whereDate('joining_date', '>=', $request->joining_from);
        }
        if ($request->filled('joining_to')) {
            $query->whereDate('joining_date', '<=', $request->joining_to);
        }

        // Filter by Salary Range
        if ($request->filled('salary_min')) {
            $query->where('salary', '>=', $request->salary_min);
        }
        if ($request->filled('salary_max')) {
            $query->where('salary', '<=', $request->salary_max);
        }

        //  Sorting by Name
        if ($request->sort_by == 'name') {
            $query->orderBy('first_name', $request->sort_order ?? 'asc');
        }
        //  Sorting by Email
        elseif ($request->sort_by == 'email') {
            $query->orderBy('email', $request->sort_order ?? 'asc');
        }
        //  Sorting by Joining Date
        elseif ($request->sort_by == 'joining_date') {
            $query->orderBy('joining_date', $request->sort_order ?? 'asc');
        }
        //  Sorting by Status
        elseif ($request->sort_by == 'status') {
            $query->orderBy('status', $request->sort_order ?? 'asc');
        }
        // Default sorting
        else {
            $query->latest();
        }

        $employees = $query->paginate(10);
        
        // Get departments for filter dropdown
        $departments = Department::where('status', 'Active')->get();
        
        return view('employees.index', compact('employees', 'departments'));
    }
    public function create()
    {
        $departments = Department::where('status', 'Active')->get();
        return view('employees.create', compact('departments'));
    }

    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('employees', 'public');
            $data['profile_image'] = $imagePath;
        }

        $employee = Employee::create($data);

        // Log activity
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
     * Display employee profile page
     */
    public function show($id)
    {
        $employee = Employee::with('department')->findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::where('status', 'Active')->get();
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
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

    public function trash()
    {
        $employees = Employee::onlyTrashed()
                            ->with('department')
                            ->latest('deleted_at')
                            ->paginate(10);
        
        return view('employees.trash', compact('employees'));
    }

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
     * CSV Export Method
     */
    public function exportCSV()
    {
        $employees = Employee::with('department')->get();

        $filename = 'employees_' . date('Y_m_d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Employee Code',
                'Name',
                'Email',
                'Department',
                'Salary',
                'Joining Date'
            ]);

            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->employee_code,
                    $employee->first_name . ' ' . $employee->last_name,
                    $employee->email,
                    $employee->department ? $employee->department->department_name : 'N/A',
                    $employee->salary,
                    date('d-m-Y', strtotime($employee->joining_date))
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}