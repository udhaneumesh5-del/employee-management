@extends('layouts.dashboard')

@section('page-title', 'Apply Leave')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-calendar-plus"></i> Apply Leave</h5>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('leave.apply') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Leave Type -->
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select name="leave_type_id" class="form-control @error('leave_type_id') is-invalid @enderror" required>
                            <option value="">Select Leave Type</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} ({{ $type->annual_limit ?? 0 }} days/year)
                                </option>
                            @endforeach
                        </select>
                        @error('leave_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Manager Info - Only for Employee -->
                @if(Auth::user()->isEmployee())
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Reporting Manager</label>
                        <div class="form-control bg-light" style="cursor: not-allowed;">
                            @if(isset($manager) && $manager)
                                <i class="fas fa-user-tie text-primary"></i>
                                <strong>{{ $manager->first_name }} {{ $manager->last_name }}</strong>
                                <span class="text-muted">({{ $manager->email ?? 'N/A' }})</span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-exclamation-triangle"></i> No manager assigned
                                </span>
                            @endif
                        </div>
                        <input type="hidden" name="manager_id" value="{{ $manager->id ?? '' }}">
                        <small class="text-muted">Your leave request will be sent to your reporting manager</small>
                        @error('manager_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                @endif

                <!-- Approval Flow Info - For Manager/HR/Admin -->
                @if(Auth::user()->isManager() || Auth::user()->isHR() || Auth::user()->isAdmin())
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Approval Flow</label>
                        <div class="form-control bg-light" style="cursor: not-allowed;">
                            @if(Auth::user()->isManager())
                                <i class="fas fa-arrow-right text-warning"></i>
                                <strong>Your leave will go to HR for approval</strong>
                            @elseif(Auth::user()->isHR())
                                <i class="fas fa-arrow-right text-info"></i>
                                <strong>Your leave will go to Admin for approval</strong>
                            @elseif(Auth::user()->isAdmin())
                                <i class="fas fa-arrow-right text-success"></i>
                                <strong>Your leave will be auto-approved</strong>
                            @endif
                        </div>
                        <small class="text-muted">No manager selection needed</small>
                    </div>
                </div>
                @endif

                <!-- From Date -->
                <div class="col-md-6 mb-3">
                    <x-input name="from_date" label="From Date" type="date" :value="old('from_date')" required="true" />
                </div>

                <!-- To Date -->
                <div class="col-md-6 mb-3">
                    <x-input name="to_date" label="To Date" type="date" :value="old('to_date')" required="true" />
                </div>

                <!-- Total Days -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Total Days</label>
                    <input type="text" id="total_days" class="form-control" readonly>
                </div>

                <!-- Reason -->
                <div class="col-md-12 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" 
                                  rows="4" required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Document -->
                <div class="col-md-12 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Supporting Document (Optional)</label>
                        <input type="file" name="document" class="form-control @error('document') is-invalid @enderror">
                        <small class="text-muted">PDF, DOC, DOCX, JPG, PNG (Max: 2MB)</small>
                        @error('document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Apply Leave
                </button>
                <a href="{{ route('leave.my-leaves') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function calculateDays() {
            var from = document.getElementById('from_date').value;
            var to = document.getElementById('to_date').value;
            var totalDays = document.getElementById('total_days');
            
            if (from && to) {
                var fromDate = new Date(from);
                var toDate = new Date(to);
                
                if (toDate >= fromDate) {
                    var diffTime = Math.abs(toDate - fromDate);
                    var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    totalDays.value = diffDays;
                } else {
                    totalDays.value = 'Invalid dates';
                }
            }
        }

        document.getElementById('from_date').addEventListener('change', calculateDays);
        document.getElementById('to_date').addEventListener('change', calculateDays);
        
        // Calculate on page load if values exist
        calculateDays();
    });
</script>
@endpush
@endsection