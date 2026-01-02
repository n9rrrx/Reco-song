<header id="main_header" class="fixed-top w-100 header-glass" style="z-index: 50;">
    <div class="container-fluid px-4 px-lg-5">
        <nav class="navbar navbar-expand-lg py-3">

            {{-- LOGO AREA - Minimal & Clean --}}
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center mx-auto mx-lg-0">
                {{-- Text Logo for Premium Feel --}}
                <span class="logo-text">
                    <span class="logo-main">RECO</span>
                    <span class="logo-accent">SONG</span>
                </span>
            </a>

            {{-- Right Side Actions (Optional) --}}
            <div class="d-none d-lg-flex align-items-center gap-3 ms-auto">
                <a href="https://github.com/n9rrrx" target="_blank" class="header-link">
                    <i class="ri-github-fill"></i>
                </a>
            </div>

        </nav>
    </div>
</header>

<style>
    .logo-text {
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        font-size: 22px;
        letter-spacing: -0.02em;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .logo-accent {
        color: #e11d48;
        font-weight: 800;
    }

    .navbar-brand:hover .logo-text {
        text-shadow: 0 0 30px rgba(225, 29, 72, 0.5);
    }

    .header-link {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.5);
        font-size: 18px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .header-link:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(225, 29, 72, 0.4);
        color: #e11d48;
        transform: translateY(-2px);
    }
</style>
