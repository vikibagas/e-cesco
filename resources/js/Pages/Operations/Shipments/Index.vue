<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../Layouts/OperationsSidebar.vue'

const props = defineProps({
    shipments: { type: Object, required: true },
    filters: { type: Object, required: true },
    summary: { type: Object, required: true },
})
const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const search = ref(props.filters.search || '')
const filter = ref(props.filters.filter || 'all')
const tabs = computed(() => [
    { key: 'all', label: isId.value ? 'Semua' : 'All', count: props.summary.total },
    { key: 'processing', label: isId.value ? 'Booking' : 'Booking', count: props.summary.processing },
    { key: 'transit', label: isId.value ? 'Dalam perjalanan' : 'In transit', count: props.summary.transit },
    { key: 'delivered', label: isId.value ? 'Terkirim' : 'Delivered', count: props.summary.delivered },
    { key: 'attention', label: isId.value ? 'Terlambat' : 'Delayed', count: props.summary.attention },
])

function applyFilters(nextFilter = filter.value) {
    filter.value = nextFilter
    router.get('/e-cesco/shipments', { search: search.value, filter: filter.value }, { preserveState: true, preserveScroll: true, replace: true })
}

function cancelShipment(shipment) {
    const reason = window.prompt(isId.value ? `Alasan membatalkan AWB ${shipment.awb}:` : `Reason for cancelling AWB ${shipment.awb}:`)
    if (reason && reason.trim()) {
        router.post(`/e-cesco/shipments/${shipment.id}/cancel`, { cancel_reason: reason.trim() }, { preserveScroll: true })
    }
}

function formatDate(value) {
    if (!value) return ''
    const date = new Date(value.replace(' ', 'T'))
    return Number.isNaN(date.getTime()) ? value : new Intl.DateTimeFormat(isId.value ? 'id-ID' : 'en-GB', { dateStyle: 'medium', timeStyle: 'short' }).format(date)
}

function statusLabel(shipment) {
    if (isId.value) {
        return { 1: 'Booking', 2: 'Pickup', 3: 'Outbound', 4: 'Inbound', 5: 'Delivery', 6: 'Terkirim', 7: 'Dibatalkan', 8: 'Retur' }[shipment.status] || shipment.statusLabel
    }
    return shipment.statusLabel
}
</script>

<template>
    <Head :title="isId ? 'e-Cesco — Pengiriman' : 'e-Cesco — Shipments'" />
    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>
        <OperationsSidebar active-page="shipments" :open="sidebarOpen" @close="sidebarOpen = false" />
        <main class="ops-main">
            <header class="ops-topbar">
                <button class="ops-mobile-toggle" type="button" :aria-label="isId ? 'Buka navigasi' : 'Open navigation'" @click="sidebarOpen = !sidebarOpen">☰</button>
                <div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><strong>{{ isId ? 'Pengiriman' : 'Shipments' }}</strong></div>
                <div class="ops-top-actions"><form class="ops-search" role="search" @submit.prevent="applyFilters()"><span aria-hidden="true">⌕</span><input v-model="search" :placeholder="isId ? 'Cari AWB, pelanggan, kota…' : 'Search AWB, customer, city…'"></form><div class="ops-language"><Link :href="page.props.site.localeUrls.id" :class="{ active: isId }" lang="id" hreflang="id" preserve-scroll>ID</Link><span></span><Link :href="page.props.site.localeUrls.en" :class="{ active: !isId }" lang="en" hreflang="en" preserve-scroll>EN</Link></div><OperationsAccountMenu /></div>
            </header>
            <div class="ops-content">
                <div v-if="page.props.flash?.success" class="ops-flash success" role="status">{{ page.props.flash.success }}</div>
                <div v-if="page.props.flash?.error" class="ops-flash error" role="alert">{{ page.props.flash.error }}</div>
                <section class="ops-heading ship-heading"><div><p class="ops-date">{{ isId ? 'OPERASIONAL' : 'OPERATIONS' }}</p><h1>{{ isId ? 'Pengiriman' : 'Shipments' }}</h1><p>{{ isId ? 'Kelola dan pantau pengiriman Cesco dari satu tempat.' : 'Manage and track Cesco shipments in one place.' }}</p></div></section>
                <section class="ship-summary">
                    <article><span class="ops-stat-icon green">▤</span><div><small>{{ isId ? 'Total pengiriman' : 'Total shipments' }}</small><strong>{{ summary.total.toLocaleString() }}</strong></div></article>
                    <article><span class="ops-stat-icon amber">◷</span><div><small>{{ isId ? 'Booking' : 'Booking' }}</small><strong>{{ summary.processing.toLocaleString() }}</strong></div></article>
                    <article><span class="ops-stat-icon blue">⌖</span><div><small>{{ isId ? 'Dalam perjalanan' : 'In transit' }}</small><strong>{{ summary.transit.toLocaleString() }}</strong></div></article>
                    <article><span class="ops-stat-icon violet">✓</span><div><small>{{ isId ? 'Terkirim' : 'Delivered' }}</small><strong>{{ summary.delivered.toLocaleString() }}</strong></div></article>
                </section>
                <section class="ops-panel ship-list-panel">
                    <div class="ship-list-heading"><div><h2>{{ isId ? 'Daftar pengiriman' : 'Shipment list' }}</h2><p>{{ isId ? 'Data pengiriman dari tabel operasional.' : 'Shipment records from the operations database.' }}</p></div></div>
                    <div class="ship-tabs"><button v-for="tab in tabs" :key="tab.key" type="button" :class="{ active: filter === tab.key }" @click="applyFilters(tab.key)">{{ tab.label }}<span>{{ tab.count.toLocaleString() }}</span></button></div>
                    <div class="ship-table-wrap"><table class="ops-table ship-table ops-live-shipment-table"><thead><tr>
                        <th>{{ isId ? 'No AWB' : 'AWB No.' }}</th><th>{{ isId ? 'Pelanggan' : 'Customer' }}</th><th>{{ isId ? 'Asal' : 'Origin' }}</th><th>{{ isId ? 'Tujuan' : 'Destination' }}</th><th>{{ isId ? 'Layanan' : 'Service' }}</th><th>{{ isId ? 'Status' : 'Status' }}</th><th>{{ isId ? 'Terlambat' : 'Delayed' }}</th><th>{{ isId ? 'Pickup date' : 'Pickup date' }}</th><th>{{ isId ? 'Pickup by' : 'Pickup by' }}</th><th>{{ isId ? 'Delivery date' : 'Delivery date' }}</th><th>{{ isId ? 'Delivery by' : 'Delivery by' }}</th><th>{{ isId ? 'Penerima' : 'Recipient' }}</th><th>{{ isId ? 'Aksi' : 'Actions' }}</th>
                    </tr></thead><tbody>
                        <tr v-for="shipment in shipments.data" :key="shipment.id">
                            <td><strong class="ops-tracking">{{ shipment.awb }}</strong></td><td><span class="ops-customer">{{ shipment.customer }}</span></td><td>{{ shipment.origin }}</td><td>{{ shipment.destination }}</td><td><span class="ops-service">{{ shipment.service }}</span></td><td><span class="ops-status-pill" :class="shipment.status === 6 ? 'delivered' : (shipment.status === 7 ? 'attention' : (shipment.status === 1 ? 'pickup' : 'transit'))">{{ statusLabel(shipment) }}</span></td>
                            <td><span v-if="shipment.delayedPickup || shipment.delayedDelivery" class="ops-delay-badge">{{ [shipment.delayedPickup ? (isId ? 'Pickup' : 'Pickup') : '', shipment.delayedDelivery ? (isId ? 'Delivery' : 'Delivery') : ''].filter(Boolean).join(' / ') }}</span></td>
                            <td>{{ formatDate(shipment.pickupDate) }}</td><td>{{ shipment.pickupBy }}</td><td>{{ formatDate(shipment.deliveryDate) }}</td><td>{{ shipment.deliveryBy }}</td><td>{{ shipment.recipient }}</td>
                            <td><div class="ops-shipment-actions"><Link class="ops-user-edit" :href="`/e-cesco/shipments/${shipment.id}/edit`">{{ isId ? 'Edit' : 'Edit' }}</Link><button class="ops-user-delete" type="button" :disabled="[6, 7].includes(shipment.status)" @click="cancelShipment(shipment)">{{ isId ? 'Cancel' : 'Cancel' }}</button><a class="ops-shipment-print" :href="`/e-cesco/shipments/${shipment.id}/print-awb`" target="_blank" rel="noopener">{{ isId ? 'Print AWB' : 'Print AWB' }}</a><a class="ops-shipment-print" :href="`/e-cesco/shipments/${shipment.id}/print-label`" target="_blank" rel="noopener">{{ isId ? 'Print label' : 'Print label' }}</a></div></td>
                        </tr>
                        <tr v-if="shipments.data.length === 0"><td colspan="13" class="ops-empty">{{ isId ? 'Tidak ada pengiriman yang cocok.' : 'No matching shipments found.' }}</td></tr>
                    </tbody></table></div>
                    <footer class="ship-table-footer"><span>{{ isId ? 'Menampilkan' : 'Showing' }} <strong>{{ shipments.from || 0 }}–{{ shipments.to || 0 }}</strong> {{ isId ? 'dari' : 'of' }} {{ shipments.total.toLocaleString() }}</span><nav class="ship-pagination"><template v-for="link in shipments.links" :key="link.label"><Link v-if="link.url" :href="link.url" :class="{ active: link.active }" preserve-scroll v-html="link.label"></Link><span v-else v-html="link.label"></span></template></nav></footer>
                </section>
            </div>
        </main>
    </div>
</template>
