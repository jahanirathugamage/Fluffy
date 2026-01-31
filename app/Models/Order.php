<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stripe_payment_intent_id',
        'amount',
        'status',
        'email',
        'fname',
        'lname',
        'address',
        'apartment',
        'city',
        'country',
        'phone',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the formatted total amount in LKR.
     */
    public function getFormattedTotalAttribute()
    {
        return number_format($this->amount / 100, 2);
    }
}
