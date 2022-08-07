<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'created_at'    => 'date:Y-m-d',
        'updated_at'    => 'date:Y-m-d',
        'payload'       => AsCollection::class
    ];

//    protected $appends = ['payloads'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function price()
    {
        return $this->belongsTo(ProductPrice::class, 'product_price_id', 'id');
    }

//    public function getPayloadsAttribute()
//    {
//        return collect($this->payload);
//    }

    public function sell()
    {
        return $this->belongsTo(Sell::class);
    }

    public function payloads()
    {
        return $this->hasMany(PricePayload::class);
    }
}
