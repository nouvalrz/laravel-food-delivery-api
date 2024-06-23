<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'shipping_cost',
        'total_price',
        'total_bill',
        'shipping_latitude',
        'shipping_longitude',
        'shipping_address_detail',
        'status',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_PROCESSING = 'processing';
    const STATUS_READY = 'ready';
    const STATUS_PICKUP = 'pickup';
    const STATUS_DELIVERING = 'delivering';
    const STATUS_RECEIVED = 'received';
    const STATUS_CANCELLED_BY_BUYER = 'cancelled_by_buyer';
    const STATUS_CANCELLED_BY_SYSTEM = 'cancelled_by_system';

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
