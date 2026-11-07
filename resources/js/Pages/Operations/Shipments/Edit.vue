<script setup>
import { computed, ref, watch } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../Layouts/OperationsSidebar.vue'

const props = defineProps({ shipment: { type: Object, required: true }, services: { type: Array, required: true }, customers: { type: Array, required: true } })
const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const selectedCustomer = computed(() => props.customers.find((customer) => Number(customer.id_kustomer) === Number(form.customerId)))
const sidebarOpen = ref(false)
const form = useForm({
    sender: props.shipment.sender || '', senderCompany: props.shipment.senderCompany || '',
    senderAddress: props.shipment.senderAddress || '', senderAddressDetails: props.shipment.senderAddressDetails || '', senderPhone: props.shipment.senderPhone || '',
    recipient: props.shipment.recipient || '', recipientCompany: props.shipment.recipientCompany || '',
    recipientAddress: props.shipment.recipientAddress || '', recipientAddressDetails: props.shipment.recipientAddressDetails || '',
    recipientPhone: props.shipment.recipientPhone || '', goods: props.shipment.goods || '', notes: props.shipment.notes || '',
    service: props.shipment.service || '', actualWeight: props.shipment.actualWeight || '', packages: props.shipment.packages || '',
    commodity: String(props.shipment.commodity || 1), length: props.shipment.dimensions?.[0] || '',
    width: props.shipment.dimensions?.[1] || '', height: props.shipment.dimensions?.[2] || '',
    packing: String(props.shipment.packing || 1), insured: props.shipment.insured || 'N',
    declaredValue: props.shipment.declaredValue || 0, returnDocument: props.shipment.returnDocument || 'N',
    specialPayment: String(props.shipment.specialPayment || 0),
    collies: props.shipment.collies || [],
    customerId: props.shipment.customerId,
    locCode: props.shipment.locCode || '', pid: props.shipment.pid || '',
    paymentName: props.shipment.paymentName || '', paymentAddress: props.shipment.paymentAddress || '',
    paymentContact: props.shipment.paymentContact || '', paymentPhone: props.shipment.paymentPhone || '',
    container20: props.shipment.container20 || 0, container40: props.shipment.container40 || 0,
    container40HighCube: props.shipment.container40HighCube || 0, lcl: props.shipment.lcl || 0,
})
const hasSavedPackageDetails = props.shipment.collies.length > 0
const packageDetailsEnabled = ref(hasSavedPackageDetails || Number(form.packages) > 1)

function newCollie() {
    return {
        note: '', weight: 1, width: Number(form.width) || 1,
        length: Number(form.length) || 1, height: Number(form.height) || 1, actualWeight: 1,
    }
}

function syncCollies(count) {
    while (form.collies.length < count) form.collies.push(newCollie())
    if (form.collies.length > count) form.collies.splice(count)
}

watch(() => form.packages, (value) => {
    if (props.shipment.priceLocked) return
    const count = Math.min(250, Math.max(1, Number(value) || 1))
    if (count > 1 || hasSavedPackageDetails) {
        packageDetailsEnabled.value = true
        syncCollies(count)
        return
    }

    packageDetailsEnabled.value = false
    form.collies.splice(0)
})

if (packageDetailsEnabled.value) {
    syncCollies(Math.min(250, Math.max(1, Number(form.packages) || 1)))
}

function submit() {
    form.put(`/e-cesco/shipments/${props.shipment.id}`)
}
</script>

<template>
    <Head :title="isId ? `Edit pengiriman ${shipment.awb}` : `Edit shipment ${shipment.awb}`" />
    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>
        <OperationsSidebar active-page="shipments" :open="sidebarOpen" @close="sidebarOpen = false" />
        <main class="ops-main">
            <header class="ops-topbar"><button class="ops-mobile-toggle" type="button" :aria-label="isId ? 'Buka navigasi' : 'Open navigation'" @click="sidebarOpen = !sidebarOpen">☰</button><div class="ops-breadcrumb"><Link href="/e-cesco/shipments">{{ isId ? 'Pengiriman' : 'Shipments' }}</Link><span>/</span><strong>{{ shipment.awb }}</strong></div><OperationsAccountMenu /></header>
            <div class="ops-content">
                <div class="ops-data-heading"><div><p class="ops-date">{{ isId ? 'PENGIRIMAN / EDIT' : 'SHIPMENTS / EDIT' }}</p><h1>{{ isId ? 'Edit pengiriman' : 'Edit shipment' }}</h1><p>{{ shipment.awb }}</p></div><Link class="ops-secondary-button" href="/e-cesco/shipments">← {{ isId ? 'Kembali' : 'Back' }}</Link></div>
                <form class="ops-panel ops-user-form" @submit.prevent="submit">
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Data pengiriman' : 'Shipment details' }}</h2></div><div class="ops-user-form-grid">
                        <label class="ops-form-field"><span>{{ isId ? 'Pelanggan' : 'Customer' }}</span><select v-model="form.customerId" :disabled="shipment.priceLocked"><option v-for="customer in customers" :key="customer.id_kustomer" :value="customer.id_kustomer">{{ customer.nama_kustomer }}</option></select><small v-if="form.errors.customerId" class="ops-form-error">{{ form.errors.customerId }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Lokasi / kode pelanggan' : 'Customer location code' }}</span><input v-model="form.locCode" maxlength="100" :required="selectedCustomer?.loc_code === 'Y'"><small v-if="form.errors.locCode" class="ops-form-error">{{ form.errors.locCode }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Nomor PID' : 'PID number' }}</span><input v-model="form.pid" maxlength="20" :required="[13, 29, 31].includes(Number(form.customerId))"><small v-if="form.errors.pid" class="ops-form-error">{{ form.errors.pid }}</small></label>
                    </div></section>
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Pengirim' : 'Sender' }}</h2></div><div class="ops-user-form-grid">
                        <label class="ops-form-field"><span>{{ isId ? 'Nama pengirim' : 'Sender name' }}</span><input v-model="form.sender" maxlength="100"><small v-if="form.errors.sender" class="ops-form-error">{{ form.errors.sender }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Perusahaan pengirim' : 'Sender company' }}</span><input v-model="form.senderCompany" maxlength="100"><small v-if="form.errors.senderCompany" class="ops-form-error">{{ form.errors.senderCompany }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Telepon pengirim' : 'Sender phone' }}</span><input v-model="form.senderPhone" maxlength="20"><small v-if="form.errors.senderPhone" class="ops-form-error">{{ form.errors.senderPhone }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Alamat pengirim' : 'Sender address' }}</span><textarea v-model="form.senderAddress" maxlength="300" rows="3"></textarea><small v-if="form.errors.senderAddress" class="ops-form-error">{{ form.errors.senderAddress }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Detail alamat pengirim' : 'Sender address details' }}</span><textarea v-model="form.senderAddressDetails" maxlength="300" rows="2"></textarea><small v-if="form.errors.senderAddressDetails" class="ops-form-error">{{ form.errors.senderAddressDetails }}</small></label>
                    </div></section>
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Penerima' : 'Recipient' }}</h2></div><div class="ops-user-form-grid">
                        <label class="ops-form-field"><span>{{ isId ? 'Nama penerima' : 'Recipient name' }}</span><input v-model="form.recipient" maxlength="100"><small v-if="form.errors.recipient" class="ops-form-error">{{ form.errors.recipient }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Perusahaan penerima' : 'Recipient company' }}</span><input v-model="form.recipientCompany" maxlength="100"><small v-if="form.errors.recipientCompany" class="ops-form-error">{{ form.errors.recipientCompany }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Telepon penerima' : 'Recipient phone' }}</span><input v-model="form.recipientPhone" maxlength="20"><small v-if="form.errors.recipientPhone" class="ops-form-error">{{ form.errors.recipientPhone }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Alamat penerima' : 'Recipient address' }}</span><input v-model="form.recipientAddress" maxlength="100"><small v-if="form.errors.recipientAddress" class="ops-form-error">{{ form.errors.recipientAddress }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Detail alamat' : 'Address details' }}</span><textarea v-model="form.recipientAddressDetails" maxlength="80" rows="3"></textarea><small v-if="form.errors.recipientAddressDetails" class="ops-form-error">{{ form.errors.recipientAddressDetails }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Jenis barang' : 'Goods description' }}</span><input v-model="form.goods" maxlength="100"><small v-if="form.errors.goods" class="ops-form-error">{{ form.errors.goods }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Catatan' : 'Notes' }}</span><textarea v-model="form.notes" maxlength="100" rows="3"></textarea><small v-if="form.errors.notes" class="ops-form-error">{{ form.errors.notes }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Layanan' : 'Service' }}</span><select v-model="form.service" :disabled="shipment.priceLocked"><option value="">{{ isId ? 'Pilih layanan' : 'Select service' }}</option><option v-for="item in services" :key="item.id_pelayanan" :value="item.id_pelayanan">{{ item.nama_pelayanan }}</option></select><small v-if="form.errors.service" class="ops-form-error">{{ form.errors.service }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Komoditi' : 'Commodity' }}</span><select v-model="form.commodity"><option value="1">{{ isId ? 'Paket' : 'Parcel' }}</option><option value="2">{{ isId ? 'Dokumen' : 'Document' }}</option></select></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Berat aktual (kg)' : 'Actual weight (kg)' }}</span><input v-model="form.actualWeight" type="number" min="1" max="36000" step="1" :disabled="shipment.priceLocked"><small v-if="form.errors.actualWeight" class="ops-form-error">{{ form.errors.actualWeight }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Jumlah koli' : 'Packages' }}</span><input v-model="form.packages" type="number" min="1" max="250" :disabled="shipment.priceLocked"><small v-if="form.errors.packages" class="ops-form-error">{{ form.errors.packages }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Rute' : 'Route' }}</span><input :value="shipment.route" readonly></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Panjang (cm)' : 'Length (cm)' }}</span><input v-model="form.length" type="number" min="0" max="999"><small v-if="form.errors.length" class="ops-form-error">{{ form.errors.length }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Lebar (cm)' : 'Width (cm)' }}</span><input v-model="form.width" type="number" min="0" max="999"><small v-if="form.errors.width" class="ops-form-error">{{ form.errors.width }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Tinggi (cm)' : 'Height (cm)' }}</span><input v-model="form.height" type="number" min="0" max="999"><small v-if="form.errors.height" class="ops-form-error">{{ form.errors.height }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Jenis packing' : 'Packing type' }}</span><select v-model="form.packing"><option value="1">{{ isId ? 'Packing oleh pemilik' : 'Packed by sender' }}</option><option value="2">{{ isId ? 'Packing kayu oleh Cesco' : 'Wood packing by Cesco' }}</option><option value="3">{{ isId ? 'Tanpa packing kayu Cesco' : 'No wood packing by Cesco' }}</option></select><small v-if="form.errors.packing" class="ops-form-error">{{ form.errors.packing }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Asuransi' : 'Insurance' }}</span><select v-model="form.insured"><option value="N">{{ isId ? 'Tidak' : 'No' }}</option><option value="Y">{{ isId ? 'Ya' : 'Yes' }}</option></select></label>
                        <label v-if="form.insured === 'Y'" class="ops-form-field"><span>{{ isId ? 'Nilai barang (Rp)' : 'Declared value (IDR)' }}</span><input v-model="form.declaredValue" type="number" min="0" max="2000000000"><small v-if="form.errors.declaredValue" class="ops-form-error">{{ form.errors.declaredValue }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Surat jalan kembali' : 'Return delivery document' }}</span><select v-model="form.returnDocument"><option value="N">{{ isId ? 'Tidak' : 'No' }}</option><option value="Y">{{ isId ? 'Ya' : 'Yes' }}</option></select></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Pembayaran khusus' : 'Special payment' }}</span><select v-model="form.specialPayment"><option value="0">{{ isId ? 'Tidak' : 'No' }}</option><option value="1">{{ isId ? 'Ya' : 'Yes' }}</option></select></label>
                        <template v-if="form.specialPayment === '1'"><label class="ops-form-field"><span>{{ isId ? 'Nama pembayaran' : 'Billing name' }}</span><input v-model="form.paymentName" maxlength="100"></label><label class="ops-form-field"><span>{{ isId ? 'Alamat pembayaran' : 'Billing address' }}</span><input v-model="form.paymentAddress" maxlength="200"></label><label class="ops-form-field"><span>{{ isId ? 'Kontak pembayaran' : 'Billing contact' }}</span><input v-model="form.paymentContact" maxlength="100"></label><label class="ops-form-field"><span>{{ isId ? 'Telepon pembayaran' : 'Billing phone' }}</span><input v-model="form.paymentPhone" maxlength="20"></label></template>
                        <template v-if="Number(form.service) === 6"><label class="ops-form-field"><span>20 ft</span><input v-model="form.container20" type="number" min="0" max="9999"></label><label class="ops-form-field"><span>40 ft</span><input v-model="form.container40" type="number" min="0" max="9999"></label><label class="ops-form-field"><span>40 ft HC</span><input v-model="form.container40HighCube" type="number" min="0" max="9999"></label><label class="ops-form-field"><span>LCL</span><input v-model="form.lcl" type="number" min="0" max="9999"></label></template>
                    </div></section>
                    <section v-if="packageDetailsEnabled" class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Rincian koli' : 'Package details' }}</h2></div><div class="shipment-collies"><article v-for="(collie, index) in form.collies" :key="index" class="shipment-collie-card"><div class="shipment-collie-heading"><strong>{{ isId ? `Koli ${index + 1}` : `Package ${index + 1}` }}</strong></div><div class="ops-user-form-grid"><label class="ops-form-field"><span>{{ isId ? 'Berat tagih (kg)' : 'Billable weight (kg)' }}</span><input v-model="collie.weight" type="number" min="1" max="450" :disabled="shipment.priceLocked"></label><label class="ops-form-field"><span>{{ isId ? 'Berat aktual (kg)' : 'Actual weight (kg)' }}</span><input v-model="collie.actualWeight" type="number" min="0.01" max="450" step="0.01" :disabled="shipment.priceLocked"></label><label class="ops-form-field"><span>{{ isId ? 'Panjang (cm)' : 'Length (cm)' }}</span><input v-model="collie.length" type="number" min="1" max="450" :disabled="shipment.priceLocked"></label><label class="ops-form-field"><span>{{ isId ? 'Lebar (cm)' : 'Width (cm)' }}</span><input v-model="collie.width" type="number" min="1" max="450" :disabled="shipment.priceLocked"></label><label class="ops-form-field"><span>{{ isId ? 'Tinggi (cm)' : 'Height (cm)' }}</span><input v-model="collie.height" type="number" min="1" max="450" :disabled="shipment.priceLocked"></label><label class="ops-form-field"><span>{{ isId ? 'Catatan koli' : 'Package note' }}</span><input v-model="collie.note" maxlength="275"></label></div></article></div><small v-if="form.errors.collies" class="ops-form-error">{{ form.errors.collies }}</small></section>
                    <p v-if="shipment.priceLocked" class="ops-flash error">{{ isId ? 'Layanan, berat, dan jumlah koli terkunci karena pengiriman sudah diproses untuk penagihan.' : 'Service, weight, and package count are locked because this shipment has been billed.' }}</p>
                    <div class="ops-user-form-footer"><Link class="ops-secondary-button" href="/e-cesco/shipments">{{ isId ? 'Batal' : 'Cancel' }}</Link><button class="ops-primary-button" type="submit" :disabled="form.processing"><span>✓</span>{{ form.processing ? (isId ? 'Menyimpan…' : 'Saving…') : (isId ? 'Simpan perubahan' : 'Save changes') }}</button></div>
                </form>
            </div>
        </main>
    </div>
</template>
