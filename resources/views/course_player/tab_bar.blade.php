@php
    $course_progress_out_of_100 = progress_bar($course_details->id);
    if (isset($_GET['tab'])) {
        $tab = $_GET['tab'];
    } elseif (Session::has('forum_tab')) {
        $tab = Session::get('forum_tab');
    } else {
        $tab = 'summary';
    }
@endphp
@php
    $instructor = $course_details->creator ?? null;
    $rawWa = $instructor->phone ?? (get_settings('whatsapp_number') ?: (get_settings('phone') ?: '255712345678'));
    $cleanWa = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($cleanWa, '0') && strlen($cleanWa) === 10) {
        $cleanWa = '255' . substr($cleanWa, 1);
    }
    $lessonTitle = $lesson_details->title ?? get_phrase('Somo');
    $waText = urlencode("Habari Mwalimu (" . ($instructor->name ?? 'Edunexa') . ")! Niko kwenye kozi ya: " . $course_details->title . ". Nina swali kuhusu somo la: " . $lessonTitle);
@endphp

<!-- WhatsApp Class Community Banner -->
<div class="card border-0 shadow-sm rounded-3 mb-3 overflow-hidden text-white" style="background: linear-gradient(135deg, #075e54, #128c7e, #25d366);">
    <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-white text-success d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 40px; height: 40px; font-size: 22px;">
                <i class="fa-brands fa-whatsapp text-success"></i>
            </div>
            <div>
                <h6 class="mb-0 text-white fw-bold d-flex align-items-center gap-2">
                    {{ get_phrase('Darasa la WhatsApp na Mwalimu') }}
                    <span class="badge bg-white text-success px-2 py-0 rounded-pill" style="font-size: 10px;">{{ get_phrase('Msaada Papo Hapo') }}</span>
                </h6>
                <small class="text-white-50" style="font-size: 12px;">
                    {{ get_phrase('Ungana na mwalimu kupata ufafanuzi wa masomo ya kozi hii papo hapo.') }}
                </small>
            </div>
        </div>
        <a href="https://wa.me/{{ $cleanWa }}?text={{ $waText }}" target="_blank" rel="noopener noreferrer" class="btn btn-light text-success fw-bold rounded-pill px-3 py-1 shadow-sm text-nowrap" style="font-size: 13px;">
            <i class="fa-brands fa-whatsapp me-1"></i>
            <span>{{ get_phrase('Uliza WhatsApp') }}</span>
        </a>
    </div>
</div>

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link @if ($tab == 'summary') active @endif" id="pills-summary-tab" data-bs-toggle="pill" data-bs-target="#pills-summary" type="button" role="tab" aria-controls="pills-summary" aria-selected="true">
            <i class="fi-rr-blog-text"></i>
            <span>{{ get_phrase('Summary') }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link @if ($tab == 'ai-tutor') active @endif" id="pills-ai-tutor-tab" data-bs-toggle="pill" data-bs-target="#pills-ai-tutor" type="button" role="tab" aria-controls="pills-ai-tutor" aria-selected="false">
            <i class="fi-rr-magic-wand"></i>
            <span>{{ get_phrase('AI Tutor') }} <span class="badge bg-primary text-white ms-1 rounded-pill" style="font-size: 9px; padding: 2px 6px;">Smart</span></span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link @if ($tab == 'live-class') active @endif" id="pills-live-class-tab" data-bs-toggle="pill" data-bs-target="#pills-live-class" type="button" role="tab" aria-controls="pills-live-class" aria-selected="true">
            <i class="fi-rr-video-camera-alt"></i>
            <span>{{ get_phrase('Live class') }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link @if ($tab == 'certificate') active @endif" id="pills-certificate-tab" data-bs-toggle="pill" data-bs-target="#pills-certificate" type="button" role="tab" aria-controls="pills-certificate" aria-selected="true">
            <i class="fi-rr-graduation-cap"></i>
            <span>{{ get_phrase('Certificate') }}</span>
        </button>
    </li>

    {{-- <li class="nav-item" role="presentation">
        <button class="nav-link @if ($tab == 'forum') active @endif" id="pills-forum-tab" data-bs-toggle="pill" data-bs-target="#pills-forum" type="button" role="tab" aria-controls="pills-forum" aria-selected="true">
            <i class="fi fi-rr-users-alt"></i>
            <span>{{ get_phrase('Forum') }}</span>
        </button>
    </li> --}}
</ul>

<div class="tab-content" id="pills-tabContent">
    @include('course_player.summary.index')
    @include('course_player.ai_tutor.index')
    @include('course_player.live_class.index')
    @include('course_player.certificate.index')
    {{-- @include('course_player.forum.index') --}}
</div>

@push('js')
    <script>
        "use strict";
        $(document).ready(function() {
            $('button.nav-link').on('click', function(e) {
                e.preventDefault();
                let tab = $(this).data('bs-target');
                $.ajax({
                    type: "get",
                    url: "{{ route('forum.tab.active') }}",
                    data: {
                        tab: tab
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>
@endpush
