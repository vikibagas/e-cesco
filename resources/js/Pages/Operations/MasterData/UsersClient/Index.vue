<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../../Layouts/OperationsSidebar.vue'

const props = defineProps({
    clients: { type: Object, required: true },
    filters: { type: Object, required: true },
})

const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const search = ref(props.filters.search || '')
const toast = ref('')

function notify(message) {
    toast.value = message
    window.setTimeout(() => {
        toast.value = ''
    }, 3000)
}

function showFeature(name) {
    notify(isId.value ? `${name}: Modul ini akan dibuat selanjutnya.` : `${name}: This module is coming next.`)
}

function searchClients() {
    router.get('/e-cesco/master-data/users-client', { search: search.value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}

function deleteClient(client) {
    const message = isId.value
        ? `Hapus akun pelanggan ${client.name} (${client.username})? Tindakan ini tidak dapat dibatalkan.`
        : `Delete client account ${client.name} (${client.username})? This cannot be undone.`

    if (window.confirm(message)) {
        router.delete(`/e-cesco/master-data/users-client/${client.id}`, { preserveScroll: true })
    }
}
</script>

<template>
    <Head :title="isId ? 'Master Data — User Client' : 'Master Data — Client Users'" />

    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>
        <OperationsSidebar active-page="master-data-users-client" :open="sidebarOpen" @close="sidebarOpen = false" @feature="showFeature" />

        <main class="ops-main">
            <header class="ops-topbar">
                <button class="ops-mobile-toggle" type="button" :aria-label="isId ? 'Buka navigasi' : 'Open navigation'" @click="sidebarOpen = !sidebarOpen">☰</button>
                <div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><span>Master Data</span><span>/</span><strong>{{ isId ? 'User Client' : 'Client Users' }}</strong></div>
                <div class="ops-top-actions">
                    <div class="ops-language" :aria-label="isId ? 'Pilih bahasa' : 'Choose language'">
                        <Link :href="page.props.site.localeUrls.id" :class="{ active: isId }" lang="id" hreflang="id" preserve-scroll>ID</Link>
                        <span></span>
                        <Link :href="page.props.site.localeUrls.en" :class="{ active: !isId }" lang="en" hreflang="en" preserve-scroll>EN</Link>
                    </div>
                    <OperationsAccountMenu />
                </div>
            </header>

            <div class="ops-content">
                <div class="ops-data-heading">
                    <div>
                        <p class="ops-date">MASTER DATA</p>
                        <h1>{{ isId ? 'Manajemen User Client' : 'Client User Management' }}</h1>
                        <p>{{ isId ? 'Kelola akun pelanggan yang menggunakan e-Cesco.' : 'Manage customer accounts that use e-Cesco.' }}</p>
                    </div>
                    <Link class="ops-primary-button" href="/e-cesco/master-data/users-client/create"><span>＋</span>{{ isId ? 'Tambah user client' : 'Add client user' }}</Link>
                </div>

                <div v-if="page.props.flash?.success" class="ops-flash success" role="status">{{ page.props.flash.success }}</div>
                <div v-if="page.props.flash?.error" class="ops-flash error" role="alert">{{ page.props.flash.error }}</div>

                <section class="ops-panel ops-user-list-panel">
                    <div class="ops-user-list-toolbar">
                        <div><h2>{{ isId ? 'Daftar user client' : 'Client users' }}</h2><p>{{ clients.total.toLocaleString() }} {{ isId ? 'akun pelanggan' : 'customer accounts' }}</p></div>
                        <form class="ops-user-search" role="search" @submit.prevent="searchClients">
                            <span aria-hidden="true">⌕</span>
                            <input v-model="search" type="search" :placeholder="isId ? 'Cari nama, username, perusahaan…' : 'Search name, username, company…'">
                            <button type="submit">{{ isId ? 'Cari' : 'Search' }}</button>
                        </form>
                    </div>

                    <div class="ops-table-wrap">
                        <table class="ops-table ops-client-table">
                            <thead>
                                <tr>
                                    <th>{{ isId ? 'Nama pelanggan' : 'Customer' }}</th>
                                    <th>Username</th>
                                    <th>{{ isId ? 'Perusahaan' : 'Company' }}</th>
                                    <th>Email</th>
                                    <th>{{ isId ? 'Jenis akses' : 'Access type' }}</th>
                                    <th>{{ isId ? 'Status' : 'Status' }}</th>
                                    <th>{{ isId ? 'Aksi' : 'Actions' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="client in clients.data" :key="client.id">
                                    <td><strong class="ops-user-name">{{ client.name || '—' }}</strong></td>
                                    <td><span class="ops-user-username">{{ client.username }}</span></td>
                                    <td>{{ client.company || '—' }}</td>
                                    <td>{{ client.email || '—' }}</td>
                                    <td><span class="ops-user-level">{{ client.type }}</span></td>
                                    <td><span class="ops-user-status" :class="client.blocked ? 'blocked' : (client.expired ? 'blocked' : 'enabled')">{{ client.blocked ? (isId ? 'Diblokir' : 'Blocked') : (client.expired ? (isId ? 'Kedaluwarsa' : 'Expired') : (isId ? 'Aktif' : 'Active')) }}</span></td>
                                    <td>
                                        <div class="ops-user-actions">
                                            <Link class="ops-user-edit" :href="`/e-cesco/master-data/users-client/${client.id}/edit`">Edit</Link>
                                            <button class="ops-user-delete" type="button" @click="deleteClient(client)">{{ isId ? 'Hapus' : 'Delete' }}</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="clients.data.length === 0"><td colspan="7" class="ops-empty">{{ isId ? 'Tidak ada akun pelanggan yang cocok.' : 'No matching client accounts found.' }}</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <footer class="ops-user-pagination">
                        <span>{{ isId ? 'Menampilkan' : 'Showing' }} {{ clients.from || 0 }}–{{ clients.to || 0 }} {{ isId ? 'dari' : 'of' }} {{ clients.total }}</span>
                        <nav :aria-label="isId ? 'Navigasi halaman' : 'Page navigation'">
                            <template v-for="link in clients.links" :key="link.label">
                                <Link v-if="link.url" :href="link.url" :class="{ active: link.active }" preserve-scroll v-html="link.label"></Link>
                                <span v-else class="disabled" v-html="link.label"></span>
                            </template>
                        </nav>
                    </footer>
                </section>
            </div>
        </main>

        <Transition name="ops-toast"><div v-if="toast" class="ops-toast-message" role="status">{{ toast }}</div></Transition>
    </div>
</template>
