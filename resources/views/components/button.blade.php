<button type="{{ $type }}" class="btn {{ $class }}">
    @if($icon)
        <i class="fas fa-{{ $icon }}"></i>
    @endif
    {{ $text }}
</button>