<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'created_at'    => 'date:Y-m-d',
        'updated_at'    => 'date:Y-m-d'
    ];
    use HasFactory;

    protected $appends = ['total', 'first_total'];

    /*
    Accessor for the total price
     */
    public function getTotalAttribute()
    {
        return $this->available_stock * $this->buying_price;
    }

    public function getFirstTotalAttribute()
    {
        return $this->first_stock * $this->buying_price;
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function payloads()
    {
        return $this->hasMany(PricePayload::class, 'product_stock_id', 'id');
    }
}
