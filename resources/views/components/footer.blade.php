{{-- FOOTER - Minimal & Clean --}}
<footer class="site-footer">
    <div class="container">
        <div class="footer-content">

            {{-- Left Side - Brand --}}
            <div class="footer-brand">
                <span class="footer-logo">RECO<span class="footer-accent">SONG</span></span>
                <span class="footer-tagline">Instant song recognition</span>
            </div>

            {{-- Center - Social Icons --}}
            <div class="footer-social">
                <a href="https://github.com/n9rrrx" target="_blank" class="social-link" title="GitHub">
                    <i class="ri-github-fill"></i>
                </a>
                <a href="https://www.linkedin.com/in/nasr-nsr-472742383/" target="_blank" class="social-link" title="LinkedIn">
                    <i class="ri-linkedin-box-fill"></i>
                </a>
                <a href="https://www.instagram.com/n9rrrx" target="_blank" class="social-link" title="Instagram">
                    <i class="ri-instagram-line"></i>
                </a>
            </div>

            {{-- Right Side - Copyright --}}
            <div class="footer-copyright">
                <span>© {{ date('Y') }} Crafted by <a href="https://github.com/n9rrrx" target="_blank">nsr</a></span>
            </div>

        </div>
    </div>
</footer>

<style>
    .site-footer {
        background: transparent;
        padding: 40px 0;
        border-top: 1px solid var(--border-color, rgba(255, 255, 255, 0.05));
        transition: border-color 0.4s ease;
    }

    .footer-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 24px;
    }

    .footer-brand {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .footer-logo {
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        font-size: 18px;
        color: var(--text-secondary, rgba(255, 255, 255, 0.9));
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: color 0.4s ease;
    }

    .footer-accent {
        color: var(--accent-color, #e11d48);
        font-weight: 800;
    }

    .footer-tagline {
        font-size: 12px;
        color: var(--text-subtle, rgba(255, 255, 255, 0.35));
        padding-left: 16px;
        border-left: 1px solid var(--border-color, rgba(255, 255, 255, 0.1));
        transition: color 0.4s ease, border-color 0.4s ease;
    }

    .footer-social {
        display: flex;
        gap: 12px;
    }

    .social-link {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: var(--btn-bg, rgba(255, 255, 255, 0.03));
        border: 1px solid var(--btn-border, rgba(255, 255, 255, 0.06));
        color: var(--text-subtle, rgba(255, 255, 255, 0.4));
        font-size: 18px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        background: var(--accent-color, #e11d48);
        border-color: var(--accent-color, #e11d48);
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 8px 24px var(--accent-glow, rgba(225, 29, 72, 0.2));
    }

    .footer-copyright {
        font-size: 12px;
        color: var(--text-faint, rgba(255, 255, 255, 0.3));
        transition: color 0.4s ease;
    }

    .footer-copyright a {
        color: var(--text-subtle, rgba(255, 255, 255, 0.5));
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-copyright a:hover {
        color: var(--accent-color, #e11d48);
    }

    @media (max-width: 767.98px) {
        .footer-content {
            flex-direction: column;
            text-align: center;
        }

        .footer-brand {
            flex-direction: column;
            gap: 8px;
        }

        .footer-tagline {
            padding-left: 0;
            border-left: none;
        }
    }
</style>
