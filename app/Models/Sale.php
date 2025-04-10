<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_line_id',
        'product_id',
        'user_id',
        'quantity',
        'unit_price',
        'total_price',
        'sale_date'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function orderLine()
    {
        return $this->belongsTo(OrderLine::class, 'order_line_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes for filtering
    public function scopeFilterByMonth($query, $month)
    {
        if ($month > 0) {
            return $query->whereMonth('sale_date', $month);
        }
        return $query;
    }

    public function scopeFilterByYear($query, $year)
    {
        if ($year) {
            return $query->whereYear('sale_date', $year);
        }
        return $query;
    }

    // Helper method to create sales records
    public static function createFromOrder(Order $order)
    {
        foreach ($order->orderLines as $line) {
            self::create([
                'order_id' => $order->id,
                'order_line_id' => $line->id,
                'product_id' => $line->product_id,
                'user_id' => $order->user_id,
                'quantity' => $line->quantity,
                'unit_price' => $line->sell_price,
                'total_price' => $line->quantity * $line->sell_price,
                'sale_date' => $order->created_at->toDateString(),
            ]);
        }
    }
}