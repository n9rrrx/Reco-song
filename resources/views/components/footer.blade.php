{{-- FOOTER --}}
<footer class="py-5 text-center" style="background:#000;">
    <div class="container">

        {{-- Divider --}}
        <div class="mb-4 mx-auto"
             style="width:80px;height:1px;
             background:linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);">
        </div>

        {{-- Social Icons --}}
        <div class="d-flex justify-content-center gap-4 mb-4">
            <a href="https://github.com/n9rrrx" target="_blank"
               class="footer-icon" title="GitHub">
                <i class="ri-github-fill"></i>
            </a>

            <a href="https://www.linkedin.com/in/nasr-nsr-472742383/" target="_blank"
               class="footer-icon" title="LinkedIn">
                <i class="ri-linkedin-box-fill"></i>
            </a>

            <a href="https://www.instagram.com/n9rrrx" target="_blank"
               class="footer-icon" title="Instagram">
                <i class="ri-instagram-line"></i>
            </a>
        </div>

        {{-- Copyright --}}
        <p class="text-white-50 small mb-0" style="opacity:.6;">
            © {{ date('Y') }} <span class="text-white">Reco-song</span> · Crafted by nsr
        </p>
    </div>
</footer>

{{-- Footer Styles --}}
<style>
    .footer-icon {
        color: rgba(255,255,255,.5);
        font-size: 1.6rem;
        transition: all .3s ease;
    }

    .footer-icon:hover {
        color: #fff;
        transform: translateY(-3px) scale(1.1);
        text-shadow: 0 0 12px rgba(255,255,255,.35);
    }
</style>
