<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

// Asia/Jakarta = UTC+7. Dihitung manual supaya tidak bergantung pada
// database timezone / ICU yang bisa berbeda di setiap browser.
const time = ref('');
let timer;

function update() {
    const jakarta = new Date(Date.now() + 7 * 60 * 60 * 1000);
    const hours = String(jakarta.getUTCHours()).padStart(2, '0');
    const minutes = String(jakarta.getUTCMinutes()).padStart(2, '0');
    time.value = `${hours}:${minutes}`;
}

onMounted(() => {
    update();
    timer = window.setInterval(update, 15000);
});

onBeforeUnmount(() => window.clearInterval(timer));
</script>

<template>
    <span class="inline-flex items-center gap-2 rounded-full border border-line bg-panel/60 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-muted" title="Asia/Jakarta (UTC+7)">
        <span class="relative flex h-1.5 w-1.5">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent opacity-60" />
            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-accent" />
        </span>
        Besuki · {{ time || '--:--' }} WIB
    </span>
</template>
