<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const progress = ref(0);
let frame = 0;

function update() {
    frame = 0;
    const documentElement = document.documentElement;
    const scrollable = documentElement.scrollHeight - window.innerHeight;
    progress.value = scrollable > 0 ? Math.min(100, Math.max(0, (window.scrollY / scrollable) * 100)) : 0;
}

function onScroll() {
    if (!frame) frame = requestAnimationFrame(update);
}

onMounted(() => {
    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
});

onBeforeUnmount(() => {
    cancelAnimationFrame(frame);
    window.removeEventListener('scroll', onScroll);
    window.removeEventListener('resize', onScroll);
});
</script>

<template>
    <div class="pointer-events-none fixed inset-x-0 top-0 z-[150] h-[3px]" aria-hidden="true">
        <div
            class="h-full bg-gradient-to-r from-accent/30 via-accent to-accent/30 shadow-[0_0_14px_rgba(181,255,60,0.85)] transition-[width] duration-150 ease-out"
            :style="{ width: `${progress}%` }"
        />
    </div>
</template>
