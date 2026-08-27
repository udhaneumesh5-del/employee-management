@extends('layouts.dashboard')

@section('page-title', 'Settings')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <h5><i class="fas fa-gear"></i> Settings</h5>
        <p class="text-muted">Manage system settings and configurations</p>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-globe"></i> General Settings</h6>
            </div>
            <div class="card-body">
                <p class="text-muted">Company name, timezone, logo, and other general settings.</p>
                @if($isAdmin || $isHR)
                    <a href="{{ route('settings.general') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Configure
                    </a>
                @else
                    <span class="text-muted">You do not have permission to edit settings.</span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-building"></i> Company Profile</h6>
            </div>
            <div class="card-body">
                <p class="text-muted">Company details, address, contact information, and logo.</p>
                @if($isAdmin || $isHR)
                    <a href="{{ route('settings.company') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Configure
                    </a>
                @else
                    <span class="text-muted">You do not have permission to edit company profile.</span>
                @endif
            </div>
        </div>
    </div>

    @if($isAdmin)
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-user-shield"></i> Admin Settings</h6>
            </div>
            <div class="card-body">
                <p class="text-muted">Admin-only settings and configurations.</p>
                <span class="badge bg-danger">Admin Only</span>
                <a href="#" class="btn btn-warning btn-sm">Configure</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection