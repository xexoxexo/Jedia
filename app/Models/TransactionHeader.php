<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHeader extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'location_id',
        'date',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }

    public function electrics()
    {
        return $this->hasMany(ElectricTransactionDetail::class, 'transaction_id', 'id');
    }

    public function electric()
    {
        return $this->electrics->where('created_at', $this->created_at)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'transaction_id', 'id');
    }

    public function review($product_id, $variant_id)
    {
        return $this->reviews->where('product_id', $product_id)->where('variant_bought', $variant_id)->first();
    }
}
