<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle2 } from 'lucide-vue-next';
import gsap from 'gsap';
import SiteNavbar from '../Components/Public/SiteNavbar.vue';
import SiteFooter from '../Components/Public/SiteFooter.vue';
import { revealTimeline } from '../composables/useMotion';

const root = ref(null);
const page = usePage();
const flash = computed(() => page.props.flash || {});
const showToast = ref(false);
let context = null;
let toastTimer;

watch(() => flash.value.success, (message) => {
    if (!message) return;
    showToast.value = true;
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { showToast.value = false; }, 3200);
});

async function initAnimations() {
    await nextTick();
    context?.revert();
    context = revealTimeline(gsap, root.value);
}

onMounted(initAnimations);
watch(() => page.url, initAnimations);
onBeforeUnmount(() => {
    context?.revert();
    context = null;
    clearTimeout(toastTimer);
});
</script>

<template>
    <div ref="root" class="relative min-h-screen bg-ink text-white">
        <SiteNavbar />
        <main>
            <slot />
        </main>
        <SiteFooter />

        <Transition name="toast">
            <div
                v-if="showToast && flash.success"
                class="fixed bottom-6 right-6 z-[200] flex items-center gap-2 rounded-full border border-accent/40 bg-panel/95 px-5 py-3 text-xs text-white shadow-2xl backdrop-blur"
                role="status"
            >
                <CheckCircle2 :size="16" class="text-accent" />
                {{ flash.success }}
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active { transition: opacity .3s ease, transform .3s ease; }
.toast-enter-from,
.toast-leave-to { opacity: 0; transform: translateY(12px); }
</style>
