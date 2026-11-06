<script setup>
import { computed, ref } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../Layouts/OperationsSidebar.vue'

const props = defineProps({ shipment: { type: Object, required: true } })
const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const form = useForm({
    sender: props.shipment.sender || '', senderCompany: props.shipment.senderCompany || '',
    senderAddress: props.shipment.senderAddress || '', senderPhone: props.shipment.senderPhone || '',
    recipient: props.shipment.recipient || '', recipientCompany: props.shipment.recipientCompany || '',
    recipientAddress: props.shipment.recipientAddress || '', recipientAddressDetails: props.shipment.recipientAddressDetails || '',
    recipientPhone: props.shipment.recipientPhone || '', goods: props.shipment.goods || '', notes: props.shipment.notes || '',
})

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
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Pengirim' : 'Sender' }}</h2></div><div class="ops-user-form-grid">
                        <label class="ops-form-field"><span>{{ isId ? 'Nama pengirim' : 'Sender name' }}</span><input v-model="form.sender" maxlength="100"><small v-if="form.errors.sender" class="ops-form-error">{{ form.errors.sender }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Perusahaan pengirim' : 'Sender company' }}</span><input v-model="form.senderCompany" maxlength="100"><small v-if="form.errors.senderCompany" class="ops-form-error">{{ form.errors.senderCompany }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Telepon pengirim' : 'Sender phone' }}</span><input v-model="form.senderPhone" maxlength="20"><small v-if="form.errors.senderPhone" class="ops-form-error">{{ form.errors.senderPhone }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Alamat pengirim' : 'Sender address' }}</span><textarea v-model="form.senderAddress" maxlength="300" rows="3"></textarea><small v-if="form.errors.senderAddress" class="ops-form-error">{{ form.errors.senderAddress }}</small></label>
                    </div></section>
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Penerima' : 'Recipient' }}</h2></div><div class="ops-user-form-grid">
                        <label class="ops-form-field"><span>{{ isId ? 'Nama penerima' : 'Recipient name' }}</span><input v-model="form.recipient" maxlength="100"><small v-if="form.errors.recipient" class="ops-form-error">{{ form.errors.recipient }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Perusahaan penerima' : 'Recipient company' }}</span><input v-model="form.recipientCompany" maxlength="100"><small v-if="form.errors.recipientCompany" class="ops-form-error">{{ form.errors.recipientCompany }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Telepon penerima' : 'Recipient phone' }}</span><input v-model="form.recipientPhone" maxlength="20"><small v-if="form.errors.recipientPhone" class="ops-form-error">{{ form.errors.recipientPhone }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Alamat penerima' : 'Recipient address' }}</span><input v-model="form.recipientAddress" maxlength="100"><small v-if="form.errors.recipientAddress" class="ops-form-error">{{ form.errors.recipientAddress }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Detail alamat' : 'Address details' }}</span><textarea v-model="form.recipientAddressDetails" maxlength="80" rows="3"></textarea><small v-if="form.errors.recipientAddressDetails" class="ops-form-error">{{ form.errors.recipientAddressDetails }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Jenis barang' : 'Goods description' }}</span><input v-model="form.goods" maxlength="100"><small v-if="form.errors.goods" class="ops-form-error">{{ form.errors.goods }}</small></label>
                        <label class="ops-form-field"><span>{{ isId ? 'Catatan' : 'Notes' }}</span><textarea v-model="form.notes" maxlength="100" rows="3"></textarea><small v-if="form.errors.notes" class="ops-form-error">{{ form.errors.notes }}</small></label>
                    </div></section>
                    <div class="ops-user-form-footer"><Link class="ops-secondary-button" href="/e-cesco/shipments">{{ isId ? 'Batal' : 'Cancel' }}</Link><button class="ops-primary-button" type="submit" :disabled="form.processing"><span>✓</span>{{ form.processing ? (isId ? 'Menyimpan…' : 'Saving…') : (isId ? 'Simpan perubahan' : 'Save changes') }}</button></div>
                </form>
            </div>
        </main>
    </div>
</template>
