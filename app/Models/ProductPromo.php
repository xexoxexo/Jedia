<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPromo extends Model
{
    use HasFactory, HasUuids;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }
}
