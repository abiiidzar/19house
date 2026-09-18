<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\PosTransaction;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $transactions = PosTransaction::with('cashier')->latest()->paginate(20);

        return view('cashier.transactions.index', compact('transactions'));
    }

    public function show(PosTransaction $transaction): View
    {
        $transaction->load(['items', 'cashier']);

        return view('cashier.transactions.show', compact('transaction'));
    }
}
