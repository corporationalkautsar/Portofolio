<script setup>
defineProps({
    compact: { type: Boolean, default: false },
    admin: { type: Boolean, default: false },
});

const logoSrc = '/favicon.svg?v=3';
</script>

<template>
    <span class="inline-flex items-center gap-3">
        <span class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-accent/30 bg-accent/10">
            <img :src="logoSrc" alt="" class="h-6 w-6" />
            <span class="absolute -bottom-0.5 -right-0.5 h-2 w-2 rounded-full bg-accent ring-2 ring-ink" />
        </span>
        <span v-if="!compact" class="font-display text-sm font-bold tracking-[0.2em]">
            MIQ<span class="text-accent">.</span><span v-if="admin" class="ml-2 text-muted">ADMIN</span>
        </span>
    </span>
</template>
