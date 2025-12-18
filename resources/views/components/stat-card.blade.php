@props(['title', 'value', 'icon', 'color' => 'primary', 'trend' => null, 'trendLabel' => ''])

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="text-muted mb-1">{{ $title }}</p>
                <h3 class="mb-0">{{ $value }}</h3>
                @if ($trend)
                    <small class="{{ $trend > 0 ? 'text-success' : 'text-danger' }}">
                        <i class="mdi mdi-arrow-{{ $trend > 0 ? 'up' : 'down' }}"></i>
                        {{ abs($trend) }}{{ $trendLabel ? ' ' . $trendLabel : '' }}
                    </small>
                @endif
            </div>
            <div class="avatar avatar-lg bg-label-{{ $color }}">
                <i class="mdi mdi-{{ $icon }} mdi-24px"></i>
            </div>
        </div>
    </div>
</div>


