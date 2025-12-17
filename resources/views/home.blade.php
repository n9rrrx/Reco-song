@extends('layouts.app')

@section('title', 'Identify Music')

@section('content')

    <div class="d-flex align-items-center justify-content-center min-vh-100 position-relative text-center overflow-hidden">

        <div class="container" style="z-index: 2;">

            <h1 class="display-title mb-5" style="font-size: 4.5rem;">
                What's playing?
            </h1>

            <div class="shazam-container">
                {{-- 👇 NEW: The Visualizer Canvas sits behind the button --}}
                <canvas id="visualizer"></canvas>

                <button id="live-listen-btn" onclick="toggleRecording()" aria-label="Identify Song">
                    <i class="ri-shazam-line"></i>
                </button>
            </div>

            <div class="mt-2">
                <div id="status-container" class="status-pill">
                    <span id="status-text">Tap to start listening</span>
                </div>
            </div>

        </div>
    </div>

    {{-- Result Overlay (Kept the same premium glass look) --}}
    <div id="result-overlay" class="result-overlay" style="display: none;">
        <div class="result-bg-dynamic"></div>
        <div class="result-card-glass">
            <button onclick="closeResult()" class="btn-close-premium">
                <i class="ri-close-line"></i>
            </button>
            <img id="result-album-art" src="" class="album-art-premium">
            <h1 id="result-title" class="result-title">Song Title</h1>
            <h3 id="result-artist" class="result-artist">Artist Name</h3>
            <div class="d-flex flex-column gap-2 w-100">
                <a id="btn-spotify" href="#" target="_blank" class="btn-action btn-spotify">
                    <i class="ri-spotify-fill fs-5"></i> Play on Spotify
                </a>
                <a id="btn-youtube" href="#" target="_blank" class="btn-action btn-youtube">
                    <i class="ri-youtube-fill fs-5"></i> Watch Video
                </a>
            </div>
        </div>
    </div>

@endsection
