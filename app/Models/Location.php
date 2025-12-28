<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'city',
        'country',
        'address',
        'notes',
        'postal_code',
        'latitude',
        'longitude',
        'locationable_type',
        'locationable_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'locationable_id', 'id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'id', 'locationable_id');
    }

    public function transaction_headers()
    {
        return $this->hasMany(TransactionHeader::class);
    }
}
