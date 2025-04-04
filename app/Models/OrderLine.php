<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'order_lines';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'sell_price',
    ];

    protected $casts = [
        'sell_price' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->sell_price;
    }

    public function getFormattedSubtotalAttribute()
    {
        return '$' . number_format($this->subtotal, 2);
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->sell_price, 2);
    }

    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeWithProductDetails($query)
    {
        return $query->with(['product' => function($q) {
            $q->with('images');
        }]);
    }

    public function updateQuantity($newQuantity)
    {
        if ($newQuantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be greater than zero");
        }

        $this->update(['quantity' => $newQuantity]);
        
        // Update the order total
        $this->order->update([
            'total_amount' => $this->order->orderLines->sum('subtotal')
        ]);
    }
}