<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { prefersReducedMotion } from '../../composables/useMotion';

const props = defineProps({
    value: { type: Number, required: true },
});

const root = ref(null);
const displayed = ref(0);
let observer = null;
let frame = 0;

function countUp() {
    if (prefersReducedMotion()) {
        displayed.value = props.value;
        return;
    }

    const duration = 1100;
    const start = performance.now();

    const tick = (now) => {
        const progress = Math.min(1, (now - start) / duration);
        const eased = 1 - Math.pow(1 - progress, 3);
        displayed.value = Math.round(props.value * eased);
        if (progress < 1) frame = requestAnimationFrame(tick);
    };

    frame = requestAnimationFrame(tick);
}

onMounted(() => {
    if (typeof IntersectionObserver === 'undefined' || !root.value) {
        countUp();
        return;
    }

    observer = new IntersectionObserver((entries) => {
        if (!entries.some((entry) => entry.isIntersecting)) return;
        observer?.disconnect();
        countUp();
    }, { threshold: 0.4 });

    observer.observe(root.value);
});

onBeforeUnmount(() => {
    observer?.disconnect();
    cancelAnimationFrame(frame);
});
</script>

<template>
    <span ref="root">{{ displayed }}</span>
</template>
