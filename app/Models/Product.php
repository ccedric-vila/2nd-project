<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'product_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'product_name',
        'size',
        'category',
        'types',
        'description',
        'cost_price',
        'sell_price',
        'stock',
        'supplier_id',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Relationship with Supplier
     */
   
     public function supplier(): BelongsTo
     {
         return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
     }
 
     // Main images relationship (used in controller)
     public function productImages(): HasMany
     {
         return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
     }
 
     // Alias if you want to keep both
     public function images(): HasMany
     {
         return $this->productImages();
     }

    /**
     * Relationship with Primary Image
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'product_id')
                    ->where('is_primary', true);
    }

    /**
     * Relationship with Reviews
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    /**
     * Get a user's specific review
     */
    public function userReview(int $userId): ?Review
    {
        return $this->reviews()->where('user_id', $userId)->first();
    }

    /**
     * Calculate average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return (float) $this->reviews()->avg('rating') ?? 0.0;
    }

    /**
     * Count total reviews
     */
    public function getRatingCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Generate star rating display
     */
    public function getStarRatingAttribute(): string
    {
        $avgRating = $this->average_rating;
        $fullStars = floor($avgRating);
        $hasHalfStar = ($avgRating - $fullStars) >= 0.5;

        $stars = str_repeat('★', $fullStars);
        $stars .= $hasHalfStar ? '½' : '';
        $stars .= str_repeat('☆', 5 - ceil($avgRating));

        return $stars;
    }
}