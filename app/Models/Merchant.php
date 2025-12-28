<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
    ];

    public function location()
    {
        return $this->hasOne(Location::class, 'locationable_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImage()
    {
        return ($this->image == null) ? 'img/logo/logo.png' : $this->image;
    }

    public function orders()
    {
        return $this->hasManyThrough(TransactionDetail::class, Product::class);
    }

    public function pendings()
    {
        return $this->hasManyThrough(TransactionDetail::class, Product::class)->withTrashedParents()->where('status', 'Pending');
    }

    public function shippings()
    {
        return $this->hasManyThrough(TransactionDetail::class, Product::class)->withTrashedParents()->where('status', 'Shipping');
    }

    public function transactions()
    {
        return $this->hasManyThrough(TransactionDetail::class, Product::class)->withTrashedParents()
            ->whereIn('status', ['Rejected', 'Completed']);
    }

    public function getBannerImage()
    {
        return ($this->banner_image == null) ? 'img/logo/banner-merchant.jpeg' : $this->banner_image;
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'roomables', 'roomable_id', 'room_id')
        ->wherePivot('roomable_type', 'merchant');
    }
}
