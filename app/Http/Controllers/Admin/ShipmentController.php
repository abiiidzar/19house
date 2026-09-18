<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Orders\OrderFulfillmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function store(Request $request, Order $order, OrderFulfillmentService $service): RedirectResponse
    {
        $data = $request->validate([
            'courier' => ['required', 'string', 'max:100'],
            'service' => ['required', 'string', 'max:100'],
            'tracking_number' => ['required', 'string', 'max:100'],
        ]);
        $service->ship($order, $data, $request->user());

        return back()->with('success', 'Shipment created and customer notified.');
    }
}
