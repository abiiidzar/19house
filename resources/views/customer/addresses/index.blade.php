@extends('layouts.account')
@section('title', 'Addresses - 19HOUSE')

@section('account_content')
<section>
    <h1 class="storefront-section-title mb-8 border-b border-neutral-200 pb-4">My Addresses</h1>

    @if(session('success'))
        <p class="mb-6 border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</p>
    @endif

    <div class="grid gap-10 lg:grid-cols-2">
        <div>
            <h2 class="mb-4 text-xs font-medium uppercase tracking-[0.08em] text-neutral-500">Saved addresses</h2>
            <div class="space-y-4">
                @forelse($addresses as $address)
                    <article class="border p-5 {{ $address->is_default ? 'border-black' : 'border-neutral-200' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-medium">{{ $address->recipient_name }}</p>
                                <p class="text-sm text-neutral-600">{{ $address->phone }}</p>
                                <p class="mt-2 text-sm text-neutral-600">{{ $address->address_line_1 }}, {{ $address->city }} {{ $address->postal_code }}</p>
                            </div>
                            @if($address->is_default)
                                <span class="text-xs uppercase tracking-wider">Default</span>
                            @endif
                        </div>
                        <details class="mt-4">
                            <summary class="cursor-pointer text-xs underline">Edit</summary>
                            <form method="POST" action="{{ route('customer.addresses.update', $address) }}" class="mt-4 space-y-3">
                                @csrf
                                @method('PUT')
                                <input name="recipient_name" value="{{ $address->recipient_name }}" required class="w-full border px-3 py-2 text-sm">
                                <input name="phone" value="{{ $address->phone }}" required class="w-full border px-3 py-2 text-sm">
                                <textarea name="address_line_1" required class="w-full border px-3 py-2 text-sm">{{ $address->address_line_1 }}</textarea>
                                <input name="city" value="{{ $address->city }}" required class="w-full border px-3 py-2 text-sm">
                                <input name="postal_code" value="{{ $address->postal_code }}" required class="w-full border px-3 py-2 text-sm">
                                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_default" value="1" @checked($address->is_default)> Make default</label>
                                <button class="storefront-button bg-black px-4 py-2 text-white">Save</button>
                            </form>
                        </details>
                        <form method="POST" action="{{ route('customer.addresses.destroy', $address) }}" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs text-red-600 underline">Delete</button>
                        </form>
                    </article>
                @empty
                    <p class="text-sm text-neutral-500">No saved addresses yet.</p>
                @endforelse
            </div>
        </div>

        <div>
            <h2 class="mb-4 text-xs font-medium uppercase tracking-[0.08em] text-neutral-500">Add address</h2>
            <form method="POST" action="{{ route('customer.addresses.store') }}" class="space-y-3 border p-5">
                @csrf
                <input name="recipient_name" placeholder="Recipient name" required class="w-full border px-3 py-2 text-sm">
                <input name="phone" placeholder="Phone" required class="w-full border px-3 py-2 text-sm">
                <textarea name="address_line_1" placeholder="Address" required class="w-full border px-3 py-2 text-sm"></textarea>
                <input name="city" placeholder="City" required class="w-full border px-3 py-2 text-sm">
                <input name="postal_code" placeholder="Postal code" required class="w-full border px-3 py-2 text-sm">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_default" value="1"> Make default</label>
                <button class="storefront-button w-full bg-black py-3 text-white">Add address</button>
            </form>
        </div>
    </div>
</section>
@endsection
