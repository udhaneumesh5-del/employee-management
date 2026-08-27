@extends('layouts.dashboard')

@section('page-title', 'Add Leave Type')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-plus"></i> Add Leave Type</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('leave-types.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-input name="name" label="Leave Name" type="text" required="true" />
                </div>

                <div class="col-md-6 mb-3">
                    <x-input name="code" label="Leave Code" type="text" required="true" />
                    <small class="text-muted">Unique code (e.g., CL, SL, EL, PL)</small>
                </div>

                <div class="col-md-6 mb-3">
                    <x-input name="annual_limit" label="Annual Limit (Days)" type="number" required="true" />
                    <small class="text-muted">Number of days per year</small>
                </div>

                <div class="col-md-6 mb-3">
                    <x-input name="max_consecutive_days" label="Max Consecutive Days" type="number" />
                    <small class="text-muted">Leave blank for unlimited</small>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="carry_forward" id="carry_forward" value="1" checked>
                        <label class="form-check-label" for="carry_forward">
                            Allow Carry Forward
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_paid" id="is_paid" value="1" checked>
                        <label class="form-check-label" for="is_paid">
                            Paid Leave
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="requires_document" id="requires_document" value="1">
                        <label class="form-check-label" for="requires_document">
                            Requires Document
                        </label>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <x-button type="submit" class="btn-primary" text="Save Leave Type" icon="save" />
                <a href="{{ route('leave-types.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection