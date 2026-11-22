<script setup>
import { isLoading, progress } from '../composables/usePageLoader';
import { useI18n } from '../composables/useI18n';

const { t } = useI18n();
const logoSrc = '/favicon.svg?v=3';
</script>

<template>
    <Transition name="loader-fade">
        <div
            v-if="isLoading"
            id="page-loader"
            class="grain fixed inset-0 z-[9999] flex items-center justify-center bg-ink"
            role="status"
            aria-live="polite"
        >
            <div class="pointer-events-none absolute left-1/2 top-1/2 h-[26rem] w-[26rem] -translate-x-1/2 -translate-y-1/2 rounded-full bg-accent/10 blur-[120px]" />

            <div class="relative flex flex-col items-center px-6 text-center">
                <div class="relative flex h-24 w-24 items-center justify-center">
                    <span class="loader-ring absolute inset-0 rounded-full border border-transparent border-t-accent/80" />
                    <span class="loader-ring-reverse absolute inset-2 rounded-full border border-transparent border-b-white/25" />
                    <span class="absolute inset-4 rounded-full border border-accent/15" />
                    <img :src="logoSrc" alt="" class="h-11 w-11 drop-shadow-[0_0_18px_rgba(181,255,60,0.35)]" />
                </div>

                <p class="mt-8 font-display text-2xl font-bold tracking-[0.38em]">MIQ<span class="text-accent">.</span></p>
                <p class="mt-3 text-[10px] font-semibold uppercase tracking-[0.34em] text-muted">
                    {{ t('common.loading') }}<span class="ml-1 text-accent">...</span>
                </p>

                <div class="mt-9 h-px w-56 overflow-hidden bg-line">
                    <div class="h-full bg-accent transition-[width] duration-300 ease-out" :style="{ width: `${progress}%` }" />
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.loader-ring { animation: loader-spin 2.2s linear infinite; }
.loader-ring-reverse { animation: loader-spin 3.4s linear infinite reverse; }

@keyframes loader-spin {
    to { transform: rotate(360deg); }
}

.loader-fade-enter-active,
.loader-fade-leave-active { transition: opacity .45s ease; }
.loader-fade-enter-from,
.loader-fade-leave-to { opacity: 0; }
</style>
