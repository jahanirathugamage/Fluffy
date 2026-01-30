<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate the total price of the cart.
     */
    public function totalPrice()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * ($item->specification->price ?? 0);
        });
    }

    /**
     * Calculate total items count.
     */
    public function totalItems()
    {
        return $this->items->sum('quantity');
    }
}
