<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Restaurante</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <!-- Token CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="min-h-screen flex flex-col">
    @include('templates.header')

    <section class="flex-grow">
        @yield('content')
    </section>

    @include('templates.footer')
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
</body>

</html>
