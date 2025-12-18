@props([
    'title',
    'description' => '',
    'icon' => 'home',
    'actionText' => '',
    'actionRoute' => '#',
    'actionIcon' => 'plus',
])

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="mdi mdi-{{ $icon }} me-2"></i>{{ $title }}
        </h4>
        @if ($description)
            <p class="text-muted mb-0">{{ $description }}</p>
        @endif
    </div>
    @if ($actionText)
        <div>
            {{ $slot }}
            @if (!$slot->isEmpty())
                {{ $slot }}
            @else
                <a href="{{ $actionRoute }}" class="btn btn-primary">
                    <i class="mdi mdi-{{ $actionIcon }} me-1"></i>{{ $actionText }}
                </a>
            @endif
        </div>
    @endif
</div>


