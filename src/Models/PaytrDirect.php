<?php

namespace Yoeb\Paytr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaytrDirect extends Model
{
    use HasFactory;
    protected $fillable = [
        'merchant_id',
        'paytr_token',
        'user_ip',
        'merchant_oid',
        'email',
        'payment_type',
        'payment_amount',
        'installment_count',
        'card_type',
        'currency',
        'client_lang',
        'test_mode',
        'non_3d',
        'non3d_test_failed',
        'cc_owner',
        'card_number',
        'expiry_month',
        'expiry_year',
        'merchant_ok_url',
        'merchant_fail_url',
        'user_name',
        'user_address',
        'user_phone',
        'user_basket',
        'debug_on',
        'sync_mode',
        'status',
        'error_code',
        'error_message',
    ];

    protected $casts = [
        "user_basket"   => "array"
    ];
}
