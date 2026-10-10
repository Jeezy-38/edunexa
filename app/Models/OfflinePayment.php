<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflinePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_type',
        'items',
        'tax',
        'total_amount',
        'coupon',
        'phone_no',
        'bank_no',
        'doc',
        'control_number',
        'status',
    ];

    /**
     * Control number shown to the student at checkout (same one is saved with the payment).
     * 12 digits: "8" + 11 random digits, unique.
     */
    public static function controlNumberFor(array $payment_details): string
    {
        $sig = md5(json_encode([auth()->id(), $payment_details['payable_amount'] ?? 0, array_column($payment_details['items'] ?? [], 'id')]));
        $saved = session('control_number');
        if (is_array($saved) && ($saved['sig'] ?? null) === $sig) {
            return $saved['number'];
        }
        do {
            $number = '8' . str_pad((string) random_int(0, 99999999999), 11, '0', STR_PAD_LEFT);
        } while (static::where('control_number', $number)->exists());

        session(['control_number' => ['sig' => $sig, 'number' => $number]]);
        return $number;
    }
}
