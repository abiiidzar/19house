<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Reporting\ReportFilters;
use App\Services\Reporting\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private ReportService $reports) {}

    public function index(Request $request): View
    {
        $filters = ReportFilters::fromRequest($request);
        $start = $filters['start'];
        $end = $filters['end'];
        $channel = $filters['channel'];

        return view('management.dashboard', [
            'filters' => $filters,
            'sales' => $this->reports->sales($start, $end, $channel),
            'trend' => $this->reports->salesTrend($start, $end, $channel),
            'products' => $this->reports->products($start, $end, $channel, 10),
            'inventory' => $this->reports->inventory($start, $end),
            'customers' => $this->reports->customers($start, $end),
        ]);
    }
}
