<?php

namespace Yoeb\Paytr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaytrFrame extends Model
{
    use HasFactory;

    protected $table = 'paytr_frames';

    protected $fillable = [
        'merchant_id',
        'user_ip',
        'merchant_oid',
        'email',
        'payment_amount',
        'currency',
        'user_basket',
        'no_installment',
        'max_installment',
        'paytr_token',
        'user_name',
        'user_phone',
        'user_address',
        'merchant_ok_url',
        'merchant_fail_url',
        'test_mode',
        'debug_on',
        'timeout_limit',
        'lang',
        'status',
        'error_code',
        'error_message',
    ];

    protected $casts = [
        "user_basket"   => "array"
    ];
}
