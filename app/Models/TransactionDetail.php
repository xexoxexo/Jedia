<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
        'shipment_id',
        'status',
        'promo_name',
        'discount',
        'total_paid',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id')->withTrashed();
    }

    public function header()
    {
        return $this->belongsTo(TransactionHeader::class, 'transaction_id', 'id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
