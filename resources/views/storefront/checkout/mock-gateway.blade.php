<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Payment Gateway</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-neutral-100 px-6">
    <main class="w-full max-w-md border border-neutral-200 bg-white p-8 text-center shadow-sm">
        <h1 class="text-xl font-bold">MOCK PAYMENT GATEWAY</h1>
        <p class="mt-4 text-sm text-neutral-500">Payment reference</p>
        <p class="font-mono text-sm">{{ $payment->payment_reference }}</p>
        <p class="mt-6 text-base font-normal">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>

        @if($payment->status === \App\Models\Payment::STATUS_PENDING)
            <form action="{{ route('mock.payment.success', $payment->payment_reference) }}" method="POST" class="mt-8">
                @csrf
                <button type="submit" class="storefront-button w-full bg-black px-6 py-3 text-white hover:bg-neutral-800">SIMULATE PAYMENT</button>
            </form>
        @else
            <p class="mt-8 text-sm text-neutral-600">This payment is {{ strtolower($payment->status) }}.</p>
        @endif
    </main>
</body>
</html>
