<script setup>
import { computed, ref } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../../Layouts/OperationsSidebar.vue'

const props = defineProps({
    client: { type: Object, default: null },
    types: { type: Array, required: true },
    customers: { type: Array, required: true },
})

const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const form = useForm({
    fullname: props.client?.fullname || '',
    username: props.client?.username || '',
    email: props.client?.email || '',
    phone: props.client?.phone || '',
    id_kustomer: props.client?.id_kustomer || '',
    customers: props.client?.customers || '',
    type: props.client?.type || 'all',
    blocked: props.client?.blocked || 'N',
    expired_date: props.client?.expired_date || '',
    report_awb_loc: props.client?.report_awb_loc ?? 0,
    password: '',
    password_confirmation: '',
})
const selectedCustomerName = computed(() => {
    const selectedCustomer = props.customers.find((customer) => String(customer.id) === String(form.id_kustomer))

    return selectedCustomer?.name || (props.client?.id_kustomer ? '' : props.client?.company || '')
})

function submit() {
    if (props.client) {
        form.put(`/e-cesco/master-data/users-client/${props.client.id}`)

        return
    }

    form.post('/e-cesco/master-data/users-client')
}

function showFeature(name) {
    window.alert(isId.value ? `${name}: Modul ini akan dibuat selanjutnya.` : `${name}: This module is coming next.`)
}
</script>

<template>
    <Head :title="props.client ? (isId ? 'Edit User Client' : 'Edit Client User') : (isId ? 'Tambah User Client' : 'Add Client User')" />

    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>
        <OperationsSidebar active-page="master-data-users-client" :open="sidebarOpen" @close="sidebarOpen = false" @feature="showFeature" />

        <main class="ops-main">
            <header class="ops-topbar">
                <button class="ops-mobile-toggle" type="button" :aria-label="isId ? 'Buka navigasi' : 'Open navigation'" @click="sidebarOpen = !sidebarOpen">☰</button>
                <div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><Link href="/e-cesco/master-data/users-client">Master Data / {{ isId ? 'User Client' : 'Client Users' }}</Link><span>/</span><strong>{{ props.client ? 'Edit' : (isId ? 'Tambah' : 'Create') }}</strong></div>
                <OperationsAccountMenu />
            </header>

            <div class="ops-content">
                <div class="ops-data-heading">
                    <div>
                        <p class="ops-date">{{ isId ? 'MASTER DATA / USER CLIENT' : 'MASTER DATA / CLIENT USERS' }}</p>
                        <h1>{{ props.client ? (isId ? 'Edit user client' : 'Edit client user') : (isId ? 'Tambah user client' : 'Add a client user') }}</h1>
                        <p>{{ isId ? 'Kelola informasi akun dan akses pelanggan.' : 'Manage customer account details and access.' }}</p>
                    </div>
                    <Link class="ops-secondary-button" href="/e-cesco/master-data/users-client">← {{ isId ? 'Kembali' : 'Back' }}</Link>
                </div>

                <div v-if="page.props.flash?.error" class="ops-flash error" role="alert">{{ page.props.flash.error }}</div>

                <form class="ops-panel ops-user-form" @submit.prevent="submit">
                    <div class="ops-user-form-section">
                        <div class="ops-user-form-heading"><h2>{{ isId ? 'Informasi pelanggan' : 'Customer information' }}</h2><p>{{ isId ? 'Username digunakan pelanggan untuk masuk ke e-Cesco.' : 'Customers use the username to sign in to e-Cesco.' }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Nama lengkap' : 'Full name' }} <b>*</b></span><input v-model="form.fullname" type="text" maxlength="100" required autocomplete="name" :aria-invalid="Boolean(form.errors.fullname)"><small v-if="form.errors.fullname" class="ops-form-error">{{ form.errors.fullname }}</small></label>
                            <label class="ops-form-field"><span>Username <b>*</b></span><input v-model="form.username" type="text" maxlength="50" required autocomplete="username" :aria-invalid="Boolean(form.errors.username)"><small v-if="form.errors.username" class="ops-form-error">{{ form.errors.username }}</small></label>
                            <label class="ops-form-field"><span>Email</span><input v-model="form.email" type="email" maxlength="100" autocomplete="email" :aria-invalid="Boolean(form.errors.email)"><small v-if="form.errors.email" class="ops-form-error">{{ form.errors.email }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Perusahaan / customer' : 'Company / customer' }}</span><select v-model="form.id_kustomer" :aria-invalid="Boolean(form.errors.id_kustomer)"><option value="">{{ isId ? 'Pilih perusahaan' : 'Select a company' }}</option><option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.label }}</option></select><small v-if="form.errors.id_kustomer" class="ops-form-error">{{ form.errors.id_kustomer }}</small><small v-if="selectedCustomerName" class="ops-customer-preview">{{ selectedCustomerName }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Nomor telepon' : 'Phone number' }}</span><input v-model="form.phone" type="tel" maxlength="13" autocomplete="tel" :aria-invalid="Boolean(form.errors.phone)"><small v-if="form.errors.phone" class="ops-form-error">{{ form.errors.phone }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Nama customer pada data lama' : 'Legacy customer name' }}</span><input v-model="form.customers" type="text" maxlength="100" :aria-invalid="Boolean(form.errors.customers)"><small v-if="form.errors.customers" class="ops-form-error">{{ form.errors.customers }}</small></label>
                        </div>
                    </div>

                    <div class="ops-user-form-section">
                        <div class="ops-user-form-heading"><h2>{{ isId ? 'Akses akun' : 'Account access' }}</h2><p>{{ isId ? 'Atur cakupan layanan dan masa berlaku akun.' : 'Set service scope and account expiry.' }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Jenis akses' : 'Access type' }} <b>*</b></span><select v-model="form.type" required :aria-invalid="Boolean(form.errors.type)"><option v-for="type in types" :key="type" :value="type">{{ type }}</option></select><small v-if="form.errors.type" class="ops-form-error">{{ form.errors.type }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Status akun' : 'Account status' }} <b>*</b></span><select v-model="form.blocked" required :aria-invalid="Boolean(form.errors.blocked)"><option value="N">{{ isId ? 'Aktif' : 'Active' }}</option><option value="Y">{{ isId ? 'Diblokir' : 'Blocked' }}</option></select><small v-if="form.errors.blocked" class="ops-form-error">{{ form.errors.blocked }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Tanggal kedaluwarsa' : 'Expiration date' }}</span><input v-model="form.expired_date" type="date" :aria-invalid="Boolean(form.errors.expired_date)"><small v-if="form.errors.expired_date" class="ops-form-error">{{ form.errors.expired_date }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Lokasi report AWB (ID)' : 'AWB report location ID' }} <b>*</b></span><input v-model="form.report_awb_loc" type="number" min="0" required :aria-invalid="Boolean(form.errors.report_awb_loc)"><small v-if="form.errors.report_awb_loc" class="ops-form-error">{{ form.errors.report_awb_loc }}</small></label>
                        </div>
                    </div>

                    <div class="ops-user-form-section">
                        <div class="ops-user-form-heading"><h2>{{ isId ? 'Kata sandi' : 'Password' }}</h2><p>{{ props.client ? (isId ? 'Kosongkan jika tidak ingin mengganti kata sandi.' : 'Leave blank to keep the current password.') : (isId ? 'Gunakan minimal 12 karakter.' : 'Use at least 12 characters.') }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Kata sandi' : 'Password' }}<b v-if="!props.client"> *</b></span><input v-model="form.password" type="password" :required="!props.client" minlength="12" autocomplete="new-password" :aria-invalid="Boolean(form.errors.password)"><small v-if="form.errors.password" class="ops-form-error">{{ form.errors.password }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Konfirmasi kata sandi' : 'Confirm password' }}<b v-if="!props.client"> *</b></span><input v-model="form.password_confirmation" type="password" :required="!props.client" minlength="12" autocomplete="new-password"></label>
                        </div>
                    </div>

                    <div class="ops-user-form-footer">
                        <Link class="ops-secondary-button" href="/e-cesco/master-data/users-client">{{ isId ? 'Batal' : 'Cancel' }}</Link>
                        <button class="ops-primary-button" type="submit" :disabled="form.processing"><span>✓</span>{{ form.processing ? (isId ? 'Menyimpan…' : 'Saving…') : (isId ? 'Simpan user client' : 'Save client user') }}</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>
