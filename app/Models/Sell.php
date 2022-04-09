<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'created_at'    => 'date:Y-m-d',
        'updated_at'    => 'date:Y-m-d',
        'invoice_date'  => 'date:Y-m-d H:i:s'
    ];

    public function details()
    {
        return $this->hasMany(SellDetail::class);
    }

    public function histories()
    {
        return $this->hasMany(SellHistory::class);
    }

    public function returns()
    {
        return $this->hasMany(SellReturn::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
