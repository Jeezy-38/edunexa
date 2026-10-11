@php
    $rawPhone = get_settings('whatsapp_number') ?: (get_settings('phone') ?: '255712345678');
    // Sanitize to digits only
    $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
    // If starts with 0 and 10 digits, convert to TZ code 255
    if (str_starts_with($cleanPhone, '0') && strlen($cleanPhone) === 10) {
        $cleanPhone = '255' . substr($cleanPhone, 1);
    }
    $siteName = get_settings('system_title') ?: 'Edunexa';
    $waText = urlencode("Habari {$siteName}! Nahitaji maelezo zaidi kuhusu masomo na huduma zenu.");
@endphp

<!-- WhatsApp Floating Button -->
<div class="edunexa-whatsapp-widget" id="edunexaWaWidget">
    <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waText }}" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="edunexa-wa-btn" 
       title="{{ get_phrase('Wasiliana nasi WhatsApp') }}"
       aria-label="WhatsApp Chat">
        <span class="edunexa-wa-tooltip">{{ get_phrase('Tupo hewani! Tuandikie WhatsApp') }}</span>
        <div class="edunexa-wa-icon-wrap">
            <i class="fa-brands fa-whatsapp"></i>
            <span class="edunexa-wa-pulse"></span>
        </div>
    </a>
</div>

<style>
.edunexa-whatsapp-widget {
    position: fixed;
    bottom: 24px;
    left: 24px;
    z-index: 1050;
    display: flex;
    align-items: center;
    font-family: inherit;
}
.edunexa-wa-btn {
    display: flex;
    align-items: center;
    text-decoration: none;
    position: relative;
    group: hover;
}
.edunexa-wa-icon-wrap {
    width: 54px;
    height: 54px;
    background: linear-gradient(135deg, #25D366, #128C7E);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
    color: #ffffff;
    font-size: 30px;
    position: relative;
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
}
.edunexa-wa-btn:hover .edunexa-wa-icon-wrap {
    transform: scale(1.08) rotate(4deg);
    box-shadow: 0 12px 28px rgba(37, 211, 102, 0.55);
}
.edunexa-wa-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(37, 211, 102, 0.4);
    animation: edunexaWaPulse 2s infinite ease-out;
    z-index: -1;
}
.edunexa-wa-tooltip {
    background: #111827;
    color: #ffffff;
    font-size: 13px;
    font-weight: 500;
    padding: 7px 14px;
    border-radius: 20px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.18);
    margin-left: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transform: translateX(-8px);
    transition: all 0.25s ease;
    border: 1px solid rgba(255,255,255,0.08);
}
.edunexa-whatsapp-widget:hover .edunexa-wa-tooltip {
    opacity: 1;
    transform: translateX(0);
}
@keyframes edunexaWaPulse {
    0% { transform: scale(1); opacity: 0.8; }
    70% { transform: scale(1.45); opacity: 0; }
    100% { transform: scale(1.45); opacity: 0; }
}
@media (max-width: 575.98px) {
    .edunexa-whatsapp-widget {
        bottom: 18px;
        left: 16px;
    }
    .edunexa-wa-icon-wrap {
        width: 48px;
        height: 48px;
        font-size: 26px;
    }
    .edunexa-wa-tooltip {
        display: none;
    }
}
</style>
