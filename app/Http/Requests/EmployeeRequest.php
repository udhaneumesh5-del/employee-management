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
        // Route parameter is 'id'
        $id = $this->route('id');

        return [
            'employee_code' => 'required|string|max:50|unique:employees,employee_code,' . $id,
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:employees,email,' . $id,
            'mobile_number' => 'required|string|max:15',
            'designation' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'status' => 'required|in:Active,Inactive',
            'department_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:employees,id', 
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'employee_code.required' => 'Employee code is required',
            'employee_code.unique' => 'This employee code is already taken',
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already taken',
            'mobile_number.required' => 'Mobile number is required',
            'designation.required' => 'Designation is required',
            'salary.required' => 'Salary is required',
            'salary.min' => 'Salary cannot be negative',
            'joining_date.required' => 'Joining date is required',
            'joining_date.date' => 'Please enter a valid date',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be Active or Inactive',
            'department_id.exists' => 'Selected department does not exist',
            'manager_id.exists' => 'Selected manager does not exist',  
            'profile_image.image' => 'File must be an image',
            'profile_image.mimes' => 'Image must be jpeg, png, jpg or gif',
            'profile_image.max' => 'Image size must be less than 2MB'
        ];
    }
}