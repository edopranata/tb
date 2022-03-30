<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSell extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at'    => 'date:Y-m-d',
        'updated_at'    => 'date:Y-m-d',
        'invoice_date'  => 'date:Y-m-d'
    ];

    public function details()
    {
        return $this->hasMany(TempSellDetail::class);
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
