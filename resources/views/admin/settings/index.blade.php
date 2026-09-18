@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<div class="max-w-3xl space-y-5"><div><h1 class="text-2xl font-semibold">Settings</h1><p class="mt-1 text-sm text-neutral-500">Changes are audited. Gateway credentials remain in environment configuration.</p></div>
<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">@csrf @method('PUT')
    <section class="border bg-white p-6"><h2 class="mb-4 font-semibold">Store & contact</h2><div class="grid gap-4 sm:grid-cols-2">
        @foreach(['store_name' => 'Store name', 'support_email' => 'Support email', 'support_phone' => 'Support phone', 'store_address' => 'Store address'] as $key => $label)
            <label class="block text-sm">{{ $label }}<input name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}" class="mt-1 block w-full border p-3" {{ $key === 'store_name' ? 'required' : '' }}></label>@error($key)<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        @endforeach
    </div></section>
    <section class="border bg-white p-6"><h2 class="mb-4 font-semibold">Commerce operations</h2><div class="grid gap-4 sm:grid-cols-2">
        @foreach(['inventory_low_stock_threshold' => ['Low-stock threshold', 1, 100], 'shipping_regular_cost' => ['Regular shipping (Rp)', 0, 10000000], 'shipping_express_cost' => ['Express shipping (Rp)', 0, 10000000], 'payment_expiry_minutes' => ['Payment expiry (minutes)', 5, 1440]] as $key => $field)
            <label class="block text-sm">{{ $field[0] }}<input type="number" name="{{ $key }}" min="{{ $field[1] }}" max="{{ $field[2] }}" value="{{ old($key, $settings[$key] ?? '') }}" required class="mt-1 block w-full border p-3"></label>@error($key)<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        @endforeach
    </div><p class="mt-4 text-xs text-neutral-500">New shipping prices and payment expiry apply to new orders. Existing orders keep their saved totals and deadlines.</p></section>
    <button class="bg-neutral-900 px-6 py-3 text-sm text-white">Save settings</button>
</form>
<section class="border bg-white p-6">
    <h2 class="font-semibold">Login editorial image</h2>
    <p class="mt-1 text-sm text-neutral-500">Foto vertikal untuk panel kiri halaman login pada layar desktop. JPG, PNG, atau WEBP; maksimal 8 MB. Rekomendasi 1200×1600 px.</p>
    @if($loginEditorialImage)
        <img src="{{ Storage::disk('public')->url($loginEditorialImage) }}" alt="Preview login editorial image" class="mt-5 h-72 w-52 object-cover">
    @else
        <p class="mt-5 text-sm text-neutral-500">Belum ada foto login; halaman login menggunakan tampilan cadangan.</p>
    @endif
    <form method="POST" action="{{ route('admin.settings.login-editorial.update') }}" enctype="multipart/form-data" class="mt-5 space-y-4">@csrf @method('PUT')
        <label class="block text-sm">Foto login<input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full border p-3"></label>
        @error('image')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        <label class="block text-sm">Teks alternatif gambar<input name="alt" value="{{ old('alt', $loginEditorialAlt) }}" maxlength="160" class="mt-1 block w-full border p-3"></label>
        @error('alt')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        @if($loginEditorialImage)<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remove_image" value="1">Hapus foto saat ini</label>@endif
        <button type="submit" class="bg-neutral-900 px-6 py-3 text-sm text-white">Simpan foto login</button>
    </form>
</section>
</div>
@endsection
