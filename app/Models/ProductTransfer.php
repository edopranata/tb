<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTransfer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function details() {
        return $this->hasMany(ProductTransferDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
