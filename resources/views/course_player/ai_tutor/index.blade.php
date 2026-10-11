<div class="tab-pane fade @if ($tab == 'ai-tutor') show active @endif" id="pills-ai-tutor" role="tabpanel" aria-labelledby="pills-ai-tutor-tab" tabindex="0">
    <div class="edunexa-ai-tutor-container card border-0 shadow-sm rounded-4 overflow-hidden my-3">
        <!-- AI Tutor Header -->
        <div class="card-header bg-gradient py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2 text-white" style="background: linear-gradient(135deg, #1e3a8a, #4338ca, #6366f1);">
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-white text-primary shadow" style="width: 44px; height: 44px; font-size: 22px;">
                        🤖
                    </div>
                    <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle p-1" style="width: 12px; height: 12px;" title="Online"></span>
                </div>
                <div>
                    <h6 class="mb-0 text-white fw-bold d-flex align-items-center gap-2">
                        Edunexa AI Tutor
                        <span class="badge bg-warning text-dark px-2 py-0 rounded-pill" style="font-size: 10px;">AI v2.0</span>
                    </h6>
                    <small class="text-white-50" style="font-size: 12px;">{{ get_phrase('Mwalimu msaidizi anayefafanua masomo kwa Kiswahili na Kiingereza') }}</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-light border-0 py-1 px-3 text-dark fw-semibold rounded-pill" id="edunexaAiResetBtn" title="{{ get_phrase('Anzisha upya mazungumzo') }}" style="font-size: 12px;">
                    <i class="fa-solid fa-arrows-rotate me-1"></i> {{ get_phrase('Upya') }}
                </button>
            </div>
        </div>

        <!-- Quick Action Prompt Chips -->
        <div class="p-3 bg-light border-bottom d-flex flex-wrap align-items-center gap-2">
            <span class="text-muted fw-semibold me-1" style="font-size: 12px;"><i class="fa-solid fa-wand-magic-sparkles text-primary me-1"></i>{{ get_phrase('Msaada wa Haraka:') }}</span>
            <button type="button" class="btn btn-sm btn-white border shadow-2xs rounded-pill edunexa-ai-chip py-1 px-3" data-action="explain">
                📘 {{ get_phrase('Fafanua Somo Hili') }}
            </button>
            <button type="button" class="btn btn-sm btn-white border shadow-2xs rounded-pill edunexa-ai-chip py-1 px-3" data-action="summary">
                📝 {{ get_phrase('Muhtasari wa Nukta Kuu') }}
            </button>
            <button type="button" class="btn btn-sm btn-white border shadow-2xs rounded-pill edunexa-ai-chip py-1 px-3" data-action="quiz">
                🎯 {{ get_phrase('Nipime Uelewa Wangu') }}
            </button>
        </div>

        <!-- Chat Conversation Messages -->
        <div class="card-body p-3 p-md-4 edunexa-ai-chat-body" id="edunexaAiChatBody" style="max-height: 480px; min-height: 320px; overflow-y: auto; background-color: #fafbfd;">
            <!-- Welcome Bot Message -->
            <div class="d-flex align-items-start gap-2 mb-3 edunexa-ai-msg bot">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white flex-shrink-0" style="width: 34px; height: 34px; font-size: 16px;">
                    🤖
                </div>
                <div class="p-3 rounded-4 bg-white border shadow-2xs text-dark" style="max-width: 85%; font-size: 14px; line-height: 1.6;">
                    <p class="mb-1 fw-semibold text-primary">Habari {{ auth()->user()->name ?? 'Mwanafunzi' }}! 👋</p>
                    <p class="mb-2">Mimi ni <strong>Edunexa AI Tutor</strong>. Nipo hapa kukusaidia kuelewa somo hili la <em>"{{ $lesson_details->title ?? 'Somo la Leo' }}"</em> kwa undani zaidi.</p>
                    <p class="mb-0 text-muted" style="font-size: 13px;">Unaweza kubonyeza vitufe vya hapo juu (k.m. <strong>Fafanua Somo</strong>) au kuniuliza swali lolote linalokutatiza hapa chini!</p>
                </div>
            </div>
        </div>

        <!-- Typing Indicator (Hidden by default) -->
        <div class="px-4 py-2 d-none" id="edunexaAiTyping">
            <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 13px;">
                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                <span><em>Edunexa AI inachakata somo lako...</em></span>
            </div>
        </div>

        <!-- Chat Input Footer -->
        <div class="card-footer bg-white p-3 border-top">
            <form id="edunexaAiChatForm" class="d-flex align-items-center gap-2">
                @csrf
                <input type="hidden" name="lesson_id" value="{{ $lesson_details->id ?? 0 }}" id="aiLessonId">
                <input type="hidden" name="course_id" value="{{ $course_details->id ?? 0 }}" id="aiCourseId">
                <div class="input-group">
                    <input type="text" 
                           id="edunexaAiInput" 
                           class="form-control rounded-pill-start ps-3 py-2 border-end-0" 
                           placeholder="{{ get_phrase('Andika swali lako kuhusu somo hili hapa...') }}" 
                           autocomplete="off" 
                           required>
                    <button class="btn btn-primary rounded-pill-end px-4 fw-semibold d-flex align-items-center gap-2" type="submit" id="edunexaAiSendBtn">
                        <span>{{ get_phrase('Tuma') }}</span>
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatForm = document.getElementById('edunexaAiChatForm');
    const chatInput = document.getElementById('edunexaAiInput');
    const chatBody = document.getElementById('edunexaAiChatBody');
    const typingIndicator = document.getElementById('edunexaAiTyping');
    const chips = document.querySelectorAll('.edunexa-ai-chip');
    const resetBtn = document.getElementById('edunexaAiResetBtn');
    const lessonId = document.getElementById('aiLessonId')?.value;
    const courseId = document.getElementById('aiCourseId')?.value;
    const askRoute = "{{ route('ai.tutor.ask') }}";
    const csrfToken = "{{ csrf_token() }}";

    function scrollToBottom() {
        if (chatBody) {
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    }

    function appendMessage(sender, htmlContent) {
        const isBot = sender === 'bot';
        const msgWrapper = document.createElement('div');
        msgWrapper.className = `d-flex align-items-start gap-2 mb-3 edunexa-ai-msg ${sender} ${isBot ? '' : 'justify-content-end'}`;

        if (isBot) {
            msgWrapper.innerHTML = `
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white flex-shrink-0" style="width: 34px; height: 34px; font-size: 16px;">
                    🤖
                </div>
                <div class="p-3 rounded-4 bg-white border shadow-2xs text-dark edunexa-ai-bubble" style="max-width: 85%; font-size: 14px; line-height: 1.6;">
                    ${htmlContent}
                </div>
            `;
        } else {
            msgWrapper.innerHTML = `
                <div class="p-3 rounded-4 bg-primary text-white shadow-2xs" style="max-width: 85%; font-size: 14px; line-height: 1.5; border-bottom-right-radius: 4px;">
                    ${htmlContent}
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white flex-shrink-0" style="width: 34px; height: 34px; font-size: 14px;">
                    <i class="fa-solid fa-user"></i>
                </div>
            `;
        }

        chatBody.appendChild(msgWrapper);
        scrollToBottom();
    }

    function formatMarkdown(text) {
        if (!text) return '';
        // Bold: **text**
        let formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Italic: *text*
        formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
        // Headers: ### text
        formatted = formatted.replace(/^### (.*$)/gim, '<h6 class="fw-bold text-primary mt-2 mb-1">$1</h6>');
        formatted = formatted.replace(/^## (.*$)/gim, '<h6 class="fw-bold text-dark mt-2 mb-1">$1</h6>');
        // Bullet points: • or -
        formatted = formatted.replace(/^[•\-] (.*$)/gim, '<li class="ms-3 mb-1">$1</li>');
        // Line breaks
        formatted = formatted.replace(/\n\n/g, '<br><br>');
        formatted = formatted.replace(/\n/g, '<br>');
        return formatted;
    }

    function sendToAi(action, promptText = '') {
        typingIndicator.classList.remove('d-none');
        scrollToBottom();

        fetch(askRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                lesson_id: lessonId,
                course_id: courseId,
                action: action,
                prompt: promptText
            })
        })
        .then(response => response.json())
        .then(data => {
            typingIndicator.classList.add('d-none');
            if (data.status === 'success') {
                appendMessage('bot', formatMarkdown(data.answer));
            } else {
                appendMessage('bot', `<span class="text-danger"><i class="fa fa-circle-exclamation me-1"></i>${data.message || 'Hitilafu imetokea. Tafadhali jaribu tena.'}</span>`);
            }
        })
        .catch(err => {
            typingIndicator.classList.add('d-none');
            appendMessage('bot', '<span class="text-danger"><i class="fa fa-triangle-exclamation me-1"></i>Samahani, mtandao umekatika au hitilafu imetokea. Jaribu tena.</span>');
            console.error(err);
        });
    }

    // Handle form submit
    if (chatForm) {
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const text = chatInput.value.trim();
            if (!text) return;

            appendMessage('user', text);
            chatInput.value = '';
            sendToAi('custom', text);
        });
    }

    // Handle quick prompt chips
    chips.forEach(chip => {
        chip.addEventListener('click', function () {
            const action = this.getAttribute('data-action');
            const label = this.textContent.trim();
            appendMessage('user', label);
            sendToAi(action);
        });
    });

    // Reset button
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            chatBody.innerHTML = `
                <div class="d-flex align-items-start gap-2 mb-3 edunexa-ai-msg bot">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white flex-shrink-0" style="width: 34px; height: 34px; font-size: 16px;">
                        🤖
                    </div>
                    <div class="p-3 rounded-4 bg-white border shadow-2xs text-dark" style="max-width: 85%; font-size: 14px; line-height: 1.6;">
                        <p class="mb-1 fw-semibold text-primary">Mazungumzo mapya yameanza! ✨</p>
                        <p class="mb-0">Ninaweza kukusaidia nini kuhusu somo hili?</p>
                    </div>
                </div>
            `;
        });
    }
});
</script>
