@extends('layouts.dashboard')

@section('page-title', 'Return Asset')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-arrow-left"></i> Return Asset</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('asset-return.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Select Issued Asset -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Issued Asset <span class="text-danger">*</span></label>
                    <select name="issue_id" id="issue_id" 
                            class="form-control @error('issue_id') is-invalid @enderror" required>
                        <option value="">-- Select Issued Asset --</option>
                        @foreach($issuedAssets as $issue)
                            <option value="{{ $issue->id }}" {{ old('issue_id') == $issue->id ? 'selected' : '' }}>
                                {{ $issue->employee_name }} - {{ $issue->asset_code }} ({{ $issue->asset_type }})
                            </option>
                        @endforeach
                    </select>
                    @error('issue_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Employee Details - Auto-fill -->
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Employee Code</label>
                            <input type="text" id="employee_code" class="form-control" readonly 
                                   value="{{ old('employee_code') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Employee Name</label>
                            <input type="text" id="employee_name" class="form-control" readonly
                                   value="{{ old('employee_name') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <input type="text" id="department" class="form-control" readonly
                                   value="{{ old('department') }}">
                        </div>
                    </div>
                </div>

                <!-- Asset Details - Auto-fill -->
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
                    <label class="form-label">Issue Date</label>
                    <input type="text" id="issue_date" class="form-control" readonly
                           value="{{ old('issue_date') }}">
                </div>

                <!-- Return Date -->
                <div class="col-md-6 mb-3">
                    <x-input name="return_date" label="Return Date" type="date" 
                             :value="old('return_date', now()->toDateString())" required="true" />
                </div>

                <!-- Return Reason -->
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Return Reason <span class="text-danger">*</span></label>
                        <select name="return_reason" class="form-control @error('return_reason') is-invalid @enderror" required>
                            <option value="">-- Select Reason --</option>
                            @foreach($returnReasons as $reason)
                                <option value="{{ $reason }}" {{ old('return_reason') == $reason ? 'selected' : '' }}>
                                    {{ $reason }}
                                </option>
                            @endforeach
                        </select>
                        @error('return_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
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
                <x-button type="submit" class="btn-primary" text="Return Asset" icon="save" />
                <a href="{{ route('asset-return.index') }}" class="btn btn-secondary">
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
        // Auto-fill issue details
        $('#issue_id').on('change', function() {
            var id = $(this).val();
            if (id) {
                $.ajax({
                    url: "{{ url('asset-return/get-issue') }}/" + id,
                    type: "GET",
                    success: function(data) {
                        if (data) {
                            $('#employee_code').val(data.employee_code);
                            $('#employee_name').val(data.employee_name);
                            $('#department').val(data.department);
                            $('#asset_code').val(data.asset_code);
                            $('#asset_type').val(data.asset_type);
                            $('#company_name').val(data.company_name);
                            $('#model').val(data.model);
                            $('#issue_date').val(data.issue_date);
                        }
                    },
                    error: function() {
                        // Clear fields on error
                        $('#employee_code').val('');
                        $('#employee_name').val('');
                        $('#department').val('');
                        $('#asset_code').val('');
                        $('#asset_type').val('');
                        $('#company_name').val('');
                        $('#model').val('');
                        $('#issue_date').val('');
                    }
                });
            } else {
                // Clear all fields when no selection
                $('#employee_code').val('');
                $('#employee_name').val('');
                $('#department').val('');
                $('#asset_code').val('');
                $('#asset_type').val('');
                $('#company_name').val('');
                $('#model').val('');
                $('#issue_date').val('');
            }
        });
    });
</script>
@endpush
@endsection