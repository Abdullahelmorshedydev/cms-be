<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="/dashboard/assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ setting('site_favicon') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/fonts/materialdesignicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/node-waves/node-waves.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/css/rtl/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <!-- Vendor -->
    <link rel="stylesheet"
        href="{{ asset('dashboard/assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('dashboard/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('dashboard/assets/vendor/js/template-customizer.js') }}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('dashboard/assets/js/config.js') }}"></script>

    @if (session('message'))
        <style>
            .toast:not(.show) {
                display: block;
            }

            .toast {
                background-color: #fff;
            }

            .toast-ex {
                top: 0.10000000000000142rem;
            }
        </style>
    @endif
</head>

<body>
    <!-- Content -->

    @if (session('message'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof toastr !== 'undefined') {
                    @if(session('message')['status'] == true)
                        toastr.success({!! json_encode(session('message')['content']) !!}, 'Success');
                    @else
                        toastr.error({!! json_encode(session('message')['content']) !!}, 'Error');
                    @endif
                }
            });
        </script>
    @endif

    <div class="authentication-wrapper authentication-cover">
        <!-- Logo -->
        <a href="#" class="auth-cover-brand d-flex align-items-center gap-2">
            {{-- <span class="app-brand-logo demo" style="background-color: #0f5bd8">
                <img style="width: 8rem; height: 50px; object-fit: cover;mix-blend-mode: hard-light !important;"
                    src="{{ asset('images/eraa_soft_logo.png') }}" alt="Eraasoft">
            </span> --}}
            <span class="app-brand-text demo text-heading fw-bold">{{ setting('site_name') }}</span>
        </a>
        <!-- /Logo -->
        <div class="authentication-inner row m-0">
            <!-- /Left Section -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center justify-content-center p-5 pb-2">
                <img src="{{ asset('dashboard/assets/img/illustrations/auth-login-illustration-light.png') }}"
                    class="auth-cover-illustration w-100" alt="auth-illustration"
                    data-app-light-img="illustrations/auth-login-illustration-light.png"
                    data-app-dark-img="illustrations/auth-login-illustration-dark.png" />
                <img src="{{ asset('dashboard/assets/img/illustrations/auth-cover-login-mask-light.png') }}"
                    class="authentication-image" alt="mask"
                    data-app-light-img="illustrations/auth-cover-login-mask-light.png"
                    data-app-dark-img="illustrations/auth-cover-login-mask-dark.png" />
            </div>
            <!-- /Left Section -->

            <!-- Login -->
            <div
                class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-5 px-4 py-4">
                <div class="w-px-400 mx-auto pt-5 pt-lg-0">
                    <h4 class="mb-2">{{ __('custom.login.welcome') }}! 👋</h4>
                    <p class="mb-4">{{ __('custom.login.header') }}</p>

                    <form id="formAuthentication" class="mb-3" action="{{ '/' . app()->getLocale() . '/dashboard/login' }}"
                        method="POST">
                        @csrf
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" class="form-control" id="user" name="user"
                                placeholder="Enter your Email Or Phone" autofocus value="{{ old('user') }}" />
                            <label for="user">{{ __('custom.inputs.emailOrPhone') }}</label>
                            @error('user')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password" class="form-control" name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password" />
                                        <label for="password">{{ __('custom.inputs.password') }}</label>
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 d-flex justify-content-between">
                            <div class="form-check">
                                <input class="form-check-input" name="remember" type="checkbox" id="remember-me" />
                                <label class="form-check-label" for="remember-me"> {{ __('custom.login.remember') }}
                                </label>
                            </div>
                            <a href="{{ route('dashboard.password.request') }}" class="float-end mb-1">
                              <span>{{ __('custom.auth.forget_password') }}?</span>
                            </a>
                        </div>
                        <button type="submit"
                            class="btn btn-primary d-grid w-100">{{ __('custom.login.login') }}</button>
                    </form>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('dashboard/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('dashboard/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('dashboard/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('dashboard/assets/js/pages-auth.js') }}"></script>

    @if (session('message'))
        <script>
            $(document).ready(function() {
                // Wait for 5 seconds and then add the 'd-none' class to the 'toast' div
                setTimeout(function() {
                    $('.toast').addClass('d-none');
                }, 3000);
            });
        </script>
    @endif
</body>

</html>
