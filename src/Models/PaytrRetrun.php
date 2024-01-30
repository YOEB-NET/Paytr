<?php

namespace Yoeb\Paytr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaytrReturn extends Model
{
    use HasFactory;

    protected $table = "paytr_return";

    protected $fillable = [
        'user_ip',
        'merchant_oid',
        'return_amount',
        'status',
        'error_code',
        'error_message',
    ];

}
