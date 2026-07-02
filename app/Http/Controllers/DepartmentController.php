<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // Display list of departments
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('departments.index', compact('departments'));
    }

    // Show create form
    public function create()
    {
        return view('departments.create');
    }

    // Store new department
    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required|string|max:100',
            'department_code' => 'required|string|max:50|unique:departments',
            'status' => 'required|in:Active,Inactive'
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully!');
    }

    // Show edit form
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    // Update department
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'department_name' => 'required|string|max:100',
            'department_code' => 'required|string|max:50|unique:departments,department_code,' . $department->id,
            'status' => 'required|in:Active,Inactive'
        ]);

        $department->update($request->all());

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully!');
    }

    // Delete department
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully!');
    }
}