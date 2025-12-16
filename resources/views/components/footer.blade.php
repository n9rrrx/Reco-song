<footer id="main_footer" style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.1);">
    <div class="container">
        <div class="col-xl-6 col-lg-8 col-md-10 mx-auto text-center mt-5">

            {{-- 1. TITLE (Added text-white so it shows up) --}}
            <h3 class="mb-5 text-white">
                <span class="text-primary">Millions of songs</span> that you access with your basic information.
            </h3>

            <div class="app-btn-group">
                <a href="#" class="btn btn-lg btn-outline-light">
                    <div class="btn__wrap"><i class="ri-google-play-fill"></i> <span class="ms-2">Google Play</span></div>
                </a>
                <a href="#" class="btn btn-lg btn-outline-light">
                    <div class="btn__wrap"><i class="ri-app-store-fill"></i> <span class="ms-2">App Store</span></div>
                </a>
            </div>
        </div>

        <div class="last-footer py-4 mt-5 d-flex flex-column flex-md-row justify-content-between align-items-center">

            {{-- 1. COPYRIGHT TEXT (Left Side) --}}
            <span class="text-white-50 mb-3 mb-md-0">
        &copy; {{ date('Y') }} Reco-song by <strong>nsr</strong>. All rights reserved.
    </span>

            {{-- 2. SOCIAL ICONS (Right Side) --}}
            <ul class="social list-inline mb-0">
                <li class="list-inline-item">
                    <a href="#" class="text-white fs-5 px-2 hover-opacity" aria-label="Facebook"><i class="ri-facebook-circle-line"></i></a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="text-white fs-5 px-2 hover-opacity" aria-label="Instagram"><i class="ri-instagram-line"></i></a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="text-white fs-5 px-2 hover-opacity" aria-label="Pinterest"><i class="ri-pinterest-line"></i></a>
                </li>
                <li class="list-inline-item">
                    <a href="#" class="text-white fs-5 px-2 hover-opacity" aria-label="Youtube"><i class="ri-youtube-line"></i></a>
                </li>
            </ul>

        </div>
    </div>

    <style>
        .hover-opacity:hover { opacity: 0.7; }
    </style>
</footer>
