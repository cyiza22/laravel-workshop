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

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    

    public function getItemsCountAttribute()
    {
        return $this->items->count();
    }

    
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });
    }
}
