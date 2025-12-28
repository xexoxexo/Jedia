<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roomable extends Model
{
    use HasFactory;

    protected $fillable = [
        'roomable_id',
        'roomable_type',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'roomable_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'roomable_id', 'id');
    }
}
