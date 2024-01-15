<?php

namespace Yoeb\Paytr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaytrPlatformTransfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'paytr_token',
        'user_ip',
        'merchant_oid',
        'trans_id',
        'submerchant_amount',
        'total_amount',
        'transfer_name',
        'transfer_iban',
        'status',
        'error_code',
        'error_message',
    ];
}
