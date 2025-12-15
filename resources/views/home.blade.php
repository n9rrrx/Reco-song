@extends('layouts.app')

@section('title', 'Reco-song - Instant Song Recognition')

@section('content')

    {{-- 1. HERO SECTION: LIVE LISTENER & LINK DROPPER --}}
    <div class="container-fluid px-xl-4">
        <div class="main-hero mx-auto">
            <div class="container">
                <div class="col-xl-8 col-lg-10 col-md-12 fs-5 text-center">
                    <h1 class="main-hero__title mb-3">
                        <span class="text-primary">Reco-song</span>
                        <br>Tap to Identify the Song.
                    </h1>

                    <div class="me-sm-5">
                        <p class="mb-4">Use your microphone or paste a link below for instant, dual-API recognition.</p>

                        {{-- MICROPHONE BUTTON --}}
                        <button id="live-listen-btn"
                                class="btn btn-lg btn-danger px-5 py-3 shadow-lg"
                                onclick="startRecording()"
                                style="z-index: 9999; position: relative;">
                            <i class="ri-mic-line me-2 fs-4 align-middle"></i>
                            <span class="fw-bold">Start Listening</span>
                        </button>

                        {{-- LINK DROPPER --}}
                        <div class="input-group mt-5 mx-auto shadow-sm" style="max-width: 600px;">
                            <input type="url" id="link-drop-input" class="form-control form-control-lg border-0" placeholder="Paste YouTube, Spotify, or SoundCloud link...">
                            <button id="process-link-btn" class="btn btn-primary px-4 fw-semibold" type="button">
                                Identify
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. RECOGNITION RESULT DISPLAY (Initially Hidden) --}}
    {{-- We use Javascript to show this block when a song is found --}}
    <div id="recognition-result-card" class="container-fluid px-xl-4 my-5" style="display: none;">
        <div class="container">
            <div class="card p-4 mx-auto shadow-lg border-0" style="max-width: 800px; border-radius: 16px;">
                <div class="d-flex align-items-center flex-column flex-md-row text-center text-md-start">

                    {{-- Album Art --}}
                    <img id="result-album-art" src="{{ asset('assets/images/misc/plan.png') }}"
                         class="me-md-4 mb-3 mb-md-0 shadow-sm"
                         style="width: 120px; height: 120px; border-radius: 12px; object-fit: cover;"
                         alt="Album Art">

                    {{-- Song Details --}}
                    <div class="flex-grow-1 w-100">
                        <h3 id="result-title" class="mb-1 fw-bold text-dark">Song Title</h3>
                        <p id="result-artist" class="mb-3 fs-5 text-muted">Artist Name</p>

                        <div id="streaming-links" class="d-flex gap-2 justify-content-center justify-content-md-start flex-wrap">
                            <a href="#" target="_blank" class="btn btn-success rounded-pill">
                                <i class="ri-spotify-fill"></i> Spotify
                            </a>
                            <a href="#" target="_blank" class="btn btn-danger rounded-pill">
                                <i class="ri-youtube-fill"></i> YouTube
                            </a>
                            <a href="#" target="_blank" class="btn btn-dark rounded-pill">
                                <i class="ri-apple-fill"></i> Apple Music
                            </a>
                        </div>
                    </div>

                    {{-- Close/Reset Button --}}
                    <button type="button" class="btn-close ms-md-3 align-self-start" aria-label="Close" onclick="resetRecognition()"></button>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. FEATURES SECTION --}}
    <div class="main-section bg-light">
        <div class="container">
            <div class="col-xl-6 col-lg-8 mx-auto text-center fs-5 mb-5">
                <h2>Why We Are <span class="text-primary">Better</span></h2>
                <p class="text-muted">Fast, accurate, and free song identification powered by dual engines.</p>
            </div>

            <div class="feature">
                <div class="row g-4 g-md-5">
                    {{-- Feature 1 --}}
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card h-100 py-2 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="feature__icon mb-3 text-primary bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="ri-exchange-2-fill fs-2"></i>
                                </div>
                                <div class="h5">Dual-API Accuracy</div>
                                <p class="text-muted">We combine AudD & ACRCloud for the highest confidence matching possible.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card h-100 py-2 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="feature__icon mb-3 text-danger bg-danger-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="ri-links-line fs-2"></i>
                                </div>
                                <div class="h5">Instant Links</div>
                                <p class="text-muted">Get direct links to Spotify, YouTube, and Apple Music immediately.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card h-100 py-2 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="feature__icon mb-3 text-success bg-success-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="ri-global-line fs-2"></i>
                                </div>
                                <div class="h5">Global Search</div>
                                <p class="text-muted">Recognize songs from any country, in any language, instantly.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 4 --}}
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card h-100 py-2 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="feature__icon mb-3 text-warning bg-warning-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="ri-history-line fs-2"></i>
                                </div>
                                <div class="h5">Search History</div>
                                <p class="text-muted">Keep track of every song you've discovered in your personal library.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. CALL TO ACTION --}}
    <div class="container my-5">
        <div class="newsletter text-white rounded-3 p-5" style="background: var(--bs-primary);">
            <div class="col-xl-7 col-lg-10 fs-5 mx-auto text-center">
                <h2 class="text-white fw-bold">Never miss a beat.</h2>
                <p class="mb-4">Join Reco-song today to save your discoveries and create playlists.</p>
                <a href="{{ route('register') }}" class="btn btn-lg btn-light text-primary fw-bold px-5">Get Started Free</a>
            </div>
        </div>
    </div>

@endsection
