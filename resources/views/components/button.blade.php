<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'btn ' . $class]) }}>
    
    @if($icon)
        <i class="fas fa-{{ $icon }}"></i>
    @endif

    {{ $text }}
</button>