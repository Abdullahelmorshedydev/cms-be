@props(['title', 'chartId', 'height' => '300px'])

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $title }}</h5>
        <div>
            {{ $actions ?? '' }}
        </div>
    </div>
    <div class="card-body">
        <div style="position: relative; height: {{ $height }};">
            <canvas id="{{ $chartId }}"></canvas>
        </div>
    </div>
</div>

