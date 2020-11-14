<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    //
    protected $fillable = [
        'user_id', 'payment_gateway', 'amount', 'status', 'payment_datetime', 'payment_log'
    ];
}
