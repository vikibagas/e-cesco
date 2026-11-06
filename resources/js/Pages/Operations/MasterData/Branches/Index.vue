<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../../Layouts/OperationsSidebar.vue'

const props = defineProps({ branches: { type: Object, required: true }, filters: { type: Object, required: true } })
const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const search = ref(props.filters.search || '')
const status = ref(props.filters.status || '')

function searchBranches() {
    router.get('/e-cesco/master-data/branches', { search: search.value, status: status.value }, { preserveState: true, preserveScroll: true, replace: true })
}

function deleteBranch(branch) {
    const message = isId.value
        ? `Hapus cabang ${branch.code} — ${branch.name}? Cabang yang digunakan akun staf tidak dapat dihapus.`
        : `Delete branch ${branch.code} — ${branch.name}? Branches assigned to staff accounts cannot be deleted.`

    if (window.confirm(message)) {
        router.delete(`/e-cesco/master-data/branches/${branch.id}`, { preserveScroll: true })
    }
}
</script>

<template>
    <Head :title="isId ? 'Master Data — Cabang' : 'Master Data — Branches'" />
    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>
        <OperationsSidebar active-page="master-data-branches" :open="sidebarOpen" @close="sidebarOpen = false" />
        <main class="ops-main">
            <header class="ops-topbar">
                <button class="ops-mobile-toggle" type="button" :aria-label="isId ? 'Buka navigasi' : 'Open navigation'" @click="sidebarOpen = !sidebarOpen">☰</button>
                <div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><span>Master Data</span><span>/</span><strong>{{ isId ? 'Cabang' : 'Branches' }}</strong></div>
                <div class="ops-top-actions">
                    <div class="ops-language" :aria-label="isId ? 'Pilih bahasa' : 'Choose language'"><Link :href="page.props.site.localeUrls.id" :class="{ active: isId }" lang="id" hreflang="id" preserve-scroll>ID</Link><span></span><Link :href="page.props.site.localeUrls.en" :class="{ active: !isId }" lang="en" hreflang="en" preserve-scroll>EN</Link></div>
                    <OperationsAccountMenu />
                </div>
            </header>
            <div class="ops-content">
                <div class="ops-data-heading"><div><p class="ops-date">MASTER DATA</p><h1>{{ isId ? 'Manajemen Cabang' : 'Branch Management' }}</h1><p>{{ isId ? 'Kelola informasi, kontak, dan status cabang Cesco.' : 'Manage Cesco branch details, contacts, and status.' }}</p></div><Link class="ops-primary-button" href="/e-cesco/master-data/branches/create"><span>＋</span>{{ isId ? 'Tambah cabang' : 'Add branch' }}</Link></div>
                <div v-if="page.props.flash?.success" class="ops-flash success" role="status">{{ page.props.flash.success }}</div>
                <div v-if="page.props.flash?.error" class="ops-flash error" role="alert">{{ page.props.flash.error }}</div>
                <section class="ops-panel ops-user-list-panel">
                    <div class="ops-user-list-toolbar">
                        <div><h2>{{ isId ? 'Daftar cabang' : 'Branches' }}</h2><p>{{ branches.total.toLocaleString() }} {{ isId ? 'cabang terdaftar' : 'registered branches' }}</p></div>
                        <form class="ops-branch-filters" role="search" @submit.prevent="searchBranches">
                            <label class="ops-form-field"><span class="sr-only">{{ isId ? 'Status cabang' : 'Branch status' }}</span><select v-model="status"><option value="">{{ isId ? 'Semua status' : 'All statuses' }}</option><option value="Y">{{ isId ? 'Aktif' : 'Active' }}</option><option value="N">{{ isId ? 'Nonaktif' : 'Inactive' }}</option></select></label>
                            <div class="ops-user-search"><span aria-hidden="true">⌕</span><input v-model="search" type="search" :placeholder="isId ? 'Cari kode, nama, kota…' : 'Search code, name, city…'"><button type="submit">{{ isId ? 'Cari' : 'Search' }}</button></div>
                        </form>
                    </div>
                    <div class="ops-table-wrap"><table class="ops-table ops-branch-table">
                        <thead><tr><th>{{ isId ? 'Kode' : 'Code' }}</th><th>{{ isId ? 'Nama cabang' : 'Branch name' }}</th><th>{{ isId ? 'Kota / provinsi' : 'City / province' }}</th><th>{{ isId ? 'PIC / kontak' : 'Contact' }}</th><th>Status</th><th>{{ isId ? 'Aksi' : 'Actions' }}</th></tr></thead>
                        <tbody>
                            <tr v-for="branch in branches.data" :key="branch.id"><td><span class="ops-user-username">{{ branch.code }}</span><span v-if="branch.cesco" class="ops-branch-tag">Cesco</span></td><td><strong class="ops-user-name">{{ branch.name }}</strong></td><td>{{ [branch.city, branch.province].filter(Boolean).join(', ') || '—' }}</td><td><strong class="ops-user-name">{{ branch.contact || '—' }}</strong><small v-if="branch.phone" class="ops-branch-contact">{{ branch.phone }}</small></td><td><span class="ops-user-status" :class="branch.active ? 'enabled' : 'blocked'">{{ branch.active ? (isId ? 'Aktif' : 'Active') : (isId ? 'Nonaktif' : 'Inactive') }}</span></td><td><div class="ops-user-actions"><Link class="ops-user-edit" :href="`/e-cesco/master-data/branches/${branch.id}/edit`">Edit</Link><button class="ops-user-delete" type="button" @click="deleteBranch(branch)">{{ isId ? 'Hapus' : 'Delete' }}</button></div></td></tr>
                            <tr v-if="branches.data.length === 0"><td colspan="6" class="ops-empty">{{ isId ? 'Tidak ada cabang yang cocok.' : 'No matching branches found.' }}</td></tr>
                        </tbody>
                    </table></div>
                    <footer class="ops-user-pagination"><span>{{ isId ? 'Menampilkan' : 'Showing' }} {{ branches.from || 0 }}–{{ branches.to || 0 }} {{ isId ? 'dari' : 'of' }} {{ branches.total }}</span><nav :aria-label="isId ? 'Navigasi halaman' : 'Page navigation'"><template v-for="link in branches.links" :key="link.label"><Link v-if="link.url" :href="link.url" :class="{ active: link.active }" preserve-scroll v-html="link.label"></Link><span v-else class="disabled" v-html="link.label"></span></template></nav></footer>
                </section>
            </div>
        </main>
    </div>
</template>
