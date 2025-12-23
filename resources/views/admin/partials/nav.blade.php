<!-- Navbar -->
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            {{-- @canany(['lead.create', 'lead.create-not-assign'])
            <li class="nav-item me-1 me-xl-0">
                <button onclick="window.location.href='{{ route('leads.create') }}'" type="button"
                    class="nav-link btn btn-outline-primary waves-effect width-md"
                    style="width: 130px;margin-inline-end: 10px;">
                    {{ __('custom.words.add') . ' ' . __('custom.lead.lead') }}
                </button>
            </li>
            @endcanany --}}

            <!-- Language -->
            <li class="nav-item dropdown-language dropdown me-1 me-xl-0">
                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                    href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="mdi mdi-translate mdi-24px"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li>
                            <a class="dropdown-item" hreflang="{{ $localeCode }}"
                                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                <span class="align-middle">{{ $properties['native'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <!--/ Language -->

            <!-- Style Switcher -->
            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                    href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="mdi mdi-24px"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle"><i
                                    class="mdi mdi-weather-sunny me-2"></i>{{ __('custom.display.light') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i
                                    class="mdi mdi-weather-night me-2"></i>{{ __('custom.display.dark') }}</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Notifications -->
            {{-- <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-0">
                <a class="nav-link hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                    data-bs-auto-close="outside" aria-expanded="false">
                    <i class="mdi mdi-bell-outline mdi-24px"></i>
                    <span class="badge rounded-pill badge-notifications bg-danger notification-counter"
                        style="display: none;">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">Notifications</h5>
                            <span class="badge rounded-pill badge-label-primary notification-counter">0</span>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush notification-list">
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class="mdi mdi-information"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">No notifications</h6>
                                        <small class="text-muted">You're all caught up!</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-menu-footer border-top">
                        <a href="javascript:void(0);"
                            class="dropdown-item d-flex justify-content-center text-primary p-2 h-underline-none">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </li> --}}
            <!--/ Notifications -->

            <!-- User -->
            @php
                $user = auth()->user();
            @endphp
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset($user->image_path ?? 'dashboard/assets/img/avatars/1.png') }}" alt
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        {{-- {{ route('dashboard.profile.index') }} --}}
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ $user->image_path }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block">{{ $user->name }}</span>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    {{-- <li>
                        <a class="dropdown-item" href="{{ route('dashboard.profile.index') }}">
                            <i class="mdi mdi-account-outline me-2"></i>
                            <span class="align-middle">{{ __('custom.nav.profile') }}</span>
                        </a>
                    </li> --}}
                    {{-- TODO : Add Settings --}}
                    {{-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="mdi mdi-cog-outline me-2"></i>
                            <span class="align-middle">{{ __('custom.nav.settings') }}</span>
                        </a>
                    </li> --}}
                    {{-- <li>
                        <div class="dropdown-divider"></div>
                    </li> --}}
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="$('#logoutForm').submit()">
                            <i class="mdi mdi-logout me-2"></i>
                            <span class="align-middle">{{ __('custom.nav.logout') }}</span>
                        </a>
                        <form id="logoutForm" class="d-none" action="{{ route('dashboard.logout') }}" method="POST">
                            @csrf</form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..."
            aria-label="Search..." />
        <i class="mdi mdi-close search-toggler cursor-pointer"></i>
    </div>
</nav>
<!-- / Navbar -->
