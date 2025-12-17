<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Reco-song - Instant Music Recognition">

    <title>@yield('title', 'Reco-song - Instant Music Recognition')</title>

    {{-- 1. FAVICON (Updated to use your Logo) --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/logos/logo.svg') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logos/logo.svg') }}">

    {{-- 2. VITE ASSETS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- 3. FONTS (Updated to Inter) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<div id="wrapper">

    @include('components.header')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

</div>


{{-- Legacy Scripts --}}
<script src="{{ asset('js/scripts.bundle.js') }}"></script>

</body>
</html>
