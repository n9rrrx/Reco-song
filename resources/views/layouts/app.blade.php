<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Reco-song | Instant song recognizer')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/logos/favicon.png') }}">

    {{-- IMMEDIATE THEME APPLICATION (prevents flash) --}}
    <script>
        (function() {
            const theme = localStorage.getItem('reco-theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    {{-- FONTS: Inter + Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700;900&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">

    {{-- ICONS --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body_class')">
<div id="wrapper">
    @include('components.header')
    <main>
        @yield('content')
    </main>
    @include('components.footer')
</div>
</body>
</html>
