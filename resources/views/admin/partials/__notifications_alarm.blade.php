@if (auth()->user()->notifications()->whereNull('read_at')->count())
    <span class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
@endif
