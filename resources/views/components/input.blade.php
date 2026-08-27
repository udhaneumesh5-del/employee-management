@props([
    'name', 
    'label' => null, 
    'value' => '', 
    'type' => 'text', 
    'required' => false,
    'placeholder' => null,
    'id' => null,
    'class' => ''
])

@php
    $inputId = $id ?? $name;
    $inputClass = 'form-control ' . $class;
    if ($errors->has($name)) {
        $inputClass .= ' is-invalid';
    }
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $inputId }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $inputId }}" 
        value="{{ old($name, $value) }}" 
        class="{{ $inputClass }}"
        placeholder="{{ $placeholder ?? '' }}"
        @if($required) required @endif
        {{ $attributes }}
    />
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>