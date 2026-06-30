<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('employee') ? $this->route('employee')->id : null;

        return [
            'employee_code' => 'required|string|max:50|unique:employees,employee_code,' . $id,
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:employees,email,' . $id,
            'mobile_number' => 'required|string|max:15',
            'designation' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'status' => 'required|in:Active,Inactive'
        ];
    }

    public function messages(): array
    {
        return [
            'employee_code.required' => 'Employee code is required',
            'employee_code.unique' => 'Employee code already exists',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',
            'salary.min' => 'Salary cannot be negative'
        ];
    }
}