<link rel="stylesheet" href="{{ asset('assets/global/plyr/plyr.css') }}">

@if (get_frontend_settings('promo_video_provider') == 'youtube')
    <div class="plyr__video-embed" id="promoPlayer">
        <iframe height="500" src="{{ get_frontend_settings('promo_video_link') }}?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
    </div>
@elseif (get_frontend_settings('promo_video_provider') == 'vimeo')
    <div class="plyr__video-embed" id="promoPlayer">
        <iframe height="500" id="promoPlayer" src="https://player.vimeo.com/video/{{ get_frontend_settings('promo_video_link') }}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
    </div>
@else
    @php
        $videoLink = get_frontend_settings('promo_video_link');
        $videoSrc = str_starts_with($videoLink, 'http') ? $videoLink : asset($videoLink);
    @endphp
    <div class="rounded-4 overflow-hidden shadow-lg bg-black">
        <video id="promoPlayer" playsinline controls class="w-100" style="max-height: 540px; width: 100%; object-fit: contain;">
            <source src="{{ $videoSrc }}" type="video/mp4">
        </video>
    </div>

    <!-- Video Chapters / Quick Study Summary -->
    <div class="p-3 bg-dark text-white rounded-bottom-4 border-top border-secondary mt-1">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary px-3 py-1 rounded-pill fw-semibold" style="font-size: 11px;">EDUNEXA TOUR</span>
                <span class="fw-bold" style="font-size: 14px;">Muhtasari wa Jukwaa la Edunexa (Platform Overview)</span>
            </div>
            <small class="text-white-50">28 Sec • Full HD 1080p</small>
        </div>
        <div class="d-flex flex-wrap gap-2 pt-1" style="font-size: 11px;">
            <span class="badge bg-secondary text-light border border-secondary px-2 py-1"><i class="fa-solid fa-graduation-cap me-1 text-info"></i> Practical Courses</span>
            <span class="badge bg-secondary text-light border border-secondary px-2 py-1"><i class="fa-solid fa-qrcode me-1 text-success"></i> QR Verified Certificates</span>
            <span class="badge bg-secondary text-light border border-secondary px-2 py-1"><i class="fa-solid fa-laptop-code me-1 text-danger"></i> Student Showcase</span>
            <span class="badge bg-secondary text-light border border-secondary px-2 py-1"><i class="fa-solid fa-briefcase me-1 text-warning"></i> Career Hub & Jobs</span>
            <span class="badge bg-secondary text-light border border-secondary px-2 py-1"><i class="fa-solid fa-id-badge me-1 text-primary"></i> Digital CV (/u/id)</span>
            <span class="badge bg-secondary text-light border border-secondary px-2 py-1"><i class="fa-solid fa-mobile-screen-button me-1 text-success"></i> M-Pesa / Tigo / Airtel</span>
        </div>
    </div>
@endif

<script src="{{ asset('assets/global/plyr/plyr.js') }}"></script>
<script>
    "use strict";
    var promoPlayer = new Plyr('#promoPlayer', {
        controls: ['play-large', 'play', 'progress', 'current-time', 'duration', 'mute', 'volume', 'fullscreen']
    });
</script>
<script>
    "use strict";
    const myModalElPromo = document.getElementById('promoVideo');
    if (myModalElPromo) {
        myModalElPromo.addEventListener('hidden.bs.modal', event => {
            if (typeof promoPlayer !== 'undefined' && promoPlayer) {
                promoPlayer.pause();
            }
            $('#promoVideo').removeClass('in');
        });
        myModalElPromo.addEventListener('shown.bs.modal', event => {
            if (typeof promoPlayer !== 'undefined' && promoPlayer) {
                promoPlayer.play();
            }
            $('#promoVideo').addClass('in');
        });
    }
</script>