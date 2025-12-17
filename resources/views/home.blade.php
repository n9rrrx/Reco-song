@extends('layouts.app')

@section('title', 'Reco-song | Instant Music Recognition')

@section('content')

    {{-- 1. HERO SECTION (Centered, Full Height) --}}
    <div class="d-flex align-items-center justify-content-center min-vh-100 position-relative flex-column"
         style="min-width: 280px; max-width: 100%;">

        <div class="container text-center" style="z-index: 2;">

            {{-- Floating Badge --}}
            <div class="mb-4">
                <span class="badge glass-card px-4 py-2 text-uppercase tracking-widest text-white border-0 shadow-sm"
                      style="letter-spacing: 3px; font-size: 0.75rem; background: rgba(255,255,255,0.1);">
                    ✨ Reco-song ✨
                </span>
            </div>

            {{-- Big Gradient Title --}}
            <h1 class="display-3 fw-bold mb-4 text-white">
                What song is <br>
                <span style="background: linear-gradient(to right, #6366f1, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    playing right now?
                </span>
            </h1>

            <p class="lead text-light opacity-75 mb-5 mx-auto" style="max-width: 600px;">
                Identify music playing around you instantly. High-speed recognition powered by ACRCloud's dual-engine technology.
            </p>

            {{-- Action Area --}}
            <div class="d-flex justify-content-center gap-3 flex-column flex-sm-row align-items-center">

                {{-- THE LAVISH MIC BUTTON --}}
                <button id="live-listen-btn"
                        class="btn btn-lavish px-5 py-4 fs-4 fw-bold d-flex align-items-center justify-content-center gap-3"
                        onclick="startRecording()"
                        style="min-width: 280px;">
                    <i class="ri-mic-line fs-2"></i>
                    <span>Start Listening</span>
                </button>

            </div>

            {{-- Link Dropper (Optional: Styled to match) --}}
            <div class="mt-5 mx-auto" style="max-width: 500px;">
                <div class="input-group glass-card p-1 rounded-pill">
                    <input type="url" id="link-drop-input" class="form-control bg-transparent border-0 text-white px-4"
                           placeholder="Or paste a Spotify/YouTube link..." style="box-shadow: none;">
                    <button class="btn btn-primary rounded-pill px-4 fw-bold" type="button">Identify</button>
                </div>
            </div>

            {{-- 2. RESULT CARD (Floating Glass - Hidden by Default) --}}
            <div id="recognition-result-card" class="mt-5 mx-auto text-start glass-card p-4"
                 style="display: none; max-width: 600px; animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);">

                <div class="d-flex align-items-center gap-4 flex-column flex-sm-row text-center text-sm-start">
                    {{-- Album Art --}}
                    <img id="result-album-art" src="{{ asset('assets/images/misc/plan.png') }}"
                         class="rounded-4 shadow-lg"
                         style="width: 120px; height: 120px; object-fit: cover;"
                         alt="Art">

                    {{-- Details --}}
                    <div class="flex-grow-1 w-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase text-primary small fw-bold mb-1 ls-2">Match Found</h6>
                                <h3 id="result-title" class="fw-bold mb-1 text-white">Song Title</h3>
                                <p id="result-artist" class="fs-5 text-light opacity-75 mb-3">Artist Name</p>
                            </div>
                            <button class="btn-close btn-close-white d-none d-sm-block" onclick="resetRecognition()"></button>
                        </div>

                        {{-- Streaming Buttons --}}
                        <div class="d-flex gap-2 justify-content-center justify-content-sm-start flex-wrap">

                            {{-- 👇 Added id="btn-spotify" --}}
                            <a id="btn-spotify" href="#" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 fw-bold" style="display: none;">
                                <i class="ri-spotify-fill"></i> Spotify
                            </a>

                            {{-- 👇 Added id="btn-youtube" --}}
                            <a id="btn-youtube" href="#" target="_blank" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold" style="display: none;">
                                <i class="ri-youtube-fill"></i> YouTube
                            </a>

                            <button class="btn btn-sm btn-outline-light rounded-pill px-3 d-sm-none" onclick="resetRecognition()">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- 3. FEATURES (Glass Cards) --}}
    <div class="container py-5 mb-5">
        <div class="row g-4 justify-content-center">

            {{-- Feature 1 --}}
            <div class="col-md-6 col-lg-3">
                <div class="glass-card p-4 h-100 text-center feature-card hover-lift">
                    <div class="feature-icon-box text-white">
                        <i class="ri-sound-module-fill"></i>
                    </div>
                    <h5 class="fw-bold text-white">Dual Engine</h5>
                    <p class="text-white-50 small mb-0">Powered by industry-standard audio fingerprinting technology.</p>
                </div>
            </div>

            {{-- Feature 2 --}}
            <div class="col-md-6 col-lg-3">
                <div class="glass-card p-4 h-100 text-center feature-card hover-lift">
                    <div class="feature-icon-box text-white">
                        <i class="ri-flashlight-fill"></i>
                    </div>
                    <h5 class="fw-bold text-white">Lightning Fast</h5>
                    <p class="text-white-50 small mb-0">Identifies songs in under 5 seconds, even in noisy environments.</p>
                </div>
            </div>

            {{-- Feature 3 --}}
            <div class="col-md-6 col-lg-3">
                <div class="glass-card p-4 h-100 text-center feature-card hover-lift">
                    <div class="feature-icon-box text-white">
                        <i class="ri-global-line"></i>
                    </div>
                    <h5 class="fw-bold text-white">Global Database</h5>
                    <p class="text-white-50 small mb-0">Recognize over 100 million songs from any country.</p>
                </div>
            </div>

            {{-- Feature 4 --}}
            <div class="col-md-6 col-lg-3">
                <div class="glass-card p-4 h-100 text-center feature-card hover-lift">
                    <div class="feature-icon-box text-white">
                        <i class="ri-history-line"></i>
                    </div>
                    <h5 class="fw-bold text-white">Search History</h5>
                    <p class="text-white-50 small mb-0">Never lose a song. We save every discovery for you.</p>
                </div>
            </div>

        </div>
    </div>

    {{-- 4. CTA SECTION --}}
    <div class="container pb-5">
        <div class="glass-card p-5 text-center position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, rgba(99, 102, 241, 0.2), rgba(236, 72, 153, 0.2)); z-index: -1;"></div>

            <h2 class="text-white fw-bold mb-3">Ready to build your library?</h2>
            <p class="text-white-50 mb-4">Join Reco-song today to save your discoveries and create playlists.</p>
            <a href="{{ route('register') }}" class="btn btn-light text-primary fw-bold px-5 py-3 rounded-pill shadow-lg hover-scale">
                Get Started Free
            </a>
        </div>
    </div>

    {{-- Custom Animation Styles --}}
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .hover-lift { transition: transform 0.3s ease; }
        .hover-lift:hover { transform: translateY(-10px); }

        .hover-scale { transition: transform 0.2s; }
        .hover-scale:hover { transform: scale(1.05); }

        /* Fix placeholder color in glass input */
        ::placeholder { color: rgba(255,255,255,0.5) !important; opacity: 1; }
    </style>

@endsection
