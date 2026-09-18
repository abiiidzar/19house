<!DOCTYPE html>
<html>
<head>
    <title>Mock Payment Gateway</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 shadow-md max-w-md text-center">
        <h1 class="text-xl font-bold mb-4">MOCK PAYMENT GATEWAY</h1>
        <p class="text-sm mb-2">Reference: {{ $payment->payment_reference }}</p>
        <p class="text-base font-normal mb-6">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>

        <form action="{{ route('mock.payment.success', $payment->payment_reference) }}" method="POST">
            @csrf
            <button type="submit" class="storefront-button bg-green-600 text-white px-6 py-3 w-full">Simulate Successful Payment</button>
        </form>
    </div>
</body>
</html>
