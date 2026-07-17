@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>
            <i class="fas fa-user"></i> My Profile
        </h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <!-- Input Component -->
                    <x-input name="name" label="Full Name" type="text" :value="auth()->user()->name" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="email" label="Email Address" type="email" :value="auth()->user()->email" required="true" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="password" label="New Password" type="password" placeholder="Leave blank to keep current password" />
                </div>
                
                <div class="col-md-6">
                    <x-input name="password_confirmation" label="Confirm Password" type="password" placeholder="Confirm new password" />
                </div>
            </div>
            
            <div class="mt-3">
                <!-- Button Component -->
                <x-button type="submit" class="btn-primary" text="Update Profile" icon="save" />
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection