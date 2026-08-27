@extends('layouts.dashboard')

@section('page-title', 'Edit Department')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Department</h5>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-input name="department_name" label="Department Name" type="text" 
                             :value="old('department_name', $department->department_name)" required="true" />
                </div>
                
                <div class="col-md-6 mb-3">
                    <x-input name="department_code" label="Department Code" type="text" 
                             :value="old('department_code', $department->department_code)" required="true" />
                    <small class="text-muted">Unique code (e.g., HR, IT, FIN)</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Active" {{ old('status', $department->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $department->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <x-button type="submit" class="btn-primary" text="Update Department" icon="save" />
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection