@php
    $locale = app()->getLocale();
    $isRTL = $locale === 'ar';
    $loadingText = $isRTL ? 'جاري التحميل...' : 'Loading...';
@endphp
<div id="page-loader" class="page-loader" role="status" aria-live="polite" aria-label="{{ $loadingText }}" aria-busy="false">
    <div class="page-loader__overlay"></div>
    <div class="page-loader__spinner">
        <div class="page-loader__spinner-inner">
            <svg class="page-loader__svg" viewBox="0 0 50 50" aria-hidden="true">
                <circle class="page-loader__circle" cx="25" cy="25" r="20" fill="none" stroke-width="4"></circle>
            </svg>
        </div>
        <p class="page-loader__text">{{ $loadingText }}</p>
    </div>
</div>

