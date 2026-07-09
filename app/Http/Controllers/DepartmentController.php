<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // Main Index - Show active departments
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

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

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

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

    // Soft Delete - Move to Trash
    public function destroy(Department $department)
    {
        $department->delete();
        
        return redirect()->route('departments.index')
            ->with('success', 'Department moved to trash successfully!');
    }

    // Show Trash Page
    public function trash()
    {
        $departments = Department::onlyTrashed()
                            ->latest('deleted_at')
                            ->paginate(10);
        
        return view('departments.trash', compact('departments'));
    }

    // Restore Department
    public function restore($id)
    {
        $department = Department::onlyTrashed()->findOrFail($id);
        $department->restore();
        
        return redirect()->route('departments.trash')
            ->with('success', 'Department restored successfully!');
    }

    // Permanently Delete
    public function forceDelete($id)
    {
        $department = Department::onlyTrashed()->findOrFail($id);
        $department->forceDelete();
        
        return redirect()->route('departments.trash')
            ->with('success', 'Department deleted permanently!');
    }
}