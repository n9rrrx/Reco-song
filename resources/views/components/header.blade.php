<header id="main_header">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a href="{{ url('/') }}" class="brand external">
                <img src="{{ asset('assets/images/logos/logo.svg') }}" alt="Logo">
            </a>
            <div class="d-flex align-items-center navbar-ex">
                <a class="btn btn-primary external" href="{{ route('register') }}">Try it free</a>
                <button class="navbar-toggler ms-3 ms-sm-4" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ri-menu-3-fill"></i>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mt-4 mt-lg-0 mx-auto fw-semibold">
                    <li class="nav-item"><a class="nav-link external" href="{{ url('/') }}">Discover</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link external" href="{{ route('about') }}">About us</a></li>
                    <li class="nav-item"><a class="nav-link external" href="{{ route('blog') }}">Blog</a></li>
                    <li class="nav-item"><a class="nav-link external" href="{{ route('contact') }}">Contact us</a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>
