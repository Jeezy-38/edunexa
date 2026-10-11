<div class="tab-pane fade @if ($tab == 'summary') show active @endif" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab" tabindex="0">
    <div class="summery-tab-content p-3 p-md-4 bg-white rounded-3 border shadow-2xs">
        <!-- Study Action Bar -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 mb-3 border-bottom">
            <div>
                <h5 class="fw-bold mb-1 text-dark">{{ $lesson_details->title ?? get_phrase('Muhtasari wa Somo') }}</h5>
                <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 13px;">
                    @if(isset($lesson_details->duration) && !empty($lesson_details->duration))
                        <span><i class="fa-regular fa-clock me-1 text-primary"></i> {{ $lesson_details->duration }}</span>
                        <span>&bull;</span>
                    @endif
                    <span><i class="fa-solid fa-book-open me-1 text-primary"></i> {{ $course_details->title ?? '' }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold d-flex align-items-center gap-2" onclick="printLessonNotes()" title="{{ get_phrase('Pakua au chapisha kama PDF/karatasi ili usome bila bando') }}">
                    <i class="fa-solid fa-print text-primary"></i>
                    <span>{{ get_phrase('Pakua / Chapisha Offline') }}</span>
                </button>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-semibold d-flex align-items-center gap-2" onclick="openAiTutorSummary()" title="{{ get_phrase('Tumia AI kufafanua somo hili') }}">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>{{ get_phrase('Muhtasari wa AI') }}</span>
                </button>
            </div>
        </div>

        <!-- Printable Lesson Notes Content -->
        <div id="lesson-printable-notes" class="lesson-notes-body" style="font-size: 15px; line-height: 1.8; color: #334155;">
            @php
                $lessonSummaryText = trim($lesson_details->summary ?? '');
                $lessonDescText = trim($lesson_details->description ?? '');
            @endphp

            @if (!empty($lessonSummaryText))
                <div class="info">
                    {!! removeScripts($lesson_details->summary) !!}
                </div>
            @elseif (!empty($lessonDescText))
                <div class="info">
                    {!! removeScripts($lesson_details->description) !!}
                </div>
            @else
                <div class="alert alert-light border rounded-3 p-4 text-center my-3">
                    <div class="mb-3 text-primary" style="font-size: 32px;">
                        <i class="fa-solid fa-note-sticky"></i>
                    </div>
                    <h6 class="fw-bold mb-2">{{ get_phrase('Hakuna muhtasari wa maandishi uliowekwa kwa somo hili bado') }}</h6>
                    <p class="text-muted mb-3" style="font-size: 14px; max-width: 500px; margin: 0 auto;">
                        {{ get_phrase('Unaweza kutumia Edunexa AI Tutor kupata muhtasari wa haraka au kuuliza maswali kuhusu somo hili wakati wowote.') }}
                    </p>
                    <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" onclick="openAiTutorSummary()">
                        <i class="fa-solid fa-wand-magic-sparkles me-2"></i> {{ get_phrase('Tengeneza Muhtasari na AI Sasa') }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function printLessonNotes() {
        const notesElem = document.getElementById('lesson-printable-notes');
        const content = notesElem ? notesElem.innerHTML : '';
        const lessonTitle = @json($lesson_details->title ?? 'Lesson Notes');
        const courseTitle = @json($course_details->title ?? 'Course');
        const printWindow = window.open('', '_blank', 'height=750,width=850');

        if (!printWindow) {
            alert('Tafadhali ruhusu pop-ups kwenye kivinjari chako ili kuchapisha madokezo.');
            return;
        }

        printWindow.document.write(`
            <!DOCTYPE html>
            <html lang="sw">
            <head>
                <meta charset="utf-8">
                <title>\${lessonTitle} - Edunexa Study Notes</title>
                <style>
                    body {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
                        padding: 36px;
                        color: #1e293b;
                        line-height: 1.7;
                        max-width: 800px;
                        margin: 0 auto;
                    }
                    .header-box {
                        border-bottom: 2px solid #2563eb;
                        padding-bottom: 14px;
                        margin-bottom: 24px;
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-end;
                    }
                    .brand {
                        font-size: 24px;
                        font-weight: 800;
                        color: #2563eb;
                        letter-spacing: -0.5px;
                    }
                    .brand span {
                        color: #f59e0b;
                    }
                    .meta {
                        font-size: 13px;
                        color: #64748b;
                        text-align: right;
                    }
                    .lesson-title {
                        font-size: 22px;
                        font-weight: 700;
                        color: #0f172a;
                        margin-bottom: 16px;
                    }
                    .notes-content {
                        font-size: 15px;
                        color: #334155;
                    }
                    .notes-content p {
                        margin-bottom: 12px;
                    }
                    .footer-box {
                        margin-top: 48px;
                        padding-top: 14px;
                        border-top: 1px dashed #cbd5e1;
                        font-size: 12px;
                        color: #94a3b8;
                        text-align: center;
                    }
                    @media print {
                        body { padding: 20px; }
                    }
                </style>
            </head>
            <body>
                <div class="header-box">
                    <div>
                        <div class="brand">Edunexa<span>.</span></div>
                        <div style="font-size: 13px; color: #64748b; margin-top: 2px;">Kozi: \${courseTitle}</div>
                    </div>
                    <div class="meta">
                        <div>Madokezo ya Somo</div>
                        <div>Tarehe: \${new Date().toLocaleDateString()}</div>
                    </div>
                </div>
                <div class="lesson-title">\${lessonTitle}</div>
                <div class="notes-content">\${content}</div>
                <div class="footer-box">
                    Imechapishwa kutoka Edunexa Learning Platform &bull; Hifadhi kama PDF au karatasi ili usome bila bando
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(function() {
            printWindow.print();
        }, 350);
    }

    function openAiTutorSummary() {
        const aiTabBtn = document.getElementById('pills-ai-tutor-tab');
        if (aiTabBtn) {
            aiTabBtn.click();
            // Trigger summary prompt chip automatically after short delay
            setTimeout(function() {
                const summaryChip = document.querySelector('.edunexa-ai-chip[data-action="summary"]');
                if (summaryChip) {
                    summaryChip.click();
                }
            }, 300);
        }
    }
</script>
