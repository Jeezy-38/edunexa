@php
    $amount = $payment_details['payable_amount'];
    $control_number = \App\Models\OfflinePayment::controlNumberFor($payment_details);
@endphp


@php
    $model = $payment_details['success_method']['model_name'];
    if ($model == 'InstructorPayment') {
        $settings = DB::table('users')
            ->where('id', $payment_details['items'][0]['id'])
            ->value('paymentkeys');

        $keys = isset($settings) ? json_decode($settings) : null;

        if ($keys) {
            $bank_information = $keys->offline->bank_information;
        }

        if ($bank_information == '') {
            $msg = "This payment gateway isn't configured.";
        }
    } else {
        $payment_keys = json_decode($payment_gateway->keys, true);
        $bank_information = '';

        if ($payment_keys != '') {
            if ($payment_gateway->status == 1) {
                $bank_information = $payment_keys['bank_information'];
                
                if ($bank_information == '') {
                    $msg = get_phrase("This payment gateway isn't configured.");
                }
            } else {
                $msg = get_phrase('Admin denied transaction through this gateway.');
            }
        } else {
            $msg = get_phrase("This payment gateway isn't configured.");
        }
    }
@endphp

<div class="border rounded p-3 mt-4 text-center">
    <div class="text-muted">{{ get_phrase('Control Number') }}</div>
    <div style="font-size: 28px; font-weight: 700; letter-spacing: 2px;">{{ $control_number }}</div>
    <div class="mt-1">{{ get_phrase('Amount to pay') }}: <strong>{{ currency($amount) }}</strong></div>
    <small class="text-muted">{{ get_phrase('Use this number as the payment reference when you pay by mobile money or bank.') }}</small>
</div>

<div class="row my-5">
    <div class="col-md-12 text-start">
        {!! removeScripts($bank_information) !!}
    </div>
</div>
<form action="{{ route('payment.offline.store') }}" method="post" enctype="multipart/form-data">@csrf
    <div class="mb-3">
        <label for="" class="form-label d-flex justify-content-between">
            <span>{{ get_phrase('Payment Document') }} ({{ get_phrase('optional') }})</span>
            <span>{{ get_phrase('(jpg, pdf, txt, png, docx)') }}</span>
        </label>
        <input type="hidden" name="item_type" value="{{ $payment_details['custom_field']['item_type'] ?? 'course' }}" required>
        <input type="file" name="doc" class="form-control">
    </div>

    <input type="submit" class="btn btn-primary" value="{{ get_phrase('Pay offline') }}">
</form>
