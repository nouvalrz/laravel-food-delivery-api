<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// import User
use App\Models\User;

class Buyer extends Model
{
    use HasFactory;

    // fillable
    protected $fillable = [
        'phone_number',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
