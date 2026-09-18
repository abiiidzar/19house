<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductSku;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockInController extends Controller
{
    public function create(): View
    {
        return view('admin.inventory.stock-in');
    }

    public function store(Request $request, InventoryService $service): RedirectResponse
    {
        $data = $request->validate([
            'sku' => ['required', 'exists:product_skus,sku'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
        $sku = ProductSku::where('sku', $data['sku'])->firstOrFail();
        $service->addStock($sku, $data['quantity'], $data['reference'] ?? null, $request->user(), $data['note'] ?? null);

        return redirect()->route('admin.inventory.index')->with('success', 'Stock added.');
    }
}
