<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductSku;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockAdjustmentController extends Controller
{
    public function create(): View
    {
        return view('admin.inventory.adjustment');
    }

    public function store(Request $request, InventoryService $service): RedirectResponse
    {
        $data = $request->validate([
            'sku' => ['required', 'exists:product_skus,sku'],
            'direction' => ['required', 'in:IN,OUT'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:255'],
        ]);
        $sku = ProductSku::where('sku', $data['sku'])->firstOrFail();
        $quantity = $data['direction'] === 'IN' ? $data['quantity'] : -$data['quantity'];
        try {
            $service->adjustStock($sku, $quantity, $data['reason'], $request->user());
        } catch (\Exception $exception) {
            return back()->withErrors(['quantity' => $exception->getMessage()])->withInput();
        }

        return redirect()->route('admin.inventory.index')->with('success', 'Stock adjusted.');
    }
}
