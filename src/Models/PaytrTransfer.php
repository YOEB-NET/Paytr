<?php

namespace Yoeb\Paytr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaytrTransfer extends Model
{
    use HasFactory;

    protected $table = "paytr_transfers";

    protected $fillable = [
        'merchant_id',
        'user_ip',
        'merchant_oid',
        'email',
        'payment_amount',
        'paytr_token',
        'user_name',
        'user_phone',
        'tc_no_last5',
        'bank',
        'test_mode',
        'debug_on',
        'timeout_limit',
        'status',
        'error_code',
        'error_message',
    ];
}
