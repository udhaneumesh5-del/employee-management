@extends('layouts.dashboard')

@section('page-title', 'Company Profile')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-building"></i> Company Profile</h5>
        <a href="{{ route('settings.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <div class="card-body">
        <form action="{{ route('settings.update-company') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="3" required>{{ $company->address ?? '' }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" value="{{ $company->phone ?? '' }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ $company->email ?? '' }}" required>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Profile
                </button>
            @else
                <div class="alert alert-warning">Only Admin can update company profile.</div>
            @endif
        </form>
    </div>
</div>
@endsection