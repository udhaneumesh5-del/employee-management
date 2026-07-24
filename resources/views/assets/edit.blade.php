@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-edit"></i> Edit Asset</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('assets.update', $asset->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Employee Code - Searchable Dropdown -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Employee Code <span class="text-danger">*</span></label>
                    <select name="employee_code" id="employee_code" 
                            class="form-control @error('employee_code') is-invalid @enderror" 
                            style="width: 100%;" required>
                        <option value="">-- Select Employee Code --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->employee_code }}" 
                                {{ old('employee_code', $asset->employee_code) == $employee->employee_code ? 'selected' : '' }}>
                                {{ $employee->employee_code }} - {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Employee Name - Auto-fill -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                    <input type="text" name="employee_name" id="employee_name" 
                           class="form-control @error('employee_name') is-invalid @enderror" 
                           value="{{ old('employee_name', $asset->employee_name) }}" 
                           readonly>
                    @error('employee_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Department - Auto-fill -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Department <span class="text-danger">*</span></label>
                    <input type="text" name="department" id="department" 
                           class="form-control @error('department') is-invalid @enderror" 
                           value="{{ old('department', $asset->department) }}" 
                           readonly>
                    @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Asset Type -->
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Asset Type <span class="text-danger">*</span></label>
                        <select name="asset_type" class="form-control @error('asset_type') is-invalid @enderror" required>
                            <option value="">Select Asset Type</option>
                            @foreach($assetTypes as $type)
                                <option value="{{ $type }}" {{ old('asset_type', $asset->asset_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('asset_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Issue Date -->
                <div class="col-md-6 mb-3">
                    <x-input name="issue_date" label="Issue Date" type="date" 
                             :value="old('issue_date', $asset->issue_date)" required="true" />
                </div>

                <!-- Return Date -->
                <div class="col-md-6 mb-3">
                    <x-input name="return_date" label="Return Date" type="date" 
                             :value="old('return_date', $asset->return_date)" />
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="Issued" {{ old('status', $asset->status) == 'Issued' ? 'selected' : '' }}>Issued</option>
                            <option value="Returned" {{ old('status', $asset->status) == 'Returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Condition -->
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Condition <span class="text-danger">*</span></label>
                        <select name="condition" class="form-control @error('condition') is-invalid @enderror" required>
                            <option value="Good" {{ old('condition', $asset->condition) == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Damaged" {{ old('condition', $asset->condition) == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                        </select>
                        @error('condition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Remarks -->
                <div class="col-md-12 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" 
                                  rows="2">{{ old('remarks', $asset->remarks) }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <x-button type="submit" class="btn-primary" text="Update Asset" icon="save" />
                <a href="{{ route('assets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        //  Auto-fill employee details on employee code selection
        $('#employee_code').on('change', function() {
            var employeeCode = $(this).val();
            
            if (employeeCode) {
                $.ajax({
                    url: "{{ route('assets.get-employee') }}",
                    type: "GET",
                    data: { employee_code: employeeCode },
                    success: function(data) {
                        if (data) {
                            $('#employee_name').val(data.employee_name);
                            $('#department').val(data.department);
                        }
                    }
                });
            } else {
                $('#employee_name').val('');
                $('#department').val('');
            }
        });
    });
</script>
@endpush
@endsection