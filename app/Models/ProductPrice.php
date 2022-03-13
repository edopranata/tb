<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $guarded = [];
    protected $casts = [
        'created_at'    => 'date:Y-m-d',
        'updated_at'    => 'date:Y-m-d'
    ];
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
