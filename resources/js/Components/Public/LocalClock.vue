<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const time = ref('');
let timer;

function update() {
    time.value = new Intl.DateTimeFormat('en-GB', {
        timeZone: 'Asia/Jakarta',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    }).format(new Date());
}

onMounted(() => {
    update();
    timer = window.setInterval(update, 15000);
});

onBeforeUnmount(() => window.clearInterval(timer));
</script>

<template>
    <span class="inline-flex items-center gap-2 rounded-full border border-line bg-panel/60 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-muted" title="Asia/Jakarta">
        <span class="relative flex h-1.5 w-1.5">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent opacity-60" />
            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-accent" />
        </span>
        Besuki · {{ time }} WIB
    </span>
</template>
