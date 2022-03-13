<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPurchase extends Model
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
        return $this->hasMany(TempPurchaseDetail::class);
    }
}
