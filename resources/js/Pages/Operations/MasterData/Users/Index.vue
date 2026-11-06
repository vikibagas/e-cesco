<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../../Layouts/OperationsSidebar.vue'

const props = defineProps({
    users: { type: Object, required: true },
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

function searchUsers() {
    router.get('/e-cesco/master-data/users', { search: search.value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}

function deleteUser(user) {
    const message = isId.value
        ? `Hapus user ${user.name} (${user.username})? Tindakan ini tidak dapat dibatalkan.`
        : `Delete user ${user.name} (${user.username})? This cannot be undone.`

    if (window.confirm(message)) {
        router.delete(`/e-cesco/master-data/users/${user.id}`, { preserveScroll: true })
    }
}
</script>

<template>
    <Head :title="isId ? 'Master Data — User' : 'Master Data — Users'" />

    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>
        <OperationsSidebar active-page="master-data-users" :open="sidebarOpen" @close="sidebarOpen = false" @feature="showFeature" />

        <main class="ops-main">
            <header class="ops-topbar">
                <button class="ops-mobile-toggle" type="button" :aria-label="isId ? 'Buka navigasi' : 'Open navigation'" @click="sidebarOpen = !sidebarOpen">☰</button>
                <div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><span>Master Data</span><span>/</span><strong>{{ isId ? 'User' : 'Users' }}</strong></div>
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
                        <h1>{{ isId ? 'Manajemen User' : 'User Management' }}</h1>
                        <p>{{ isId ? 'Kelola akun staf, level akses, dan cabang.' : 'Manage staff accounts, access levels, and branches.' }}</p>
                    </div>
                    <Link class="ops-primary-button" href="/e-cesco/master-data/users/create"><span>＋</span>{{ isId ? 'Tambah user' : 'Add user' }}</Link>
                </div>

                <div v-if="page.props.flash?.success" class="ops-flash success" role="status">{{ page.props.flash.success }}</div>
                <div v-if="page.props.flash?.error" class="ops-flash error" role="alert">{{ page.props.flash.error }}</div>

                <section class="ops-panel ops-user-list-panel">
                    <div class="ops-user-list-toolbar">
                        <div><h2>{{ isId ? 'Daftar user' : 'Users' }}</h2><p>{{ users.total.toLocaleString() }} {{ isId ? 'akun terdaftar' : 'accounts' }}</p></div>
                        <form class="ops-user-search" role="search" @submit.prevent="searchUsers">
                            <span aria-hidden="true">⌕</span>
                            <input v-model="search" type="search" :placeholder="isId ? 'Cari nama, username, email…' : 'Search name, username, email…'">
                            <button type="submit">{{ isId ? 'Cari' : 'Search' }}</button>
                        </form>
                    </div>

                    <div class="ops-table-wrap">
                        <table class="ops-table ops-user-table">
                            <thead>
                                <tr>
                                    <th>{{ isId ? 'Nama user' : 'User' }}</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>{{ isId ? 'Level' : 'Role' }}</th>
                                    <th>{{ isId ? 'Cabang' : 'Branch' }}</th>
                                    <th>Status</th>
                                    <th>{{ isId ? 'Aksi' : 'Actions' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td><strong class="ops-user-name">{{ user.name }}</strong></td>
                                    <td><span class="ops-user-username">{{ user.username }}</span></td>
                                    <td>{{ user.email || '—' }}</td>
                                    <td><span class="ops-user-level">{{ user.level?.replaceAll('_', ' ') || '—' }}</span></td>
                                    <td>{{ user.branch || '—' }}</td>
                                    <td><span class="ops-user-status" :class="user.blocked ? 'blocked' : 'enabled'">{{ user.blocked ? (isId ? 'Diblokir' : 'Blocked') : (isId ? 'Aktif' : 'Active') }}</span></td>
                                    <td>
                                        <div class="ops-user-actions">
                                            <Link class="ops-user-edit" :href="`/e-cesco/master-data/users/${user.id}/edit`">Edit</Link>
                                            <button class="ops-user-delete" type="button" @click="deleteUser(user)">{{ isId ? 'Hapus' : 'Delete' }}</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="users.data.length === 0"><td colspan="7" class="ops-empty">{{ isId ? 'Tidak ada user yang cocok.' : 'No matching users found.' }}</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <footer class="ops-user-pagination">
                        <span>{{ isId ? 'Menampilkan' : 'Showing' }} {{ users.from || 0 }}–{{ users.to || 0 }} {{ isId ? 'dari' : 'of' }} {{ users.total }}</span>
                        <nav :aria-label="isId ? 'Navigasi halaman' : 'Page navigation'">
                            <template v-for="link in users.links" :key="link.label">
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
