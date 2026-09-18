@extends('layouts.storefront')
@section('title', 'Shipping - 19HOUSE')

@section('content')
<section class="mx-auto max-w-3xl px-6 py-14 sm:py-20">
    <p class="storefront-nav text-neutral-500">Customer help</p>
    <h1 class="storefront-section-title mt-3">Shipping</h1>
    <div class="mt-8 space-y-5 text-sm leading-7 text-neutral-700">
        <p>Metode dan biaya pengiriman yang tersedia ditampilkan saat checkout, sebelum pesanan dikonfirmasi. Setelah pesanan dikirim, nomor pelacakan dapat dilihat pada detail pesanan di akun Anda.</p>
        <p>Untuk pertanyaan tentang pengiriman atau pesanan tertentu, hubungi tim kami melalui kontak di bawah ini dan sertakan nomor pesanan Anda.</p>
        @if($supportEmail = \App\Models\Setting::valueFor('support_email'))
            <p><a class="underline underline-offset-4" href="mailto:{{ $supportEmail }}?subject=Pertanyaan%20pengiriman">{{ $supportEmail }}</a></p>
        @endif
        @if($supportPhone = \App\Models\Setting::valueFor('support_phone'))
            <p>{{ $supportPhone }}</p>
        @endif
    </div>
</section>
@endsection
