<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'product_id',
        'variant_bought',
        'review',
        'message',
    ];

    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }

    public function videos()
    {
        return $this->hasMany(ReviewVideo::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_bought', 'id')->withTrashed();
    }

    public function header()
    {
        return $this->belongsTo(TransactionHeader::class, 'transaction_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
