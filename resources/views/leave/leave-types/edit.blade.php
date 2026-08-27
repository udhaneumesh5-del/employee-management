@extends('layouts.dashboard')

@section('page-title', 'Edit Leave Type')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-edit"></i> Edit Leave Type</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('leave-types.update', $leaveType->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-input name="name" label="Leave Name" type="text" :value="$leaveType->name" required="true" />
                </div>

                <div class="col-md-6 mb-3">
                    <x-input name="code" label="Leave Code" type="text" :value="$leaveType->code" required="true" />
                    <small class="text-muted">Unique code (e.g., CL, SL, EL, PL)</small>
                </div>

                <div class="col-md-6 mb-3">
                    <x-input name="annual_limit" label="Annual Limit (Days)" type="number" :value="$leaveType->annual_limit" required="true" />
                </div>

                <div class="col-md-6 mb-3">
                    <x-input name="max_consecutive_days" label="Max Consecutive Days" type="number" :value="$leaveType->max_consecutive_days" />
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="carry_forward" id="carry_forward" value="1" {{ $leaveType->carry_forward ? 'checked' : '' }}>
                        <label class="form-check-label" for="carry_forward">
                            Allow Carry Forward
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_paid" id="is_paid" value="1" {{ $leaveType->is_paid ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_paid">
                            Paid Leave
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="requires_document" id="requires_document" value="1" {{ $leaveType->requires_document ? 'checked' : '' }}>
                        <label class="form-check-label" for="requires_document">
                            Requires Document
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $leaveType->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ $leaveType->description }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <x-button type="submit" class="btn-primary" text="Update Leave Type" icon="save" />
                <a href="{{ route('leave-types.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection