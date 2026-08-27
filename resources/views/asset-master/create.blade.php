@extends('layouts.dashboard')

@section('page-title', 'Add New Asset')

@section('content')
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-plus"></i> Add New Asset</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('asset-master.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-input name="asset_code" label="Asset Code" type="text" 
                             :value="old('asset_code')" required="true" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-input name="asset_type" label="Asset Type" type="text" 
                             :value="old('asset_type')" required="true" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-input name="company_name" label="Asset Company Name" type="text" 
                             :value="old('company_name')" required="true" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-input name="model" label="Model" type="text" 
                             :value="old('model')" required="true" />
                </div>
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="form-label">Condition <span class="text-danger">*</span></label>
                        <select name="condition" class="form-select @error('condition') is-invalid @enderror" required>
                            <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Damaged" {{ old('condition') == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                        </select>
                        @error('condition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
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
                <x-button type="submit" class="btn-primary" text="Save Asset" icon="save" />
                <a href="{{ route('asset-master.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection