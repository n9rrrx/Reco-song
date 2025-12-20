<header id="main_header" class="fixed-top w-100 header-glass" style="z-index: 50;">
    <div class="container-fluid px-4 px-lg-5">
        <nav class="navbar navbar-expand-lg py-2">

            {{-- LOGO AREA (F1 Style - Bold and Prominent) --}}
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center mx-auto mx-lg-0">

                {{-- F1-STYLE LOGO (Extra Large with Racing Aesthetic) --}}
                <img src="{{ asset('assets/images/logos/logo.svg') }}" alt="Logo"
                     style="height: 140px; width: auto; filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.3)); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='scale(1.05) translateY(-2px)'; this.style.filter='drop-shadow(0 0 30px rgba(255, 255, 255, 0.5))';"
                     onmouseout="this.style.transform='scale(1)'; this.style.filter='drop-shadow(0 0 20px rgba(255, 255, 255, 0.3))';">

            </a>

        </nav>
    </div>
</header>
