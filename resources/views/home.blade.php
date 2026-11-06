<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#16763d">
    <meta name="description" content="{{ __('home.meta_description') }}">
    <title>{{ __('home.meta_title') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <div class="topline">
        <div class="container topline-inner">
            <span>{{ __('home.support_line') }}</span>
            <span>{{ __('home.experience_line') }}</span>
        </div>
    </div>

    <header class="site-header">
        <div class="container header-inner">
            <div class="brand" aria-label="Cesco Logistics">
                <img src="{{ asset('images/cesco-logo.png') }}" alt="{{ __('home.brand_alt') }}">
            </div>

            <div class="header-actions">
                <div class="language-switch" aria-label="{{ __('home.language_label') }}">
                    <a href="{{ route('home', ['lang' => 'id']) }}" @class(['active' => $locale === 'id']) hreflang="id" lang="id">ID</a>
                    <span aria-hidden="true"></span>
                    <a href="{{ route('home', ['lang' => 'en']) }}" @class(['active' => $locale === 'en']) hreflang="en" lang="en">EN</a>
                </div>
                <a class="portal-button" href="{{ config('services.cesco_portal_url') }}">
                    {{ __('home.portal_button') }}
                    <span aria-hidden="true">↗</span>
                </a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-content">
                <p class="eyebrow"><span></span>{{ __('home.eyebrow') }}</p>
                <h1>{{ __('home.hero_title') }}</h1>
                <p class="hero-description">{{ __('home.hero_description') }}</p>
                <div class="hero-proof">
                    <span><b aria-hidden="true">✓</b>{{ __('home.support_line') }}</span>
                    <span><b aria-hidden="true">✓</b>{{ __('home.experience_line') }}</span>
                </div>
            </div>
            <div class="hero-orbit hero-orbit-one" aria-hidden="true"></div>
            <div class="hero-orbit hero-orbit-two" aria-hidden="true"></div>
            <div class="hero-label" aria-hidden="true"><span class="pulse"></span> PT CESCO LOGISTIK INDONESIA</div>
        </section>

        <section class="metrics" aria-label="{{ __('home.metrics_label') }}">
            <div class="container metrics-grid">
                <article class="metric"><strong>20<span>+</span></strong><span>{{ __('home.stats_experience') }}</span></article>
                <article class="metric"><strong>05</strong><span>{{ __('home.stats_services') }}</span></article>
                <article class="metric"><strong>{{ $locale === 'id' ? 'ID' : 'ID' }}</strong><span>{{ __('home.stats_network') }}</span></article>
                <article class="metric"><strong>JKT</strong><span>{{ __('home.stats_base') }}</span></article>
            </div>
        </section>

        <section class="services section-space">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">{{ __('home.services_kicker') }}</p>
                        <h2>{{ __('home.services_title') }}</h2>
                    </div>
                    <p class="section-intro">{{ __('home.services_intro') }}</p>
                </div>

                <div class="service-grid">
                    <article class="service-card">
                        <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M5 12h23v21H5zM28 19h8l7 8v6H28z"/><circle cx="15" cy="35" r="4"/><circle cx="36" cy="35" r="4"/><path d="M31 20v7h10"/></svg></div>
                        <h3>{{ __('home.trucking_title') }}</h3><p>{{ __('home.trucking_description') }}</p>
                    </article>
                    <article class="service-card">
                        <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="m5 28 18-5V9a4 4 0 0 1 8 0v14l12 5v5l-16-2-2 9-4-1-3-8-13 1z"/></svg></div>
                        <h3>{{ __('home.sea_title') }}</h3><p>{{ __('home.sea_description') }}</p>
                    </article>
                    <article class="service-card">
                        <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M5 27 43 9 31 40l-7-10-10 6 3-12z"/><path d="m17 24 14-8"/></svg></div>
                        <h3>{{ __('home.air_title') }}</h3><p>{{ __('home.air_description') }}</p>
                    </article>
                    <article class="service-card">
                        <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 43s14-13.3 14-24A14 14 0 1 0 10 19c0 10.7 14 24 14 24Z"/><circle cx="24" cy="19" r="5"/></svg></div>
                        <h3>{{ __('home.courier_title') }}</h3><p>{{ __('home.courier_description') }}</p>
                    </article>
                    <article class="service-card">
                        <div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M6 42V10h36v32M3 42h42M12 17h8v8h-8zM28 17h8v8h-8zM12 30h8v8h-8zM28 30h8v12h-8z"/></svg></div>
                        <h3>{{ __('home.warehouse_title') }}</h3><p>{{ __('home.warehouse_description') }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="about section-space">
            <div class="container about-grid">
                <div class="about-image">
                    <img src="{{ asset('images/trucking-service.png') }}" alt="{{ __('home.truck_image_alt') }}" loading="lazy">
                    <div class="image-caption"><span class="caption-mark">C</span><span><strong>Cesco Logistics</strong><small>{{ __('home.eyebrow') }}</small></span></div>
                </div>
                <div class="about-copy">
                    <p class="section-kicker">{{ __('home.about_kicker') }}</p>
                    <h2>{{ __('home.about_title') }}</h2>
                    <p class="about-description">{{ __('home.about_description') }}</p>
                    <ul class="benefits">
                        <li><span aria-hidden="true">✓</span>{{ __('home.benefit_one') }}</li>
                        <li><span aria-hidden="true">✓</span>{{ __('home.benefit_two') }}</li>
                        <li><span aria-hidden="true">✓</span>{{ __('home.benefit_three') }}</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="portal-strip">
            <div class="container portal-strip-inner">
                <div><p class="section-kicker">e-Cesco</p><h2>{{ __('home.portal_title') }}</h2><p>{{ __('home.portal_description') }}</p></div>
            <div class="portal-mark" aria-hidden="true">C<span>›</span></div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-main">
            <div class="footer-brand"><img src="{{ asset('images/cesco-logo.png') }}" alt="{{ __('home.brand_alt') }}"><p>{{ __('home.footer_description') }}</p></div>
            <div class="footer-contact"><strong>{{ __('home.contact_title') }}</strong><span>(021) 7884 0825</span><span>customercare@cesco-logistics.com</span><span>{{ __('home.address') }}</span></div>
        </div>
        <div class="container footer-bottom"><span>© {{ now()->year }} {{ __('home.copyright') }}</span><span>CESCO LOGISTICS</span></div>
    </footer>
</body>
</html>
