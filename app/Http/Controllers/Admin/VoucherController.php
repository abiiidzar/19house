<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VoucherController extends Controller
{
    public function index(Request $request): View
    {
        $vouchers = Voucher::query()
            ->withCount([
                'usages as claimed_count' => fn ($query) => $query->whereIn('status', [VoucherUsage::STATUS_RESERVED, VoucherUsage::STATUS_REDEEMED]),
                'usages as redeemed_count' => fn ($query) => $query->where('status', VoucherUsage::STATUS_REDEEMED),
            ])
            ->when($request->query('search'), fn ($query, $search) => $query->where('code', 'like', '%'.$search.'%'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create(): View
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $voucher = Voucher::create($this->validated($request));

        return redirect()->route('admin.vouchers.show', $voucher)->with('success', 'Voucher created.');
    }

    public function show(Voucher $voucher): View
    {
        $usages = $voucher->usages()->with(['order', 'user'])->latest()->paginate(20);

        return view('admin.vouchers.show', compact('voucher', 'usages'));
    }

    public function edit(Voucher $voucher): View
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher): RedirectResponse
    {
        $data = $this->validated($request, $voucher);
        if ($data['code'] !== $voucher->code && $voucher->usages()->exists()) {
            throw ValidationException::withMessages(['code' => 'A voucher code cannot change after it has been used.']);
        }

        $voucher->update($data);

        return redirect()->route('admin.vouchers.show', $voucher)->with('success', 'Voucher updated.');
    }

    private function validated(Request $request, ?Voucher $voucher = null): array
    {
        $request->merge(['code' => mb_strtoupper(trim((string) $request->input('code')))]);

        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'regex:/^[A-Z0-9_-]+$/', Rule::unique('vouchers', 'code')->ignore($voucher?->id)],
            'type' => ['required', Rule::in([Voucher::TYPE_FIXED, Voucher::TYPE_PERCENTAGE])],
            'value' => ['required', 'integer', 'min:1', 'max:999999999999'],
            'min_purchase' => ['required', 'integer', 'min:0'],
            'max_discount' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_user_limit' => ['required', 'integer', 'min:1'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($data['type'] === Voucher::TYPE_PERCENTAGE && (int) $data['value'] > 100) {
            throw ValidationException::withMessages(['value' => 'Percentage cannot exceed 100.']);
        }

        $data['is_active'] = $request->boolean('is_active');
        if ($data['type'] === Voucher::TYPE_FIXED) {
            $data['max_discount'] = null;
        }

        return $data;
    }
}
