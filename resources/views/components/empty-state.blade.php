@props(['icon' => 'inbox', 'message' => 'No records found', 'actionText' => '', 'actionRoute' => '#'])

<div class="text-center py-5">
    <i class="mdi mdi-{{ $icon }}-outline mdi-48px text-muted d-block mb-3"></i>
    <p class="text-muted mb-{{ $actionText ? '3' : '0' }}">{{ $message }}</p>
    @if ($actionText)
        <a href="{{ $actionRoute }}" class="btn btn-primary">
            <i class="mdi mdi-plus me-1"></i>{{ $actionText }}
        </a>
    @endif
</div>


