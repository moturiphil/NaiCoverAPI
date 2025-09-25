<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_reference',
        'amount',
        'method',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the order that owns the payment
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the customer through the order
     */
    public function customer()
    {
        return $this->hasOneThrough(Customer::class, Order::class, 'id', 'id', 'order_id', 'customer_id');
    }

    /**
     * Get the user through order->customer relationship
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Order::class,
            'id', // Foreign key on orders table
            'id', // Foreign key on users table
            'order_id', // Local key on payments table
            'customer_id' // Local key on orders table (through customers)
        )->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->where('customers.user_id', '=', 'users.id');
    }
}
