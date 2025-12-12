<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
            <div class="mb-2 mb-md-0">
                © {{ now()->year }}
                , {{ __('custom.footer.made_with') }} <span class="text-danger"><i
                        class="tf-icons mdi mdi-heart"></i></span> {{ __('custom.footer.by') }}
                <a href="#" target="_blank" class="footer-link fw-medium">{{ setting('site_name') }}</a>
            </div>
        </div>
    </div>
</footer>
<!-- / Footer -->
