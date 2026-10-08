@extends('layouts.dashboard')

@section('page-title', 'Generate Payroll')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-plus-circle"></i> Generate Payroll</h5>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('payroll.process-generate') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Month <span class="text-danger">*</span></label>
                    <select name="month" class="form-control" required>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Year <span class="text-danger">*</span></label>
                    <select name="year" class="form-control" required>
                        @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                @if(Auth::user()->isHR())
                    <strong>HR:</strong> तुम्ही Employee आणि Manager चा payroll generate करू शकता.
                @elseif(Auth::user()->isAdmin())
                    <strong>Admin:</strong> तुम्ही HR चा payroll generate करू शकता.
                @endif
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                    <label class="form-check-label" for="selectAll"><strong>Select All</strong></label>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th width="50"><input type="checkbox" id="selectAllHeader"></th>
                            <th>Employee Code</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td>
                                <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="employee-checkbox">
                            </td>
                            <td>{{ $employee->employee_code }}</td>
                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $employee->user->role ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $employee->department->department_name ?? 'N/A' }}</td>
                            <td>₹{{ number_format($employee->salary, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No employees available for payroll generation.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Generate payroll for selected employees?')">
                    <i class="fas fa-cogs"></i> Generate Payroll
                </button>
                <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = this.checked);
    });
    document.getElementById('selectAllHeader').addEventListener('change', function() {
        document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = this.checked);
        document.getElementById('selectAll').checked = this.checked;
    });
</script>
@endpush
@endsection