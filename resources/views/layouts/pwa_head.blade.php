{{-- PWA + theme bootstrap (runs before paint to avoid flash) --}}
<script>
    (function() {
        try {
            var t = localStorage.getItem('theme');
            if (!t) t = window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        } catch (e) {}
    })();
</script>
<link rel="manifest" href="{{ route('pwa.manifest') }}">
<meta name="theme-color" content="#2f57ef">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ get_settings('system_name') }}">
<link rel="apple-touch-icon" href="{{ asset('assets/pwa/icon-180.png') }}">
<link rel="stylesheet" href="{{ asset('assets/frontend/default/css/theme.css') }}?v=7">
