<script setup>
defineProps({
    title: { type: String, default: '' },
    subtitle: { type: String, default: '' },
});
</script>

<template>
    <section class="rounded-2xl border border-line bg-panel">
        <header v-if="title || $slots.actions" class="flex flex-wrap items-center justify-between gap-4 border-b border-line px-5 py-4 md:px-6">
            <div>
                <h2 class="font-display text-base font-semibold">{{ title }}</h2>
                <p v-if="subtitle" class="mt-1 text-xs text-muted">{{ subtitle }}</p>
            </div>
            <slot name="actions" />
        </header>
        <div class="p-5 md:p-6">
            <slot />
        </div>
    </section>
</template>
