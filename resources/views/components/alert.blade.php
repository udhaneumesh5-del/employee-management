@if($message)
    <div class="alert alert-{{ $type }} alert-dismissible fade show">
        <i class="fas fa-{{ $type == 'success' ? 'check-circle' : ($type == 'danger' ? 'exclamation-circle' : 'info-circle') }}"></i>
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif