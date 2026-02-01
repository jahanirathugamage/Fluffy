<?php
// app\Models\Order.php
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
        'payment_status',
        'delivery_status',
        'delivery_expected_at',
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
        'delivery_expected_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    protected $appends = [
        'customer_email',
    ];

    public function getCustomerEmailAttribute()
    {
        return $this->user->email ?? 'N/A';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
