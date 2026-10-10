<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\FileUploader;
use App\Models\OfflinePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OfflinePaymentController extends Controller
{
    public function store(Request $request)
    {
        // check amount
        $payment_details = Session::get('payment_details');
        $item_id_arr = [];
        foreach($payment_details['items'] as $item){
            $item_id_arr[] = $item['id'];
        }

        $rules = [
            'doc'      => 'nullable|mimes:jpeg,jpg,pdf,txt,png,docx,doc|max:3072',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // proof of payment is optional: the control number identifies the payment
        $path = null;
        if ($request->hasFile('doc')) {
            $file      = $request->doc;
            $file_name = Str::random(20) . '.' . $file->extension();
            $path      = 'uploads/offline_payment/' . slugify(auth()->user()->name) . '/' . $file_name;
            FileUploader::upload($file, $path, null, null, 300);
        }

        $control_number = OfflinePayment::controlNumberFor($payment_details);

        $offline_payment['user_id']      = auth()->user()->id;
        $offline_payment['item_type']    = $request->item_type;
        $offline_payment['items']        = json_encode($item_id_arr);
        $offline_payment['tax']          = $payment_details['tax'];
        $offline_payment['total_amount'] = $payment_details['payable_amount'];
        $offline_payment['doc']          = $path;
        $offline_payment['coupon'] = $payment_details['coupon'] ?? null;
        $offline_payment['control_number'] = $control_number;

        // insert offline payment history
        OfflinePayment::insert($offline_payment);
        Session::forget('control_number');

        // remove from cart
        if ($request->item_type == 'course') {
            $url = 'purchase.history';
            CartItem::whereIn('course_id', $item_id_arr)->where('user_id', auth()->user()->id)->delete();
        } elseif ($request->item_type == 'bootcamp') {
            $url = 'bootcamps';
        } elseif ($request->item_type == 'package') {
            $url = 'team.packages';
        } elseif ($request->item_type == 'tutor_booking') {
            $url = 'tutor_list';
        }

        // return to courses
        Session::flash('success', get_phrase('Your control number is') . ' ' . $control_number . '. ' . get_phrase('The payment will be completed once the admin reviews and approves it.'));
        return redirect()->route($url);
    }
}
