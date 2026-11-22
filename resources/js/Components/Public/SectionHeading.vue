<script setup>
defineProps({
    eyebrow: { type: String, required: true },
    title: { type: String, required: true },
    description: { type: String, default: '' },
    number: { type: String, default: '01' },
});
</script>

<template>
    <div class="grid gap-8 md:grid-cols-[10rem_1fr]">
        <div data-reveal>
            <p class="eyebrow">{{ eyebrow }}</p>
            <p class="mt-6 font-display text-6xl font-bold text-white/5">{{ number }}</p>
        </div>
        <div>
            <h2 data-reveal class="max-w-3xl font-display text-4xl font-semibold leading-[1.05] text-balance sm:text-5xl lg:text-6xl">
                {{ title }}
            </h2>
            <p v-if="description" data-reveal class="mt-6 max-w-xl text-sm leading-relaxed text-muted md:text-base">
                {{ description }}
            </p>
        </div>
    </div>
</template>
