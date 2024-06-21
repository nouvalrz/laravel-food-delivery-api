<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'image',
        'user_id',
        'license_plate',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
