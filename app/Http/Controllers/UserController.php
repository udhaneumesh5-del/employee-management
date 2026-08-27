<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * User List (with JOIN for employee data)
     * Admin and HR can view all users
     */
    public function index(Request $request)
    {
        // Admin and HR can access
        if (!auth()->user()->isAdmin() && !auth()->user()->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        // Using LEFT JOIN to get employee data
        $query = DB::table('users')
            ->leftJoin('employees', 'users.employee_id', '=', 'employees.id')
            ->select(
                'users.*',
                'employees.employee_code',
                'employees.first_name as employee_first_name',
                'employees.last_name as employee_last_name',
                'employees.designation',
                'employees.department_id'
            );

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'LIKE', "%{$search}%")
                  ->orWhere('users.email', 'LIKE', "%{$search}%")
                  ->orWhere('employees.employee_code', 'LIKE', "%{$search}%")
                  ->orWhere('employees.first_name', 'LIKE', "%{$search}%")
                  ->orWhere('employees.last_name', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Role
        if ($request->filled('role')) {
            $query->where('users.role', $request->role);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('users.status', $request->status);
        }

        $users = $query->orderBy('users.id', 'desc')->paginate(10);
        $roles = ['admin', 'hr', 'manager', 'employee'];

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show Create User Form
     * Admin and HR can create users
     */
    public function create()
    {
        // Admin and HR can access
        if (!auth()->user()->isAdmin() && !auth()->user()->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $allowedRoles = auth()->user()->getAllowedRolesToCreate();

        // Get employees without user accounts
        $employees = DB::table('employees')
            ->leftJoin('users', 'employees.id', '=', 'users.employee_id')
            ->whereNull('users.id')
            ->where('employees.status', 'Active')
            ->select(
                'employees.id',
                'employees.employee_code',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'employees.designation'
            )
            ->get();

        return view('users.create', compact('allowedRoles', 'employees'));
    }

    /**
     * Store New User
     * Admin and HR can store users
     */
    public function store(Request $request)
    {
        // Admin and HR can access
        if (!auth()->user()->isAdmin() && !auth()->user()->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(auth()->user()->getAllowedRolesToCreate())],
            'employee_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Using DB transaction
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status,
                'employee_id' => $request->employee_id,
            ]);

            // If employee_id is provided, update employee status
            if ($request->employee_id) {
                DB::table('employees')
                    ->where('id', $request->employee_id)
                    ->update(['status' => 'Active']);
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Show Edit User Form
     * Admin and HR can edit users
     */
    public function edit($id)
    {
        // Admin and HR can access
        if (!auth()->user()->isAdmin() && !auth()->user()->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($id);

        // HR cannot edit Admin users
        if (auth()->user()->isHR() && $user->isAdmin()) {
            return redirect()->back()
                ->with('error', 'HR cannot edit Admin users.');
        }

        $allowedRoles = auth()->user()->getAllowedRolesToCreate();
        
        // Get all employees (including those with user accounts)
        $employees = DB::table('employees')
            ->leftJoin('users', 'employees.id', '=', 'users.employee_id')
            ->select('employees.id', 'employees.employee_code', 'employees.first_name', 'employees.last_name', 'users.id as user_id')
            ->where('employees.status', 'Active')
            ->orWhere('employees.id', $user->employee_id)
            ->get();

        return view('users.edit', compact('user', 'allowedRoles', 'employees'));
    }

    /**
     * Update User
     * Admin and HR can update users
     */
    public function update(Request $request, $id)
    {
        // Admin and HR can access
        if (!auth()->user()->isAdmin() && !auth()->user()->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($id);

        // HR cannot update Admin users
        if (auth()->user()->isHR() && $user->isAdmin()) {
            return redirect()->back()
                ->with('error', 'HR cannot update Admin users.');
        }

        // HR cannot change role to Admin
        if (auth()->user()->isHR() && $request->role == 'Admin') {
            return redirect()->back()
                ->with('error', 'HR cannot assign Admin role.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(auth()->user()->getAllowedRolesToCreate())],
            'employee_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Using DB transaction
        DB::transaction(function () use ($request, $user) {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Update employee_id
            if ($request->employee_id) {
                $data['employee_id'] = $request->employee_id;
            }

            $user->update($data);

            // Update employee status if linked
            if ($request->employee_id) {
                DB::table('employees')
                    ->where('id', $request->employee_id)
                    ->update(['status' => $request->status]);
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Delete User (Soft Delete)
     * Admin and HR can delete users (except Admin users for HR)
     */
    public function destroy($id)
    {
        // Admin and HR can access
        if (!auth()->user()->isAdmin() && !auth()->user()->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($id);

        // Cannot delete self
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        // HR cannot delete Admin users
        if (auth()->user()->isHR() && $user->isAdmin()) {
            return redirect()->back()
                ->with('error', 'HR cannot delete Admin users.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Toggle User Status (Activate/Deactivate)
     * Admin and HR can toggle status (except Admin users for HR)
     */
    public function toggleStatus($id)
    {
        // Admin and HR can access
        if (!auth()->user()->isAdmin() && !auth()->user()->isHR()) {
            abort(403, 'Unauthorized access.');
        }

        $user = User::findOrFail($id);

        // Cannot deactivate self
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot change your own status.');
        }

        // HR cannot toggle Admin users
        if (auth()->user()->isHR() && $user->isAdmin()) {
            return redirect()->back()
                ->with('error', 'HR cannot change Admin user status.');
        }

        $newStatus = $user->isActive() ? 'Inactive' : 'Active';
        $user->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "User status changed to {$newStatus} successfully!");
    }

    /**
     * Get Employee Details (AJAX)
     */
    public function getEmployeeDetails($id)
    {
        $employee = DB::table('employees')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->select(
                'employees.*',
                'departments.department_name'
            )
            ->where('employees.id', $id)
            ->first();

        return response()->json($employee);
    }
}