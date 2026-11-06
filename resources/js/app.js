import '../css/app.css';
import { createInertiaApp, router } from '@inertiajs/vue3';

const loadingOverlay = document.getElementById('app-loading-overlay');
let loadingTimer;

router.on('start', () => {
    window.clearTimeout(loadingTimer);
    loadingTimer = window.setTimeout(() => {
        loadingOverlay?.classList.add('is-visible');
        loadingOverlay?.setAttribute('aria-hidden', 'false');
    }, 100);
});

router.on('finish', () => {
    window.clearTimeout(loadingTimer);
    loadingOverlay?.classList.remove('is-visible');
    loadingOverlay?.setAttribute('aria-hidden', 'true');
});

createInertiaApp({
    progress: false,
});
