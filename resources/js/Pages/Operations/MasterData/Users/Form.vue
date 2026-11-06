<script setup>
import { computed, ref } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../../Layouts/OperationsSidebar.vue'

const props = defineProps({
    user: { type: Object, default: null },
    levels: { type: Array, required: true },
    branches: { type: Array, required: true },
})

const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const form = useForm({
    name: props.user?.name || '',
    username: props.user?.username || '',
    email: props.user?.email || '',
    telp: props.user?.telp || '',
    level: props.user?.level || 'operator',
    id_cabang: props.user?.id_cabang || '',
    blokir: props.user?.blokir || 'N',
    password: '',
    password_confirmation: '',
})

function submit() {
    if (props.user) {
        form.put(`/e-cesco/master-data/users/${props.user.id}`)

        return
    }

    form.post('/e-cesco/master-data/users')
}

function showFeature(name) {
    window.alert(isId.value ? `${name}: Modul ini akan dibuat selanjutnya.` : `${name}: This module is coming next.`)
}
</script>

<template>
    <Head :title="props.user ? 'Edit User' : (isId ? 'Tambah User' : 'Add User')" />

    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>
        <OperationsSidebar active-page="master-data-users" :open="sidebarOpen" @close="sidebarOpen = false" @feature="showFeature" />

        <main class="ops-main">
            <header class="ops-topbar">
                <button class="ops-mobile-toggle" type="button" :aria-label="isId ? 'Buka navigasi' : 'Open navigation'" @click="sidebarOpen = !sidebarOpen">☰</button>
                <div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><Link href="/e-cesco/master-data/users">Master Data / {{ isId ? 'User' : 'Users' }}</Link><span>/</span><strong>{{ props.user ? 'Edit' : (isId ? 'Tambah' : 'Create') }}</strong></div>
                <OperationsAccountMenu />
            </header>

            <div class="ops-content">
                <div class="ops-data-heading">
                    <div>
                        <p class="ops-date">{{ isId ? 'MASTER DATA / USER' : 'MASTER DATA / USERS' }}</p>
                        <h1>{{ props.user ? (isId ? 'Edit user' : 'Edit user') : (isId ? 'Tambah user baru' : 'Add a new user') }}</h1>
                        <p>{{ isId ? 'Lengkapi informasi akun dan atur aksesnya.' : 'Enter account details and set access.' }}</p>
                    </div>
                    <Link class="ops-secondary-button" href="/e-cesco/master-data/users">← {{ isId ? 'Kembali' : 'Back' }}</Link>
                </div>

                <div v-if="page.props.flash?.error" class="ops-flash error" role="alert">{{ page.props.flash.error }}</div>

                <form class="ops-panel ops-user-form" @submit.prevent="submit">
                    <div class="ops-user-form-section">
                        <div class="ops-user-form-heading"><h2>{{ isId ? 'Informasi akun' : 'Account information' }}</h2><p>{{ isId ? 'Data ini digunakan untuk mengenali pengguna.' : 'These details identify the account.' }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Nama lengkap' : 'Full name' }} <b>*</b></span><input v-model="form.name" type="text" maxlength="100" required autocomplete="name" :aria-invalid="Boolean(form.errors.name)"><small v-if="form.errors.name" class="ops-form-error">{{ form.errors.name }}</small></label>
                            <label class="ops-form-field"><span>Username <b>*</b></span><input v-model="form.username" type="text" maxlength="20" required autocomplete="username" :aria-invalid="Boolean(form.errors.username)"><small v-if="form.errors.username" class="ops-form-error">{{ form.errors.username }}</small></label>
                            <label class="ops-form-field"><span>Email</span><input v-model="form.email" type="email" maxlength="255" autocomplete="email" :aria-invalid="Boolean(form.errors.email)"><small v-if="form.errors.email" class="ops-form-error">{{ form.errors.email }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Nomor telepon' : 'Phone number' }}</span><input v-model="form.telp" type="tel" maxlength="20" autocomplete="tel" :aria-invalid="Boolean(form.errors.telp)"><small v-if="form.errors.telp" class="ops-form-error">{{ form.errors.telp }}</small></label>
                        </div>
                    </div>

                    <div class="ops-user-form-section">
                        <div class="ops-user-form-heading"><h2>{{ isId ? 'Akses dan penempatan' : 'Access and assignment' }}</h2><p>{{ isId ? 'Level menentukan hak akses pengguna di sistem.' : 'The role determines what the user can access.' }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Level / posisi' : 'Role / level' }} <b>*</b></span><select v-model="form.level" required :aria-invalid="Boolean(form.errors.level)"><option v-for="level in levels" :key="level" :value="level">{{ level.replaceAll('_', ' ') }}</option></select><small v-if="form.errors.level" class="ops-form-error">{{ form.errors.level }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Cabang' : 'Branch' }}</span><select v-model="form.id_cabang" :aria-invalid="Boolean(form.errors.id_cabang)"><option value="">{{ isId ? 'Pilih cabang' : 'Select a branch' }}</option><option v-for="branch in branches" :key="branch.id" :value="branch.id">{{ branch.label }}</option></select><small v-if="form.errors.id_cabang" class="ops-form-error">{{ form.errors.id_cabang }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Status akun' : 'Account status' }} <b>*</b></span><select v-model="form.blokir" required :aria-invalid="Boolean(form.errors.blokir)"><option value="N">{{ isId ? 'Aktif' : 'Active' }}</option><option value="Y">{{ isId ? 'Diblokir' : 'Blocked' }}</option></select><small v-if="form.errors.blokir" class="ops-form-error">{{ form.errors.blokir }}</small></label>
                        </div>
                    </div>

                    <div class="ops-user-form-section">
                        <div class="ops-user-form-heading"><h2>{{ isId ? 'Kata sandi' : 'Password' }}</h2><p>{{ props.user ? (isId ? 'Kosongkan jika tidak ingin mengganti kata sandi.' : 'Leave blank to keep the current password.') : (isId ? 'Gunakan minimal 12 karakter.' : 'Use at least 12 characters.') }}</p></div>
                        <div class="ops-user-form-grid">
                            <label class="ops-form-field"><span>{{ isId ? 'Kata sandi' : 'Password' }}<b v-if="!props.user"> *</b></span><input v-model="form.password" type="password" :required="!props.user" minlength="12" autocomplete="new-password" :aria-invalid="Boolean(form.errors.password)"><small v-if="form.errors.password" class="ops-form-error">{{ form.errors.password }}</small></label>
                            <label class="ops-form-field"><span>{{ isId ? 'Konfirmasi kata sandi' : 'Confirm password' }}<b v-if="!props.user"> *</b></span><input v-model="form.password_confirmation" type="password" :required="!props.user" minlength="12" autocomplete="new-password"></label>
                        </div>
                    </div>

                    <div class="ops-user-form-footer">
                        <Link class="ops-secondary-button" href="/e-cesco/master-data/users">{{ isId ? 'Batal' : 'Cancel' }}</Link>
                        <button class="ops-primary-button" type="submit" :disabled="form.processing"><span>✓</span>{{ form.processing ? (isId ? 'Menyimpan…' : 'Saving…') : (isId ? 'Simpan user' : 'Save user') }}</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>
