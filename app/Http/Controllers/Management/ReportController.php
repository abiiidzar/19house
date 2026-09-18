<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Reporting\ReportFilters;
use App\Services\Reporting\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private ReportService $reports) {}

    public function sales(Request $request): View
    {
        $filters = ReportFilters::fromRequest($request);

        return view('management.reports.sales', [
            'filters' => $filters,
            'sales' => $this->reports->sales($filters['start'], $filters['end'], $filters['channel']),
            'trend' => $this->reports->salesTrend($filters['start'], $filters['end'], $filters['channel']),
        ]);
    }

    public function products(Request $request): View
    {
        $filters = ReportFilters::fromRequest($request);

        return view('management.reports.products', [
            'filters' => $filters,
            'report' => $this->reports->products($filters['start'], $filters['end'], $filters['channel']),
        ]);
    }

    public function inventory(Request $request): View
    {
        $filters = ReportFilters::fromRequest($request);

        return view('management.reports.inventory', [
            'filters' => $filters,
            'report' => $this->reports->inventory($filters['start'], $filters['end']),
            'stocks' => $this->reports->inventoryRows(),
        ]);
    }

    public function customers(Request $request): View
    {
        $filters = ReportFilters::fromRequest($request);

        return view('management.reports.customers', [
            'filters' => $filters,
            'report' => $this->reports->customers($filters['start'], $filters['end']),
        ]);
    }
}
