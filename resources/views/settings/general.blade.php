@extends('layouts.dashboard')

@section('page-title', 'General Settings')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-globe"></i> General Settings</h5>
        <a href="{{ route('settings.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <div class="card-body">
        <form action="{{ route('settings.update-general') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control" 
                           value="{{ $settings->company_name ?? 'Employee Management System' }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Timezone <span class="text-danger">*</span></label>
                    <select name="timezone" class="form-control" required>
                        <option value="Asia/Kolkata" {{ (isset($settings->timezone) && $settings->timezone == 'Asia/Kolkata') ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                        <option value="UTC" {{ (isset($settings->timezone) && $settings->timezone == 'UTC') ? 'selected' : '' }}>UTC</option>
                        <option value="America/New_York" {{ (isset($settings->timezone) && $settings->timezone == 'America/New_York') ? 'selected' : '' }}>America/New_York</option>
                        <option value="Europe/London" {{ (isset($settings->timezone) && $settings->timezone == 'Europe/London') ? 'selected' : '' }}>Europe/London</option>
                    </select>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            @else
                <div class="alert alert-warning">Only Admin can update these settings.</div>
            @endif
        </form>
    </div>
</div>
@endsection