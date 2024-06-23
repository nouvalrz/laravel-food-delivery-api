<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'price', 'description', 'image', 'stock', 'is_available', 'is_favorite', 'merchant_id'
    ];

    function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
