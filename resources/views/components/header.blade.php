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

            {{-- Right Side Actions --}}
            <div class="d-flex align-items-center gap-3 ms-auto">
                {{-- Theme Toggle Button (visible on all screens) --}}
                <button id="theme-toggle" class="theme-toggle-btn" title="Toggle theme">
                    <i class="ri-moon-line theme-icon-dark"></i>
                    <i class="ri-sun-line theme-icon-light"></i>
                </button>
                <a href="https://github.com/n9rrrx" target="_blank" class="header-link d-none d-lg-grid">
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
        color: var(--text-primary, #fff);
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .logo-accent {
        color: var(--accent-color, #e11d48);
        font-weight: 800;
    }

    .navbar-brand:hover .logo-text {
        text-shadow: 0 0 30px var(--accent-glow, rgba(225, 29, 72, 0.5));
    }

    .header-link {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: var(--btn-bg, rgba(255, 255, 255, 0.05));
        border: 1px solid var(--btn-border, rgba(255, 255, 255, 0.08));
        color: var(--text-muted, rgba(255, 255, 255, 0.5));
        font-size: 18px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .header-link:hover {
        background: var(--btn-hover-bg, rgba(255, 255, 255, 0.1));
        border-color: var(--accent-glow, rgba(225, 29, 72, 0.4));
        color: var(--accent-color, #e11d48);
        transform: translateY(-2px);
    }

    /* Theme Toggle Button */
    .theme-toggle-btn {
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-color, #e11d48), var(--accent-secondary, #ff6b9d));
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        box-shadow: 0 4px 15px var(--accent-glow, rgba(225, 29, 72, 0.4));
    }

    .theme-toggle-btn:hover {
        transform: scale(1.1) rotate(15deg);
        box-shadow: 0 6px 25px var(--accent-glow, rgba(225, 29, 72, 0.6));
    }

    .theme-toggle-btn i {
        position: absolute;
        font-size: 20px;
        color: #fff;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .theme-icon-dark {
        opacity: 1;
        transform: rotate(0deg) scale(1);
    }

    .theme-icon-light {
        opacity: 0;
        transform: rotate(-90deg) scale(0);
    }

    /* Light theme icon states */
    [data-theme="light"] .theme-icon-dark {
        opacity: 0;
        transform: rotate(90deg) scale(0);
    }

    [data-theme="light"] .theme-icon-light {
        opacity: 1;
        transform: rotate(0deg) scale(1);
    }
</style>
