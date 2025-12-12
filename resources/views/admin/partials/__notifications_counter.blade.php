<span class="badge rounded-pill bg-label-primary">{{ auth()->user()->notifications()->whereNull('read_at')->count() }}
    {{ __('custom.nav.new') }}</span>
