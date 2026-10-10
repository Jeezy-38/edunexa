<div id="pwa-install" class="pwa-install" hidden>
    <img src="{{ asset('assets/pwa/icon-192.png') }}" alt="" width="40" height="40">
    <div class="pwa-install-text">
        <strong>{{ get_phrase('Install app') }}</strong>
        <span>{{ get_phrase('Add to your home screen for faster access') }}</span>
    </div>
    <button type="button" class="pwa-btn" id="pwa-install-btn">{{ get_phrase('Install') }}</button>
    <button type="button" class="pwa-close" id="pwa-install-close" aria-label="{{ get_phrase('Close') }}">&times;</button>
</div>
<script>window.PWA_SW_URL = "{{ url('/sw.js') }}";</script>
<script src="{{ asset('assets/frontend/default/js/theme.js') }}?v=1"></script>
