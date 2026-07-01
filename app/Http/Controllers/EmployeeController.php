<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $employees = $query->latest()->paginate(10);
        
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('employees', 'public');
            $data['profile_image'] = $imagePath;
        }

        Employee::create($data);
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully!');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image
            if ($employee->profile_image) {
                Storage::disk('public')->delete($employee->profile_image);
            }
            
            // Store new image
            $imagePath = $request->file('profile_image')->store('employees', 'public');
            $data['profile_image'] = $imagePath;
        }

        $employee->update($data);
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        // Delete profile image
        if ($employee->profile_image) {
            Storage::disk('public')->delete($employee->profile_image);
        }

        $employee->delete();
        
        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully!');
    }
}