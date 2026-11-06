<script setup>
import { computed, ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../Layouts/OperationsSidebar.vue'

const page = usePage()
const authenticatedUser = computed(() => page.props.auth?.user)
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const search = ref('')
const selectedFilter = ref('all')
const selectedPeriod = ref('week')
const toast = ref('')

const text = computed(() => isId.value ? {
    dashboard: 'Dashboard',
    shipments: 'Pengiriman',
    tracking: 'Pelacakan',
    warehouse: 'Gudang & Manifest',
    rates: 'Tarif Pengiriman',
    reports: 'Laporan',
    settings: 'Pengaturan',
    search: 'Cari resi, pelanggan, tujuan…',
    greeting: 'Selamat pagi, ' + (authenticatedUser.value?.name || 'Pengguna Cesco'),
    subtitle: 'Berikut ringkasan operasional Cesco hari ini.',
    demo: 'DATA DEMO',
    demoNote: 'Pratinjau menggunakan data contoh.',
    total: 'Total pengiriman',
    transit: 'Dalam perjalanan',
    deliveredToday: 'Terkirim hari ini',
    attention: 'Perlu perhatian',
    lastWeek: 'vs. minggu lalu',
    trend: 'Tren pengiriman',
    volume: 'Volume pengiriman',
    week: '7 hari',
    month: '30 hari',
    year: 'Tahun ini',
    status: 'Status pengiriman',
    activeShipments: 'Ringkasan pengiriman aktif',
    pickup: 'Menunggu pickup',
    outForDelivery: 'Sedang diantar',
    delivered: 'Terkirim',
    latest: 'Pengiriman terbaru',
    latestDescription: 'Pantau status pengiriman yang sedang berjalan.',
    seeAll: 'Lihat semua',
    all: 'Semua',
    trackingNo: 'Nomor resi',
    customer: 'Pelanggan',
    route: 'Rute',
    service: 'Layanan',
    eta: 'Estimasi tiba',
    actions: 'Aksi cepat',
    createShipment: 'Buat pengiriman',
    manifest: 'Buat manifest',
    addCustomer: 'Tambah pelanggan',
    activity: 'Aktivitas terbaru',
    activityDescription: 'Pembaruan dari tim operasional',
    online: 'Semua sistem berjalan normal',
    admin: authenticatedUser.value?.role || 'Pengguna',
    onTime: 'Sesuai SLA',
    noResults: 'Tidak ada pengiriman yang cocok.',
    featureSoon: 'Bagian ini akan tersedia pada sistem operasional.',
    shipmentSoon: 'Form pengiriman akan tersedia pada modul Buat Pengiriman.',
    readyPickup: 'Siap pickup',
    noNotifications: 'Tidak ada notifikasi baru.',
    filter: 'Filter',
    today: 'Hari ini',
} : {
    dashboard: 'Dashboard',
    shipments: 'Shipments',
    tracking: 'Tracking',
    warehouse: 'Warehouse & Manifest',
    rates: 'Shipping rates',
    reports: 'Reports',
    settings: 'Settings',
    search: 'Search tracking, customer, destination…',
    greeting: 'Good morning, ' + (authenticatedUser.value?.name || 'Cesco user'),
    subtitle: 'Here is Cesco’s operations snapshot for today.',
    demo: 'DEMO DATA',
    demoNote: 'Dashboard preview uses sample data.',
    total: 'Total shipments',
    transit: 'In transit',
    deliveredToday: 'Delivered today',
    attention: 'Needs attention',
    lastWeek: 'vs. last week',
    trend: 'Shipment trend',
    volume: 'Shipment volume',
    week: '7 days',
    month: '30 days',
    year: 'This year',
    status: 'Shipment status',
    activeShipments: 'Overview of active shipments',
    pickup: 'Awaiting pickup',
    outForDelivery: 'Out for delivery',
    delivered: 'Delivered',
    latest: 'Latest shipments',
    latestDescription: 'Track the status of your latest shipments.',
    seeAll: 'View all',
    all: 'All',
    trackingNo: 'Tracking number',
    customer: 'Customer',
    route: 'Route',
    service: 'Service',
    eta: 'Estimated arrival',
    actions: 'Quick actions',
    createShipment: 'Create shipment',
    manifest: 'Create manifest',
    addCustomer: 'Add customer',
    activity: 'Recent activity',
    activityDescription: 'Updates from the operations team',
    online: 'All systems operational',
    admin: authenticatedUser.value?.role || 'User',
    onTime: 'On time',
    noResults: 'No matching shipments found.',
    featureSoon: 'This area will be available in the operations system.',
    shipmentSoon: 'The shipment form will be available in the Create Shipment module.',
    readyPickup: 'Ready for pickup',
    noNotifications: 'No new notifications.',
    filter: 'Filter',
    today: 'Today',
})

const shipments = [
    { tracking: 'CSL-261104-0842', customer: 'PT Sumber Makmur', route: 'Jakarta → Surabaya', service: 'Trucking', status: 'transit', eta: '04 Nov, 16:30' },
    { tracking: 'CSL-261104-0838', customer: 'Nusantara Teknologi', route: 'Jakarta → Medan', service: 'Air Freight', status: 'pickup', eta: '04 Nov, 18:00' },
    { tracking: 'CSL-261104-0827', customer: 'Mitra Niaga Abadi', route: 'Bandung → Jakarta', service: 'Trucking', status: 'delivered', eta: '04 Nov, 11:42' },
    { tracking: 'CSL-261104-0819', customer: 'Prima Karya Industri', route: 'Surabaya → Makassar', service: 'Sea Freight', status: 'attention', eta: 'Perlu konfirmasi' },
    { tracking: 'CSL-261104-0805', customer: 'CV Berkah Jaya', route: 'Jakarta → Semarang', service: 'Trucking', status: 'transit', eta: '05 Nov, 09:15' },
]

const visibleShipments = computed(() => shipments.filter((shipment) => {
    const matchesFilter = selectedFilter.value === 'all' || shipment.status === selectedFilter.value
    const query = search.value.trim().toLowerCase()
    const matchesSearch = !query || (shipment.tracking + ' ' + shipment.customer + ' ' + shipment.route).toLowerCase().includes(query)

    return matchesFilter && matchesSearch
}))

const bars = computed(() => {
    if (selectedPeriod.value === 'month') return [34, 48, 39, 62, 53, 72, 58, 84, 68, 93, 77, 88]
    if (selectedPeriod.value === 'year') return [28, 36, 42, 38, 51, 57, 49, 66, 73, 81, 76, 94]

    return [44, 66, 52, 83, 61, 100, 73]
})

const chartLabels = computed(() => selectedPeriod.value === 'week'
    ? (isId.value ? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])
    : ['1', '3', '5', '7', '9', '11', '13', '15', '17', '19', '21', '23'])

const statusRows = computed(() => [
    { key: 'pickup', label: text.value.pickup, count: 34, width: '12%', tone: 'amber' },
    { key: 'transit', label: text.value.transit, count: 286, width: '68%', tone: 'blue' },
    { key: 'delivery', label: text.value.outForDelivery, count: 51, width: '23%', tone: 'violet' },
    { key: 'delivered', label: text.value.delivered, count: 913, width: '91%', tone: 'green' },
])

const dateLabel = computed(() => new Intl.DateTimeFormat(isId.value ? 'id-ID' : 'en-US', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
}).format(new Date()))

function statusLabel(status) {
    return {
        transit: text.value.transit,
        pickup: text.value.readyPickup,
        delivered: text.value.delivered,
        attention: text.value.attention,
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
    <Head :title="'e-Cesco — ' + text.dashboard" />

    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>

        <OperationsSidebar active-page="dashboard" :open="sidebarOpen" @close="sidebarOpen = false" @feature="openFeature" />

        <main class="ops-main">
            <header class="ops-topbar">
                <button class="ops-mobile-toggle" type="button" aria-label="Open navigation" @click="sidebarOpen = !sidebarOpen">☰</button>
                <div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><strong>{{ text.dashboard }}</strong></div>
                <div class="ops-top-actions">
                    <label class="ops-search"><span aria-hidden="true">⌕</span><input v-model="search" :placeholder="text.search"></label>
                    <div class="ops-language" :aria-label="isId ? 'Pilih bahasa' : 'Choose language'">
                        <Link :href="page.props.site.localeUrls.id" :class="{ active: isId }" :aria-current="isId ? 'page' : undefined" lang="id" hreflang="id" preserve-scroll>ID</Link>
                        <span></span>
                        <Link :href="page.props.site.localeUrls.en" :class="{ active: !isId }" :aria-current="!isId ? 'page' : undefined" lang="en" hreflang="en" preserve-scroll>EN</Link>
                    </div>
                    <button class="ops-icon-button notification-button" type="button" :aria-label="isId ? 'Notifikasi' : 'Notifications'" @click="notify(text.noNotifications)"><span>♧</span><i></i></button>
                    <OperationsAccountMenu />
                </div>
            </header>

            <div class="ops-content">
                <div class="ops-demo-note"><span class="ops-demo-dot"></span>{{ text.demoNote }}<strong>{{ text.demo }}</strong></div>

                <section class="ops-heading">
                    <div><p class="ops-date">{{ dateLabel }}</p><h1>{{ text.greeting }} <span>👋</span></h1><p>{{ text.subtitle }}</p></div>
                    <button class="ops-primary-button" type="button" @click="notify(text.shipmentSoon)"><span>＋</span>{{ text.createShipment }}</button>
                </section>

                <section class="ops-stats-grid" :aria-label="text.dashboard">
                    <article class="ops-stat-card"><div class="ops-stat-top"><span class="ops-stat-icon green">▤</span><span class="ops-trend positive">↗ 12.8%</span></div><p>{{ text.total }}</p><strong>1,284</strong><small>{{ text.lastWeek }}</small><span class="ops-sparkline green-line"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></span></article>
                    <article class="ops-stat-card"><div class="ops-stat-top"><span class="ops-stat-icon blue">⌖</span><span class="ops-trend positive">↗ 8.4%</span></div><p>{{ text.transit }}</p><strong>286</strong><small>{{ text.lastWeek }}</small><span class="ops-sparkline blue-line"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></span></article>
                    <article class="ops-stat-card"><div class="ops-stat-top"><span class="ops-stat-icon violet">✓</span><span class="ops-trend positive">↗ 6.2%</span></div><p>{{ text.deliveredToday }}</p><strong>913</strong><small>{{ text.lastWeek }}</small><span class="ops-sparkline violet-line"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></span></article>
                    <article class="ops-stat-card"><div class="ops-stat-top"><span class="ops-stat-icon amber">!</span><span class="ops-trend negative">↓ 2.1%</span></div><p>{{ text.attention }}</p><strong>12</strong><small>{{ text.today }}</small><span class="ops-sparkline amber-line"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></span></article>
                </section>

                <section class="ops-insights-grid">
                    <article class="ops-panel ops-chart-panel">
                        <div class="ops-panel-heading"><div><h2>{{ text.trend }}</h2><p>{{ text.volume }}</p></div><div class="ops-period-switch"><button v-for="period in ['week', 'month', 'year']" :key="period" type="button" :class="{ active: selectedPeriod === period }" @click="selectedPeriod = period">{{ text[period] }}</button></div></div>
                        <div class="ops-chart-total"><strong>1,284</strong><span><b>↗ 12.8%</b> {{ text.lastWeek }}</span></div>
                        <div class="ops-chart" role="img" :aria-label="text.volume"><div class="ops-chart-grid"><i></i><i></i><i></i><i></i></div><div class="ops-chart-bars"><div v-for="(bar, index) in bars" :key="selectedPeriod + '-' + index" class="ops-chart-column"><span class="ops-chart-value" :style="{ height: bar + '%' }"><i v-if="index === bars.length - 2">214</i></span><small>{{ chartLabels[index] }}</small></div></div></div>
                    </article>

                    <article class="ops-panel ops-status-panel">
                        <div class="ops-panel-heading"><div><h2>{{ text.status }}</h2><p>{{ text.activeShipments }}</p></div><button class="ops-more" type="button" @click="openFeature(text.shipments)">···</button></div>
                        <div class="ops-status-total"><strong>1,284</strong><span>{{ text.total }}</span><div class="ops-donut"><span>78%<small>{{ text.onTime }}</small></span></div></div>
                        <div class="ops-status-list"><div v-for="row in statusRows" :key="row.key" class="ops-status-row"><div><span><i :class="row.tone"></i>{{ row.label }}</span><strong>{{ row.count }}</strong></div><div class="ops-progress"><i :class="row.tone" :style="{ width: row.width }"></i></div></div></div>
                    </article>
                </section>

                <section class="ops-bottom-grid">
                    <article class="ops-panel ops-shipments-panel">
                        <div class="ops-panel-heading ops-table-heading"><div><h2>{{ text.latest }}</h2><p>{{ text.latestDescription }}</p></div><button class="ops-text-button" type="button" @click="openFeature(text.shipments)">{{ text.seeAll }} <span>→</span></button></div>
                        <div class="ops-table-controls"><div class="ops-filter-tabs"><button v-for="filter in ['all', 'transit', 'delivered', 'attention']" :key="filter" type="button" :class="{ active: selectedFilter === filter }" @click="selectedFilter = filter">{{ filter === 'all' ? text.all : statusLabel(filter) }}</button></div><button class="ops-filter-button" type="button" @click="openFeature(text.filter)"><span>☷</span>{{ text.filter }}</button></div>
                        <div class="ops-table-wrap"><table class="ops-table"><thead><tr><th>{{ text.trackingNo }}</th><th>{{ text.customer }}</th><th>{{ text.route }}</th><th>{{ text.service }}</th><th>{{ text.status }}</th><th>{{ text.eta }}</th><th></th></tr></thead>
                            <tbody><tr v-for="shipment in visibleShipments" :key="shipment.tracking"><td><strong class="ops-tracking">{{ shipment.tracking }}</strong></td><td><span class="ops-customer">{{ shipment.customer }}</span></td><td><span class="ops-route">{{ shipment.route }}</span></td><td><span class="ops-service">{{ shipment.service }}</span></td><td><span class="ops-status-pill" :class="shipment.status">{{ statusLabel(shipment.status) }}</span></td><td><span class="ops-eta">{{ shipment.eta }}</span></td><td><button class="ops-row-more" type="button" :aria-label="text.trackingNo + ' ' + shipment.tracking" @click="openFeature(shipment.tracking)">···</button></td></tr>
                                <tr v-if="visibleShipments.length === 0"><td colspan="7" class="ops-empty">{{ text.noResults }}</td></tr></tbody>
                        </table></div>
                    </article>

                    <aside class="ops-side-panels">
                        <article class="ops-panel ops-quick-panel"><div class="ops-panel-heading"><div><h2>{{ text.actions }}</h2><p>{{ isId ? 'Akses tugas utama lebih cepat.' : 'Jump into common tasks.' }}</p></div></div>
                            <button type="button" class="ops-quick-action" @click="notify(text.shipmentSoon)"><span class="quick-icon green">＋</span><span>{{ text.createShipment }}</span><b>→</b></button>
                            <button type="button" class="ops-quick-action" @click="openFeature(text.manifest)"><span class="quick-icon blue">▦</span><span>{{ text.manifest }}</span><b>→</b></button>
                            <button type="button" class="ops-quick-action" @click="openFeature(text.addCustomer)"><span class="quick-icon violet">♙</span><span>{{ text.addCustomer }}</span><b>→</b></button>
                        </article>
                        <article class="ops-panel ops-activity-panel"><div class="ops-panel-heading"><div><h2>{{ text.activity }}</h2><p>{{ text.activityDescription }}</p></div><button class="ops-more" type="button" @click="openFeature(text.activity)">···</button></div>
                            <div class="ops-activity-list">
                                <div class="ops-activity-item"><span class="activity-symbol green">✓</span><div><p><strong>CSL-261104-0827</strong> {{ isId ? 'telah diterima penerima.' : 'was delivered to the recipient.' }}</p><small>Jakarta · 11:42 {{ text.today.toLowerCase() }}</small></div></div>
                                <div class="ops-activity-item"><span class="activity-symbol blue">↗</span><div><p><strong>CSL-261104-0842</strong> {{ isId ? 'berangkat dari gudang Jakarta.' : 'departed from Jakarta hub.' }}</p><small>Jakarta Hub · 10:18 {{ text.today.toLowerCase() }}</small></div></div>
                                <div class="ops-activity-item"><span class="activity-symbol amber">!</span><div><p><strong>CSL-261104-0819</strong> {{ isId ? 'menunggu konfirmasi alamat.' : 'is waiting for address confirmation.' }}</p><small>Surabaya Hub · 09:56 {{ text.today.toLowerCase() }}</small></div></div>
                            </div>
                        </article>
                    </aside>
                </section>

                <footer class="ops-footer"><span>© {{ new Date().getFullYear() }} PT Cesco Logistik Indonesia</span><span>{{ text.demo }} · e-Cesco</span></footer>
            </div>
        </main>

        <Transition name="ops-toast"><div v-if="toast" class="ops-toast-message" role="status">{{ toast }}</div></Transition>
    </div>
</template>
