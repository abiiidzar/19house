<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', '19HOUSE')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    @livewireStyles
</head>

<body class="storefront-shell min-h-screen bg-[#F7F7F5] text-[#111111]">

    @yield('content')

    @livewireScripts
</body>
</html>
