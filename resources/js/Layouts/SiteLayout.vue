<script setup>
import { computed, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const menuOpen = ref(false)
const navigation = computed(() => page.props.navigation)
const site = computed(() => page.props.site)
const locale = computed(() => page.props.locale)
const currentPath = computed(() => page.url.split('?')[0].split('#')[0])

function closeMenu() {
    menuOpen.value = false
}
</script>

<template>
    <div class="site-shell">
        <div class="topline">
            <div class="container topline-inner">
                <span>{{ locale === 'id' ? 'Solusi logistik untuk bisnis Indonesia' : 'Logistics solutions for Indonesian businesses' }}</span>
                <span>{{ locale === 'id' ? 'Didukung tim yang berpengalaman' : 'Supported by an experienced team' }}</span>
            </div>
        </div>

        <header class="site-header">
            <div class="container header-inner">
                <Link class="brand" :href="site.homeUrl" @click="closeMenu" :aria-label="locale === 'id' ? 'Beranda Cesco Logistics' : 'Cesco Logistics home'">
                    <img :src="'/images/cesco-logo.png'" :alt="locale === 'id' ? 'Logo Cesco Logistics' : 'Cesco Logistics logo'">
                </Link>

                <button class="menu-toggle" type="button" :aria-expanded="menuOpen" aria-controls="main-navigation" @click="menuOpen = !menuOpen">
                    <span></span><span></span><span></span>
                    <span class="sr-only">{{ locale === 'id' ? 'Buka menu' : 'Open menu' }}</span>
                </button>

                <nav id="main-navigation" class="main-menu" :class="{ 'is-open': menuOpen }" :aria-label="locale === 'id' ? 'Navigasi utama' : 'Main navigation'">
                    <Link :href="site.homeUrl" :class="{ active: currentPath === '/' }" @click="closeMenu">{{ navigation.home }}</Link>
                    <Link :href="site.servicesUrl" @click="closeMenu">{{ navigation.services }}</Link>
                    <Link :href="site.aboutUrl" :class="{ active: currentPath === '/about' }" @click="closeMenu">{{ navigation.about }}</Link>
                    <Link :href="site.networkUrl" @click="closeMenu">{{ navigation.network }}</Link>
                    <Link :href="site.contactUrl" @click="closeMenu">{{ navigation.contact }}</Link>
                    <Link class="login-link" :href="site.loginUrl" @click="closeMenu">{{ navigation.login }}</Link>
                </nav>

                <div class="header-actions">
                    <div class="language-switch" :aria-label="navigation.language">
                        <Link :href="site.localeUrls.id" :class="{ active: locale === 'id' }" hreflang="id" lang="id" :aria-current="locale === 'id' ? 'page' : undefined" preserve-scroll>ID</Link>
                        <span aria-hidden="true"></span>
                        <Link :href="site.localeUrls.en" :class="{ active: locale === 'en' }" hreflang="en" lang="en" :aria-current="locale === 'en' ? 'page' : undefined" preserve-scroll>EN</Link>
                    </div>
                    <a class="portal-button" :href="site.portalUrl">{{ navigation.portal_button }}<span aria-hidden="true">↗</span></a>
                </div>
            </div>
        </header>

        <slot />

        <footer id="kontak" class="site-footer">
            <div class="container footer-main">
                <div class="footer-brand">
                    <img :src="'/images/cesco-logo.png'" :alt="locale === 'id' ? 'Logo Cesco Logistics' : 'Cesco Logistics logo'">
                    <p>{{ locale === 'id' ? 'Mitra logistik dan rantai pasok untuk bisnis Indonesia.' : 'A logistics and supply chain partner for Indonesian businesses.' }}</p>
                </div>
                <div class="footer-contact">
                    <strong>{{ navigation.contact }}</strong>
                    <span>(021) 7884 0825</span>
                    <span>customercare@cesco-logistics.com</span>
                    <span>{{ locale === 'id' ? 'Cilandak, Jakarta Selatan' : 'Cilandak, South Jakarta' }}</span>
                </div>
            </div>
            <div class="container footer-bottom">
                <span>© {{ new Date().getFullYear() }} PT Cesco Logistik Indonesia. {{ locale === 'id' ? 'Hak cipta dilindungi.' : 'All rights reserved.' }}</span>
                <span>CESCO LOGISTICS</span>
            </div>
        </footer>
    </div>
</template>
