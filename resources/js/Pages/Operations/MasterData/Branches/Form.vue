<script setup>
import { computed, ref } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../../Layouts/OperationsSidebar.vue'

const props = defineProps({ branch: { type: Object, default: null } })
const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const form = useForm({
    kode_cabang: props.branch?.code || '', nama_cabang: props.branch?.name || '',
    provinsi_text: props.branch?.province || '', kota_text: props.branch?.city || '',
    alamat1: props.branch?.address || '', alamat2: props.branch?.addressDetails || '',
    pic: props.branch?.contact || '', telp: props.branch?.phone || '', email: props.branch?.email || '',
    maps: props.branch?.maps || '', google_maps: props.branch?.googleMaps || '',
    cabang_cesco: props.branch?.cesco || 'N', status: props.branch?.active || 'Y',
})

function submit() {
    if (props.branch) {
        form.put(`/e-cesco/master-data/branches/${props.branch.id}`)
        return
    }
    form.post('/e-cesco/master-data/branches')
}
</script>

<template>
    <Head :title="props.branch ? (isId ? 'Edit Cabang' : 'Edit Branch') : (isId ? 'Tambah Cabang' : 'Add Branch')" />
    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>
        <OperationsSidebar active-page="master-data-branches" :open="sidebarOpen" @close="sidebarOpen = false" />
        <main class="ops-main">
            <header class="ops-topbar"><button class="ops-mobile-toggle" type="button" :aria-label="isId ? 'Buka navigasi' : 'Open navigation'" @click="sidebarOpen = !sidebarOpen">☰</button><div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><Link href="/e-cesco/master-data/branches">Master Data / {{ isId ? 'Cabang' : 'Branches' }}</Link><span>/</span><strong>{{ props.branch ? 'Edit' : (isId ? 'Tambah' : 'Create') }}</strong></div><OperationsAccountMenu /></header>
            <div class="ops-content">
                <div class="ops-data-heading"><div><p class="ops-date">{{ isId ? 'MASTER DATA / CABANG' : 'MASTER DATA / BRANCHES' }}</p><h1>{{ props.branch ? (isId ? 'Edit cabang' : 'Edit branch') : (isId ? 'Tambah cabang baru' : 'Add a new branch') }}</h1><p>{{ isId ? 'Isi informasi dasar dan kontak cabang.' : 'Enter the branch details and contact information.' }}</p></div><Link class="ops-secondary-button" href="/e-cesco/master-data/branches">← {{ isId ? 'Kembali' : 'Back' }}</Link></div>
                <div v-if="page.props.flash?.error" class="ops-flash error" role="alert">{{ page.props.flash.error }}</div>
                <form class="ops-panel ops-user-form" @submit.prevent="submit">
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Informasi cabang' : 'Branch information' }}</h2><p>{{ isId ? 'Kode cabang mengikuti format 3 karakter pada data Cesco.' : 'Use the existing 3-character Cesco branch code format.' }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Kode cabang' : 'Branch code' }} <b>*</b></span><input v-model="form.kode_cabang" type="text" maxlength="3" minlength="3" required autocomplete="off" :aria-invalid="Boolean(form.errors.kode_cabang)"><small v-if="form.errors.kode_cabang" class="ops-form-error">{{ form.errors.kode_cabang }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Nama cabang' : 'Branch name' }} <b>*</b></span><input v-model="form.nama_cabang" type="text" maxlength="50" required :aria-invalid="Boolean(form.errors.nama_cabang)"><small v-if="form.errors.nama_cabang" class="ops-form-error">{{ form.errors.nama_cabang }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Kota' : 'City' }}</span><input v-model="form.kota_text" type="text" maxlength="75" :aria-invalid="Boolean(form.errors.kota_text)"><small v-if="form.errors.kota_text" class="ops-form-error">{{ form.errors.kota_text }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Provinsi' : 'Province' }}</span><input v-model="form.provinsi_text" type="text" maxlength="50" :aria-invalid="Boolean(form.errors.provinsi_text)"><small v-if="form.errors.provinsi_text" class="ops-form-error">{{ form.errors.provinsi_text }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Alamat' : 'Address' }}</span><input v-model="form.alamat1" type="text" maxlength="90" :aria-invalid="Boolean(form.errors.alamat1)"><small v-if="form.errors.alamat1" class="ops-form-error">{{ form.errors.alamat1 }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Alamat lengkap / patokan' : 'Full address / directions' }}</span><textarea v-model="form.alamat2" rows="3" maxlength="10000" :aria-invalid="Boolean(form.errors.alamat2)"></textarea><small v-if="form.errors.alamat2" class="ops-form-error">{{ form.errors.alamat2 }}</small></label>
                        </div>
                    </section>
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Kontak dan peta' : 'Contact and map' }}</h2><p>{{ isId ? 'Data ini membantu tim dan pelanggan menghubungi cabang.' : 'This information helps teams and customers reach the branch.' }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Nama PIC' : 'Contact person' }}</span><input v-model="form.pic" type="text" maxlength="35" :aria-invalid="Boolean(form.errors.pic)"><small v-if="form.errors.pic" class="ops-form-error">{{ form.errors.pic }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Nomor telepon' : 'Phone number' }}</span><input v-model="form.telp" type="tel" maxlength="100" :aria-invalid="Boolean(form.errors.telp)"><small v-if="form.errors.telp" class="ops-form-error">{{ form.errors.telp }}</small></label>
                            <label class="ops-form-field"><span>Email</span><input v-model="form.email" type="email" maxlength="50" :aria-invalid="Boolean(form.errors.email)"><small v-if="form.errors.email" class="ops-form-error">{{ form.errors.email }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Koordinat peta' : 'Map coordinates' }}</span><input v-model="form.maps" type="text" maxlength="1000" placeholder="-6.200000, 106.816666" :aria-invalid="Boolean(form.errors.maps)"><small v-if="form.errors.maps" class="ops-form-error">{{ form.errors.maps }}</small></label>
                            <label class="ops-form-field"><span>Google Maps URL</span><input v-model="form.google_maps" type="url" maxlength="150" placeholder="https://maps.google.com/..." :aria-invalid="Boolean(form.errors.google_maps)"><small v-if="form.errors.google_maps" class="ops-form-error">{{ form.errors.google_maps }}</small></label>
                        </div>
                    </section>
                    <section class="ops-user-form-section"><div class="ops-user-form-heading"><h2>{{ isId ? 'Status operasional' : 'Operational status' }}</h2><p>{{ isId ? 'Cabang nonaktif tetap disimpan agar riwayat operasional terjaga.' : 'Inactive branches remain stored to preserve operational history.' }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Status cabang' : 'Branch status' }} <b>*</b></span><select v-model="form.status" required :aria-invalid="Boolean(form.errors.status)"><option value="Y">{{ isId ? 'Aktif' : 'Active' }}</option><option value="N">{{ isId ? 'Nonaktif' : 'Inactive' }}</option></select><small v-if="form.errors.status" class="ops-form-error">{{ form.errors.status }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Cabang Cesco' : 'Cesco branch' }} <b>*</b></span><select v-model="form.cabang_cesco" required :aria-invalid="Boolean(form.errors.cabang_cesco)"><option value="Y">{{ isId ? 'Ya' : 'Yes' }}</option><option value="N">{{ isId ? 'Tidak' : 'No' }}</option></select><small v-if="form.errors.cabang_cesco" class="ops-form-error">{{ form.errors.cabang_cesco }}</small></label>
                        </div>
                    </section>
                    <div class="ops-user-form-footer"><Link class="ops-secondary-button" href="/e-cesco/master-data/branches">{{ isId ? 'Batal' : 'Cancel' }}</Link><button class="ops-primary-button" type="submit" :disabled="form.processing"><span>✓</span>{{ form.processing ? (isId ? 'Menyimpan…' : 'Saving…') : (isId ? 'Simpan cabang' : 'Save branch') }}</button></div>
                </form>
            </div>
        </main>
    </div>
</template>
