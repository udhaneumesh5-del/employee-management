@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Add New Department</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('departments.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <!-- Input Component -->
                    <x-input name="department_name" label="Department Name" type="text" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="department_code" label="Department Code" type="text" required="true" />
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <!-- Button Component -->
                <x-button type="submit" class="btn-primary" text="Create Department" icon="save" />
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection