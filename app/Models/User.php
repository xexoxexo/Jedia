<?php

namespace App\Models;

use App\Casts\SometimesEncrypted;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'telepon',
        'tanggal_lahir',
        'jenis_kelamin',
        'gambar',
        'ID_Google',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'phone' => SometimesEncrypted::class,
    ];

    public function merchant()
    {
        return $this->hasOne(Merchant::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'locationable_id', 'id');
    }

    public function getImage()
    {
        return ($this->image == null) ? 'img/logo/user.png' : $this->image;
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function transaction_headers()
    {
        return $this->hasMany(TransactionHeader::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'roomables', 'roomable_id', 'room_id')
        ->wherePivot('roomable_type', 'user');
    }

    public function followings()
    {
        return $this->hasMany(Following::class);
    }

    public function following($merchant_id)
    {
        return $this->followings->where('merchant_id', $merchant_id)->first();
    }
}
