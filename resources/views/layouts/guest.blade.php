<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ url('final_eagri/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('final_eagri/vendor/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('final_eagri/css/admin.css') }}">
    <link rel="stylesheet" href="{{ url('final_eagri/css/card.css') }}">
    <link rel="stylesheet" href="{{ url('final_eagri/css/farmer-style.css') }}">
    <link rel="stylesheet" href="{{ url('final_eagri/css/home-style.css') }}">
    <script src="{{ url('final_eagri/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('final_eagri/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
