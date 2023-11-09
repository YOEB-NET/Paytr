<?php

namespace Yoeb\Paytr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaytrLink extends Model
{
    use HasFactory;

    protected $table = 'paytr_orders';

    protected $fillable = [
        'merchant_id',
        'name',
        'price',
        'currency',
        'max_installment',
        'lang',
        'get_qr',
        'link_type',
        'min_count',
        'max_count',
        'email',
        'pft',
        'expiry_date',
        'callback_link',
        'callback_id',
        'debug_on',
        'paytr_token',
        'status',
        'error_code',
        'error_message',
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
    ];
}
