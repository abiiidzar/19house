@extends('layouts.admin')
@section('title', 'Homepage Content')
@section('content')
<div class="max-w-6xl space-y-10"><div class="flex flex-wrap items-end justify-between gap-4"><div><h1 class="text-2xl font-semibold">Homepage Content</h1><p class="mt-1 text-sm text-neutral-500">Atur fotografi hero dan editorial. Foto produk tetap dikelola di Products, foto featured collection di Collections.</p></div><a class="text-sm underline" href="{{ route('home') }}" target="_blank" rel="noopener">Lihat homepage ↗</a></div>
<section class="space-y-4"><div><h2 class="text-xl font-semibold">Hero utama</h2><p class="text-sm text-neutral-500">Foto besar di bagian paling atas.</p></div>@include('admin.homepage._form', ['section' => $hero, 'mode' => 'hero', 'method' => 'PUT', 'action' => route('admin.homepage.hero.update')])</section>
<section class="space-y-4"><div><h2 class="text-xl font-semibold">Editorial images</h2><p class="text-sm text-neutral-500">Blok aktif tampil berpasangan sesuai urutan. Nonaktifkan blok untuk menyembunyikannya tanpa menghapus konten.</p></div>
@forelse($editorials as $editorial)<details class="border bg-white"><summary class="cursor-pointer p-5 font-medium">{{ $editorial->sort_order }} · {{ $editorial->title }} <span class="ml-2 text-xs {{ $editorial->is_active ? 'text-green-700' : 'text-neutral-500' }}">{{ $editorial->is_active ? 'AKTIF' : 'NONAKTIF' }}</span></summary><div class="border-t">@include('admin.homepage._form', ['section' => $editorial, 'mode' => 'edit', 'method' => 'PUT', 'action' => route('admin.homepage.editorials.update', $editorial)])</div></details>@empty<p class="border bg-white p-6 text-sm text-neutral-500">Belum ada editorial image. Tambahkan yang pertama di bawah ini.</p>@endforelse
<h3 class="pt-3 font-semibold">Tambah editorial</h3>@include('admin.homepage._form', ['section' => null, 'mode' => 'create', 'method' => 'POST', 'action' => route('admin.homepage.editorials.store')])</section>
</div>
@endsection
