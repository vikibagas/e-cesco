<script setup>
import { Head, Link } from '@inertiajs/vue3'
import SiteLayout from '../Layouts/SiteLayout.vue'

defineOptions({ layout: SiteLayout })
defineProps({ content: { type: Object, required: true } })
</script>

<template>
    <Head :title="content.meta_title">
        <meta head-key="description" name="description" :content="content.meta_description">
    </Head>

    <main>
        <section class="hero">
            <div class="container hero-content">
                <p class="eyebrow"><span></span>{{ content.eyebrow }}</p>
                <h1>{{ content.hero_title[0] }}<br>{{ content.hero_title[1] }}</h1>
                <p class="hero-description">{{ content.hero_description }}</p>
                <div class="hero-proof">
                    <span><b aria-hidden="true">✓</b>{{ content.support_line }}</span>
                    <span><b aria-hidden="true">✓</b>{{ content.experience_line }}</span>
                </div>
            </div>
            <div class="hero-orbit hero-orbit-one" aria-hidden="true"></div>
            <div class="hero-orbit hero-orbit-two" aria-hidden="true"></div>
            <div class="hero-label" aria-hidden="true"><span class="pulse"></span> PT CESCO LOGISTIK INDONESIA</div>
        </section>

        <section class="metrics" :aria-label="content.metrics_label">
            <div class="container metrics-grid">
                <article class="metric"><strong>20<span>+</span></strong><span>{{ content.stats_experience }}</span></article>
                <article class="metric"><strong>05</strong><span>{{ content.stats_services }}</span></article>
                <article class="metric"><strong>ID</strong><span>{{ content.stats_network }}</span></article>
                <article class="metric"><strong>JKT</strong><span>{{ content.stats_base }}</span></article>
            </div>
        </section>

        <section id="layanan" class="services section-space">
            <div class="container">
                <div class="section-heading">
                    <div><p class="section-kicker">{{ content.services_kicker }}</p><h2>{{ content.services_title }}</h2></div>
                    <p class="section-intro">{{ content.services_intro }}</p>
                </div>
                <div class="service-grid">
                    <article class="service-card"><div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M5 12h23v21H5zM28 19h8l7 8v6H28z"/><circle cx="15" cy="35" r="4"/><circle cx="36" cy="35" r="4"/><path d="M31 20v7h10"/></svg></div><h3>{{ content.trucking_title }}</h3><p>{{ content.trucking_description }}</p></article>
                    <article class="service-card"><div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="m5 28 18-5V9a4 4 0 0 1 8 0v14l12 5v5l-16-2-2 9-4-1-3-8-13 1z"/></svg></div><h3>{{ content.sea_title }}</h3><p>{{ content.sea_description }}</p></article>
                    <article class="service-card"><div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M5 27 43 9 31 40l-7-10-10 6 3-12z"/><path d="m17 24 14-8"/></svg></div><h3>{{ content.air_title }}</h3><p>{{ content.air_description }}</p></article>
                    <article class="service-card"><div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 43s14-13.3 14-24A14 14 0 1 0 10 19c0 10.7 14 24 14 24Z"/><circle cx="24" cy="19" r="5"/></svg></div><h3>{{ content.courier_title }}</h3><p>{{ content.courier_description }}</p></article>
                    <article class="service-card"><div class="service-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M6 42V10h36v32M3 42h42M12 17h8v8h-8zM28 17h8v8h-8zM12 30h8v8h-8zM28 30h8v12h-8z"/></svg></div><h3>{{ content.warehouse_title }}</h3><p>{{ content.warehouse_description }}</p></article>
                </div>
            </div>
        </section>

        <section id="jaringan" class="network-section section-space">
            <div class="container network-grid">
                <div class="network-graphic" aria-hidden="true"><span class="network-dot dot-one"></span><span class="network-dot dot-two"></span><span class="network-dot dot-three"></span><span class="network-dot dot-four"></span><span class="network-line line-one"></span><span class="network-line line-two"></span><span class="network-line line-three"></span><span class="network-core">C</span></div>
                <div class="network-copy"><p class="section-kicker">{{ content.network_tag }}</p><h2>{{ content.network_title }}</h2><p>{{ content.network_description }}</p><Link class="text-link" href="/about">{{ content.about_kicker }} <span aria-hidden="true">→</span></Link></div>
            </div>
        </section>

        <section class="about section-space">
            <div class="container about-grid">
                <div class="about-image"><img :src="'/images/trucking-service.png'" :alt="content.truck_image_alt" loading="lazy"><div class="image-caption"><span class="caption-mark">C</span><span><strong>Cesco Logistics</strong><small>{{ content.eyebrow }}</small></span></div></div>
                <div class="about-copy"><p class="section-kicker">{{ content.about_kicker }}</p><h2>{{ content.about_title }}</h2><p class="about-description">{{ content.about_description }}</p><ul class="benefits"><li><span aria-hidden="true">✓</span>{{ content.benefit_one }}</li><li><span aria-hidden="true">✓</span>{{ content.benefit_two }}</li><li><span aria-hidden="true">✓</span>{{ content.benefit_three }}</li></ul><Link class="text-link" href="/about">{{ content.about_kicker }} <span aria-hidden="true">→</span></Link></div>
            </div>
        </section>

        <section class="portal-strip">
            <div class="container portal-strip-inner"><div><p class="section-kicker">e-Cesco</p><h2>{{ content.portal_title }}</h2><p>{{ content.portal_description }}</p></div><div class="portal-mark" aria-hidden="true">C<span>›</span></div></div>
        </section>
    </main>
</template>
