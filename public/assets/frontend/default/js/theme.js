(function () {
    'use strict';
    var root = document.documentElement;

    function setTheme(t) {
        root.setAttribute('data-bs-theme', t);
        try { localStorage.setItem('theme', t); } catch (e) {}
        var m = document.querySelector('meta[name="theme-color"]');
        if (m) m.setAttribute('content', t === 'dark' ? '#0f172a' : '#2f57ef');
        document.querySelectorAll('.header-dark-logo').forEach(function (el) { el.classList.toggle('d-none', t === 'dark'); });
        document.querySelectorAll('.header-light-logo').forEach(function (el) { el.classList.toggle('d-none', t !== 'dark'); });
    }
    setTheme(root.getAttribute('data-bs-theme') || 'light');

    document.addEventListener('click', function (e) {
        var b = e.target.closest('[data-theme-toggle]');
        if (!b) return;
        setTheme(root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
    });

    document.addEventListener('change', function (e) {
        if (e.target.classList && e.target.classList.contains('js-lng-selector')) {
            e.target.form.submit();
        }
    });

    // ---- PWA ----
    if ('serviceWorker' in navigator && (location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1')) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register(window.PWA_SW_URL || '/sw.js', { scope: '/' }).catch(function () {});
        });
    }

    var deferred = null, box = document.getElementById('pwa-install');
    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferred = e;
        var dismissed = false;
        try { dismissed = localStorage.getItem('pwa-dismissed') === '1'; } catch (x) {}
        if (box && !dismissed) box.hidden = false;
    });
    var ib = document.getElementById('pwa-install-btn');
    if (ib) ib.addEventListener('click', function () {
        if (!deferred) return;
        deferred.prompt();
        deferred.userChoice.finally(function () { deferred = null; if (box) box.hidden = true; });
    });
    var cb = document.getElementById('pwa-install-close');
    if (cb) cb.addEventListener('click', function () {
        if (box) box.hidden = true;
        try { localStorage.setItem('pwa-dismissed', '1'); } catch (x) {}
    });
    window.addEventListener('appinstalled', function () { if (box) box.hidden = true; });
})();
