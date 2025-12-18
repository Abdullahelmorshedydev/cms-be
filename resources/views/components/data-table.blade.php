@props(['title' => '', 'headers' => [], 'data' => [], 'actions' => true])

<div class="card">
    @if ($title)
        <div class="card-header">
            <h5 class="mb-0">{{ $title }}</h5>
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover">
            @if (!empty($headers))
                <thead>
                    <tr>
                        @foreach ($headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                        @if ($actions)
                            <th width="100">Actions</th>
                        @endif
                    </tr>
                </thead>
            @endif
            <tbody>
                {{ $slot }}
                @if (empty($data) || count($data) == 0)
                    <tr>
                        <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="text-center py-5">
                            <i class="mdi mdi-inbox mdi-48px text-muted d-block mb-2"></i>
                            <p class="text-muted mb-0">No records found</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


