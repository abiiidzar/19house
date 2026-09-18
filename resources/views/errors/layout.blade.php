<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') — 19HOUSE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-[#F7F7F5] p-6 text-[#111111]">
    <main class="w-full max-w-2xl border-y border-neutral-300 py-14 text-center sm:py-20">
        <a href="{{ route('home') }}" class="brand-logo text-sm">19HOUSE</a>
        <p class="editorial-text mt-10 text-8xl leading-none sm:text-9xl">@yield('code')</p>
        <h1 class="editorial-text mt-5 text-3xl sm:text-4xl">@yield('message')</h1>
        <p class="mx-auto mt-4 max-w-md text-sm leading-6 text-neutral-500">@yield('description', 'Something interrupted your visit. You can return to the storefront and continue browsing.')</p>
        <a href="{{ route('home') }}" class="editorial-link mt-9">Return home <span aria-hidden="true">→</span></a>
    </main>
</body>
</html>
