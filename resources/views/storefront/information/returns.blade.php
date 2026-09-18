@extends('layouts.storefront')
@section('title', 'Returns - 19HOUSE')

@section('content')
<section class="mx-auto max-w-3xl px-6 py-14 sm:py-20">
    <p class="storefront-nav text-neutral-500">Customer help</p>
    <h1 class="storefront-section-title mt-3">Returns & cancellations</h1>
    <div class="mt-8 space-y-5 text-sm leading-7 text-neutral-700">
        <p>Pesanan yang belum dibayar dapat dibatalkan dari halaman detail pesanan. Untuk pesanan yang sudah dibayar, Anda dapat mengajukan permintaan pembatalan dari halaman yang sama; tim kami akan meninjaunya.</p>
        <p>Jika Anda ingin menanyakan pengembalian barang setelah diterima, hubungi tim kami dengan nomor pesanan dan penjelasan kondisi barang. Ketentuan pengembalian akan dikonfirmasi oleh tim sebelum Anda mengirim barang.</p>
        @if($supportEmail = \App\Models\Setting::valueFor('support_email'))
            <p><a class="underline underline-offset-4" href="mailto:{{ $supportEmail }}?subject=Pertanyaan%20pengembalian">{{ $supportEmail }}</a></p>
        @endif
        @if($supportPhone = \App\Models\Setting::valueFor('support_phone'))
            <p>{{ $supportPhone }}</p>
        @endif
    </div>
</section>
@endsection
