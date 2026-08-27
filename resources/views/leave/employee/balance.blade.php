@extends('layouts.dashboard')

@section('page-title', 'Leave Balance')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-balance-scale"></i> Leave Balance - {{ now()->year }}</h5>
        <span class="badge bg-primary">{{ now()->year }}</span>
    </div>
    <div class="card-body">
        <!-- Employee Info -->
        @if(isset($balances) && $balances->isNotEmpty() && !is_null($balances->first()->employee_id))
        <div class="alert alert-info">
            <i class="fas fa-user"></i> 
            <strong>{{ $balances->first()->first_name }} {{ $balances->first()->last_name }}</strong>
        </div>
        @endif

        @php
            $hasBalance = false;
            if(isset($balances) && $balances->isNotEmpty()) {
                foreach($balances as $b) {
                    if(!is_null($b->balance_id)) {
                        $hasBalance = true;
                        break;
                    }
                }
            }
        @endphp

        @if(!$hasBalance)
            <div class="text-center py-5">
                <i class="fas fa-info-circle fa-4x text-muted d-block mb-3"></i>
                <h5 class="text-muted">No leave balance found for {{ now()->year }}</h5>
                <p class="text-muted">Please contact HR department to set up your leave balance.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        @else
            <div class="row">
                @foreach($balances as $balance)
                    @if(!is_null($balance->balance_id))
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <!-- FIXED: $balance->leaveType->name नाही, $balance->leave_type_name -->
                                <div class="mb-3">
                                    <span class="badge bg-primary" style="font-size: 14px; padding: 8px 16px;">
                                        <i class="fas fa-calendar-alt"></i> {{ $balance->leave_type_name }}
                                    </span>
                                </div>
                                
                                <!-- Balance Details -->
                                <div class="row mt-3">
                                    <div class="col-4">
                                        <div class="border-end">
                                            <span class="text-muted small d-block">Allocated</span>
                                            <h4 class="mb-0 text-primary">{{ $balance->allocated_days }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-end">
                                            <span class="text-muted small d-block">Used</span>
                                            <h4 class="mb-0 text-danger">{{ $balance->used_days }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <span class="text-muted small d-block">Remaining</span>
                                        <h4 class="mb-0 text-success">{{ $balance->remaining_days }}</h4>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                @if($balance->allocated_days > 0)
                                    @php
                                        $percentage = ($balance->used_days / $balance->allocated_days) * 100;
                                        $progressColor = $percentage >= 80 ? 'bg-danger' : ($percentage >= 50 ? 'bg-warning' : 'bg-success');
                                    @endphp
                                    <div class="mt-3">
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar {{ $progressColor }}" 
                                                 role="progressbar" 
                                                 style="width: {{ min($percentage, 100) }}%" 
                                                 aria-valuenow="{{ $percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ number_format($percentage, 1) }}% used</small>
                                    </div>
                                @endif

                                <!-- Pending Days -->
                                @if(isset($balance->pending_days) && $balance->pending_days > 0)
                                    <div class="mt-3">
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock"></i> Pending: {{ $balance->pending_days }} days
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-white border-0 text-center">
                                <a href="{{ route('leave.my-leaves') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-list"></i> View History
                                </a>
                                <a href="{{ route('leave.apply-form') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Apply Leave
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- Summary Section -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h6 class="mb-1">Total Allocated</h6>
                            <h3 class="mb-0">
                                @php
                                    $totalAllocated = 0;
                                    foreach($balances as $b) {
                                        if(!is_null($b->balance_id)) {
                                            $totalAllocated += $b->allocated_days;
                                        }
                                    }
                                    echo $totalAllocated;
                                @endphp
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h6 class="mb-1">Total Used</h6>
                            <h3 class="mb-0">
                                @php
                                    $totalUsed = 0;
                                    foreach($balances as $b) {
                                        if(!is_null($b->balance_id)) {
                                            $totalUsed += $b->used_days;
                                        }
                                    }
                                    echo $totalUsed;
                                @endphp
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h6 class="mb-1">Total Remaining</h6>
                            <h3 class="mb-0">
                                @php
                                    $totalRemaining = 0;
                                    foreach($balances as $b) {
                                        if(!is_null($b->balance_id)) {
                                            $totalRemaining += $b->remaining_days;
                                        }
                                    }
                                    echo $totalRemaining;
                                @endphp
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center">
                            <h6 class="mb-1">Leave Types</h6>
                            <h3 class="mb-0">
                                @php
                                    $count = 0;
                                    foreach($balances as $b) {
                                        if(!is_null($b->balance_id)) {
                                            $count++;
                                        }
                                    }
                                    echo $count;
                                @endphp
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection