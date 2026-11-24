<script setup>
import SkillLogo from './SkillLogo.vue';

const stack = [
    { label: 'HTML', icon: 'html5' },
    { label: 'CSS', icon: 'css' },
    { label: 'JavaScript', icon: 'javascript' },
    { label: 'Vue.js', icon: 'vuejs' },
    { label: 'Laravel', icon: 'laravel' },
    { label: 'PHP', icon: 'php' },
];
</script>

<template>
    <div class="relative overflow-hidden border-y border-line bg-panel/40 py-4" aria-hidden="true">
        <div class="marquee-track flex w-max items-center">
            <div v-for="copy in 2" :key="copy" class="flex shrink-0 items-center gap-10 pr-10">
                <span
                    v-for="item in stack"
                    :key="`${copy}-${item.label}`"
                    class="flex items-center gap-2 whitespace-nowrap font-display text-xs font-semibold uppercase tracking-[0.18em] text-white/45"
                >
                    <SkillLogo :icon="item.icon" class="h-4 w-4" />
                    {{ item.label }}
                    <span class="ml-2 text-accent/40">✦</span>
                </span>
            </div>
        </div>

        <div class="pointer-events-none absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-ink to-transparent" />
        <div class="pointer-events-none absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-ink to-transparent" />
    </div>
</template>

<style scoped>
.marquee-track {
    animation: marquee 28s linear infinite;
}

.marquee-track:hover {
    animation-play-state: paused;
}

@keyframes marquee {
    to {
        transform: translateX(-50%);
    }
}

@media (prefers-reduced-motion: reduce) {
    .marquee-track {
        animation: none;
    }
}
</style>
