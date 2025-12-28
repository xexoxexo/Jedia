<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory, HasUuids;

    public function products()
    {
        return $this->hasMany(ProductPromo::class);
    }

    public function discount($product_id)
    {
        return $this->products->where('product_id', $product_id)->first()->discount;
    }
}
