<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricTransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'electric_token',
        'subscription_number',
        'nominal',
    ];
}
