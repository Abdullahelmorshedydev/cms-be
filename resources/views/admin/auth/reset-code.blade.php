<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('dashboard/assets') }}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ __('custom.reset_code.title') }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ setting('site_favicon') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/vendor/fonts/materialdesignicons.css" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/vendor/fonts/flag-icons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/vendor/css/rtl/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/vendor/css/rtl/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="{{ asset('dashboard/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet"
        href="{{ asset('dashboard/assets') }}/vendor/libs/@form-validation/umd/styles/index.min.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="{{ asset('dashboard/assets') }}/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('dashboard/assets') }}/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('dashboard/assets') }}/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    @if (session('message'))
        <div class="bs-toast toast toast-ex animate__animated my-2 fade {{ session('message')['status'] == true ? 'animate__pulse' : 'animate__shakeX' }}  show"
            role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i
                    class="mdi mdi-home me-2 {{ session('message')['status'] == true ? 'text-primary' : 'text-danger' }}"></i>
                <div class="me-auto fw-medium">{{ session('message')['status'] == true ? 'Success' : 'Error' }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ session('message')['content'] }}</div>
        </div> @endif

    <div class="authentication-wrapper
        authentication-cover">
    <!-- Logo -->
    <a href="{{ route('dashboard.login') }}" class="auth-cover-brand d-flex align-items-center gap-2">
        {{-- <span class="app-brand-logo demo">
            <span style="color: var(--bs-primary)">
                <svg width="268" height="150" viewBox="0 0 38 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M30.0944 2.22569C29.0511 0.444187 26.7508 -0.172113 24.9566 0.849138C23.1623 1.87039 22.5536 4.14247 23.5969 5.92397L30.5368 17.7743C31.5801 19.5558 33.8804 20.1721 35.6746 19.1509C37.4689 18.1296 38.0776 15.8575 37.0343 14.076L30.0944 2.22569Z"
                        fill="currentColor" />
                    <path
                        d="M30.171 2.22569C29.1277 0.444187 26.8274 -0.172113 25.0332 0.849138C23.2389 1.87039 22.6302 4.14247 23.6735 5.92397L30.6134 17.7743C31.6567 19.5558 33.957 20.1721 35.7512 19.1509C37.5455 18.1296 38.1542 15.8575 37.1109 14.076L30.171 2.22569Z"
                        fill="url(#paint0_linear_2989_100980)" fill-opacity="0.4" />
                    <path
                        d="M22.9676 2.22569C24.0109 0.444187 26.3112 -0.172113 28.1054 0.849138C29.8996 1.87039 30.5084 4.14247 29.4651 5.92397L22.5251 17.7743C21.4818 19.5558 19.1816 20.1721 17.3873 19.1509C15.5931 18.1296 14.9843 15.8575 16.0276 14.076L22.9676 2.22569Z"
                        fill="currentColor" />
                    <path
                        d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                        fill="currentColor" />
                    <path
                        d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                        fill="url(#paint1_linear_2989_100980)" fill-opacity="0.4" />
                    <path
                        d="M7.82901 2.22569C8.87231 0.444187 11.1726 -0.172113 12.9668 0.849138C14.7611 1.87039 15.3698 4.14247 14.3265 5.92397L7.38656 17.7743C6.34325 19.5558 4.04298 20.1721 2.24875 19.1509C0.454514 18.1296 -0.154233 15.8575 0.88907 14.076L7.82901 2.22569Z"
                        fill="currentColor" />
                    <defs>
                        <linearGradient id="paint0_linear_2989_100980" x1="5.36642" y1="0.849138" x2="10.532"
                            y2="24.104" gradientUnits="userSpaceOnUse">
                            <stop offset="0" stop-opacity="1" />
                            <stop offset="1" stop-opacity="0" />
                        </linearGradient>
                        <linearGradient id="paint1_linear_2989_100980" x1="5.19475" y1="0.849139" x2="10.3357"
                            y2="24.1155" gradientUnits="userSpaceOnUse">
                            <stop offset="0" stop-opacity="1" />
                            <stop offset="1" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                </svg>
            </span>
        </span> --}}
        <span class="app-brand-text demo text-heading fw-bold">{{ setting('site_name') }}</span>
    </a>
    <!-- /Logo -->
    <div class="authentication-inner row m-0">
        <!-- /Left Section -->
        <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center justify-content-center p-5 pb-2">
            <img src="{{ asset('dashboard/assets') }}/img/illustrations/auth-two-steps-illustration-light.png"
                class="auth-cover-illustration w-100" alt="auth-illustration"
                data-app-light-img="illustrations/auth-two-steps-illustration-light.png"
                data-app-dark-img="illustrations/auth-two-steps-illustration-dark.png" />
            <img src="{{ asset('dashboard/assets') }}/img/illustrations/auth-cover-register-mask-light.png"
                class="authentication-image" alt="mask"
                data-app-light-img="illustrations/auth-cover-register-mask-light.png"
                data-app-dark-img="illustrations/auth-cover-register-mask-dark.png" />
        </div>
        <!-- /Left Section -->

        <!-- Two Steps Verification -->
        <div
            class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-5 px-4 py-4">
            <div class="w-px-400 mx-auto pt-5 pt-lg-0">
                <h4 class="mb-2">{{ __('custom.reset_code.header_title') }} 💬</h4>
                <p class="text-start mb-4">
                    {{ __('custom.reset_code.header') }}.
                    @php
                        $parts = explode('@', $email);
                        $name = $parts[0];
                        $domain = $parts[1];
                        $maskedName =
                            substr($name, 0, 1) . str_repeat('*', max(0, strlen($name) - 2)) . substr($name, -1);

                        $value = $maskedName . '@' . $domain;
                    @endphp
                    <span class="fw-medium d-block mt-2">{{ $value }}</span>
                </p>
                <p class="mb-0 fw-medium">{{ __('custom.reset_code.6_digit') }}</p>
                <form id="twoStepsForm" action="{{ route('dashboard.verify_code') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <div class="mb-3">
                        <div
                            class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
                            <input type="tel"
                                class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2"
                                maxlength="1" autofocus />
                            <input type="tel"
                                class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2"
                                maxlength="1" />
                            <input type="tel"
                                class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2"
                                maxlength="1" />
                            <input type="tel"
                                class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2"
                                maxlength="1" />
                            <input type="tel"
                                class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2"
                                maxlength="1" />
                            <input type="tel"
                                class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2"
                                maxlength="1" />
                        </div>
                        <input type="hidden" name="code" id="hiddenCode" />
                    </div>
                    <button class="btn btn-primary d-grid w-100 mb-3">{{ __('custom.reset_code.verify') }}</button>
                </form>
                <div class="text-center">
                    {{ __('custom.reset_code.resend_header') }}
                    <form action="{{ route('dashboard.send_reset_code') }}" method="POST"
                        style="display: inline;">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit" class="btn btn-link p-0"
                            style="background: none; border: none; color: inherit; cursor: pointer;">
                            {{ __('custom.reset_code.resend') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Two Steps Verification -->
    </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/node-waves/node-waves.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/cleavejs/cleave.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="{{ asset('dashboard/assets') }}/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('dashboard/assets') }}/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ asset('dashboard/assets') }}/js/pages-auth.js"></script>
    <script src="{{ asset('dashboard/assets') }}/js/pages-auth-two-steps.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll(".numeral-mask");
            const hiddenInput = document.getElementById("hiddenCode");

            inputs.forEach((input, index) => {
                input.addEventListener("input", function() {
                    if (input.value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    hiddenInput.value = Array.from(inputs).map(input => input.value).join('');
                });

                input.addEventListener("keydown", function(event) {
                    if (event.key === "Backspace" && input.value === "" && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });
        });
    </script>
    </body>

</html>
