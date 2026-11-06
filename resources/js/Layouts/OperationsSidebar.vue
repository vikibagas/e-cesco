<script setup>
import { computed, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
    activePage: { type: String, required: true },
    open: { type: Boolean, default: false },
})

const emit = defineEmits(['close', 'feature'])
const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const masterDataOpen = ref(props.activePage.startsWith('master-data'))
const user = computed(() => page.props.auth?.user)

const text = computed(() => isId.value ? {
    overview: 'Ringkasan',
    dashboard: 'Dashboard',
    operations: 'Operasional',
    shipments: 'Pengiriman',
    tracking: 'Pelacakan',
    warehouse: 'Gudang & Manifest',
    rates: 'Tarif Pengiriman',
    management: 'Manajemen',
    finance: 'Keuangan',
    reports: 'Laporan',
    masterData: 'Master Data',
    users: 'User',
    clients: 'User Client',
    branches: 'Cabang',
    customers: 'Kustomer',
    settings: 'Pengaturan',
    online: 'Semua sistem berjalan normal',
    system: 'Sistem Operasional',
} : {
    overview: 'Overview',
    dashboard: 'Dashboard',
    operations: 'Operations',
    shipments: 'Shipments',
    tracking: 'Tracking',
    warehouse: 'Warehouse & Manifest',
    rates: 'Shipping rates',
    management: 'Management',
    finance: 'Finance',
    reports: 'Reports',
    masterData: 'Master Data',
    users: 'User',
    clients: 'Client users',
    branches: 'Branches',
    customers: 'Customers',
    settings: 'Settings',
    online: 'All systems operational',
    system: 'Operations System',
})

function closeMenu() {
    emit('close')
}

function showFeature(name) {
    emit('feature', name)
}
</script>

<template>
    <aside class="ops-sidebar" :class="{ 'is-open': open }">
        <div class="ops-brand">
            <a href="/"><img :src="'/images/cesco-logo.png'" alt="Cesco Logistics"></a>
            <span>{{ text.system }}</span>
        </div>

        <div class="ops-side-section">
            <span class="ops-side-label">{{ text.overview }}</span>
            <Link class="ops-nav-item" :class="{ active: activePage === 'dashboard' }" href="/e-cesco/dashboard" @click="closeMenu">
                <span class="ops-icon">◫</span><span>{{ text.dashboard }}</span>
            </Link>
        </div>

        <div v-if="user?.role === 'admin'" class="ops-side-section">
            <button class="ops-nav-item ops-nav-toggle" :class="{ active: activePage.startsWith('master-data') }" type="button" :aria-expanded="masterDataOpen" @click="masterDataOpen = !masterDataOpen">
                <span class="ops-icon">▦</span><span>{{ text.masterData }}</span><span class="ops-nav-chevron" :class="{ open: masterDataOpen }">⌄</span>
            </button>
            <Transition name="ops-master-data">
                <div v-if="masterDataOpen" class="ops-master-subnav">
                    <Link class="ops-subnav-item" :class="{ active: activePage === 'master-data-users' }" href="/e-cesco/master-data/users" @click="closeMenu">
                        <span class="ops-subnav-dot"></span><span>{{ text.users }}</span>
                    </Link>
                    <Link class="ops-subnav-item" :class="{ active: activePage === 'master-data-users-client' }" href="/e-cesco/master-data/users-client" @click="closeMenu">
                        <span class="ops-subnav-dot"></span><span>{{ text.clients }}</span>
                    </Link>
                    <Link class="ops-subnav-item" :class="{ active: activePage === 'master-data-branches' }" href="/e-cesco/master-data/branches" @click="closeMenu">
                        <span class="ops-subnav-dot"></span><span>{{ text.branches }}</span>
                    </Link>
                    <Link class="ops-subnav-item" :class="{ active: activePage === 'master-data-customers' }" href="/e-cesco/master-data/customers" @click="closeMenu">
                        <span class="ops-subnav-dot"></span><span>{{ text.customers }}</span>
                    </Link>
                </div>
            </Transition>
        </div>

        <div class="ops-side-section">
            <span class="ops-side-label">{{ text.operations }}</span>
            <Link class="ops-nav-item" :class="{ active: activePage === 'shipments' }" href="/e-cesco/shipments" @click="closeMenu">
                <span class="ops-icon">▤</span><span>{{ text.shipments }}</span><span class="ops-count">24</span>
            </Link>
            <button class="ops-nav-item" type="button" @click="showFeature(text.tracking)"><span class="ops-icon">⌖</span><span>{{ text.tracking }}</span></button>
            <button class="ops-nav-item" type="button" @click="showFeature(text.warehouse)"><span class="ops-icon">▦</span><span>{{ text.warehouse }}</span></button>
            <button class="ops-nav-item" type="button" @click="showFeature(text.rates)"><span class="ops-icon">◇</span><span>{{ text.rates }}</span></button>
        </div>

        <div class="ops-side-section">
            <span class="ops-side-label">{{ text.management }}</span>
            <button class="ops-nav-item" type="button" @click="showFeature(text.finance)"><span class="ops-icon">◉</span><span>{{ text.finance }}</span></button>
            <button class="ops-nav-item" type="button" @click="showFeature(text.reports)"><span class="ops-icon">▥</span><span>{{ text.reports }}</span></button>
        </div>

        <div class="ops-side-bottom">
            <button class="ops-nav-item" type="button" @click="showFeature(text.settings)"><span class="ops-icon">⚙</span><span>{{ text.settings }}</span></button>
            <div class="ops-system-status"><span class="ops-live-dot"></span><span>{{ text.online }}</span></div>
            <div class="ops-user">
                <span class="ops-user-copy"><strong>{{ user?.name || 'Cesco' }}</strong><small>{{ user?.role || 'User' }}</small></span>
            </div>
        </div>
    </aside>
</template>
