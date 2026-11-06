<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3'
import SiteLayout from '../../Layouts/SiteLayout.vue'

defineOptions({ layout: SiteLayout })

const page = usePage()
const isId = page.props.locale === 'id'
const form = useForm({
    username: '',
    password: '',
    remember: false,
})

function submit() {
    form.post('/e-cesco/login', {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <Head :title="isId ? 'Login e-Cesco' : 'e-Cesco Login'" />

    <main class="login-page">
        <section class="login-card" aria-labelledby="login-title">
            <div class="login-brand-mark" aria-hidden="true">C</div>
            <p class="login-eyebrow">CESCO LOGISTICS</p>
            <h1 id="login-title">{{ isId ? 'Selamat datang kembali' : 'Welcome back' }}</h1>
            <p class="login-intro">
                {{ isId ? 'Masuk untuk melanjutkan ke sistem operasional e-Cesco.' : 'Sign in to continue to the e-Cesco operations system.' }}
            </p>

            <form class="login-form" @submit.prevent="submit">
                <div class="login-field">
                    <label for="username">Username</label>
                    <input
                        id="username"
                        v-model="form.username"
                        type="text"
                        name="username"
                        autocomplete="username"
                        required
                        autofocus
                        maxlength="50"
                        :placeholder="isId ? 'Masukkan username' : 'Enter your username'"
                        :aria-invalid="Boolean(form.errors.username)"
                        aria-describedby="username-error"
                    >
                    <p v-if="form.errors.username" id="username-error" class="login-error" role="alert">{{ form.errors.username }}</p>
                </div>

                <div class="login-field">
                    <div class="login-label-row">
                        <label for="password">{{ isId ? 'Kata sandi' : 'Password' }}</label>
                    </div>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        :placeholder="isId ? 'Masukkan kata sandi' : 'Enter your password'"
                        :aria-invalid="Boolean(form.errors.password)"
                        aria-describedby="password-error"
                    >
                    <p v-if="form.errors.password" id="password-error" class="login-error" role="alert">{{ form.errors.password }}</p>
                </div>

                <label class="login-remember">
                    <input v-model="form.remember" type="checkbox" name="remember" value="1">
                    <span>{{ isId ? 'Ingat saya di perangkat ini' : 'Remember me on this device' }}</span>
                </label>

                <button class="login-submit" type="submit" :disabled="form.processing">
                    <span>{{ form.processing ? (isId ? 'Memproses…' : 'Signing in…') : (isId ? 'Masuk ke e-Cesco' : 'Sign in to e-Cesco') }}</span>
                    <span aria-hidden="true">→</span>
                </button>
            </form>

            <p class="login-help">
                {{ isId ? 'Butuh bantuan akses?' : 'Need help accessing your account?' }}
                <a href="mailto:customercare@cesco-logistics.com">{{ isId ? 'Hubungi tim Cesco' : 'Contact the Cesco team' }}</a>
            </p>
            <a class="login-back" href="/">{{ isId ? '← Kembali ke website Cesco' : '← Back to Cesco website' }}</a>
        </section>

        <p class="login-footer">© {{ new Date().getFullYear() }} PT Cesco Logistik Indonesia</p>
    </main>
</template>
