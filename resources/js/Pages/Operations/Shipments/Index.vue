<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../Layouts/OperationsSidebar.vue'

const props = defineProps({
    shipments: { type: Object, required: true },
    filters: { type: Object, required: true },
    counts: { type: Object, required: true },
})
const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const canManage = computed(() => page.props.auth?.user?.role !== 'customer')
const sidebarOpen = ref(false)
const search = ref(props.filters.search || '')
const filter = ref(props.filters.filter || 'all')
const perPage = ref(props.filters.perPage || 10)
const selectedShipment = ref(null)
const modalType = ref('')
const actionForm = useForm({ cancel_reason: '', pickup_date: '' })
const importForm = useForm({ type: 'recipients', file: null })
const toolsOpen = ref(false)
const statusModal = ref(false)
const statusDetails = ref(null)
const statusLoading = ref(false)
const statusError = ref('')
const photoError = ref(false)
const signaturePaths = computed(() => {
    const value = statusDetails.value?.shipment?.deliverySignature
    if (!value) return []
    try {
        const lines = JSON.parse(value).lines
        if (!Array.isArray(lines)) return []
        return lines.map((line) => line.filter((point) => Array.isArray(point) && point.length === 2 && point.every((coordinate) => Number.isFinite(Number(coordinate)) && Math.abs(Number(coordinate)) <= 10000)).map((point) => point.map(Number))).filter((line) => line.length > 1).map((line) => line.map(([x, y], index) => `${index ? 'L' : 'M'}${x} ${y}`).join(' '))
    } catch {
        return []
    }
})
const tabs = computed(() => [
    { key: 'all', label: isId.value ? 'Semua' : 'All', count: props.counts.all },
    { key: 'processing', label: 'Booking', count: props.counts.processing },
    { key: 'transit', label: isId.value ? 'Dalam perjalanan' : 'In transit', count: props.counts.transit },
])

function applyFilters(nextFilter = filter.value) {
    filter.value = nextFilter
    router.get('/e-cesco/shipments', { search: search.value, filter: filter.value, per_page: perPage.value }, { preserveState: true, preserveScroll: true, replace: true })
}

async function openStatus(shipment) {
    statusModal.value = true
    statusLoading.value = true
    statusError.value = ''
    photoError.value = false
    statusDetails.value = null
    try {
        const response = await fetch(`/e-cesco/shipments/${shipment.id}/status`, { headers: { Accept: 'application/json' }, credentials: 'same-origin' })
        if (!response.ok) throw new Error('Status detail could not be loaded.')
        statusDetails.value = await response.json()
    } catch {
        statusError.value = isId.value ? 'Detail status gagal dimuat.' : 'Unable to load shipment status.'
    } finally {
        statusLoading.value = false
    }
}

function closeStatus() {
    statusModal.value = false
    statusDetails.value = null
    statusError.value = ''
}

function cancelShipment(shipment) {
    selectedShipment.value = shipment
    actionForm.cancel_reason = ''
    modalType.value = 'cancel'
}

function editPickupDate(shipment) {
    selectedShipment.value = shipment
    actionForm.pickup_date = shipment.pickupDate ? shipment.pickupDate.slice(0, 10) : ''
    modalType.value = 'pickup'
}

function submitAction() {
    if (modalType.value === 'cancel') {
        actionForm.post(`/e-cesco/shipments/${selectedShipment.value.id}/cancel`, { preserveScroll: true, onSuccess: closeModal })
    } else {
        actionForm.put(`/e-cesco/shipments/${selectedShipment.value.id}/pickup-date`, { preserveScroll: true, onSuccess: closeModal })
    }
}

function closeModal() {
    modalType.value = ''
    selectedShipment.value = null
    actionForm.reset()
}

function startImport(type) {
    importForm.reset()
    importForm.type = type
    toolsOpen.value = false
    modalType.value = 'import'
}

function setImportFile(event) {
    importForm.file = event.target.files?.[0] ?? null
}

function submitImport() {
    importForm.post('/e-cesco/shipments/import', { forceFormData: true, preserveScroll: true, onSuccess: closeModal })
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
                <div class="ops-top-actions"><div class="ops-language"><Link :href="page.props.site.localeUrls.id" :class="{ active: isId }" lang="id" hreflang="id" preserve-scroll>ID</Link><span></span><Link :href="page.props.site.localeUrls.en" :class="{ active: !isId }" lang="en" hreflang="en" preserve-scroll>EN</Link></div><OperationsAccountMenu /></div>
            </header>
            <div class="ops-content">
                <div v-if="page.props.flash?.success" class="ops-flash success" role="status">{{ page.props.flash.success }}</div>
                <div v-if="page.props.flash?.error" class="ops-flash error" role="alert">{{ page.props.flash.error }}</div>
                <section class="ops-heading ship-heading"><div><p class="ops-date">{{ isId ? 'OPERASIONAL' : 'OPERATIONS' }}</p><h1>{{ isId ? 'Pengiriman' : 'Shipments' }}</h1><p>{{ isId ? 'Kelola dan pantau pengiriman Cesco dari satu tempat.' : 'Manage and track Cesco shipments in one place.' }}</p></div></section>
                <section class="ops-panel ship-list-panel">
                    <div class="ship-list-heading"><div class="ship-list-title"><h2>{{ isId ? 'Daftar pengiriman' : 'Shipment list' }}</h2><p>{{ isId ? 'Data pengiriman dari tabel operasional.' : 'Shipment records from the operations database.' }}</p></div><div class="ship-list-controls"><form class="ship-table-search" role="search" @submit.prevent="applyFilters()"><span aria-hidden="true">⌕</span><input v-model="search" :placeholder="isId ? 'Cari AWB, pelanggan, kota…' : 'Search AWB, customer, city…'"><button type="submit">{{ isId ? 'Cari' : 'Search' }}</button></form><label class="ship-page-size"><span>{{ isId ? 'Tampilkan' : 'Show' }}</span><select v-model="perPage" @change="applyFilters()"><option :value="10">10</option><option :value="25">25</option><option :value="50">50</option><option :value="100">100</option></select><span>{{ isId ? 'data' : 'rows' }}</span></label><div v-if="canManage" class="ship-tools"><button class="ops-secondary-button" type="button" @click="toolsOpen = !toolsOpen">{{ isId ? 'Alat AWB' : 'AWB tools' }}⌄</button><div v-if="toolsOpen" class="ship-tools-dropdown"><button type="button" @click="startImport('recipients')">{{ isId ? 'Import penerima' : 'Import recipients' }}</button><button type="button" @click="startImport('asus')">{{ isId ? 'Import detail ASUS' : 'Import ASUS details' }}</button><button type="button" @click="startImport('weights')">{{ isId ? 'Import berat aktual' : 'Import actual weights' }}</button><button type="button" @click="startImport('services')">{{ isId ? 'Import layanan' : 'Import services' }}</button></div></div></div></div>
                    <div class="ship-tabs"><button v-for="tab in tabs" :key="tab.key" type="button" :class="{ active: filter === tab.key }" @click="applyFilters(tab.key)">{{ tab.label }} <small>{{ tab.count.toLocaleString() }}</small></button></div>
                    <div class="ship-table-wrap"><table class="ops-table ship-table ops-live-shipment-table"><thead><tr>
                        <th>{{ isId ? 'No AWB' : 'AWB No.' }}</th><th>{{ isId ? 'Pelanggan' : 'Customer' }}</th><th>{{ isId ? 'Asal' : 'Origin' }}</th><th>{{ isId ? 'Tujuan' : 'Destination' }}</th><th>{{ isId ? 'Layanan' : 'Service' }}</th><th>{{ isId ? 'Status' : 'Status' }}</th><th>{{ isId ? 'Terlambat' : 'Delayed' }}</th><th>{{ isId ? 'Pickup date' : 'Pickup date' }}</th><th>{{ isId ? 'Pickup by' : 'Pickup by' }}</th><th>{{ isId ? 'Delivery date' : 'Delivery date' }}</th><th>{{ isId ? 'Delivery by' : 'Delivery by' }}</th><th>{{ isId ? 'Penerima' : 'Recipient' }}</th><th>{{ isId ? 'Aksi' : 'Actions' }}</th>
                    </tr></thead><tbody>
                        <tr v-for="shipment in shipments.data" :key="shipment.id">
                            <td><button class="ops-tracking status-trigger" type="button" @click="openStatus(shipment)">{{ shipment.awb }}</button></td><td><span class="ops-customer">{{ shipment.customer }}</span></td><td>{{ shipment.origin }}</td><td>{{ shipment.destination }}</td><td><span class="ops-service">{{ shipment.service }}</span></td><td><button class="ops-status-pill" :class="shipment.status === 6 ? 'delivered' : (shipment.status === 7 ? 'attention' : (shipment.status === 1 ? 'pickup' : 'transit'))" type="button" @click="openStatus(shipment)">{{ statusLabel(shipment) }}</button></td>
                            <td><span v-if="shipment.delayedPickup || shipment.delayedDelivery" class="ops-delay-badge">{{ [shipment.delayedPickup ? (isId ? 'Pickup' : 'Pickup') : '', shipment.delayedDelivery ? (isId ? 'Delivery' : 'Delivery') : ''].filter(Boolean).join(' / ') }}</span></td>
                            <td><button v-if="canManage" class="ops-inline-action" type="button" @click="editPickupDate(shipment)">{{ formatDate(shipment.pickupDate) || (isId ? 'Atur' : 'Set') }}</button><span v-else>{{ formatDate(shipment.pickupDate) }}</span></td><td>{{ shipment.pickupBy }}</td><td>{{ formatDate(shipment.deliveryDate) }}</td><td>{{ shipment.deliveryBy }}</td><td>{{ shipment.recipient }}</td>
                            <td><div class="ops-shipment-actions">
                                <template v-if="canManage">
                                    <Link class="shipment-action-button action-edit" :href="`/e-cesco/shipments/${shipment.id}/edit`"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg><span>{{ isId ? 'Edit' : 'Edit' }}</span></Link>
                                    <button class="shipment-action-button action-cancel" type="button" :disabled="[6, 7].includes(shipment.status)" @click="cancelShipment(shipment)"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m9 9 6 6m0-6-6 6"/></svg><span>{{ isId ? 'Batalkan' : 'Cancel' }}</span></button>
                                </template>
                                <a class="shipment-action-button action-awb" :href="`/e-cesco/shipments/${shipment.id}/print-awb`" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v7H6zM18 12h.01"/></svg><span>{{ isId ? 'Cetak AWB' : 'Print AWB' }}</span></a>
                                <a class="shipment-action-button action-label" :href="`/e-cesco/shipments/${shipment.id}/print-label`" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8Z"/><circle cx="7.5" cy="7.5" r="1"/></svg><span>{{ isId ? 'Cetak label' : 'Print label' }}</span></a>
                                <a v-if="canManage" class="shipment-action-button action-inspection" :href="`/e-cesco/shipments/${shipment.id}/print-inspection`" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 4H5a2 2 0 0 0-2 2v15h18V6a2 2 0 0 0-2-2h-3"/><rect x="8" y="2" width="8" height="5" rx="1"/><path d="M7 12h10m-10 4h7"/></svg><span>{{ isId ? 'Form pemeriksaan' : 'Inspection form' }}</span></a>
                                <a v-if="shipment.customerId === 106" class="shipment-action-button action-asus" :href="`/e-cesco/shipments/${shipment.id}/print-asus`" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v14H4zM8 7V3h8v4m-8 5h8m-8 4h8"/></svg><span>{{ isId ? 'Cetak ASUS' : 'Print ASUS' }}</span></a>
                            </div></td>
                        </tr>
                        <tr v-if="shipments.data.length === 0"><td colspan="13" class="ops-empty">{{ isId ? 'Tidak ada pengiriman yang cocok.' : 'No matching shipments found.' }}</td></tr>
                    </tbody></table></div>
                    <footer class="ship-table-footer"><span>{{ isId ? 'Menampilkan' : 'Showing' }} <strong>{{ shipments.from || 0 }}–{{ shipments.to || 0 }}</strong> {{ isId ? 'dari' : 'of' }} {{ shipments.total.toLocaleString() }}</span><nav class="ship-pagination"><template v-for="link in shipments.links" :key="link.label"><Link v-if="link.url" :href="link.url" :class="{ active: link.active }" preserve-scroll v-html="link.label"></Link><span v-else v-html="link.label"></span></template></nav></footer>
                </section>
            </div>
            <div v-if="modalType" class="ops-modal-backdrop" @click.self="closeModal">
                <form v-if="modalType !== 'import'" class="ops-modal-card" @submit.prevent="submitAction">
                    <button class="ops-modal-close" type="button" :aria-label="isId ? 'Tutup' : 'Close'" @click="closeModal">×</button>
                    <h2>{{ modalType === 'cancel' ? (isId ? 'Batalkan pengiriman' : 'Cancel shipment') : (isId ? 'Ubah tanggal pickup' : 'Update pickup date') }}</h2>
                    <p>{{ selectedShipment?.awb }}</p>
                    <label v-if="modalType === 'cancel'" class="ops-form-field"><span>{{ isId ? 'Alasan pembatalan' : 'Cancellation reason' }}</span><textarea v-model="actionForm.cancel_reason" required maxlength="100" rows="3"></textarea><small v-if="actionForm.errors.cancel_reason" class="ops-form-error">{{ actionForm.errors.cancel_reason }}</small></label>
                    <label v-else class="ops-form-field"><span>{{ isId ? 'Tanggal pickup' : 'Pickup date' }}</span><input v-model="actionForm.pickup_date" type="date"><small v-if="actionForm.errors.pickup_date" class="ops-form-error">{{ actionForm.errors.pickup_date }}</small></label>
                    <div class="ops-user-form-footer"><button class="ops-secondary-button" type="button" @click="closeModal">{{ isId ? 'Tutup' : 'Close' }}</button><button class="ops-primary-button" type="submit" :disabled="actionForm.processing">{{ actionForm.processing ? (isId ? 'Memproses…' : 'Saving…') : (isId ? 'Simpan' : 'Save') }}</button></div>
                </form>
                <form v-else class="ops-modal-card" @submit.prevent="submitImport">
                    <button class="ops-modal-close" type="button" :aria-label="isId ? 'Tutup' : 'Close'" @click="closeModal">×</button>
                    <h2>{{ isId ? 'Import data AWB' : 'Import AWB data' }}</h2>
                    <label class="ops-form-field"><span>{{ isId ? 'Jenis data' : 'Data type' }}</span><select v-model="importForm.type"><option value="recipients">{{ isId ? 'Penerima dan tanggal terkirim' : 'Recipient and delivery date' }}</option><option value="asus">{{ isId ? 'Detail barang ASUS' : 'ASUS item details' }}</option><option value="weights">{{ isId ? 'Berat aktual' : 'Actual weight' }}</option><option value="services">{{ isId ? 'Layanan' : 'Service' }}</option></select></label>
                    <p class="import-format-hint">{{ importForm.type === 'recipients' ? 'CSV: No AWB, nama penerima, tanggal (YYYY-MM-DD).' : importForm.type === 'asus' ? 'CSV: product, APRC, part number, deskripsi, RMA, qty, remark, width, height, length, weight, No AWB.' : 'CSV: No AWB, nilai. Layanan memakai ID layanan.' }}</p>
                    <label class="ops-form-field"><span>{{ isId ? 'File CSV (maks. 2 MB, 1.000 baris)' : 'CSV file (max 2 MB, 1,000 rows)' }}</span><input type="file" accept=".csv,text/csv" required @change="setImportFile"><small v-if="importForm.errors.file" class="ops-form-error">{{ importForm.errors.file }}</small></label>
                    <div class="ops-user-form-footer"><button class="ops-secondary-button" type="button" @click="closeModal">{{ isId ? 'Tutup' : 'Close' }}</button><button class="ops-primary-button" type="submit" :disabled="importForm.processing || !importForm.file">{{ importForm.processing ? (isId ? 'Mengimpor…' : 'Importing…') : (isId ? 'Mulai import' : 'Start import') }}</button></div>
                </form>
            </div>
            <div v-if="statusModal" class="ops-modal-backdrop" @click.self="closeStatus">
                <section class="ops-modal-card status-modal-card" role="dialog" aria-modal="true" :aria-label="isId ? 'Detail status pengiriman' : 'Shipment status details'">
                    <button class="ops-modal-close" type="button" :aria-label="isId ? 'Tutup' : 'Close'" @click="closeStatus">×</button>
                    <h2>{{ isId ? 'Detail status pengiriman' : 'Shipment status details' }}</h2>
                    <p>{{ statusDetails?.shipment?.awb }}</p>
                    <div v-if="statusLoading" class="status-modal-loading"><span class="ops-spinner"></span>{{ isId ? 'Memuat status…' : 'Loading status…' }}</div>
                    <p v-else-if="statusError" class="ops-flash error">{{ statusError }}</p>
                    <template v-else-if="statusDetails">
                        <ol class="shipment-timeline-modal"><li v-for="(event, index) in statusDetails.events" :key="`${event.status}-${index}`"><span class="shipment-timeline-dot"></span><div><strong>{{ event.status }}</strong><p>{{ event.date }}<span v-if="event.by"> · {{ event.by }}</span></p><small v-if="event.note">{{ event.note }}</small></div></li><li v-if="statusDetails.events.length === 0">{{ isId ? 'Belum ada riwayat status.' : 'No status history yet.' }}</li></ol>
                        <section v-if="statusDetails.shipment.delivered" class="delivery-proof"><h3>{{ isId ? 'Bukti penerima' : 'Delivery proof' }}</h3><p><strong>{{ statusDetails.shipment.recipient || '—' }}</strong><span v-if="statusDetails.shipment.deliveryDate"> · {{ formatDate(statusDetails.shipment.deliveryDate) }}</span></p><img v-if="statusDetails.shipment.photoUrl && !photoError" class="delivery-proof-photo" :src="statusDetails.shipment.photoUrl" :alt="isId ? `Foto bukti penerima ${statusDetails.shipment.recipient || ''}` : `Recipient proof photo ${statusDetails.shipment.recipient || ''}`" @error="photoError = true"><div v-else-if="signaturePaths.length" class="delivery-signature"><span>{{ isId ? 'Tanda tangan penerima' : 'Recipient signature' }}</span><svg viewBox="0 0 500 150" role="img" :aria-label="isId ? 'Tanda tangan penerima' : 'Recipient signature'"><path v-for="(path, index) in signaturePaths" :key="index" :d="path" /></svg></div><p v-else class="delivery-proof-empty">{{ isId ? 'Foto atau tanda tangan penerima tidak tersedia.' : 'Recipient photo or signature is not available.' }}</p><span v-if="statusDetails.shipment.photoDeliveryVerified" class="delivery-proof-badge">{{ isId ? 'Foto penerimaan terverifikasi' : 'Delivery photo verified' }}</span></section>
                    </template>
                </section>
            </div>
        </main>
    </div>
</template>
