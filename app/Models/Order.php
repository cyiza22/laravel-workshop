<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
        'status'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Calculate total price of the order
     */

    public function getTotalPriceAttribute()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });
    }
    /**
     * Calculate total items in the order
     */

    public function getTotalItemsAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    
}
