<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at'    => 'date:Y-m-d',
        'updated_at'    => 'date:Y-m-d'
    ];
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
