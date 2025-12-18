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
        @canany(['page.show', 'blog.show'])
            <li class="menu-item {{ isActiveRoute(['dashboard.pages.*', 'dashboard.blogs.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-file-document-edit-outline"></i>
                    <div>{{ __('custom.sidebar.content_management') ?? 'Content Management' }}</div>
                </a>
                <ul class="menu-sub">
                    @can('page.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.pages.*') }}">
                            <a href="{{ route('dashboard.pages.create') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-file-outline"></i>
                                <div>{{ __('custom.sidebar.pages') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('blog.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.blogs.*') }}">
                            <a href="{{ route('dashboard.blogs.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-post-outline"></i>
                                <div>{{ __('custom.sidebar.blogs') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- ============================================
            CMS MANAGEMENT
        ============================================ -->
        <li class="menu-item {{ isActiveRoute(['dashboard.cms.*']) ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-content-copy"></i>
                <div>{{ __('custom.sidebar.cms_management') ?? 'CMS Management' }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ isActiveRoute('dashboard.cms.pages.*') }}">
                    <a href="{{ route('dashboard.cms.pages.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-file-document-outline"></i>
                        <div>{{ __('custom.words.pages') ?? 'Pages' }}</div>
                    </a>
                </li>
                <li class="menu-item {{ isActiveRoute('dashboard.cms.sections.*') }}">
                    <a href="{{ route('dashboard.cms.sections.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-view-sequential-outline"></i>
                        <div>{{ __('custom.words.sections') ?? 'Sections' }}</div>
                    </a>
                </li>
                <li class="menu-item {{ isActiveRoute('dashboard.cms.section-types.*') }}">
                    <a href="{{ route('dashboard.cms.section-types.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-shape-outline"></i>
                        <div>{{ __('custom.words.section_types') ?? 'Section Types' }}</div>
                    </a>
                </li>
            </ul>
        </li>

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
            USER MANAGEMENT
        ============================================ -->
        @canany(['user.show', 'role.show', 'student.show', 'parent.show'])
            <li
                class="menu-item {{ isActiveRoute(['dashboard.users.*', 'dashboard.roles.*', 'dashboard.students.*', 'dashboard.parents.*']) ? 'open' : '' }}">
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

                    @can('student.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.students.*') }}">
                            <a href="{{ route('dashboard.students.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-school-outline"></i>
                                <div>{{ __('custom.sidebar.students') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('parent.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.parents.*') }}">
                            <a href="{{ route('dashboard.parents.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-group-outline"></i>
                                <div>{{ __('custom.sidebar.parents') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- ============================================
            E-LEARNING MODULE
        ============================================ -->
        @canany(['course.show', 'enrollment.show', 'quiz.show', 'assignment.show', 'exam.show'])
            <li class="menu-item {{ isActiveRoute(['dashboard.elearning.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-school-outline"></i>
                    <div>{{ __('custom.sidebar.elearning') ?? 'E-Learning' }}</div>
                </a>
                <ul class="menu-sub">
                    @can('course.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.elearning.categories.*') }}">
                            <a href="{{ route('dashboard.elearning.categories.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-folder-outline"></i>
                                <div>{{ __('custom.sidebar.course_categories') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('course.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.elearning.courses.*') }}">
                            <a href="{{ route('dashboard.elearning.courses.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-book-open-outline"></i>
                                <div>{{ __('custom.sidebar.courses') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('course.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.elearning.chapters.*') }}">
                            <a href="{{ route('dashboard.elearning.chapters.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-book-outline"></i>
                                <div>{{ __('custom.sidebar.chapters') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('course.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.elearning.topics.*') }}">
                            <a href="{{ route('dashboard.elearning.topics.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-bookmark-outline"></i>
                                <div>{{ __('custom.sidebar.topics') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('course.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.elearning.lessons.*') }}">
                            <a href="{{ route('dashboard.elearning.lessons.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-play-circle-outline"></i>
                                <div>{{ __('custom.sidebar.lessons') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('enrollment.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.elearning.enrollments.*') }}">
                            <a href="{{ route('dashboard.elearning.enrollments.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-plus-outline"></i>
                                <div>{{ __('custom.sidebar.enrollments') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('quiz.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.elearning.quizzes.*') }}">
                            <a href="{{ route('dashboard.elearning.quizzes.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-help-circle-outline"></i>
                                <div>{{ __('custom.sidebar.quizzes') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('assignment.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.elearning.assignments.*') }}">
                            <a href="{{ route('dashboard.elearning.assignments.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-file-document-outline"></i>
                                <div>{{ __('custom.sidebar.assignments') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('exam.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.elearning.exams.*') }}">
                            <a href="{{ route('dashboard.elearning.exams.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-file-check-outline"></i>
                                <div>{{ __('custom.sidebar.exams') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- ============================================
                CRM MODULE
            ============================================ -->
        @canany(['crm_lead.show', 'crm_contact.show', 'crm_deal.show', 'crm_activity.show', 'crm_call.show',
            'crm_transaction.show'])
            <li class="menu-item {{ isActiveRoute(['dashboard.crm.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-star-outline"></i>
                    <div>{{ __('custom.sidebar.crm') ?? 'CRM' }}</div>
                </a>
                <ul class="menu-sub">
                    @can('crm_lead.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.crm.leads.*') }}">
                            <a href="{{ route('dashboard.crm.leads.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-star-outline"></i>
                                <div>{{ __('custom.sidebar.crm_leads') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('crm_contact.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.crm.contacts.*') }}">
                            <a href="{{ route('dashboard.crm.contacts.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-box-outline"></i>
                                <div>{{ __('custom.sidebar.crm_contacts') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('crm_deal.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.crm.deals.*') }}">
                            <a href="{{ route('dashboard.crm.deals.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-handshake-outline"></i>
                                <div>{{ __('custom.sidebar.crm_deals') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('crm_activity.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.crm.activities.*') }}">
                            <a href="{{ route('dashboard.crm.activities.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-calendar-check-outline"></i>
                                <div>{{ __('custom.sidebar.crm_activities') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('crm_call.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.crm.calls.*') }}">
                            <a href="{{ route('dashboard.crm.calls.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-phone-outline"></i>
                                <div>{{ __('custom.sidebar.crm_calls') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('crm_transaction.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.crm.transactions.*') }}">
                            <a href="{{ route('dashboard.crm.transactions.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-cash-multiple"></i>
                                <div>{{ __('custom.sidebar.crm_transactions') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- ============================================
                    HRMS MODULE
                ============================================ -->
        @canany(['employee.show', 'attendance.show', 'payroll.show'])
            <li class="menu-item {{ isActiveRoute(['dashboard.hrms.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-tie-outline"></i>
                    <div>{{ __('custom.sidebar.hrms') ?? 'HRMS' }}</div>
                </a>
                <ul class="menu-sub">
                    @can('employee.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.hrms.employees.*') }}">
                            <a href="{{ route('dashboard.hrms.employees.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-tie-outline"></i>
                                <div>{{ __('custom.sidebar.employees') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('attendance.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.hrms.attendance.*') }}">
                            <a href="{{ route('dashboard.hrms.attendance.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-calendar-clock-outline"></i>
                                <div>{{ __('custom.sidebar.attendance') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('payroll.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.hrms.payroll.*') }}">
                            <a href="{{ route('dashboard.hrms.payroll.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-cash-check"></i>
                                <div>{{ __('custom.sidebar.payroll') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- ============================================
                    ERP MODULE
                ============================================ -->
        @canany(['account.show', 'expense.show', 'revenue.show'])
            <li class="menu-item {{ isActiveRoute(['dashboard.erp.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-cash-multiple"></i>
                    <div>{{ __('custom.sidebar.erp') ?? 'ERP' }}</div>
                </a>
                <ul class="menu-sub">
                    @can('account.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.erp.accounts.*') }}">
                            <a href="{{ route('dashboard.erp.accounts.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-bank-outline"></i>
                                <div>{{ __('custom.sidebar.accounts') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('expense.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.erp.expenses.*') }}">
                            <a href="{{ route('dashboard.erp.expenses.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-arrow-down-circle-outline"></i>
                                <div>{{ __('custom.sidebar.expenses') }}</div>
                            </a>
                        </li>
                    @endcan

                    @can('revenue.show')
                        <li class="menu-item {{ isActiveRoute('dashboard.erp.revenues.*') }}">
                            <a href="{{ route('dashboard.erp.revenues.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-arrow-up-circle-outline"></i>
                                <div>{{ __('custom.sidebar.revenues') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- ============================================
                    REPORTS & ANALYTICS
                ============================================ -->
        @can('report.show')
            <li class="menu-item {{ isActiveRoute(['dashboard.reports.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-chart-box-outline"></i>
                    <div>{{ __('custom.sidebar.reports') ?? 'Reports & Analytics' }}</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ isActiveRoute('dashboard.reports.dashboard') }}">
                        <a href="{{ route('dashboard.reports.dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-chart-box-outline"></i>
                            <div>{{ __('custom.sidebar.reports_dashboard') }}</div>
                        </a>
                    </li>

                    <li class="menu-item {{ isActiveRoute('dashboard.reports.sales') }}">
                        <a href="{{ route('dashboard.reports.sales') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-chart-line"></i>
                            <div>{{ __('custom.sidebar.reports_sales') }}</div>
                        </a>
                    </li>

                    <li class="menu-item {{ isActiveRoute('dashboard.reports.financial') }}">
                        <a href="{{ route('dashboard.reports.financial') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-chart-pie"></i>
                            <div>{{ __('custom.sidebar.reports_financial') }}</div>
                        </a>
                    </li>

                    <li class="menu-item {{ isActiveRoute('dashboard.reports.attendance') }}">
                        <a href="{{ route('dashboard.reports.attendance') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-calendar-account-outline"></i>
                            <div>{{ __('custom.sidebar.reports_attendance') }}</div>
                        </a>
                    </li>

                    <li class="menu-item {{ isActiveRoute('dashboard.reports.leads') }}">
                        <a href="{{ route('dashboard.reports.leads') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-chart-timeline-variant"></i>
                            <div>{{ __('custom.sidebar.reports_leads') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        <!-- ============================================
                    AUTOMATION
                ============================================ -->
        @can('workflow.show')
            <li class="menu-item {{ isActiveRoute('dashboard.automation.*') }}">
                <a href="{{ route('dashboard.automation.workflows.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-robot-outline"></i>
                    <div>{{ __('custom.sidebar.automation') }}</div>
                </a>
            </li>
        @endcan

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
