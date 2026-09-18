<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = CustomerAddress::where('user_id', auth()->id())->get();

        return view('customer.addresses.index', compact('addresses'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', CustomerAddress::class);
        $validated = $this->validateAddress($request);

        if ($request->boolean('is_default') || ! CustomerAddress::where('user_id', auth()->id())->exists()) {
            CustomerAddress::where('user_id', auth()->id())->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        CustomerAddress::create(array_merge($validated, ['user_id' => auth()->id()]));

        return back()->with('success', 'Address added.');
    }

    public function update(Request $request, CustomerAddress $address): RedirectResponse
    {
        Gate::authorize('update', $address);

        $validated = $this->validateAddress($request);

        if (! $validated['is_default'] && $address->is_default) {
            $validated['is_default'] = true;
        }

        if ($validated['is_default']) {
            CustomerAddress::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $address->update($validated);

        return back()->with('success', 'Address updated.');
    }

    public function destroy(CustomerAddress $address): RedirectResponse
    {
        Gate::authorize('delete', $address);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            CustomerAddress::where('user_id', auth()->id())
                ->latest('id')
                ->first()
                ?->update(['is_default' => true]);
        }

        return back()->with('success', 'Address deleted.');
    }

    private function validateAddress(Request $request): array
    {
        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address_line_1' => ['required', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'is_default' => ['sometimes', 'boolean'],
        ]);

        $validated['is_default'] = $request->boolean('is_default');

        return $validated;
    }
}
