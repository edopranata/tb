<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
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
        return $this->hasMany(PurchaseDetail::class);
    }

    public function histories()
    {
        return $this->hasMany(PurchaseHistory::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
