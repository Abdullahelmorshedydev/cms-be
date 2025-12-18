<div class="mt-3 px-3">
    <ul class="pagination">
        {{-- Previous Page --}}
        <li class="page-item {{ $meta['current_page'] == 1 ? 'disabled' : '' }}">
            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $meta['current_page'] - 1]) }}">
                &laquo;
            </a>
        </li>

        {{-- Page Numbers --}}
        @for ($i = 1; $i <= $meta['last_page']; $i++)
            <li class="page-item {{ $i == $meta['current_page'] ? 'active' : '' }}">
                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
            </li>
        @endfor

        {{-- Next Page --}}
        <li class="page-item {{ $meta['current_page'] == $meta['last_page'] ? 'disabled' : '' }}">
            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $meta['current_page'] + 1]) }}">
                &raquo;
            </a>
        </li>
    </ul>
</div>
