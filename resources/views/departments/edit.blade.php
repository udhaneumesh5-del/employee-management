@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Department</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <!-- Input Component -->
                    <x-input name="department_name" label="Department Name" type="text" :value="$department->department_name" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="department_code" label="Department Code" type="text" :value="$department->department_code" required="true" />
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
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
                <!-- Button Component -->
                <x-button type="submit" class="btn-primary" text="Update Department" icon="save" />
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection