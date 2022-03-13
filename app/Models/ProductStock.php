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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
