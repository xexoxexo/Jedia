<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'condition',
        'product_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function lowestPriceVariant()
    {
        $variant = $this->variants->where('price', $this->variants->min('price'))->first();
        return $variant;
    }

    public function promos()
    {
        return $this->hasMany(ProductPromo::class);
    }

    public function flash_sale()
    {
        return $this->hasOne(FlashSaleProduct::class);
    }

    public function lowestPromo()
    {
        $promo = $this->promos->where('discount', $this->promos->min('discount'))->first();
        return $promo;
    }

    public function lowestDiscount()
    {
        if ($this->flash_sale != null && (date('H') >= 22 && date('H') <= 23)) {
            return $this->flash_sale->discount;
        } else if ($this->promos->count() > 0) {
            return $this->promos->min('discount');
        }

        return 0;
    }

    public function transaction_details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function soldCount()
    {
        return $this->transaction_details->count();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reviewsAverage()
    {
        if ($this->reviews()->count() == 0) return 0;
        $avg = $this->reviews()->sum('review') / $this->reviews()->count();
        return number_format($avg, 1);
    }
}
