<header id="main_header" class="fixed-top w-100"
        style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">
    <div class="container">
        <nav class="navbar navbar-expand-lg py-3">

            {{-- 1. LOGO (Larger & Glowing) --}}
            <a href="{{ url('/') }}" class="brand external d-flex align-items-center text-decoration-none" style="gap: 15px;">
                {{-- Logo Image --}}
                <img src="{{ asset('assets/images/logos/logo.svg') }}" alt="Logo"
                     style="height: 50px; width: auto; filter: drop-shadow(0 0 8px rgba(99, 102, 241, 0.4));">

                {{-- Brand Name (Fixed wrapping issue) --}}

            </a>

            {{-- 2. BUTTONS (Right Side) --}}
            <div class="d-flex align-items-center navbar-ex order-lg-3">
                <a class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-lg hover-scale" href="{{ route('register') }}"
                   style="background: linear-gradient(135deg, #6366f1, #ec4899); border: none;">
                    Try it free
                </a>

                {{-- Mobile Toggler (White) --}}
                <button class="navbar-toggler ms-3 border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ri-menu-3-fill fs-1 text-white"></i>
                </button>
            </div>

            {{-- 3. NAVIGATION LINKS (Centered) --}}
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto fw-semibold gap-lg-4 mt-3 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 hover-glow" href="{{ url('/') }}">Discover</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 hover-glow" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 hover-glow" href="{{ route('about') }}">About us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 hover-glow" href="{{ route('blog') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 hover-glow" href="{{ route('contact') }}">Contact us</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
