<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Listen App - Online Music Streaming App Template">
    <meta name="keywords" content="music template, music app, music web app, responsive music app, music, themeforest, html music app template, css3, html5">

    <title>@yield('title', 'Listen App - Online Music Streaming App')</title>

    <link href="{{ asset('images/logos/favicon.png') }}" rel="icon">
    <link rel="apple-touch-icon" href="{{ asset('images/logos/touch-icon-iphone.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/logos/touch-icon-ipad.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logos/touch-icon-iphone-retina.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('images/logos/touch-icon-ipad-retina.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
</head>
<body>

<div id="loader">
    <div class="loader">
        <div class="loader__eq mx-auto">
            <span></span><span></span><span></span><span></span><span></span><span></span>
        </div>
        <span class="loader__text mt-2">Loading</span>
    </div>
</div>

<div id="wrapper">

    @include('components.header')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

</div>

</body>
</html>
