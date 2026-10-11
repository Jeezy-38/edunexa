@php
    $amount = $payment_details['payable_amount'];
    $control_number = \App\Models\OfflinePayment::controlNumberFor($payment_details);
    $model = $payment_details['success_method']['model_name'];
    $bank_information = '';

    if ($model == 'InstructorPayment') {
        $settings = DB::table('users')
            ->where('id', $payment_details['items'][0]['id'])
            ->value('paymentkeys');

        $keys = isset($settings) ? json_decode($settings) : null;
        if ($keys && isset($keys->offline->bank_information)) {
            $bank_information = $keys->offline->bank_information;
        }
    } else {
        $payment_keys = json_decode($payment_gateway->keys, true);
        if ($payment_keys && $payment_gateway->status == 1) {
            $bank_information = $payment_keys['bank_information'] ?? '';
        }
    }
@endphp

<!-- Control Number & Amount Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4 text-center overflow-hidden" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: #ffffff;">
    <div class="card-body p-4">
        <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill mb-2" style="font-size: 11px; letter-spacing: 1px;">
            {{ get_phrase('KUMBUKUMBU YA MALIPO / REFERENCE') }}
        </span>
        <div class="text-white-50 small mb-1">{{ get_phrase('Namba ya Malipo (Control Number):') }}</div>
        
        <div class="d-flex align-items-center justify-content-center gap-2 my-2 flex-wrap">
            <span id="edunexaControlNumber" class="text-warning fw-bold fs-2" style="letter-spacing: 3px; font-family: monospace;">
                {{ $control_number }}
            </span>
            <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 d-flex align-items-center gap-1" onclick="copyControlNumber()" id="copyControlBtn" title="{{ get_phrase('Nakili Namba') }}">
                <i class="fa-regular fa-copy"></i>
                <span id="copyBtnText">{{ get_phrase('Nakili') }}</span>
            </button>
        </div>

        <div class="mt-2 text-white" style="font-size: 15px;">
            {{ get_phrase('Kiasi Kinachotakiwa Kulipwa:') }} <strong class="text-success fs-5">{{ currency($amount) }}</strong>
        </div>
        <p class="text-white-50 small mb-0 mt-1">
            {{ get_phrase('Tumia namba hii ya kumbukumbu (Control Number) unapolipa kwa M-Pesa, Tigo Pesa, Airtel Money, au Benki.') }}
        </p>
    </div>
</div>

<!-- USSD Mobile Money Step-by-Step Instructions -->
<div class="card border rounded-3 mb-4 shadow-2xs">
    <div class="card-header bg-light py-2 px-3">
        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 14px;">
            <i class="fa-solid fa-mobile-screen-button text-primary"></i>
            {{ get_phrase('Jinsi ya Kulipa kwa Simu (Mobile Money USSD):') }}
        </h6>
    </div>
    <div class="card-body p-3">
        <ul class="nav nav-pills mb-3 gap-2" id="paymentUssdTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active btn-sm rounded-pill py-1 px-3" id="mpesa-tab" data-bs-toggle="pill" data-bs-target="#tab-mpesa" type="button" role="tab" style="font-size: 12px;">
                    🔴 M-Pesa
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link btn-sm rounded-pill py-1 px-3" id="tigopesa-tab" data-bs-toggle="pill" data-bs-target="#tab-tigopesa" type="button" role="tab" style="font-size: 12px;">
                    🟡 Tigo Pesa
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link btn-sm rounded-pill py-1 px-3" id="airtel-tab" data-bs-toggle="pill" data-bs-target="#tab-airtel" type="button" role="tab" style="font-size: 12px;">
                    🔴 Airtel Money
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link btn-sm rounded-pill py-1 px-3" id="bank-tab" data-bs-toggle="pill" data-bs-target="#tab-bank" type="button" role="tab" style="font-size: 12px;">
                    🏦 Benki / CRDB / NMB
                </button>
            </li>
        </ul>

        <div class="tab-content" id="paymentUssdContent" style="font-size: 13px; line-height: 1.6;">
            <!-- M-Pesa Tab -->
            <div class="tab-pane fade show active" id="tab-mpesa" role="tabpanel">
                <ol class="ps-3 mb-0 text-muted">
                    <li>Piga <strong>*150*00#</strong> kwenye laini yako ya Vodacom.</li>
                    <li>Chagua <strong>4 (Lipa kwa M-Pesa)</strong>.</li>
                    <li>Chagua <strong>4 (Weka namba ya kampuni / Paybill)</strong> au namba iliyoagizwa na chuo.</li>
                    <li>Weka Namba ya Kumbukumbu: <strong class="text-dark">{{ $control_number }}</strong>.</li>
                    <li>Weka Kiasi: <strong class="text-dark">{{ currency($amount) }}</strong>.</li>
                    <li>Weka namba yako ya siri kuthibitisha muamala. Utapokea SMS ya uthibitisho.</li>
                </ol>
            </div>

            <!-- Tigo Pesa Tab -->
            <div class="tab-pane fade" id="tab-tigopesa" role="tabpanel">
                <ol class="ps-3 mb-0 text-muted">
                    <li>Piga <strong>*150*01#</strong> kwenye laini yako ya Tigo.</li>
                    <li>Chagua <strong>4 (Lipa Bili / Lipa kwa Simu)</strong>.</li>
                    <li>Weka Namba ya Kumbukumbu ya Malipo: <strong class="text-dark">{{ $control_number }}</strong>.</li>
                    <li>Weka Kiasi: <strong class="text-dark">{{ currency($amount) }}</strong>.</li>
                    <li>Weka namba yako ya siri kuthibitisha.</li>
                </ol>
            </div>

            <!-- Airtel Money Tab -->
            <div class="tab-pane fade" id="tab-airtel" role="tabpanel">
                <ol class="ps-3 mb-0 text-muted">
                    <li>Piga <strong>*150*60#</strong> kwenye simu yako ya Airtel.</li>
                    <li>Chagua <strong>5 (Lipa Bili)</strong>.</li>
                    <li>Weka Namba ya Kumbukumbu: <strong class="text-dark">{{ $control_number }}</strong>.</li>
                    <li>Weka Kiasi cha <strong>{{ currency($amount) }}</strong> na namba yako ya siri.</li>
                </ol>
            </div>

            <!-- Bank Tab -->
            <div class="tab-pane fade" id="tab-bank" role="tabpanel">
                <p class="text-muted mb-1">
                    Unaweza kulipa kupitia <strong>SimBanking (CRDB)</strong>, <strong>NMB Mkononi</strong>, au kwa wakala/tawi lolote kwa kuonyesha Namba ya Kumbukumbu: <strong class="text-dark">{{ $control_number }}</strong>.
                </p>
            </div>
        </div>

        @if (!empty($bank_information))
            <div class="alert alert-light border mt-3 mb-0 p-3 rounded-2" style="font-size: 13px;">
                <div class="fw-bold mb-1 text-primary"><i class="fa-solid fa-circle-info me-1"></i> {{ get_phrase('Maelezo ya Ziada ya Akaunti:') }}</div>
                {!! removeScripts($bank_information) !!}
            </div>
        @endif
    </div>
</div>

<!-- Proof of Payment Submission Form -->
<form action="{{ route('payment.offline.store') }}" method="post" enctype="multipart/form-data" class="border rounded-3 p-3 p-md-4 bg-white shadow-2xs">
    @csrf
    <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
        <i class="fa-solid fa-receipt text-success"></i>
        {{ get_phrase('Thibitisha Malipo Yako:') }}
    </h6>

    <input type="hidden" name="item_type" value="{{ $payment_details['custom_field']['item_type'] ?? 'course' }}" required>

    <div class="row g-3">
        <!-- Phone Number Used to Pay -->
        <div class="col-md-6">
            <label class="form-label fw-semibold text-muted" style="font-size: 13px;">
                {{ get_phrase('Namba ya Simu Uliyolipia (Sender Phone)') }} <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-phone"></i></span>
                <input type="text" 
                       name="phone_on" 
                       class="form-control" 
                       placeholder="k.m. 0712 345 678" 
                       value="{{ auth()->user()->phone ?? '' }}" 
                       required>
            </div>
            <small class="text-muted" style="font-size: 11px;">Namba ya simu iliyotoa pesa.</small>
        </div>

        <!-- Transaction ID / SMS Code -->
        <div class="col-md-6">
            <label class="form-label fw-semibold text-muted" style="font-size: 13px;">
                {{ get_phrase('Kodi ya Muamala / M-Pesa SMS Code') }} <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-hashtag"></i></span>
                <input type="text" 
                       name="bank_no" 
                       class="form-control" 
                       placeholder="k.m. 9BK893XYZ..." 
                       required>
            </div>
            <small class="text-muted" style="font-size: 11px;">Kodi uliyotumiwa kwenye SMS ya muamala.</small>
        </div>

        <!-- Optional Screenshot / Receipt -->
        <div class="col-12">
            <label class="form-label d-flex justify-content-between text-muted fw-semibold" style="font-size: 13px;">
                <span>{{ get_phrase('Picha ya Risiti au Screenshot ya SMS') }} <span class="text-muted fw-normal">({{ get_phrase('hiari') }})</span></span>
                <span class="text-muted fw-normal" style="font-size: 11px;">(jpg, png, pdf)</span>
            </label>
            <input type="file" name="doc" class="form-control" accept="image/*,.pdf">
        </div>
    </div>

    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
            <i class="fa-solid fa-check"></i>
            <span>{{ get_phrase('Kamilisha na Thibitisha Malipo') }}</span>
        </button>
    </div>
</form>

<script>
    function copyControlNumber() {
        const text = document.getElementById('edunexaControlNumber').innerText.trim();
        navigator.clipboard.writeText(text).then(function() {
            const btnText = document.getElementById('copyBtnText');
            const originalText = btnText.innerText;
            btnText.innerText = "{{ get_phrase('Imenakiliwa!') }}";
            setTimeout(function() {
                btnText.innerText = originalText;
            }, 2500);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
        });
    }
</script>
