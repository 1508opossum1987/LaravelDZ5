<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @auth
        <meta name="user-id" content="{{ auth()->id() }}">
        @if(auth()->user()->isAdmin())
            <meta name="is-admin" content="1">
        @endif
    @endauth

    <title>Electro - Premium Electronics Store</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    @vite(['resources/js/bootstrap.js'])
</head>
<body class="flex flex-col min-h-screen">

@include('components.header')

<main class="flex-grow container mx-auto px-4 py-8">
    @yield('content')
</main>

@include('components.footer')

</body>
</html>
