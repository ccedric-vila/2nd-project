<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews for a product
     */
    public function index(Request $request, Product $product): JsonResponse
    {
        try {
            $reviews = Review::with(['user' => function($query) {
                    $query->select('id', 'name');
                }])
                ->where('product_id', $product->product_id)
                ->orderBy('created_at', 'desc')
                ->paginate(5);

            return response()->json([
                'success' => true,
                'reviews' => $reviews->isEmpty() ? [] : $reviews,
                'average_rating' => $product->averageRating() ?? 0,
                'total_reviews' => $product->reviews()->count(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of user's reviews
     */
    public function userReviews(Request $request): JsonResponse
    {
        try {
            $reviews = Review::with(['product' => function($query) {
                    $query->select('product_id', 'product_name');
                }])
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'reviews' => $reviews->isEmpty() ? [] : $reviews,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user reviews'
            ], 500);
        }
    }

    /**
     * Store or update a review
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'order_id' => [
                    'required',
                    Rule::exists('orders', 'id')->where(function ($query) {
                        $query->where('user_id', auth()->id())
                            ->where('status', 'delivered');
                    }),
                ],
                'product_id' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $exists = DB::table('order_lines')
                            ->where('order_id', $request->order_id)
                            ->where('product_id', $value)
                            ->exists();
                        
                        if (!$exists) {
                            $fail('The selected product was not ordered in this order.');
                        }
                    },
                ],
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            $order = Order::findOrFail($validated['order_id']);
            
            // Check if order is too old (30 days)
            if ($order->created_at->diffInDays(now()) > 30) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order is too old to be reviewed.',
                ], 422);
            }

            // Check if product exists in the order
            $productInOrder = DB::table('order_lines')
                ->where('order_id', $validated['order_id'])
                ->where('product_id', $validated['product_id'])
                ->exists();

            if (!$productInOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in this order',
                ], 404);
            }

            $review = Review::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'order_id' => $validated['order_id'],
                    'product_id' => $validated['product_id'],
                ],
                [
                    'rating' => $validated['rating'],
                    'comment' => $validated['comment'] ?? null,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'review' => $review->load([
                    'user' => function($query) {
                        $query->select('id', 'name');
                    },
                    'product' => function($query) {
                        $query->select('product_id', 'product_name');
                    }
                ]),
                'message' => $review->wasRecentlyCreated 
                    ? 'Review submitted successfully!' 
                    : 'Review updated successfully!',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Order or product not found',
            ], 404);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if review exists for given order and product
     */
    public function check(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'order_id' => [
                    'required',
                    Rule::exists('orders', 'id')->where(function ($query) {
                        $query->where('user_id', auth()->id())
                            ->where('status', 'delivered');
                    }),
                ],
                'product_id' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $exists = DB::table('order_lines')
                            ->where('order_id', $request->order_id)
                            ->where('product_id', $value)
                            ->exists();
                        
                        if (!$exists) {
                            $fail('The selected product was not ordered in this order.');
                        }
                    },
                ],
            ]);

            $review = Review::with(['product' => function($query) {
                    $query->select('product_id', 'product_name');
                }])
                ->where([
                    'user_id' => auth()->id(),
                    'order_id' => $validated['order_id'],
                    'product_id' => $validated['product_id'],
                ])
                ->first();

            return response()->json([
                'success' => true,
                'exists' => !is_null($review),
                'review' => $review,
                'product' => optional($review)->product,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check review status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a review (user version)
     */
    public function destroy(Review $review): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            if (auth()->id() !== $review->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }
        
            if ($review->created_at->diffInDays(now()) > 7) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reviews can only be deleted within 7 days of submission.',
                ], 403);
            }
        
            $review->delete();
        
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user can review a product
     */
    public function canReview(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'order_id' => [
                    'required',
                    Rule::exists('orders', 'id')->where(function ($query) {
                        $query->where('user_id', auth()->id())
                            ->where('status', 'delivered');
                    }),
                ],
                'product_id' => 'required|exists:products,product_id',
            ]);

            $reviewExists = Review::where([
                'user_id' => auth()->id(),
                'order_id' => $validated['order_id'],
                'product_id' => $validated['product_id'],
            ])->exists();

            $productInOrder = DB::table('order_lines')
                ->where('order_id', $validated['order_id'])
                ->where('product_id', $validated['product_id'])
                ->exists();

            return response()->json([
                'success' => true,
                'can_review' => !$reviewExists && $productInOrder,
                'product_in_order' => $productInOrder,
                'already_reviewed' => $reviewExists,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check review eligibility',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}