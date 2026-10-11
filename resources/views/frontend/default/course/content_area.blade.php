<div class="ps-box p-0 shadow-none">
    <h4 class="g-title mb-15">{{ get_phrase('Course curriculum') }}</h4>
    <div class="lesson-play-list p-0">
        @if ($sections->count() > 0)
            <div class="accordion" id="accordionExample">
                @foreach ($sections as $key => $section)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button @if($key > 0) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $section->id }}" aria-expanded="@if($key == 0) true @else false @endif" aria-controls="collapse_{{ $section->id }}">{{ ucfirst($section->title) }}
                            </button>
                        </h2>
                        <div id="collapse_{{ $section->id }}" class="accordion-collapse collapse @if($key == 0) show @endif" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="lesson-list course_list">
                                    @php
                                        $lessons = DB::table('lessons')
                                            ->where('section_id', $section->id)
                                            ->orderBy('sort')
                                            ->get();
                                    @endphp
                                    @foreach ($lessons as $lesson)
                                        <li>
                                            <div class="d-flex justify-content-between align-items-center w-100 py-1">
                                                <a href="{{ $course_details->is_paid ? 'javascript:void(0);' : route('course.player', $course_details->slug) }}" class="d-flex align-items-center flex-grow-1 text-decoration-none">
                                                    <p class="d-flex align-items-center mb-0">
                                                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M8.87508 13.1253L12.9376 10.5212C13.132 10.3962 13.2292 10.2225 13.2292 10.0003C13.2292 9.7781 13.132 9.60449 12.9376 9.47949L8.87508 6.87533C8.66675 6.73644 8.45494 6.72602 8.23966 6.84408C8.02439 6.96213 7.91675 7.14616 7.91675 7.39616V12.6045C7.91675 12.8545 8.02439 13.0385 8.23966 13.1566C8.45494 13.2746 8.66675 13.2642 8.87508 13.1253ZM10.0001 18.3337C8.8473 18.3337 7.76397 18.1149 6.75008 17.6774C5.73619 17.2399 4.85425 16.6462 4.10425 15.8962C3.35425 15.1462 2.7605 14.2642 2.323 13.2503C1.8855 12.2364 1.66675 11.1531 1.66675 10.0003C1.66675 8.84755 1.8855 7.76421 2.323 6.75033C2.7605 5.73644 3.35425 4.85449 4.10425 4.10449C4.85425 3.35449 5.73619 2.76074 6.75008 2.32324C7.76397 1.88574 8.8473 1.66699 10.0001 1.66699C11.1529 1.66699 12.2362 1.88574 13.2501 2.32324C14.264 2.76074 15.1459 3.35449 15.8959 4.10449C16.6459 4.85449 17.2397 5.73644 17.6772 6.75033C18.1147 7.76421 18.3334 8.84755 18.3334 10.0003C18.3334 11.1531 18.1147 12.2364 17.6772 13.2503C17.2397 14.2642 16.6459 15.1462 15.8959 15.8962C15.1459 16.6462 14.264 17.2399 13.2501 17.6774C12.2362 18.1149 11.1529 18.3337 10.0001 18.3337ZM10.0001 16.667C11.8612 16.667 13.4376 16.0212 14.7292 14.7295C16.0209 13.4378 16.6667 11.8614 16.6667 10.0003C16.6667 8.13921 16.0209 6.56283 14.7292 5.27116C13.4376 3.97949 11.8612 3.33366 10.0001 3.33366C8.13897 3.33366 6.56258 3.97949 5.27091 5.27116C3.97925 6.56283 3.33341 8.13921 3.33341 10.0003C3.33341 11.8614 3.97925 13.4378 5.27091 14.7295C6.56258 16.0212 8.13897 16.667 10.0001 16.667Z" fill="currentColor"/>
                                                        </svg>
                                                        <span>{{ ucfirst($lesson->title) }}</span>
                                                    </p>
                                                </a>
                                                
                                                <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-2">
                                                    @if(isset($lesson->is_free) && $lesson->is_free == 1)
                                                        <button type="button" 
                                                                class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2 rounded-pill edunexa-preview-btn text-decoration-none"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#freeLessonPreviewModal"
                                                                data-lesson-title="{{ $lesson->title }}"
                                                                data-lesson-src="{{ $lesson->lesson_src }}"
                                                                data-lesson-type="{{ $lesson->lesson_type }}"
                                                                data-video-type="{{ $lesson->video_type ?? '' }}"
                                                                title="{{ get_phrase('Tazama Bure') }}"
                                                                style="cursor: pointer; font-weight: 600; font-size: 11px;">
                                                            <i class="fa-solid fa-circle-play me-1"></i>{{ get_phrase('Preview') }}
                                                        </button>
                                                    @endif

                                                    @if($lesson->duration != '00:00:00' && $lesson->duration != "")
                                                        <span class="preview-text text-muted" style="font-size: 12px;">{{ $lesson->duration }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center">{{ get_phrase('Course curriculum Empty') }}</p>
        @endif
    </div>
</div>

<!-- Free Lesson Preview Modal -->
<div class="modal fade" id="freeLessonPreviewModal" tabindex="-1" aria-labelledby="freeLessonPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0 overflow-hidden" style="border-radius: 14px;">
            <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success text-white py-1 px-2" style="font-size: 11px;">{{ get_phrase('FREE LESSON') }}</span>
                    <h6 class="modal-title text-white mb-0 text-truncate" id="freeLessonPreviewModalLabel" style="max-width: 500px;">{{ get_phrase('Somo la Bure') }}</h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-black position-relative" style="min-height: 380px;">
                <div id="freeLessonPlayerContainer" class="w-100 h-100 d-flex align-items-center justify-content-center">
                    <!-- Dynamic Video / Content injected here via JS -->
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-2 px-4 d-flex justify-content-between align-items-center">
                <small class="text-muted"><i class="fa fa-info-circle me-1"></i>{{ get_phrase('Hili ni somo la majaribio bila malipo.') }}</small>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ get_phrase('Funga') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const previewModal = document.getElementById('freeLessonPreviewModal');
    if (!previewModal) return;

    const modalTitle = document.getElementById('freeLessonPreviewModalLabel');
    const playerContainer = document.getElementById('freeLessonPlayerContainer');

    previewModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const title = button.getAttribute('data-lesson-title') || 'Free Lesson Preview';
        const src = button.getAttribute('data-lesson-src') || '';
        const lessonType = (button.getAttribute('data-lesson-type') || '').toLowerCase();
        const videoType = (button.getAttribute('data-video-type') || '').toLowerCase();

        modalTitle.textContent = title;
        playerContainer.innerHTML = '';

        if (!src) {
            playerContainer.innerHTML = '<div class="text-white p-5 text-center"><p class="mb-0">Maudhui ya somo hili hayapo kwa sasa.</p></div>';
            return;
        }

        // YouTube check
        let ytId = null;
        if (src.includes('youtube.com/watch?v=')) {
            ytId = new URLSearchParams(new URL(src).search).get('v');
        } else if (src.includes('youtu.be/')) {
            ytId = src.split('youtu.be/')[1]?.split('?')[0];
        } else if (src.includes('youtube.com/embed/')) {
            ytId = src.split('youtube.com/embed/')[1]?.split('?')[0];
        }

        if (ytId) {
            playerContainer.innerHTML = `<iframe width="100%" height="450" src="https://www.youtube.com/embed/${ytId}?autoplay=1&rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="display:block;"></iframe>`;
            return;
        }

        // Vimeo check
        if (src.includes('vimeo.com/')) {
            const vimeoId = src.split('vimeo.com/')[1]?.split('?')[0];
            playerContainer.innerHTML = `<iframe width="100%" height="450" src="https://player.vimeo.com/video/${vimeoId}?autoplay=1" frameborder="0" allow="autoplay; fullscreen" allowfullscreen style="display:block;"></iframe>`;
            return;
        }

        // Direct video (mp4, webm, etc.)
        if (src.endsWith('.mp4') || src.endsWith('.webm') || videoType === 'system-video' || lessonType === 'system-video') {
            const videoUrl = src.startsWith('http') ? src : `/${src.replace(/^\//, '')}`;
            playerContainer.innerHTML = `<video controls autoplay playsinline style="width:100%; max-height:450px;"><source src="${videoUrl}" type="video/mp4">Kivinjari chako hakiauni video hii.</video>`;
            return;
        }

        // Generic iframe fallback
        playerContainer.innerHTML = `<iframe width="100%" height="450" src="${src}" frameborder="0" allowfullscreen style="display:block;"></iframe>`;
    });

    previewModal.addEventListener('hidden.bs.modal', function () {
        playerContainer.innerHTML = ''; // Stops playback instantly
    });
});
</script>
