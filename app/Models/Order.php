<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'status',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Add accessor for total items
    public function getTotalItemsAttribute()
    {
        return $this->orderItems->count('quantity');
    }

    // Add accessor for total price
    public function getTotalPriceAttribute()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });
    }

    // Add method to check if status can be updated
    public function canUpdateStatus()
    {
        return $this->status !== 'delivered';
    }
}