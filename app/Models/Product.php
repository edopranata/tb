<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = ['total'];

    /*
    Accessor for the total price
     */
    public function getTotalAttribute()
    {
        return $this->store_stock + $this->warehouse_stock;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function sells()
    {
        return $this->hasMany(SellDetail::class);
    }

    public function scopeFilter($query, $filters)
    {
        $query
            ->when($filters ?? null, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('barcode', 'like', '%'.$search.'%');
            });
    }
}
