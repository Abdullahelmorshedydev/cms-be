@props(['action' => '', 'method' => 'GET'])

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ $action }}" method="{{ $method }}" class="row g-3">
            @if ($method !== 'GET')
                @csrf
                @method($method)
            @endif
            {{ $slot }}
            <div class="col-auto">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">
                    <i class="mdi mdi-magnify me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>


