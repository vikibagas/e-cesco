<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import SiteLayout from '../Layouts/SiteLayout.vue'

defineOptions({ layout: SiteLayout })
defineProps({ content: { type: Object, required: true } })

const page = usePage()
</script>

<template>
    <Head :title="content.title">
        <meta head-key="description" name="description" :content="content.description">
    </Head>

    <main>
        <section class="about-hero">
            <div class="container about-hero-inner">
                <p class="eyebrow"><span></span>{{ content.eyebrow }}</p>
                <h1>{{ content.hero_title }}</h1>
                <p>{{ content.hero_description }}</p>
            </div>
        </section>

        <section class="company-intro section-space">
            <div class="container intro-grid">
                <div class="intro-image"><img :src="'/images/trucking-service.png'" :alt="content.image_alt" loading="lazy"><span class="year-badge"><strong>2006</strong><small>{{ page.props.locale === 'id' ? 'Berdiri di Jakarta' : 'Founded in Jakarta' }}</small></span></div>
                <div class="intro-copy">
                    <p class="section-kicker">{{ content.intro_kicker }}</p>
                    <h2>{{ content.intro_title }}</h2>
                    <p v-for="paragraph in content.intro_paragraphs" :key="paragraph">{{ paragraph }}</p>
                </div>
            </div>
        </section>

        <section class="culture-section section-space">
            <div class="container">
                <div class="section-heading about-section-heading"><div><p class="section-kicker">{{ content.culture_kicker }}</p><h2>{{ content.culture_title }}</h2></div></div>
                <div class="purpose-grid">
                    <article class="purpose-card vision-card"><div class="purpose-icon" aria-hidden="true">◎</div><p class="purpose-label">{{ content.vision_label }}</p><h3>{{ content.vision }}</h3></article>
                    <article class="purpose-card mission-card"><div class="purpose-icon" aria-hidden="true">↗</div><p class="purpose-label">{{ content.mission_label }}</p><h3>{{ content.mission_intro }}</h3><ul class="mission-list"><li v-for="item in content.mission_points" :key="item"><span aria-hidden="true">✓</span>{{ item }}</li></ul></article>
                </div>
                <div class="spirit-band"><span class="spirit-mark" aria-hidden="true">C</span><span><small>{{ content.spirit_label }}</small><strong>{{ content.spirit }}</strong></span></div>
            </div>
        </section>

        <section class="values-section section-space">
            <div class="container">
                <div class="section-heading about-section-heading"><div><p class="section-kicker">{{ content.values_kicker }}</p><h2>{{ content.values_title }}</h2></div></div>
                <div class="values-grid"><article v-for="(value, index) in content.values" :key="value.title" class="value-card"><span class="value-number">0{{ index + 1 }}</span><h3>{{ value.title }}</h3><p>{{ value.description }}</p></article></div>
                <div class="approach-grid"><article><span class="section-kicker">{{ content.strategy_label }}</span><p>{{ content.strategy }}</p></article><article><span class="section-kicker">{{ content.service_concept_label }}</span><p>{{ content.service_concept }}</p></article></div>
            </div>
        </section>

        <section class="future-section section-space">
            <div class="container future-grid">
                <div><p class="section-kicker">{{ content.future_kicker }}</p><h2>{{ content.future_title }}</h2></div>
                <ol class="future-list"><li v-for="(item, index) in content.future_items" :key="item"><span>0{{ index + 1 }}</span><p>{{ item }}</p></li></ol>
            </div>
        </section>

        <section class="about-portal">
            <div class="container about-portal-inner"><p>{{ content.portal_prompt }}</p><a class="portal-button" :href="page.props.site.portalUrl">{{ page.props.navigation.portal_button }}<span aria-hidden="true">↗</span></a></div>
        </section>
    </main>
</template>
