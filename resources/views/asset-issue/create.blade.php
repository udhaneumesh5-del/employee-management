@extends('layouts.dashboard')

@section('page-title', 'Issue Asset')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-arrow-right"></i> Issue Asset</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('asset-issue.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Employee Code -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Employee Code <span class="text-danger">*</span></label>
                    <select name="employee_code" id="employee_code" 
                            class="form-control @error('employee_code') is-invalid @enderror" required>
                        <option value="">-- Select Employee --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->employee_code }}" {{ old('employee_code') == $employee->employee_code ? 'selected' : '' }}>
                                {{ $employee->employee_code }} - {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Employee Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                    <input type="text" name="employee_name" id="employee_name" 
                           class="form-control" placeholder="Auto-filled" readonly 
                           value="{{ old('employee_name') }}">
                </div>

                <!-- Department -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Department <span class="text-danger">*</span></label>
                    <input type="text" name="department" id="department" 
                           class="form-control" placeholder="Auto-filled" readonly
                           value="{{ old('department') }}">
                </div>

                <!-- Asset Selection -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Asset <span class="text-danger">*</span></label>
                    <select name="asset_id" id="asset_id" 
                            class="form-control @error('asset_id') is-invalid @enderror" required>
                        <option value="">-- Select Asset --</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_code }} - {{ $asset->asset_type }}
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Asset Details -->
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Asset Code</label>
                            <input type="text" id="asset_code" class="form-control" readonly 
                                   value="{{ old('asset_code') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Asset Type</label>
                            <input type="text" id="asset_type" class="form-control" readonly
                                   value="{{ old('asset_type') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Company</label>
                            <input type="text" id="company_name" class="form-control" readonly
                                   value="{{ old('company_name') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Model</label>
                            <input type="text" id="model" class="form-control" readonly
                                   value="{{ old('model') }}">
                        </div>
                    </div>
                </div>

                <!-- Issue Date -->
                <div class="col-md-6 mb-3">
                    <x-input name="issue_date" label="Issue Date" type="date" 
                             :value="old('issue_date', now()->toDateString())" required="true" />
                </div>

                <!-- Remarks -->
                <div class="col-md-12 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" 
                                  rows="2">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <x-button type="submit" class="btn-primary" text="Issue Asset" icon="save" />
                <a href="{{ route('asset-issue.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Auto-fill employee details
        $('#employee_code').on('change', function() {
            var code = $(this).val();
            if (code) {
                $.ajax({
                    url: "{{ route('asset-issue.get-employee') }}",
                    type: "GET",
                    data: { employee_code: code },
                    success: function(data) {
                        if (data) {
                            $('#employee_name').val(data.employee_name);
                            $('#department').val(data.department);
                        }
                    },
                    error: function() {
                        $('#employee_name').val('');
                        $('#department').val('');
                    }
                });
            } else {
                $('#employee_name').val('');
                $('#department').val('');
            }
        });

        // Auto-fill asset details
        $('#asset_id').on('change', function() {
            var id = $(this).val();
            if (id) {
                $.ajax({
                    url: "{{ url('asset-issue/get-asset') }}/" + id,
                    type: "GET",
                    success: function(data) {
                        if (data) {
                            $('#asset_code').val(data.asset_code);
                            $('#asset_type').val(data.asset_type);
                            $('#company_name').val(data.company_name);
                            $('#model').val(data.model);
                        }
                    },
                    error: function() {
                        $('#asset_code').val('');
                        $('#asset_type').val('');
                        $('#company_name').val('');
                        $('#model').val('');
                    }
                });
            } else {
                $('#asset_code').val('');
                $('#asset_type').val('');
                $('#company_name').val('');
                $('#model').val('');
            }
        });
    });
</script>
@endpush
@endsection