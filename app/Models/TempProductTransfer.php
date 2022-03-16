<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempProductTransfer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'transfer_date' => 'date',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(TempProductTransferDetails::class);
    }
}
