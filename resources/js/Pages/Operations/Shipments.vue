<script setup>
import { computed, ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../Layouts/OperationsSidebar.vue'

const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const search = ref('')
const filter = ref('all')
const toast = ref('')

const text = computed(() => isId.value ? {
    dashboard: 'Dashboard',
    shipments: 'Pengiriman',
    tracking: 'Pelacakan',
    warehouse: 'Gudang & Manifest',
    rates: 'Tarif Pengiriman',
    reports: 'Laporan',
    settings: 'Pengaturan',
    operations: 'Operasional',
    overview: 'Ringkasan',
    search: 'Cari nomor resi, pelanggan, kota…',
    title: 'Pengiriman',
    subtitle: 'Kelola dan pantau seluruh pengiriman Cesco.',
    create: 'Buat pengiriman',
    demo: 'DATA DEMO',
    note: 'Pratinjau menggunakan data contoh.',
    total: 'Total pengiriman',
    processing: 'Diproses',
    transit: 'Dalam perjalanan',
    delivered: 'Terkirim',
    attention: 'Perlu perhatian',
    last30: '30 hari terakhir',
    all: 'Semua',
    awaiting: 'Menunggu pickup',
    outForDelivery: 'Sedang diantar',
    trackingNo: 'Nomor resi',
    customer: 'Pelanggan',
    origin: 'Asal',
    destination: 'Tujuan',
    service: 'Layanan',
    status: 'Status',
    updated: 'Pembaruan terakhir',
    eta: 'Estimasi tiba',
    filter: 'Filter',
    export: 'Ekspor',
    results: 'hasil',
    noResults: 'Tidak ada pengiriman yang cocok.',
    featureSoon: 'Aksi ini akan tersedia pada sistem operasional.',
    createSoon: 'Form pengiriman akan tersedia pada modul Buat Pengiriman.',
    online: 'Semua sistem berjalan normal',
    admin: 'Administrator',
    today: 'Hari ini',
    onTime: 'Tepat waktu',
    late: 'Terlambat',
    attentionStatus: 'Perlu perhatian',
    ready: 'Siap pickup',
    inTransit: 'Dalam perjalanan',
    deliveredStatus: 'Terkirim',
    page: 'Halaman',
    shown: 'Menampilkan',
    ofShipments: 'Dari 1.284 pengiriman',
    listDescription: 'Daftar pengiriman dan status terbaru.',
} : {
    dashboard: 'Dashboard',
    shipments: 'Shipments',
    tracking: 'Tracking',
    warehouse: 'Warehouse & Manifest',
    rates: 'Shipping rates',
    reports: 'Reports',
    settings: 'Settings',
    operations: 'Operations',
    overview: 'Overview',
    search: 'Search tracking, customer, city…',
    title: 'Shipments',
    subtitle: 'Manage and track every Cesco shipment.',
    create: 'Create shipment',
    demo: 'DEMO DATA',
    note: 'Dashboard preview uses sample data.',
    total: 'Total shipments',
    processing: 'Processing',
    transit: 'In transit',
    delivered: 'Delivered',
    attention: 'Needs attention',
    last30: 'Last 30 days',
    all: 'All shipments',
    awaiting: 'Awaiting pickup',
    outForDelivery: 'Out for delivery',
    trackingNo: 'Tracking number',
    customer: 'Customer',
    origin: 'Origin',
    destination: 'Destination',
    service: 'Service',
    status: 'Status',
    updated: 'Last update',
    eta: 'Estimated arrival',
    filter: 'Filter',
    export: 'Export',
    results: 'results',
    noResults: 'No matching shipments found.',
    featureSoon: 'This action will be available in the operations system.',
    createSoon: 'The shipment form will be available in the Create Shipment module.',
    online: 'All systems operational',
    admin: 'Administrator',
    today: 'Today',
    onTime: 'On time',
    late: 'Delayed',
    attentionStatus: 'Needs attention',
    ready: 'Ready for pickup',
    inTransit: 'In transit',
    deliveredStatus: 'Delivered',
    page: 'Page',
    shown: 'Showing',
    ofShipments: 'of 1,284 shipments',
    listDescription: 'Shipment list and latest status updates.',
})

const rows = [
    { code: 'CSL-261104-0842', customer: 'PT Sumber Makmur', origin: 'Jakarta', destination: 'Surabaya', service: 'Trucking', status: 'transit', updated: '04 Nov, 10:18', eta: '04 Nov, 16:30' },
    { code: 'CSL-261104-0838', customer: 'Nusantara Teknologi', origin: 'Jakarta', destination: 'Medan', service: 'Air Freight', status: 'pickup', updated: '04 Nov, 09:52', eta: '04 Nov, 18:00' },
    { code: 'CSL-261104-0831', customer: 'Global Niaga Persada', origin: 'Tangerang', destination: 'Semarang', service: 'Trucking', status: 'processing', updated: '04 Nov, 09:34', eta: '05 Nov, 12:00' },
    { code: 'CSL-261104-0827', customer: 'Mitra Niaga Abadi', origin: 'Bandung', destination: 'Jakarta', service: 'Trucking', status: 'delivered', updated: '04 Nov, 08:42', eta: '04 Nov, 11:42' },
    { code: 'CSL-261104-0819', customer: 'Prima Karya Industri', origin: 'Surabaya', destination: 'Makassar', service: 'Sea Freight', status: 'attention', updated: '04 Nov, 08:16', eta: 'Konfirmasi alamat' },
    { code: 'CSL-261104-0805', customer: 'CV Berkah Jaya', origin: 'Jakarta', destination: 'Semarang', service: 'Trucking', status: 'transit', updated: '04 Nov, 07:58', eta: '05 Nov, 09:15' },
    { code: 'CSL-261103-0792', customer: 'Sentra Elektronik', origin: 'Jakarta', destination: 'Yogyakarta', service: 'Air Freight', status: 'delivered', updated: '03 Nov, 17:21', eta: '03 Nov, 16:45' },
    { code: 'CSL-261103-0774', customer: 'Duta Pangan Sejahtera', origin: 'Bogor', destination: 'Surabaya', service: 'Trucking', status: 'transit', updated: '03 Nov, 15:08', eta: '05 Nov, 14:00' },
]

const tabs = computed(() => [
    { key: 'all', label: text.value.all, count: 1284 },
    { key: 'processing', label: text.value.processing, count: 34 },
    { key: 'transit', label: text.value.transit, count: 286 },
    { key: 'delivered', label: text.value.delivered, count: 913 },
    { key: 'attention', label: text.value.attention, count: 12 },
])

const filteredRows = computed(() => rows.filter((row) => {
    const matchesFilter = filter.value === 'all' || row.status === filter.value
    const query = search.value.trim().toLowerCase()
    const matchesSearch = !query || (row.code + ' ' + row.customer + ' ' + row.origin + ' ' + row.destination).toLowerCase().includes(query)

    return matchesFilter && matchesSearch
}))

function statusLabel(status) {
    return {
        transit: text.value.inTransit,
        pickup: text.value.ready,
        processing: text.value.processing,
        delivered: text.value.deliveredStatus,
        attention: text.value.attentionStatus,
    }[status] || status
}

function notify(message) {
    toast.value = message
    window.setTimeout(() => {
        toast.value = ''
    }, 2800)
}

function openFeature(name) {
    notify(name + ': ' + text.value.featureSoon)
}
</script>

<template>
    <Head :title="'e-Cesco — ' + text.shipments" />

    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>

        <OperationsSidebar active-page="shipments" :open="sidebarOpen" @close="sidebarOpen = false" @feature="openFeature" />

        <main class="ops-main">
            <header class="ops-topbar">
                <button class="ops-mobile-toggle" type="button" aria-label="Open navigation" @click="sidebarOpen = !sidebarOpen">☰</button>
                <div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><strong>{{ text.shipments }}</strong></div>
                <div class="ops-top-actions"><label class="ops-search"><span aria-hidden="true">⌕</span><input v-model="search" :placeholder="text.search"></label><div class="ops-language" :aria-label="isId ? 'Pilih bahasa' : 'Choose language'"><Link :href="page.props.site.localeUrls.id" :class="{ active: isId }" :aria-current="isId ? 'page' : undefined" lang="id" hreflang="id" preserve-scroll>ID</Link><span></span><Link :href="page.props.site.localeUrls.en" :class="{ active: !isId }" :aria-current="!isId ? 'page' : undefined" lang="en" hreflang="en" preserve-scroll>EN</Link></div><button class="ops-icon-button notification-button" type="button" :aria-label="isId ? 'Notifikasi' : 'Notifications'" @click="notify(isId ? 'Tidak ada notifikasi baru.' : 'No new notifications.')"><span>♧</span><i></i></button><OperationsAccountMenu /></div>
            </header>

            <div class="ops-content">
                <div class="ops-demo-note"><span class="ops-demo-dot"></span>{{ text.note }}<strong>{{ text.demo }}</strong></div>
                <section class="ops-heading ship-heading"><div><p class="ops-date">{{ text.last30 }}</p><h1>{{ text.title }}</h1><p>{{ text.subtitle }}</p></div><button class="ops-primary-button" type="button" @click="notify(text.createSoon)"><span>＋</span>{{ text.create }}</button></section>

                <section class="ship-summary">
                    <article><span class="ops-stat-icon green">▤</span><div><small>{{ text.total }}</small><strong>1,284</strong></div><span class="ship-summary-note positive">+12.8%</span></article>
                    <article><span class="ops-stat-icon amber">◷</span><div><small>{{ text.processing }}</small><strong>34</strong></div><span class="ship-summary-note">{{ text.last30 }}</span></article>
                    <article><span class="ops-stat-icon blue">⌖</span><div><small>{{ text.transit }}</small><strong>286</strong></div><span class="ship-summary-note positive">+8.4%</span></article>
                    <article><span class="ops-stat-icon violet">✓</span><div><small>{{ text.delivered }}</small><strong>913</strong></div><span class="ship-summary-note positive">96.8% {{ text.onTime }}</span></article>
                </section>

                <section class="ops-panel ship-list-panel">
                    <div class="ship-list-heading"><div><h2>{{ text.title }}</h2><p>{{ text.listDescription }}</p></div><div class="ship-list-actions"><button class="ops-filter-button" type="button" @click="openFeature(text.filter)"><span>☷</span>{{ text.filter }}</button><button class="ops-filter-button" type="button" @click="notify(isId ? 'Ekspor contoh tersedia pada sistem operasional.' : 'Sample export will be available in the operations system.')"><span>↓</span>{{ text.export }}</button></div></div>
                    <div class="ship-tabs"><button v-for="tab in tabs" :key="tab.key" type="button" :class="{ active: filter === tab.key }" @click="filter = tab.key">{{ tab.label }}<span>{{ tab.count }}</span></button></div>
                    <div class="ship-table-wrap"><table class="ops-table ship-table"><thead><tr><th>{{ text.trackingNo }}</th><th>{{ text.customer }}</th><th>{{ text.origin }}</th><th></th><th>{{ text.destination }}</th><th>{{ text.service }}</th><th>{{ text.status }}</th><th>{{ text.updated }}</th><th>{{ text.eta }}</th><th></th></tr></thead><tbody>
                        <tr v-for="row in filteredRows" :key="row.code"><td><strong class="ops-tracking">{{ row.code }}</strong></td><td><span class="ops-customer">{{ row.customer }}</span></td><td>{{ row.origin }}</td><td class="ship-route-arrow">→</td><td>{{ row.destination }}</td><td><span class="ops-service">{{ row.service }}</span></td><td><span class="ops-status-pill" :class="row.status">{{ statusLabel(row.status) }}</span></td><td>{{ row.updated }}</td><td><span class="ops-eta">{{ row.eta }}</span></td><td><button class="ops-row-more" type="button" :aria-label="text.trackingNo + ' ' + row.code" @click="openFeature(row.code)">···</button></td></tr>
                        <tr v-if="filteredRows.length === 0"><td colspan="10" class="ops-empty">{{ text.noResults }}</td></tr>
                    </tbody></table></div>
                    <div class="ship-table-footer"><span>{{ text.shown }} <strong>{{ filteredRows.length }}</strong> {{ text.results }}</span><div class="ship-pagination"><button type="button" disabled>‹</button><button class="active" type="button">1</button><button type="button" @click="openFeature(text.page + ' 2')">2</button><button type="button" @click="openFeature(text.page + ' 3')">3</button><span>…</span><button type="button" @click="openFeature(text.page + ' 12')">12</button><button type="button" @click="openFeature(text.page + ' 2')">›</button></div><span>{{ text.ofShipments }}</span></div>
                </section>

                <footer class="ops-footer"><span>© {{ new Date().getFullYear() }} PT Cesco Logistik Indonesia</span><span>{{ text.demo }} · e-Cesco</span></footer>
            </div>
        </main>

        <Transition name="ops-toast"><div v-if="toast" class="ops-toast-message" role="status">{{ toast }}</div></Transition>
    </div>
</template>
