<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Order;
use App\Models\Product;
use App\Services\Reporting\ReportService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(ReportService $reports): View
    {
        $start = now()->startOfMonth();
        $end = now()->endOfDay();

        return view('admin.dashboard', [
            'counts' => [
                'products' => Product::count(),
                'categories' => Category::count(),
                'collections' => Collection::count(),
                'orders_to_fulfill' => Order::whereIn('status', [Order::STATUS_PAID, Order::STATUS_PROCESSING, Order::STATUS_READY_TO_SHIP])->count(),
            ],
            'sales' => $reports->sales($start, $end),
            'inventory' => $reports->inventory($start, $end)['summary'],
            'recentActivities' => ActivityLog::with('user')->latest()->limit(6)->get(),
        ]);
    }
}
