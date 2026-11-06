<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const page = usePage()
const isId = computed(() => page.props.locale === 'id')
const user = computed(() => page.props.auth?.user)
const accountMenuOpen = ref(false)
const notice = ref('')
let noticeTimeout

function closeOnOutsidePointer(event) {
    if (!event.target.closest('.ops-account')) {
        accountMenuOpen.value = false
    }
}

function closeOnEscape(event) {
    if (event.key === 'Escape') {
        accountMenuOpen.value = false
    }
}

function openAccountSettings() {
    accountMenuOpen.value = false
    notice.value = isId.value
        ? 'Pengaturan akun akan tersedia di modul pengaturan.'
        : 'Account settings will be available in the settings module.'

    window.clearTimeout(noticeTimeout)
    noticeTimeout = window.setTimeout(() => {
        notice.value = ''
    }, 3000)
}

onMounted(() => {
    document.addEventListener('pointerdown', closeOnOutsidePointer)
    document.addEventListener('keydown', closeOnEscape)
})

onBeforeUnmount(() => {
    document.removeEventListener('pointerdown', closeOnOutsidePointer)
    document.removeEventListener('keydown', closeOnEscape)
    window.clearTimeout(noticeTimeout)
})
</script>

<template>
    <div class="ops-account">
        <button
            class="ops-account-trigger"
            type="button"
            :aria-label="isId ? 'Menu akun' : 'Account menu'"
            aria-haspopup="menu"
            :aria-expanded="accountMenuOpen"
            @click="accountMenuOpen = !accountMenuOpen"
        >
            <span class="ops-account-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="8" r="3.5" />
                    <path d="M5.5 20c.7-3.3 3.1-5 6.5-5s5.8 1.7 6.5 5" />
                </svg>
            </span>
            <span class="ops-account-trigger-copy">
                <strong>{{ user?.name || 'Cesco' }}</strong>
                <small>{{ user?.role || (isId ? 'Pengguna' : 'User') }}</small>
            </span>
            <span class="ops-account-chevron" aria-hidden="true">⌄</span>
        </button>

        <Transition name="ops-account-menu">
            <div v-if="accountMenuOpen" class="ops-account-dropdown" role="menu">
                <div class="ops-account-summary">
                    <strong>{{ user?.name || 'Cesco' }}</strong>
                    <span>{{ user?.role || (isId ? 'Pengguna' : 'User') }}</span>
                </div>
                <button class="ops-account-action" type="button" role="menuitem" @click="openAccountSettings">
                    <span aria-hidden="true">⚙</span>{{ isId ? 'Pengaturan akun' : 'Account settings' }}
                </button>
                <button class="ops-account-action sign-out" type="button" role="menuitem" @click="router.post('/logout')">
                    <span aria-hidden="true">⇥</span>{{ isId ? 'Keluar' : 'Sign out' }}
                </button>
            </div>
        </Transition>

        <Transition name="ops-toast">
            <div v-if="notice" class="ops-toast-message" role="status">{{ notice }}</div>
        </Transition>
    </div>
</template>
