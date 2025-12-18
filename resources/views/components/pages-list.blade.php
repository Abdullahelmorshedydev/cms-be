@foreach ($pages as $page)
    <li
        class="menu-item {{ isActiveRoute('dashboard.pages.edit') && request()->route('page') == $page->slug ? 'active' : '' }}">
        <a href="{{ route('dashboard.pages.edit', $page->slug) }}" class="menu-link">
            <div>{{ $page->getTranslation('name', app()->getLocale()) }}</div>
        </a>
    </li>
@endforeach
