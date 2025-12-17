<header id="main_header" class="fixed-top w-100 header-glass" style="z-index: 50;">
    <div class="container">
        <nav class="navbar navbar-expand-lg py-3">

            {{-- LOGO AREA (Centered on mobile, Left on Desktop) --}}
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center gap-4 mx-auto mx-lg-0">

                {{-- 1. LOGO IMAGE ONLY (Massive Size) --}}
                <img src="{{ asset('assets/images/logos/logo.svg') }}" alt="Logo"
                     style="height: 110px; width: auto; transition: transform 0.3s ease;">

            </a>

        </nav>
    </div>
</header>
