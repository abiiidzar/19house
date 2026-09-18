<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancellationRequest;
use App\Services\Orders\OrderFulfillmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CancellationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        abort_if($status !== null && ! in_array($status, [CancellationRequest::STATUS_REQUESTED, CancellationRequest::STATUS_APPROVED, CancellationRequest::STATUS_REJECTED], true), 404);
        $requests = CancellationRequest::with(['order', 'requester', 'reviewer'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($query) => $query
                ->where('reason', 'like', '%'.$search.'%')
                ->orWhereHas('order', fn ($query) => $query->where('order_number', 'like', '%'.$search.'%'))
                ->orWhereHas('requester', fn ($query) => $query
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%'))))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.cancellations.index', compact('requests', 'status'));
    }

    public function review(Request $request, CancellationRequest $cancellationRequest, OrderFulfillmentService $service): RedirectResponse
    {
        $data = $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);
        $service->reviewCancellation($cancellationRequest, $data['decision'] === 'approve', $request->user(), $data['admin_note'] ?? null);

        return back()->with('success', 'Cancellation request reviewed.');
    }
}
