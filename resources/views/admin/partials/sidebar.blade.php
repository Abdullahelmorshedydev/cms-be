<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        @php
            $companyName = setting('company_name');
            if (is_array($companyName)) {
                $companyName = $companyName[app()->getLocale()] ?? $companyName['en'] ?? reset($companyName);
            }
        @endphp
        <a href="{{ route('dashboard.home') }}" class="app-brand-link">
            <img src="{{ setting('site_favicon') }}" alt="site_favicon"
                style="width: 50px; height: 50px; object-fit: cover; mix-blend-mode: hard-light !important; margin-inline-end: 10px">
            <span class="app-brand-text demo menu-text fw-bold ms-2">{{ $companyName }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                    fill="currentColor" fill-opacity="0.6" />
                <path
                    d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                    fill="currentColor" fill-opacity="0.38" />
            </svg>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- ============================================
            MAIN NAVIGATION
        ============================================ -->

        <!-- Dashboard -->
        <li class="menu-item {{ isActiveRoute('dashboard.home') }}">
            <a href="{{ route('dashboard.home') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-view-dashboard-outline"></i>
                <div>{{ __('custom.sidebar.dashboard') }}</div>
            </a>
        </li>

        <!-- ============================================
            CONTENT MANAGEMENT
        ============================================ -->
        @canany(['page.show'])
            <li
                class="menu-item {{ isActiveRoute(['dashboard.cms.pages.*', 'dashboard.cms.section-types.*', 'dashboard.blogs.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-file-document-edit-outline"></i>
                    <div>{{ __('custom.sidebar.content_management') ?? 'Content Management' }}</div>
                </a>
                <ul class="menu-sub">
                    @can('page.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.cms.pages.*') }}">
                            <a href="{{ route('dashboard.cms.pages.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-file-outline"></i>
                                <div>{{ __('custom.sidebar.pages') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('section-type.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.cms.section-types.*') }}">
                            <a href="{{ route('dashboard.cms.section-types.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-shape-outline"></i>
                                <div>{{ __('custom.words.section_types') ?? 'Section Types' }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- ============================================
            FORMS MANAGEMENT
        ============================================ -->
        @canany(['form.show', 'form-email.show'])

            <li class="menu-item {{ isActiveRoute(['dashboard.forms.*', 'dashboard.form-emails.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-form-select"></i>
                    <div>{{ __('custom.sidebar.forms_management') ?? 'Forms Management' }}</div>
                </a>
                <ul class="menu-sub">
                    @can('form.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.forms.index') }}">
                            <a href="{{ route('dashboard.forms.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-email-multiple-outline"></i>
                                <div>{{ __('custom.sidebar.all_forms') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('form-email.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.form-emails.*') }}">
                            <a href="{{ route('dashboard.form-emails.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-email-outline"></i>
                                <div>{{ __('custom.sidebar.email_recipients') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- ============================================
            MANAGEMENT
        ============================================ -->
        @canany(['tag.show', 'project.show', 'service.show', 'partner.show'])
            <li
                class="menu-item {{ isActiveRoute(['dashboard.tags.*', 'dashboard.services.*', 'dashboard.projects.*', 'dashboard.partners.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-cog-outline"></i>
                    <div>{{ __('custom.sidebar.management') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('tag.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.tags.*') }}">
                            <a href="{{ route('dashboard.tags.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-tag-outline"></i>
                                <div>{{ __('custom.sidebar.tags') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('service.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.services.*') }}">
                            <a href="{{ route('dashboard.services.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-briefcase-outline"></i>
                                <div>{{ __('custom.sidebar.services') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('project.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.projects.*') }}">
                            <a href="{{ route('dashboard.projects.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-folder-outline"></i>
                                <div>{{ __('custom.sidebar.projects') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('partner.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.partners.*') }}">
                            <a href="{{ route('dashboard.partners.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-folder-outline"></i>
                                <div>{{ __('custom.sidebar.partners') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- ============================================
            USER MANAGEMENT
        ============================================ -->
        @canany(['user.show', 'role.show'])
            <li class="menu-item {{ isActiveRoute(['dashboard.users.*', 'dashboard.roles.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-group-outline"></i>
                    <div>{{ __('custom.sidebar.user_management') ?? 'User Management' }}</div>
                </a>
                <ul class="menu-sub">
                    @can('user.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.users.*') }}">
                            <a href="{{ route('dashboard.users.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                                <div>{{ __('custom.sidebar.users') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('role.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.roles.*') }}">
                            <a href="{{ route('dashboard.roles.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-shield-account-outline"></i>
                                <div>{{ __('custom.sidebar.roles_permissions') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- ============================================
                    SETTINGS
                ============================================ -->
        @can('settings.show')
            <li class="menu-item {{ isActiveRoute('dashboard.settings.index') }}">
                <a href="{{ route('dashboard.settings.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-cog-outline"></i>
                    <div>{{ __('custom.sidebar.settings') }}</div>
                </a>
            </li>
        @endcan
    </ul>
</aside>
<!-- / Menu -->