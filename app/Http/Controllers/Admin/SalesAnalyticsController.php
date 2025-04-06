<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesAnalyticsController extends Controller
{
    public function index()
    {
        return view('admin.sales.analytics');
    }

    public function getChartData(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'group_by' => 'in:daily,monthly,yearly'
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
        $groupBy = $request->group_by ?? 'monthly';

        // Sales Data
        $salesQuery = Sale::whereBetween('sale_date', [$startDate, $endDate]);

        switch ($groupBy) {
            case 'daily':
                $salesData = $salesQuery->selectRaw('DATE(sale_date) as date, SUM(total_price) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                $labels = $salesData->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d'));
                break;
                
            case 'monthly':
                $salesData = $salesQuery->selectRaw('DATE_FORMAT(sale_date, "%Y-%m") as month, SUM(total_price) as total')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                $labels = $salesData->pluck('month')->map(fn($month) => Carbon::createFromFormat('Y-m', $month)->format('M Y'));
                break;
                
            case 'yearly':
                $salesData = $salesQuery->selectRaw('YEAR(sale_date) as year, SUM(total_price) as total')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
                $labels = $salesData->pluck('year');
                break;
        }

        // Product Sales Data
        $productSales = Sale::with('product')
            ->selectRaw('product_id, SUM(total_price) as total')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->get();

        // Generate colors for products
        $colors = [];
        foreach ($productSales as $item) {
            $colors[] = $this->generateColor($item->product_id);
        }

        return response()->json([
            'sales_chart' => [
                'labels' => $labels,
                'data' => $salesData->pluck('total'),
            ],
            'product_chart' => [
                'labels' => $productSales->pluck('product.name'),
                'data' => $productSales->pluck('total'),
                'colors' => $colors,
            ],
            'summary' => [
                'total_sales' => $salesData->sum('total'),
                'total_products' => $productSales->count(),
                'date_range' => $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y')
            ]
        ]);
    }

    private function generateColor($seed)
    {
        $goldenRatio = 0.618033988749895;
        $hue = fmod($seed * $goldenRatio, 1);
        $hsvToRgb = function($h, $s, $v) {
            $i = floor($h * 6);
            $f = $h * 6 - $i;
            $p = $v * (1 - $s);
            $q = $v * (1 - $f * $s);
            $t = $v * (1 - (1 - $f) * $s);
            
            switch ($i % 6) {
                case 0: $r = $v; $g = $t; $b = $p; break;
                case 1: $r = $q; $g = $v; $b = $p; break;
                case 2: $r = $p; $g = $v; $b = $t; break;
                case 3: $r = $p; $g = $q; $b = $v; break;
                case 4: $r = $t; $g = $p; $b = $v; break;
                case 5: $r = $v; $g = $p; $b = $q; break;
            }
            
            return [round($r * 255), round($g * 255), round($b * 255)];
        };
        
        $rgb = $hsvToRgb($hue, 0.5, 0.95);
        return sprintf('rgba(%d, %d, %d, 0.7)', $rgb[0], $rgb[1], $rgb[2]);
    }
}