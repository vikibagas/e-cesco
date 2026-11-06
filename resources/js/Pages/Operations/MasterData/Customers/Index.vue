<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import OperationsAccountMenu from '../../../../Layouts/OperationsAccountMenu.vue'
import OperationsSidebar from '../../../../Layouts/OperationsSidebar.vue'

const props = defineProps({ customers: { type: Object, required: true }, filters: { type: Object, required: true } })
const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const sidebarOpen = ref(false)
const search = ref(props.filters.search || '')
const status = ref(props.filters.status || '')

function searchCustomers() {
    router.get('/e-cesco/master-data/customers', { search: search.value, status: status.value }, { preserveState: true, preserveScroll: true, replace: true })
}

function deleteCustomer(customer) {
    const message = isId.value
        ? `Hapus kustomer ${customer.code} — ${customer.name}? Kustomer yang masih dipakai user client tidak dapat dihapus.`
        : `Delete customer ${customer.code} — ${customer.name}? Customers still used by client users cannot be deleted.`

    if (window.confirm(message)) {
        router.delete(`/e-cesco/master-data/customers/${customer.id}`, { preserveScroll: true })
    }
}
</script>

<template>
    <Head :title="isId ? 'Master Data — Kustomer' : 'Master Data — Customers'" />
    <div class="ops-app">
        <button v-if="sidebarOpen" class="ops-backdrop" type="button" aria-label="Close menu" @click="sidebarOpen = false"></button>
        <OperationsSidebar active-page="master-data-customers" :open="sidebarOpen" @close="sidebarOpen = false" />
        <main class="ops-main">
            <header class="ops-topbar">
                <button class="ops-mobile-toggle" type="button" :aria-label="isId ? 'Buka navigasi' : 'Open navigation'" @click="sidebarOpen = !sidebarOpen">☰</button>
                <div class="ops-breadcrumb"><span>e-Cesco</span><span>/</span><span>Master Data</span><span>/</span><strong>{{ isId ? 'Kustomer' : 'Customers' }}</strong></div>
                <div class="ops-top-actions"><div class="ops-language" :aria-label="isId ? 'Pilih bahasa' : 'Choose language'"><Link :href="page.props.site.localeUrls.id" :class="{ active: isId }" lang="id" hreflang="id" preserve-scroll>ID</Link><span></span><Link :href="page.props.site.localeUrls.en" :class="{ active: !isId }" lang="en" hreflang="en" preserve-scroll>EN</Link></div><OperationsAccountMenu /></div>
            </header>
            <div class="ops-content">
                <div class="ops-data-heading"><div><p class="ops-date">MASTER DATA</p><h1>{{ isId ? 'Manajemen Kustomer' : 'Customer Management' }}</h1><p>{{ isId ? 'Kelola data pelanggan dan perusahaan Cesco.' : 'Manage Cesco customer and company records.' }}</p></div><Link class="ops-primary-button" href="/e-cesco/master-data/customers/create"><span>＋</span>{{ isId ? 'Tambah kustomer' : 'Add customer' }}</Link></div>
                <div v-if="page.props.flash?.success" class="ops-flash success" role="status">{{ page.props.flash.success }}</div>
                <div v-if="page.props.flash?.error" class="ops-flash error" role="alert">{{ page.props.flash.error }}</div>
                <section class="ops-panel ops-user-list-panel">
                    <div class="ops-user-list-toolbar"><div><h2>{{ isId ? 'Daftar kustomer' : 'Customers' }}</h2><p>{{ customers.total.toLocaleString() }} {{ isId ? 'kustomer terdaftar' : 'registered customers' }}</p></div><form class="ops-branch-filters" role="search" @submit.prevent="searchCustomers"><label class="ops-form-field"><span class="sr-only">{{ isId ? 'Status kustomer' : 'Customer status' }}</span><select v-model="status"><option value="">{{ isId ? 'Semua status' : 'All statuses' }}</option><option value="Y">{{ isId ? 'Aktif' : 'Active' }}</option><option value="N">{{ isId ? 'Nonaktif' : 'Inactive' }}</option></select></label><div class="ops-user-search"><span aria-hidden="true">⌕</span><input v-model="search" type="search" :placeholder="isId ? 'Cari kode, nama, cabang…' : 'Search code, name, branch…'"><button type="submit">{{ isId ? 'Cari' : 'Search' }}</button></div></form></div>
                    <div class="ops-table-wrap"><table class="ops-table ops-customer-table">
                        <thead><tr><th>{{ isId ? 'Kode' : 'Code' }}</th><th>{{ isId ? 'Nama kustomer' : 'Customer name' }}</th><th>{{ isId ? 'Cabang' : 'Branch' }}</th><th>{{ isId ? 'Kontak' : 'Contact' }}</th><th>{{ isId ? 'Layanan' : 'Services' }}</th><th>{{ isId ? 'Status' : 'Status' }}</th><th>{{ isId ? 'Aksi' : 'Actions' }}</th></tr></thead>
                        <tbody>
                            <tr v-for="customer in customers.data" :key="customer.id"><td><span class="ops-user-username">{{ customer.code }}</span></td><td><strong class="ops-user-name">{{ customer.name || customer.callname }}</strong><small v-if="customer.callname && customer.callname !== customer.name" class="ops-branch-contact">{{ customer.callname }}</small></td><td>{{ customer.branch }}</td><td><strong class="ops-user-name">{{ customer.contact }}</strong><small v-if="customer.phone" class="ops-branch-contact">{{ customer.phone }}</small></td><td><span v-if="customer.cesco" class="ops-branch-tag">Cesco</span><span v-if="customer.dhl" class="ops-branch-tag">DHL</span></td><td><span class="ops-user-status" :class="customer.active ? 'enabled' : 'blocked'">{{ customer.active ? (isId ? 'Aktif' : 'Active') : (isId ? 'Nonaktif' : 'Inactive') }}</span></td><td><div class="ops-user-actions"><Link class="ops-user-edit" :href="`/e-cesco/master-data/customers/${customer.id}/edit`">Edit</Link><button class="ops-user-delete" type="button" @click="deleteCustomer(customer)">{{ isId ? 'Hapus' : 'Delete' }}</button></div></td></tr>
                            <tr v-if="customers.data.length === 0"><td colspan="7" class="ops-empty">{{ isId ? 'Tidak ada kustomer yang cocok.' : 'No matching customers found.' }}</td></tr>
                        </tbody>
                    </table></div>
                    <footer class="ops-user-pagination"><span>{{ isId ? 'Menampilkan' : 'Showing' }} {{ customers.from || 0 }}–{{ customers.to || 0 }} {{ isId ? 'dari' : 'of' }} {{ customers.total }}</span><nav :aria-label="isId ? 'Navigasi halaman' : 'Page navigation'"><template v-for="link in customers.links" :key="link.label"><Link v-if="link.url" :href="link.url" :class="{ active: link.active }" preserve-scroll v-html="link.label"></Link><span v-else class="disabled" v-html="link.label"></span></template></nav></footer>
                </section>
            </div>
        </main>
    </div>
</template>
