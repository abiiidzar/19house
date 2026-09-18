<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $stocks = InventoryStock::with('sku.product', 'sku.variant', 'sku.size')
            ->when($request->query('search'), fn ($query, $search) => $query->whereHas('sku', fn ($query) => $query
                ->where('sku', 'like', '%'.$search.'%')
                ->orWhereHas('product', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
                ->orWhereHas('variant', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
                ->orWhereHas('size', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.inventory.index', compact('stocks'));
    }

    public function movements(Request $request): View
    {
        $movements = InventoryMovement::with('sku.product', 'actor')
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($query) => $query
                ->where('type', 'like', '%'.$search.'%')
                ->orWhere('reference', 'like', '%'.$search.'%')
                ->orWhere('reason', 'like', '%'.$search.'%')
                ->orWhereHas('sku', fn ($query) => $query
                    ->where('sku', 'like', '%'.$search.'%')
                    ->orWhereHas('product', fn ($query) => $query->where('name', 'like', '%'.$search.'%')))
                ->orWhereHas('actor', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.inventory.movements', compact('movements'));
    }
}
