@extends('layouts.dashboard')

@section('page-title', 'Salary Structure')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-cog"></i> Salary Structures</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add Salary Structure
        </button>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Basic</th>
                        <th>HRA</th>
                        <th>Gross</th>
                        <th>PF</th>
                        <th>PT</th>
                        <th>Effective From</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($structures as $structure)
                    <tr>
                        <td>{{ $loop->iteration + ($structures->currentPage() - 1) * $structures->perPage() }}</td>
                        <td>
                            {{ $structure->employee->first_name ?? 'N/A' }} {{ $structure->employee->last_name ?? '' }}
                            <br>
                            <small class="text-muted">{{ $structure->employee->employee_code ?? '' }}</small>
                        </td>
                        <td>₹{{ number_format($structure->basic_salary, 2) }}</td>
                        <td>₹{{ number_format($structure->hra, 2) }}</td>
                        <td><strong>₹{{ number_format($structure->gross_salary, 2) }}</strong></td>
                        <td>{{ $structure->pf_enabled ? $structure->pf_percentage . '%' : 'Disabled' }}</td>
                        <td>₹{{ number_format($structure->professional_tax, 2) }}</td>
                        <td>{{ $structure->effective_from->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge {{ $structure->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $structure->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No salary structures found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $structures->links() }}
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('payroll.salary-structure.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Salary Structure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Effective From <span class="text-danger">*</span></label>
                            <input type="date" name="effective_from" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                            <input type="number" name="basic_salary" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">HRA</label>
                            <input type="number" name="hra" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Conveyance</label>
                            <input type="number" name="conveyance_allowance" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Medical Allowance</label>
                            <input type="number" name="medical_allowance" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Special Allowance</label>
                            <input type="number" name="special_allowance" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Other Allowance</label>
                            <input type="number" name="other_allowance" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">PF Percentage</label>
                            <input type="number" name="pf_percentage" class="form-control" step="0.01" min="0" max="100" value="12">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Professional Tax</label>
                            <input type="number" name="professional_tax" class="form-control" step="0.01" min="0" value="200">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Other Deduction</label>
                            <input type="number" name="other_deduction" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="pf_enabled" class="form-check-input" value="1" checked id="pf_enabled">
                                <label class="form-check-label" for="pf_enabled">Enable PF Deduction</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection