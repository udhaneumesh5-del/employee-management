@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-plus-circle"></i> Assign Multiple Assets</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('assets.store') }}" method="POST" id="assetForm">
            @csrf

            <div class="row">
                <!-- Employee Code -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Employee Code <span class="text-danger">*</span></label>
                    <select name="employee_code" id="employee_code" 
                            class="form-select @error('employee_code') is-invalid @enderror" required>
                        <option value="">-- Select Employee Code --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->employee_code }}" 
                                {{ old('employee_code') == $employee->employee_code ? 'selected' : '' }}>
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
                           class="form-select @error('employee_name') is-invalid @enderror" 
                           value="{{ old('employee_name') }}" 
                           placeholder="Auto-filled from employee code"
                           readonly>
                    @error('employee_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Department - Auto-fill -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Department <span class="text-danger">*</span></label>
                    <input type="text" name="department" id="department" 
                           class="form-select @error('department') is-invalid @enderror" 
                           value="{{ old('department') }}" 
                           placeholder="Auto-filled from employee code"
                           readonly>
                    @error('department')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Asset Type Checkboxes - Dynamic from database -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Select Assets <span class="text-danger">*</span></label>
                    <div class="row" id="asset-checkboxes">
                        @foreach($assetTypes as $type)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input asset-checkbox" type="checkbox" 
                                       name="asset_types[]" value="{{ $type }}" 
                                       id="asset_{{ $loop->index }}"
                                       {{ in_array($type, old('asset_types', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="asset_{{ $loop->index }}">
                                    {{ $type }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Add New Asset Option -->
                        <div class="col-md-12 mt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addNewAssetBtn">
                                <i class="fas fa-plus"></i> Add New Asset
                            </button>
                        </div>
                    </div>
                    @error('asset_types')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Asset Input (Hidden by default) -->
                <div class="col-md-12 mb-3" id="newAssetContainer" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">New Asset Name</label>
                            <input type="text" id="newAssetName" class="form-select" placeholder="Enter new asset type">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-success btn-sm" id="addNewAssetConfirm">
                                <i class="fas fa-check"></i> Add
                            </button>
                            <button type="button" class="btn btn-danger btn-sm ms-1" id="cancelNewAsset">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Selected Assets Display -->
                <div class="col-md-12 mb-3" id="selected-assets-container" style="display: none;">
                    <label class="form-label">Selected Assets:</label>
                    <div id="selected-assets" class="d-flex flex-wrap gap-2"></div>
                </div>

                <!-- Issue Date -->
                <div class="col-md-6 mb-3">
                    <x-input name="issue_date" label="Issue Date" type="date" 
                             :value="old('issue_date', now()->toDateString())" required="true" />
                </div>

                <!-- Return Date -->
                <div class="col-md-6 mb-3">
                    <x-input name="return_date" label="Return Date" type="date" 
                             value="{{ old('return_date') }}" />
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="Issued" {{ old('status') == 'Issued' ? 'selected' : '' }}>Issued</option>
                        <option value="Returned" {{ old('status') == 'Returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Condition -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Condition <span class="text-danger">*</span></label>
                    <select name="condition" class="form-select @error('condition') is-invalid @enderror" required>
                        <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                        <option value="Damaged" {{ old('condition') == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                    </select>
                    @error('condition')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remarks -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" 
                              rows="2">{{ old('remarks') }}</textarea>
                    @error('remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-3">
                <x-button type="submit" class="btn-primary" text="Assign Assets" icon="save" />
                <a href="{{ route('assets.index') }}" class="btn btn-secondary">
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
            var employeeCode = $(this).val();
            
            if (employeeCode) {
                $.ajax({
                    url: "{{ route('assets.get-employee') }}",
                    type: "GET",
                    data: { employee_code: employeeCode },
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            $('#employee_name').val(data.employee_name);
                            $('#department').val(data.department);
                        } else {
                            $('#employee_name').val('');
                            $('#department').val('');
                        }
                    }
                });
            } else {
                $('#employee_name').val('');
                $('#department').val('');
            }
        });

        // Show/hide new asset input
        $('#addNewAssetBtn').on('click', function() {
            $('#newAssetContainer').slideDown();
            $('#newAssetName').focus();
        });

        $('#cancelNewAsset').on('click', function() {
            $('#newAssetContainer').slideUp();
            $('#newAssetName').val('');
        });

        // Add new asset
        $('#addNewAssetConfirm').on('click', function() {
            var newAsset = $('#newAssetName').val().trim();
            
            if (newAsset === '') {
                alert('Please enter asset name');
                return;
            }

            // Check if asset already exists
            var exists = false;
            $('.asset-checkbox').each(function() {
                if ($(this).val() === newAsset) {
                    exists = true;
                }
            });

            if (exists) {
                alert('"' + newAsset + '" already exists!');
                $('#newAssetName').val('');
                $('#newAssetContainer').slideUp();
                return;
            }

            // Add new checkbox
            var checkboxHtml = '<div class="col-md-4 mb-2">' +
                                '<div class="form-check">' +
                                    '<input class="form-check-input asset-checkbox" type="checkbox" ' +
                                           'name="asset_types[]" value="' + newAsset + '" id="asset_new" checked>' +
                                    '<label class="form-check-label" for="asset_new">' + newAsset + '</label>' +
                                '</div>' +
                              '</div>';

            // Insert before "Add New Asset" button
            $('#asset-checkboxes .col-md-12').before(checkboxHtml);

            // Clear input and hide
            $('#newAssetName').val('');
            $('#newAssetContainer').slideUp();

            // Trigger change to update selected badges
            $('.asset-checkbox').trigger('change');
        });

        // Press Enter to add asset
        $('#newAssetName').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#addNewAssetConfirm').click();
            }
        });

        // Show selected assets as badges
        $(document).on('change', '.asset-checkbox', function() {
            var selected = [];
            $('.asset-checkbox:checked').each(function() {
                selected.push($(this).val());
            });
            
            var container = $('#selected-assets');
            var displayDiv = $('#selected-assets-container');
            
            if (selected.length > 0) {
                displayDiv.show();
                container.empty();
                selected.forEach(function(item) {
                    container.append('<span class="badge bg-primary me-1 mb-1" style="padding: 8px 15px; font-size: 14px;">' + item + ' <i class="fas fa-check-circle ms-1"></i></span>');
                });
            } else {
                displayDiv.hide();
                container.empty();
            }
        });

        // Trigger on load
        $('.asset-checkbox').trigger('change');

        // Form validation
        $('#assetForm').on('submit', function(e) {
            var checked = $('.asset-checkbox:checked').length;
            if (checked === 0) {
                alert('Please select at least one asset type.');
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush
@endsection