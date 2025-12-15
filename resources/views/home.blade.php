@extends('layouts.app')

@section('title', 'Reco-song - Instant Song Recognition')

@section('content')

    {{-- MODIFIED HERO SECTION (LIVE LISTENER / LINK DROPPER) --}}
    <div class="container-fluid px-xl-4">
        <div class="main-hero mx-auto">
            <div class="container">
                <div class="col-xl-8 col-lg-10 col-md-12 fs-5 text-center">
                    <h1 class="main-hero__title mb-3"><span class="text-primary">Reco-song</span>
                        <br>Tap to Identify the Song.</h1>
                    <div class="me-sm-5">
                        <p>Use your microphone or paste a link below for instant, dual-API recognition.</p>

                        {{-- 1. THE LIVE LISTENER BUTTON (The core CTA) --}}
                        <a id="live-listen-btn" class="btn btn-lg btn-primary external mt-3" href="#">
                            <i class="ri-mic-line me-2"></i> Start Listening
                        </a>

                        {{-- 2. THE LINK DROPPER INPUT --}}
                        <div class="input-group mt-4 mx-auto" style="max-width: 500px;">
                            <input type="url" id="link-drop-input" class="form-control form-control-lg" placeholder="Paste YouTube, Spotify, or SoundCloud link...">
                            <button id="process-link-btn" class="btn btn-outline-dark btn-lg" type="button">
                                Process Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- The Recognition Result Display (Initially Hidden) --}}
    <div id="recognition-result-card" class="container-fluid px-xl-4 my-5" style="display: none;">
        <div class="container">
            <div class="card p-4 mx-auto" style="max-width: 800px;">
                <div class="d-flex align-items-center">
                    <img id="result-album-art" src="{{ asset('images/misc/placeholder-art.png') }}" class="me-4" style="width: 100px; height: 100px; border-radius: 8px;" alt="Album Art">
                    <div class="flex-grow-1">
                        <h4 id="result-title" class="mb-1 text-primary">Song Title Goes Here</h4>
                        <p id="result-artist" class="mb-2 text-muted">Artist Name</p>
                        <div id="streaming-links">
                            <a href="#" class="btn btn-sm btn-success me-2"><i class="ri-spotify-fill"></i> Spotify</a>
                            <a href="#" class="btn btn-sm btn-danger me-2"><i class="ri-apple-fill"></i> Apple Music</a>
                            <a href="#" class="btn btn-sm btn-dark"><i class="ri-file-text-line"></i> Lyrics</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODIFIED FEATURES SECTION (Focusing on the "Better" features) --}}
    <div class="main-section">
        <div class="container">
            <div class="col-xl-6 col-lg-8 mx-auto text-center fs-5 mb-5">
                <h2>Why We Are <span class="text-primary">Better</span></h2>
            </div>
            <div class="feature">
                <div class="row g-4 g-md-5">

                    {{-- Feature 1: Dual-API Resilience --}}
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card h-100 py-2">
                            <div class="card-body">
                                <div class="feature__icon" style="color: var(--bs-blue);"><i class="ri-exchange-2-fill fs-4"></i></div>
                                <div class="mt-4 mb-3 h5">Dual-API Accuracy</div>
                                <p>We use both AudD & ACRCloud for failover and highest confidence matching.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 2: Rich Metadata & Streaming Links --}}
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card h-100 py-2">
                            <div class="card-body">
                                <div class="feature__icon" style="color: var(--bs-pink);"><i class="ri-link-m fs-4"></i></div>
                                <div class="mt-4 mb-3 h5">Instant Streaming Links</div>
                                <p>One-click links to Spotify, Apple Music, and lyrics immediately upon recognition.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 3: Browser Extension --}}
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card h-100 py-2">
                            <div class="card-body">
                                <div class="feature__icon" style="color: var(--bs-purple);"><i class="ri-extension-line fs-4"></i></div>
                                <div class="mt-4 mb-3 h5">Browser Extension</div>
                                <p>Identify songs playing inside any browser tab without needing your microphone.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 4: Geo-Trending Charts --}}
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card h-100 py-2">
                            <div class="card-body">
                                <div class="feature__icon" style="color: var(--bs-indigo);"><i class="ri-bar-chart-2-fill fs-4"></i></div>
                                <div class="mt-4 mb-3 h5">Real-Time Trending</div>
                                <p>See what's being recognized right now in your city and around the world.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Retain original design blocks for visual structure --}}
                    <div class="col-xl-3 col-lg-4 col-sm-6"><div class="card h-100 py-2"><div class="card-body"><div class="feature__icon" style="color: var(--bs-red);"><i class="ri-radio-fill fs-4"></i></div><div class="mt-4 mb-3 h5">Live streaming</div><p>Mollitia temporibus fuga est atque harum quod dolorum inventore distinctio!</p></div></div></div>
                    <div class="col-xl-3 col-lg-4 col-sm-6"><div class="card h-100 py-2"><div class="card-body"><div class="feature__icon" style="color: var(--bs-orange);"><i class="ri-vip-crown-fill fs-4"></i></div><div class="mt-4 mb-3 h5">Subscription plan</div><p>Mollitia temporibus fuga est atque harum quod dolorum inventore distinctio!</p></div></div></div>
                    <div class="col-xl-3 col-lg-4 col-sm-6"><div class="card h-100 py-2"><div class="card-body"><div class="feature__icon" style="color: var(--bs-green);"><i class="ri-user-4-fill fs-4"></i></div><div class="mt-4 mb-3 h5">User management</div><p>Dolorum aut reprehenderit facere quia dolore nesciunt aliquam voluptatem distinctio.</p></div></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Remaining original template sections (Events, Pricing, Trending, Blog) --}}
    <div class="main-section bg-light">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between text-center mb-5"><h2 class="mb-4 mb-sm-0">Upcoming <span class="text-primary">Events</span></h2><a class="btn btn-outline-primary external" href="login.html">Explore all events</a></div>
            <div class="row g-4 g-md-5">
                <div class="col-lg-4 col-sm-6"><div class="cover cover--round"><div class="cover__image"><div class="ratio ratio-16x9"><img src="{{ asset('images/background/horizontal/2.jpg') }}" alt="Event cover"></div></div><div class="cover__foot mt-3 px-2"><p class="cover__subtitle d-flex mb-2"><i class="ri-map-pin-fill fs-6"></i> <span class="ms-1 fw-semibold">258 Goff Avenue, MI - USA</span></p><span class="cover__title fs-6 mb-3">New year 1st night with BendiQ Band</span><div class="d-flex align-items-center justify-content-between"><div class="d-flex align-items-center"><div class="avatar-group"><div class="avatar"><div class="avatar__image"><img src="{{ asset('images/users/thumb-3.jpg') }}" alt=""></div></div><div class="avatar"><div class="avatar__image"><img src="{{ asset('images/users/thumb-4.jpg') }}" alt=""></div></div><div class="avatar"><div class="avatar__image"><img src="{{ asset('images/users/thumb-5.jpg') }}" alt=""></div></div></div><div class="ps-1">24+</div></div><a href="login.html" class="btn btn-sm btn-light-primary">Join Event</a></div></div></div></div>
                <div class="col-lg-4 col-sm-6"><div class="cover cover--round"><div class="cover__image"><div class="ratio ratio-16x9"><img src="{{ asset('images/background/horizontal/3.jpg') }}" alt="Event cover"></div></div><div class="cover__foot mt-3 px-2"><p class="cover__subtitle d-flex mb-2"><i class="ri-map-pin-fill fs-6"></i> <span class="ms-1 fw-semibold">2105 Badger Pond Lane, FL - USA</span></p><span class="cover__title fs-6 mb-3">Varida Meronny music band</span><div class="d-flex align-items-center justify-content-between"><div class="d-flex align-items-center"><div class="avatar-group"><div class="avatar"><div class="avatar__image"><img src="{{ asset('images/users/thumb.jpg') }}" alt=""></div></div><div class="avatar"><div class="avatar__image"><img src="{{ asset('images/users/thumb-2.jpg') }}" alt=""></div></div><div class="avatar"><div class="avatar__image"><img src="{{ asset('images/users/thumb-3.jpg') }}" alt=""></div></div></div><div class="ps-1">40+</div></div><a href="login.html" class="btn btn-sm btn-light-primary">Join Event</a></div></div></div></div>
                <div class="col-lg-4 col-sm-6"><div class="cover cover--round"><div class="cover__image"><div class="ratio ratio-16x9"><img src="{{ asset('images/background/horizontal/1.jpg') }}" alt="Event cover"></div></div><div class="cover__foot mt-3 px-2"><p class="cover__subtitle d-flex mb-2"><i class="ri-map-pin-fill fs-6"></i> <span class="ms-1 fw-semibold">2801 Pine Lake Rd, TX - USA</span></p><span class="cover__title fs-6 mb-3">Music night virtual event to welcome new year</span><div class="d-flex align-items-center justify-content-between"><div class="d-flex align-items-center"><div class="avatar-group"><div class="avatar"><div class="avatar__image"><img src="{{ asset('images/users/thumb.jpg') }}" alt=""></div></div><div class="avatar"><div class="avatar__image"><img src="{{ asset('images/users/thumb-2.jpg') }}" alt=""></div></div><div class="avatar"><div class="avatar__image"><img src="{{ asset('images/users/thumb-3.jpg') }}" alt=""></div></div></div><div class="ps-1">40+</div></div><a href="login.html" class="btn btn-sm btn-light-primary">Join Event</a></div></div></div></div>
            </div>
        </div>
    </div>
    <div id="pricing" class="main-section">
        <div class="container">
            <div class="text-center mb-5"><h2>Flexible <span class="text-primary">Plans</span></h2></div>
            <div class="col-xl-11 col-lg-8 mx-auto pt-4">
                <div class="plan bg-light">
                    <div class="card plan__info overflow-hidden">
                        <div class="card-body d-flex flex-column p-0">
                            <div class="p-4">
                                <div class="mb-3 h4">Free <span class="text-primary">Trial</span></div>
                                <p class="fs-6">Get 30 days <b>Free Trial</b> subscription plan to experience awesome music.</p><a href="{{ route('register') }}" class="d-inline-flex align-items-center"><span class="me-1">Register now</span> <i class="ri-arrow-right-line ltr fs-6"></i> <i class="ri-arrow-left-line rtl fs-6"></i></a>
                            </div>
                            <div class="px-3 text-center mt-auto"><img src="{{ asset('images/misc/plan.png') }}" class="img-fluid" alt=""></div>
                        </div>
                    </div>
                    <div class="plan__data">
                        <div class="card plan__col">
                            <div class="card-body fw-medium">
                                <div class="d-flex align-items-center text-dark mb-4"><i class="ri-music-2-line fs-3"></i><div class="h4 ps-3">Ads <span class="text-primary">free</span></div></div>
                                <p class="fs-6 opacity-50">What you'll get</p>
                                <div class="d-flex mb-3"><i class="ri-checkbox-circle-fill text-primary opacity-75 fs-6"></i> <span class="ps-2">Access all free tracks and app features</span></div>
                                <div class="d-flex mb-3"><i class="ri-checkbox-circle-fill text-primary opacity-75 fs-6"></i> <span class="ps-2">No Ads between tracks</span></div>
                            </div>
                            <div class="card-footer pb-4 pb-sm-0"><div class="text-dark mb-3"><span class="fs-4 fw-bold">$10.99</span>/year</div><button type="button" class="btn btn-primary w-100">Choose</button></div>
                        </div>
                        <div class="card plan__col">
                            <div class="card-body fw-medium">
                                <div class="d-flex align-items-center text-dark mb-4"><i class="ri-vip-crown-line fs-3"></i><div class="h4 ps-3">Premium</div></div>
                                <p class="fs-6 opacity-50">What you'll get</p>
                                <div class="d-flex mb-3"><i class="ri-checkbox-circle-fill text-primary opacity-75 fs-6"></i> <span class="ps-2">Access all free tracks and app features</span></div>
                                <div class="d-flex mb-3"><i class="ri-checkbox-circle-fill text-primary opacity-75 fs-6"></i> <span class="ps-2">No Ads between tracks</span></div>
                                <div class="d-flex mb-3"><i class="ri-checkbox-circle-fill text-primary opacity-75 fs-6"></i> <span class="ps-2">Create playlist & access analytics</span></div>
                                <div class="d-flex mb-3"><i class="ri-checkbox-circle-fill text-primary opacity-75 fs-6"></i> <span class="ps-2">Listen paid track once & purchase</span></div>
                                <div class="d-flex mb-3"><i class="ri-checkbox-circle-fill text-primary opacity-75 fs-6"></i> <span class="ps-2">Download and listen offline</span></div>
                            </div>
                            <div class="card-footer"><div class="text-dark mb-3"><span class="fs-4 fw-bold">$99.99</span>/year</div><button type="button" class="btn btn-primary w-100">Choose</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-section bg-light">
        <div class="container">
            <div class="col-xl-6 col-lg-8 mx-auto text-center fs-5 mb-5"><h2>Trending <span class="text-primary">Artists</span></h2><p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Possimus sit aliquid molestiae sint ab illo.</p></div>
            <div class="swiper-carousel swiper-carousel-button">
                <div class="swiper" data-swiper-slides="6" data-swiper-autoplay="true">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><div class="avatar avatar--xxl d-block text-center scale-animation"><a href="artist-details.html" class="avatar__image mx-auto"><img src="{{ asset('images/cover/large/1.jpg') }}" alt="Arebica Luna"> </a><a href="artist-details.html" class="avatar__title mt-3">Arebica Luna</a></div></div>
                        <div class="swiper-slide"><div class="avatar avatar--xxl d-block text-center scale-animation"><a href="artist-details.html" class="avatar__image mx-auto"><img src="{{ asset('images/cover/large/2.jpg') }}" alt="Gerrina Linda"> </a><a href="artist-details.html" class="avatar__title mt-3">Gerrina Linda</a></div></div>
                        <div class="swiper-slide"><div class="avatar avatar--xxl d-block text-center scale-animation"><a href="artist-details.html" class="avatar__image mx-auto"><img src="{{ asset('images/cover/large/3.jpg') }}" alt="Zunira Willy"> </a><a href="artist-details.html" class="avatar__title mt-3">Zunira Willy</a></div></div>
                        <div class="swiper-slide"><div class="avatar avatar--xxl d-block text-center scale-animation"><a href="artist-details.html" class="avatar__image mx-auto"><img src="{{ asset('images/cover/large/4.jpg') }}" alt="Johnny Marro"> </a><a href="artist-details.html" class="avatar__title mt-3">Johnny Marro</a></div></div>
                        <div class="swiper-slide"><div class="avatar avatar--xxl d-block text-center scale-animation"><a href="artist-details.html" class="avatar__image mx-auto"><img src="{{ asset('images/cover/large/5.jpg') }}" alt="Jina Moore"> </a><a href="artist-details.html" class="avatar__title mt-3">Jina Moore</a></div></div>
                        <div class="swiper-slide"><div class="avatar avatar--xxl d-block text-center scale-animation"><a href="artist-details.html" class="avatar__image mx-auto"><img src="{{ asset('images/cover/large/6.jpg') }}" alt="Rasomi Pelina"> </a><a href="artist-details.html" class="avatar__title mt-3">Rasomi Pelina</a></div></div>
                        <div class="swiper-slide"><div class="avatar avatar--xxl d-block text-center scale-animation"><a href="artist-details.html" class="avatar__image mx-auto"><img src="{{ asset('images/cover/large/7.jpg') }}" alt="Pimila Holliwy"> </a><a href="artist-details.html" class="avatar__title mt-3">Pimila Holliwy</a></div></div>
                        <div class="swiper-slide"><div class="avatar avatar--xxl d-block text-center scale-animation"><a href="artist-details.html" class="avatar__image mx-auto"><img src="{{ asset('images/cover/large/8.jpg') }}" alt="Karen Jennings"> </a><a href="artist-details.html" class="avatar__title mt-3">Karen Jennings</a></div></div>
                        <div class="swiper-slide"><div class="avatar avatar--xxl d-block text-center scale-animation"><a href="artist-details.html" class="avatar__image mx-auto"><img src="{{ asset('images/cover/large/9.jpg') }}" alt="Lenisa Gory"> </a><a href="artist-details.html" class="avatar__title mt-3">Lenisa Gory</a></div></div>
                        <div class="swiper-slide"><div class="avatar avatar--xxl d-block text-center scale-animation"><a href="artist-details.html" class="avatar__image mx-auto"><img src="{{ asset('images/cover/large/10.jpg') }}" alt="Nutty Nina"> </a><a href="artist-details.html" class="avatar__title mt-3">Nutty Nina</a></div></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-section">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between text-center mb-5"><h2 class="mb-4 mb-sm-0">Latest <span class="text-primary">Articles</span></h2><a class="btn btn-outline-primary external" href="blog.html">Explore all blogs</a></div>
            <div class="row g-4 g-md-5">
                <div class="col-lg-4 col-sm-6"><div class="cover cover--round title-line-animation"><a href="blog-details.html" class="cover__image"><div class="ratio ratio-16x9"><img src="{{ asset('images/background/horizontal/4.jpg') }}" alt="Blog cover"></div></a><div class="cover__foot mt-3 px-2"><span class="cover__subtitle fw-medium mb-3">Admin - Jun 20, 2022</span> <a href="blog-details.html" class="cover__title h5">Nihil quaerat asperiores repudiandae expedita libero cupiditate.</a></div></div></div>
                <div class="col-lg-4 col-sm-6"><div class="cover cover--round title-line-animation"><a href="blog-details.html" class="cover__image"><div class="ratio ratio-16x9"><img src="{{ asset('images/background/horizontal/5.jpg') }}" alt="Blog cover"></div></a><div class="cover__foot mt-3 px-2"><span class="cover__subtitle fw-medium mb-3">Admin - Jun 20, 2022</span> <a href="blog-details.html" class="cover__title h5">Doloribus repudiandae possimus. Quia dolorum voluptatum dignissimos.</a></div></div></div>
                <div class="col-lg-4 col-sm-6"><div class="cover cover--round title-line-animation"><a href="blog-details.html" class="cover__image"><div class="ratio ratio-16x9"><img src="{{ asset('images/background/horizontal/6.jpg') }}" alt="Blog cover"></div></a><div class="cover__foot mt-3 px-2"><span class="cover__subtitle fw-medium mb-3">Admin - Jun 20, 2022</span> <a href="blog-details.html" class="cover__title h5">Molestias id porro incidunt aliquid dolor esse obcaecati maiores quas.</a></div></div></div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="newsletter text-white">
            <div class="col-xl-7 col-lg-10 fs-5 mx-auto text-center"><h2 class="text-white">Join with <span class="newsletter__title-text">Listen App</span></h2><p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Molestias explicabo harum perspiciatis voluptates sed ut.</p><a href="{{ route('register') }}" class="btn btn-lg btn-white external mt-3">Register now</a></div>
        </div>
    </div>

@endsection
