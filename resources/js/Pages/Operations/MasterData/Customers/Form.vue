<script setup>
import { computed, ref } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../../Layouts/OperationsSidebar.vue'

const props = defineProps({ customer: { type: Object, default: null }, branches: { type: Array, required: true } })
const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const form = useForm({
    inisial: props.customer?.inisial || '', id_cabang: props.customer?.id_cabang || '',
    kode_kustomer: props.customer?.kode_kustomer || '', nama_kustomer: props.customer?.nama_kustomer || '',
    callname: props.customer?.callname || '', alamat: props.customer?.alamat || '',
    telp: props.customer?.telp || '', pic_utama: props.customer?.pic_utama || '',
    status: props.customer?.status || 'Y', dhl: props.customer?.dhl || 'N',
    cesco: props.customer?.cesco || 'N', akun_utama: props.customer?.akun_utama === 'Y' ? 'Y' : '',
})

function submit() {
    if (props.customer) {
        form.put(`/e-cesco/master-data/customers/${props.customer.id}`)
        return
    }
    form.post('/e-cesco/master-data/customers')
}
</script>

<template>
    <Head :title="props.customer ? (isId ? 'Edit Kustomer' : 'Edit Customer') : (isId ? 'Tambah Kustomer' : 'Add Customer')" />
    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>
        <OperationsSidebar active-page="master-data-customers" :open="sidebarOpen" @close="sidebarOpen = false" />
        <main class="ops-main">
            <header class="ops-topbar"><button class="ops-mobile-toggle" type="button" :aria-label="isId ? 'Buka navigasi' : 'Open navigation'" @click="sidebarOpen = !sidebarOpen">☰</button><div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><Link href="/e-cesco/master-data/customers">Master Data / {{ isId ? 'Kustomer' : 'Customers' }}</Link><span>/</span><strong>{{ props.customer ? 'Edit' : (isId ? 'Tambah' : 'Create') }}</strong></div><OperationsAccountMenu /></header>
            <div class="ops-content">
                <div class="ops-data-heading"><div><p class="ops-date">{{ isId ? 'MASTER DATA / KUSTOMER' : 'MASTER DATA / CUSTOMERS' }}</p><h1>{{ props.customer ? (isId ? 'Edit kustomer' : 'Edit customer') : (isId ? 'Tambah kustomer baru' : 'Add a new customer') }}</h1><p>{{ isId ? 'Lengkapi data perusahaan dan hubungkan dengan cabang.' : 'Enter the company details and assign a branch.' }}</p></div><Link class="ops-secondary-button" href="/e-cesco/master-data/customers">← {{ isId ? 'Kembali' : 'Back' }}</Link></div>
                <div v-if="page.props.flash?.error" class="ops-flash error" role="alert">{{ page.props.flash.error }}</div>
                <form class="ops-panel ops-user-form" @submit.prevent="submit">
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Informasi perusahaan' : 'Company information' }}</h2><p>{{ isId ? 'Data ini digunakan untuk identifikasi dan layanan pengiriman.' : 'Used to identify the customer and its shipping services.' }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Cabang' : 'Branch' }} <b>*</b></span><select v-model="form.id_cabang" required :aria-invalid="Boolean(form.errors.id_cabang)"><option value="">{{ isId ? 'Pilih cabang' : 'Select a branch' }}</option><option v-for="branch in branches" :key="branch.id" :value="branch.id">{{ branch.label }}</option></select><small v-if="form.errors.id_cabang" class="ops-form-error">{{ form.errors.id_cabang }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Kode kustomer' : 'Customer code' }} <b>*</b></span><input v-model="form.kode_kustomer" type="text" maxlength="3" minlength="3" required :aria-invalid="Boolean(form.errors.kode_kustomer)"><small v-if="form.errors.kode_kustomer" class="ops-form-error">{{ form.errors.kode_kustomer }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Nama perusahaan / kustomer' : 'Customer / company name' }} <b>*</b></span><input v-model="form.nama_kustomer" type="text" maxlength="100" required :aria-invalid="Boolean(form.errors.nama_kustomer)"><small v-if="form.errors.nama_kustomer" class="ops-form-error">{{ form.errors.nama_kustomer }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Nama panggilan' : 'Display name' }}</span><input v-model="form.callname" type="text" maxlength="100" :aria-invalid="Boolean(form.errors.callname)"><small v-if="form.errors.callname" class="ops-form-error">{{ form.errors.callname }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Inisial (opsional)' : 'Initial (optional)' }}</span><input v-model="form.inisial" type="text" maxlength="3" :aria-invalid="Boolean(form.errors.inisial)"><small v-if="form.errors.inisial" class="ops-form-error">{{ form.errors.inisial }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Alamat' : 'Address' }}</span><textarea v-model="form.alamat" rows="3" maxlength="300" :aria-invalid="Boolean(form.errors.alamat)"></textarea><small v-if="form.errors.alamat" class="ops-form-error">{{ form.errors.alamat }}</small></label>
                        </div>
                    </section>
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Kontak utama' : 'Primary contact' }}</h2><p>{{ isId ? 'Informasi untuk komunikasi terkait pengiriman.' : 'Contact details for shipment communications.' }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Nama PIC' : 'Contact person' }}</span><input v-model="form.pic_utama" type="text" maxlength="30" :aria-invalid="Boolean(form.errors.pic_utama)"><small v-if="form.errors.pic_utama" class="ops-form-error">{{ form.errors.pic_utama }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Nomor telepon' : 'Phone number' }}</span><input v-model="form.telp" type="tel" maxlength="100" :aria-invalid="Boolean(form.errors.telp)"><small v-if="form.errors.telp" class="ops-form-error">{{ form.errors.telp }}</small></label>
                        </div>
                    </section>
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Akses dan status layanan' : 'Service access and status' }}</h2><p>{{ isId ? 'Atur status kustomer dan layanan yang tersedia.' : 'Set customer status and available services.' }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Status' : 'Status' }} <b>*</b></span><select v-model="form.status" required :aria-invalid="Boolean(form.errors.status)"><option value="Y">{{ isId ? 'Aktif' : 'Active' }}</option><option value="N">{{ isId ? 'Nonaktif' : 'Inactive' }}</option></select><small v-if="form.errors.status" class="ops-form-error">{{ form.errors.status }}</small></label>
                            <label class="ops-form-field"><span>Cesco</span><select v-model="form.cesco"><option value="Y">{{ isId ? 'Ya' : 'Yes' }}</option><option value="N">{{ isId ? 'Tidak' : 'No' }}</option></select><small v-if="form.errors.cesco" class="ops-form-error">{{ form.errors.cesco }}</small></label>
                            <label class="ops-form-field"><span>DHL</span><select v-model="form.dhl"><option value="Y">{{ isId ? 'Ya' : 'Yes' }}</option><option value="N">{{ isId ? 'Tidak' : 'No' }}</option></select><small v-if="form.errors.dhl" class="ops-form-error">{{ form.errors.dhl }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Akun utama' : 'Primary account' }}</span><select v-model="form.akun_utama"><option value="">{{ isId ? 'Tidak' : 'No' }}</option><option value="Y">{{ isId ? 'Ya' : 'Yes' }}</option></select><small v-if="form.errors.akun_utama" class="ops-form-error">{{ form.errors.akun_utama }}</small></label>
                        </div>
                    </section>
                    <div class="ops-user-form-footer"><Link class="ops-secondary-button" href="/e-cesco/master-data/customers">{{ isId ? 'Batal' : 'Cancel' }}</Link><button class="ops-primary-button" type="submit" :disabled="form.processing"><span>✓</span>{{ form.processing ? (isId ? 'Menyimpan…' : 'Saving…') : (isId ? 'Simpan kustomer' : 'Save customer') }}</button></div>
                </form>
            </div>
        </main>
    </div>
</template>
