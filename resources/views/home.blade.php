@extends('layouts.app')

@section('title', 'RECO SONG | Instant Song Recognition')

@section('body_class', 'page-home')

@section('content')

    {{-- FLOATING PARTICLES --}}
    <div class="particles-container">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <section class="hero-section">
        <div class="hero-shell">
            <div class="main-container">

                {{-- LEFT SIDE - Branding & CTA --}}
                <div class="left-content">

                    {{-- Subtle Top Label --}}

                    <h2 class="tagline">
                        <span class="tagline-dash"></span>
                        <span class="tagline-text">HEAR IT. NAME IT.</span>
                    </h2>



                    {{-- MASSIVE BRAND TITLE --}}
                    <h1 class="brand-title">
                        <span class="title-line">RECO</span>
                        <span class="title-accent">SONG</span>
                    </h1>

                    {{-- Tagline with accent --}}


                    <h2 class="tagline">
                        <span class="tagline-dash"></span>
                        <span class="tagline-text">Instant song recognition.</span>
                    </h2>

                    {{-- Description --}}
                    <p class="description">
                        Identify any song playing around you<br>
                        in seconds. Just tap and listen.
                    </p>


                    {{-- Modern Audio Wave Animation --}}
                    <div class="audio-wave-display">
                        <div class="wave-bars">
                            <span class="wave-bar"></span>
                            <span class="wave-bar"></span>
                            <span class="wave-bar"></span>
                            <span class="wave-bar"></span>
                            <span class="wave-bar"></span>
                            <span class="wave-bar"></span>
                            <span class="wave-bar"></span>
                        </div>
                        <span class="wave-label">Tap the mic to start</span>
                    </div>

                </div>

                {{-- RIGHT SIDE - Phone Mockup with Button --}}
                <div class="right-content">
                    <div class="phone-mockup">
                        {{-- Phone Frame --}}
                        <div class="phone-frame">
                            {{-- Notch --}}
                            <div class="phone-notch"></div>

                            {{-- Screen Content --}}
                            <div class="phone-screen">
                                {{-- Mini Status Bar --}}
                                <div class="screen-header">
                                    <span class="screen-time">12:30</span>
                                    <div class="screen-icons">
                                        <i class="ri-signal-wifi-3-line"></i>
                                        <i class="ri-battery-2-charge-line"></i>
                                    </div>
                                </div>

                                {{-- Current Song Display (shows when identified) --}}
                                <div class="screen-content" id="phone-screen-content">
                                    <div class="screen-visualization">
                                        <canvas id="visualizer"></canvas>
                                    </div>
                                    <div class="screen-song-info">
                                        <span class="screen-label">TAP TO IDENTIFY</span>
                                    </div>
                                </div>

                                {{-- Bottom Action Area --}}
                                <div class="screen-actions">
                                    <button class="screen-btn-secondary">
                                        <i class="ri-history-line"></i>
                                    </button>
                                    <button id="live-listen-btn" onclick="toggleRecording()" class="screen-btn-main">
                                        <div class="btn-pulse"></div>
                                        <div class="btn-inner">
                                            <i class="ri-mic-line"></i>
                                        </div>
                                    </button>
                                    <button class="screen-btn-secondary">
                                        <i class="ri-spotify-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Elements Around Phone --}}
                        <div class="floating-element el-1">
                            <i class="ri-music-2-fill"></i>
                        </div>
                        <div class="floating-element el-2">
                            <i class="ri-headphone-fill"></i>
                        </div>
                        <div class="floating-element el-3">
                            <i class="ri-disc-fill"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- RESULT OVERLAY - Premium Style --}}
    <div id="result-overlay" class="result-overlay" style="display: none;">
        <div class="result-bg-dynamic"></div>

        <div class="result-card">
            <button onclick="closeResult()" class="close-btn">
                <i class="ri-close-line"></i>
            </button>

            <div class="result-header">
                <div class="result-badges">
                    <span id="source-badge" class="badge-source" style="display: none;"></span>
                    <span id="recognition-time" class="badge-time" style="display: none;"></span>
                </div>
            </div>

            <div class="result-artwork">
                <img id="result-album-art" src="" class="album-art" alt="Album artwork">
                <div id="spotify-embed-container" style="display: none;"></div>
            </div>

            <div class="result-info">
                <h1 id="result-title" class="song-title">Song Title</h1>
                <p id="result-artist" class="song-artist">Artist Name</p>
            </div>

            <div class="action-btns">
                <a id="btn-spotify" href="#" target="_blank" class="btn-action btn-spotify">
                    <i class="ri-spotify-fill"></i>
                    <span>Play on Spotify</span>
                </a>
                <a id="btn-youtube" href="#" target="_blank" class="btn-action btn-youtube">
                    <i class="ri-youtube-fill"></i>
                    <span>Watch on YouTube</span>
                </a>
                <button onclick="shareResult()" class="btn-action btn-share">
                    <i class="ri-share-forward-fill"></i>
                    <span>Share Discovery</span>
                </button>
            </div>
        </div>
    </div>

@endsection
