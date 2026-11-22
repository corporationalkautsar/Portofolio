import { ref } from 'vue';

/** Shared state for the full-screen intro/transition loader. */
export const isLoading = ref(true);
export const progress = ref(6);

let hideTimer;

export function showLoader() {
    clearTimeout(hideTimer);
    isLoading.value = true;
    progress.value = 8;
}

export function setProgress(value) {
    const next = Number(value) || 0;
    progress.value = Math.max(progress.value, Math.min(next, 100));
}

export function hideLoader(delay = 280) {
    progress.value = 100;
    clearTimeout(hideTimer);
    hideTimer = setTimeout(() => {
        isLoading.value = false;
    }, delay);
}
