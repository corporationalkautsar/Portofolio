<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import gsap from 'gsap';
import QrcodeVue from 'qrcode.vue';
import { prefersReducedMotion } from '../../composables/useMotion';
import { useI18n } from '../../composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    profile: { type: Object, default: null },
    projectsCount: { type: Number, default: 0 },
    qrUrl: { type: String, default: '' },
});

const card = ref(null);
const lanyard = ref(null);
const flipped = ref(false);
const pullX = ref(0);
const pullY = ref(0);
const dragging = ref(false);
const sheenX = ref(50);
const sheenY = ref(50);
const isCompact = ref(typeof window !== 'undefined' && window.innerWidth < 640);
const lanyardWidth = ref(256);

let pointerId = null;
let startX = 0;
let startY = 0;
let startPullX = 0;
let startPullY = 0;
let didDrag = false;
let suppressClick = false;
let snapTween = null;
let clickTimer = null;

const maxPull = 150;

const baseHeight = computed(() => (isCompact.value ? 88 : 112));

const photo = computed(() => props.profile?.photo_url || '/images/profile/muhammad-iqbal-fadholi.png');
const name = computed(() => props.profile?.name || t('footer.portfolioFallback'));
const profession = computed(() => props.profile?.profession || t('home.profession'));
const email = computed(() => props.profile?.email || 'hello@example.com');
const location = computed(() => props.profile?.location || t('card.location'));
const projectCode = computed(() => String(props.projectsCount).padStart(2, '0'));

const whatsappNumber = computed(() => {
    const digits = (props.profile?.whatsapp || '').replace(/\D/g, '');
    if (!digits) return '';

    return digits.startsWith('62') ? `+${digits}` : `+62${digits.replace(/^0/, '')}`;
});

const escapeMecard = (value) => String(value || '').replace(/[\\;,:]/g, '\\$&');

const qrValue = computed(() => {
    const fields = [
        `N:${escapeMecard(name.value)}`,
        email.value ? `EMAIL:${escapeMecard(email.value)}` : '',
        whatsappNumber.value ? `TEL:${whatsappNumber.value}` : '',
        location.value ? `ADR:;;;${escapeMecard(location.value)};;;` : '',
        `URL:${escapeMecard(props.qrUrl || 'https://github.com/corporationalkautsar/Portofolio')}`,
        `NOTE:${escapeMecard(`Portfolio ID · ${projectCode.value} projects`)}`,
    ].filter(Boolean);

    return `MECARD:${fields.join(';')};;`;
});

const strapPaths = computed(() => {
    const width = lanyardWidth.value || 256;
    const containerHeight = baseHeight.value + Math.max(0, pullY.value);
    const toViewX = (px) => 128 + (px * 256) / width;
    const toViewY = (px) => ((baseHeight.value + px) / containerHeight) * 112;
    const gap = 34;

    const makePath = (endX, endY) => {
        const c1x = 128 + (endX - 128) * 0.15;
        const c2x = endX - (endX - 128) * 0.12;
        const c2y = endY * 0.72;

        return `M 128 0 C ${c1x} 28, ${c2x} ${c2y}, ${endX} ${endY}`;
    };

    return {
        left: makePath(toViewX(pullX.value - gap), toViewY(pullY.value)),
        right: makePath(toViewX(pullX.value + gap), toViewY(pullY.value)),
    };
});

const clipStyle = computed(() => ({
    left: `calc(50% + ${pullX.value}px)`,
    bottom: `${-12 - Math.min(0, pullY.value)}px`,
}));

function onHover(event) {
    if (dragging.value || !card.value) return;

    const rect = card.value.getBoundingClientRect();
    sheenX.value = ((event.clientX - rect.left) / rect.width) * 100;
    sheenY.value = ((event.clientY - rect.top) / rect.height) * 100;
}

function resetSheen() {
    sheenX.value = 50;
    sheenY.value = 50;
}

function clampVector(x, y) {
    const distance = Math.hypot(x, y);
    if (distance <= maxPull) return { x, y };

    const ratio = maxPull / distance;
    return { x: x * ratio, y: y * ratio };
}

function setPull(x, y) {
    const clamped = clampVector(x, y);
    pullX.value = clamped.x;
    pullY.value = clamped.y;

    if (!card.value || !lanyard.value) return;

    gsap.set(card.value, { x: clamped.x, y: clamped.y, rotation: clamped.x * -0.012 });
    gsap.set(lanyard.value, { height: baseHeight.value + Math.max(0, clamped.y) });
}

function addPointerListeners() {
    window.addEventListener('pointermove', onPointerMove);
    window.addEventListener('pointerup', onPointerEnd);
    window.addEventListener('pointercancel', onPointerEnd);
}

function removePointerListeners() {
    window.removeEventListener('pointermove', onPointerMove);
    window.removeEventListener('pointerup', onPointerEnd);
    window.removeEventListener('pointercancel', onPointerEnd);
}

function onPointerDown(event) {
    if (event.pointerType === 'mouse' && event.button !== 0) return;

    pointerId = event.pointerId;
    startX = event.clientX;
    startY = event.clientY;
    startPullX = pullX.value;
    startPullY = pullY.value;
    didDrag = false;
    dragging.value = true;
    snapTween?.kill();
    addPointerListeners();
}

function onPointerMove(event) {
    if (pointerId !== event.pointerId) return;

    const deltaX = event.clientX - startX;
    const deltaY = event.clientY - startY;

    if (!didDrag && Math.hypot(deltaX, deltaY) > 5) didDrag = true;
    if (didDrag) setPull(startPullX + deltaX, startPullY + deltaY);
}

function onPointerEnd(event) {
    if (pointerId !== event.pointerId) return;

    removePointerListeners();
    pointerId = null;
    dragging.value = false;

    if (!didDrag) return;

    didDrag = false;
    suppressClick = true;
    window.clearTimeout(clickTimer);
    clickTimer = window.setTimeout(() => { suppressClick = false; }, 180);

    snapTween = gsap.to(card.value, {
        x: 0,
        y: 0,
        rotation: 0,
        duration: prefersReducedMotion() ? 0 : 0.85,
        ease: 'elastic.out(1, 0.5)',
        onUpdate: () => {
            const currentX = Number(gsap.getProperty(card.value, 'x')) || 0;
            const currentY = Number(gsap.getProperty(card.value, 'y')) || 0;
            const clamped = clampVector(currentX, currentY);

            pullX.value = clamped.x;
            pullY.value = clamped.y;

            if (lanyard.value) {
                gsap.set(lanyard.value, { height: baseHeight.value + Math.max(0, clamped.y) });
            }
        },
        onComplete: () => {
            pullX.value = 0;
            pullY.value = 0;
            if (lanyard.value) gsap.set(lanyard.value, { height: baseHeight.value });
        },
    });
}

function onClick() {
    if (suppressClick) return;
    flipped.value = !flipped.value;
}

function updateViewport() {
    if (typeof window === 'undefined') return;

    isCompact.value = window.innerWidth < 640;
    lanyardWidth.value = lanyard.value?.clientWidth || (isCompact.value ? 224 : 256);
}

onMounted(() => {
    updateViewport();
    window.addEventListener('resize', updateViewport);
    if (lanyard.value) gsap.set(lanyard.value, { height: baseHeight.value });
});

onBeforeUnmount(() => {
    snapTween?.kill();
    window.clearTimeout(clickTimer);
    removePointerListeners();
    if (typeof window !== 'undefined') window.removeEventListener('resize', updateViewport);
});
</script>

<template>
    <div data-reveal class="relative mx-auto w-full max-w-sm pt-24 sm:pt-28">
        <div
            ref="lanyard"
            class="pointer-events-none absolute left-1/2 top-0 z-0 w-56 -translate-x-1/2 sm:w-64"
            style="height: 112px;"
            aria-hidden="true"
        >
            <div class="absolute -top-2 left-1/2 h-4 w-4 -translate-x-1/2 rounded-full border-2 border-accent/60 bg-ink shadow-[0_0_16px_rgba(181,255,60,0.25)]" />
            <svg class="h-full w-full overflow-visible" viewBox="0 0 256 112" preserveAspectRatio="none">
                <path :d="strapPaths.left" class="lanyard-shadow" />
                <path :d="strapPaths.right" class="lanyard-shadow" />
                <path :d="strapPaths.left" class="lanyard-strap" />
                <path :d="strapPaths.right" class="lanyard-strap" />
                <path :d="strapPaths.left" class="lanyard-weave" />
                <path :d="strapPaths.right" class="lanyard-weave" />
            </svg>
            <div
                class="absolute flex h-6 w-9 -translate-x-1/2 items-center justify-center rounded-full border border-white/25 bg-gradient-to-b from-[#2a2d2a] to-[#0b0d0b] shadow-lg"
                :style="clipStyle"
            >
                <div class="h-2.5 w-2.5 rounded-full border border-white/30 bg-ink" />
            </div>
        </div>

        <button
            ref="card"
            type="button"
            class="relative z-10 block w-full cursor-grab touch-none select-none rounded-2xl border-0 bg-transparent p-0 text-left outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-ink active:cursor-grabbing"
            :aria-label="flipped ? t('card.flipToFront') : t('card.flipToBack')"
            :aria-pressed="flipped"
            @pointerdown="onPointerDown"
            @pointermove="onHover"
            @pointerleave="resetSheen"
            @dragstart.prevent
            @click="onClick"
        >
            <div class="card-float" :class="{ 'is-dragging': dragging }">
                <div class="id-card-scene pointer-events-none relative aspect-[4/5] w-full">
                <div class="card-inner absolute inset-0" :class="{ 'is-flipped': flipped }">
                    <div class="card-face card-front absolute inset-0 overflow-hidden rounded-2xl border border-white/10 bg-[#0b0d0b] shadow-[0_35px_90px_-35px_rgba(0,0,0,0.95)]" :style="{ '--sheen-x': `${sheenX}%`, '--sheen-y': `${sheenY}%` }">
                        <div class="card-grid absolute inset-0 opacity-30" />
                        <div class="portrait-glow absolute inset-0" />
                        <img :src="photo" :alt="`${name} ${t('common.name')}`" loading="lazy" draggable="false" class="portrait-image absolute inset-0 h-full w-full object-cover" />
                        <div class="portrait-fade absolute inset-0" />
                        <div class="holo-glow absolute inset-0" />
                        <div class="holo-foil absolute inset-0" />
                        <div class="corner corner-tl" />
                        <div class="corner corner-br" />
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-accent to-transparent" />
                        <div class="absolute left-1/2 top-3 h-3 w-16 -translate-x-1/2 rounded-full border border-white/15 bg-black/40" />
                        <span class="vertical-label">{{ t('card.portfolioYear') }}</span>

                        <div class="relative flex h-full flex-col p-5 sm:p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-[9px] font-semibold uppercase tracking-[0.28em] text-accent">{{ t('card.portfolioId') }}</p>
                                    <p class="mt-1 font-display text-lg font-semibold tracking-tight">STUDIO<span class="text-accent">.</span></p>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="rounded-full border border-white/15 bg-black/20 px-2.5 py-1 text-[9px] tracking-[0.18em] text-white/70 backdrop-blur-sm">NO. 001</span>
                                    <span class="flex items-center gap-1.5 text-[8px] tracking-[0.18em] text-white/60">
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent opacity-60" />
                                            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-accent" />
                                        </span>
                                        {{ t('card.active') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <p class="text-[8px] font-semibold uppercase tracking-[0.26em] text-accent">{{ t('card.creativeProfile') }}</p>
                                <h3 class="mt-2 max-w-[15rem] font-display text-[1.65rem] font-semibold leading-[0.95] tracking-[-0.035em] text-white sm:text-[1.9rem]">{{ name }}</h3>
                                <p class="mt-3 max-w-[13rem] text-[9px] uppercase leading-relaxed tracking-[0.16em] text-white/55">{{ profession }}</p>

                                <div class="mt-5 grid grid-cols-2 gap-3 border-t border-white/10 pt-4 text-[9px]">
                                    <div class="min-w-0">
                                        <p class="uppercase tracking-[0.2em] text-white/45">{{ t('card.email') }}</p>
                                        <p class="mt-1 truncate text-white/85">{{ email }}</p>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="uppercase tracking-[0.2em] text-white/45">{{ t('card.location') }}</p>
                                        <p class="mt-1 truncate text-white/85">{{ location }}</p>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-end justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <div class="barcode h-7 opacity-60" />
                                        <p class="mt-2 text-[8px] uppercase tracking-[0.2em] text-white/40">{{ t('card.authorizedAccess') }}</p>
                                    </div>
                                    <span class="shrink-0 text-[9px] uppercase tracking-[0.18em] text-accent">{{ projectCode }} / {{ t('card.works') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-face card-back absolute inset-0 overflow-hidden rounded-2xl border border-accent/25 bg-[#0a0c0a] p-6 shadow-[0_35px_90px_-35px_rgba(0,0,0,0.95)]">
                        <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-accent/10 blur-3xl" />
                        <div class="card-grid absolute inset-0 opacity-25" />
                        <div class="scan-line" />

                        <div class="relative flex h-full flex-col items-center text-center">
                            <div class="flex w-full items-center justify-between">
                                <p class="text-[9px] font-semibold uppercase tracking-[0.28em] text-accent">{{ t('card.digitalPass') }}</p>
                                <span class="text-[9px] tracking-[0.18em] text-muted">{{ t('card.back') }}</span>
                            </div>

                            <div class="qr-shell mt-8 rounded-2xl bg-white p-4 shadow-[0_20px_50px_-20px_rgba(181,255,60,0.35)]">
                                <QrcodeVue
                                    :value="qrValue"
                                    :size="140"
                                    :margin="1"
                                    level="M"
                                    foreground="#080808"
                                    background="#ffffff"
                                />
                            </div>

                            <p class="mt-5 font-display text-lg font-semibold text-white">{{ name }}</p>
                            <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-muted">{{ t('card.scan') }}</p>

                            <div class="mt-auto w-full border-t border-white/10 pt-4 text-[9px] uppercase tracking-[0.2em] text-muted">
                                {{ t('card.tapToFlip') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </button>

        <p class="mt-6 text-center text-[9px] uppercase tracking-[0.2em] text-muted">
            {{ t('card.dragHint') }}
        </p>
    </div>
</template>

<style scoped>
.id-card-scene {
    perspective: 1400px;
}

.card-inner {
    transform-style: preserve-3d;
    transition: transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.card-inner.is-flipped {
    transform: rotateY(180deg);
}

.card-face {
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}

.card-back {
    transform: rotateY(180deg);
}

.lanyard-shadow {
    fill: none;
    stroke: rgba(0, 0, 0, 0.75);
    stroke-width: 10;
    stroke-linecap: round;
    vector-effect: non-scaling-stroke;
}

.lanyard-strap {
    fill: none;
    stroke: rgba(181, 255, 60, 0.4);
    stroke-width: 5;
    stroke-linecap: round;
    vector-effect: non-scaling-stroke;
    filter: drop-shadow(0 0 4px rgba(181, 255, 60, 0.35));
}

.card-grid {
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
    background-size: 22px 22px;
}

.barcode {
    background: repeating-linear-gradient(
        90deg,
        rgba(255, 255, 255, 0.75) 0 2px,
        transparent 2px 5px,
        rgba(255, 255, 255, 0.75) 5px 6px,
        transparent 6px 10px
    );
}

.card-float {
    animation: card-float 6s ease-in-out infinite;
    will-change: transform;
}

.card-float.is-dragging {
    animation-play-state: paused;
}

.card-face {
    transition: box-shadow 0.45s ease, border-color 0.45s ease;
}

.portrait-glow {
    pointer-events: none;
    background: radial-gradient(
        60% 50% at 50% 28%,
        rgba(181, 255, 60, 0.18),
        rgba(181, 255, 60, 0.05) 45%,
        transparent 72%
    );
}

.portrait-image {
    filter: saturate(0.92) contrast(1.06);
    transform-origin: 50% 35%;
    transition: transform 1.2s cubic-bezier(0.2, 0.8, 0.2, 1), filter 0.6s ease;
    will-change: transform;
}

.card-float:hover .portrait-image {
    filter: saturate(1) contrast(1.08);
    transform: scale(1.035);
}

.portrait-fade {
    pointer-events: none;
    background:
        linear-gradient(
            to bottom,
            rgba(8, 8, 8, 0.88) 0%,
            rgba(8, 8, 8, 0.18) 17%,
            transparent 32%,
            transparent 44%,
            rgba(8, 8, 8, 0.52) 60%,
            rgba(8, 8, 8, 0.94) 80%,
            #080808 100%
        ),
        radial-gradient(110% 65% at 50% 100%, rgba(8, 8, 8, 0.65), transparent 65%);
}

.vertical-label {
    position: absolute;
    right: 9px;
    top: 50%;
    transform: translateY(-50%) rotate(180deg);
    writing-mode: vertical-rl;
    font-size: 8px;
    letter-spacing: 0.26em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.28);
    pointer-events: none;
}

.card-float:hover .card-front {
    border-color: rgba(181, 255, 60, 0.28);
    box-shadow:
        0 45px 110px -35px rgba(0, 0, 0, 0.95),
        0 0 45px -18px rgba(181, 255, 60, 0.35);
}

.card-float:hover .card-back {
    box-shadow:
        0 45px 110px -35px rgba(0, 0, 0, 0.95),
        0 0 50px -18px rgba(181, 255, 60, 0.45);
}

.holo-glow {
    pointer-events: none;
    background: radial-gradient(
        circle 180px at var(--sheen-x, 50%) var(--sheen-y, 50%),
        rgba(181, 255, 60, 0.20),
        rgba(181, 255, 60, 0.07) 35%,
        transparent 70%
    );
    mix-blend-mode: screen;
    opacity: 0.55;
    transition: opacity 0.35s ease;
}

.card-float:hover .holo-glow {
    opacity: 1;
}

.holo-foil {
    pointer-events: none;
    overflow: hidden;
    mix-blend-mode: screen;
    opacity: 0.4;
    transition: opacity 0.35s ease;
}

.holo-foil::after {
    content: '';
    position: absolute;
    inset: -60%;
    background: linear-gradient(
        115deg,
        transparent 42%,
        rgba(255, 255, 255, 0.16) 48%,
        rgba(181, 255, 60, 0.22) 50%,
        rgba(255, 255, 255, 0.12) 52%,
        transparent 58%
    );
    animation: foil-sweep 6s ease-in-out infinite;
}

.card-float:hover .holo-foil {
    opacity: 0.85;
}

.corner {
    position: absolute;
    width: 22px;
    height: 22px;
    pointer-events: none;
    opacity: 0.75;
}

.corner-tl {
    top: 12px;
    left: 12px;
    border-top: 1px solid rgba(181, 255, 60, 0.7);
    border-left: 1px solid rgba(181, 255, 60, 0.7);
}

.corner-br {
    bottom: 12px;
    right: 12px;
    border-bottom: 1px solid rgba(181, 255, 60, 0.7);
    border-right: 1px solid rgba(181, 255, 60, 0.7);
}

.lanyard-weave {
    fill: none;
    stroke: rgba(255, 255, 255, 0.42);
    stroke-width: 1.2;
    stroke-dasharray: 2 7;
    stroke-linecap: round;
    vector-effect: non-scaling-stroke;
    animation: weave-flow 1.4s linear infinite;
}

.scan-line {
    position: absolute;
    left: 0;
    right: 0;
    top: 8%;
    height: 1px;
    pointer-events: none;
    background: linear-gradient(90deg, transparent, rgba(181, 255, 60, 0.75), transparent);
    box-shadow: 0 0 14px rgba(181, 255, 60, 0.55);
    opacity: 0.65;
    animation: scan 4.5s ease-in-out infinite;
}

.qr-shell {
    position: relative;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.card-float:hover .qr-shell {
    transform: scale(1.025);
    box-shadow: 0 25px 60px -18px rgba(181, 255, 60, 0.6);
}

@keyframes card-float {
    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-6px);
    }
}

@keyframes foil-sweep {
    0%,
    100% {
        transform: translateX(-55%) rotate(8deg);
    }

    50% {
        transform: translateX(55%) rotate(8deg);
    }
}

@keyframes weave-flow {
    to {
        stroke-dashoffset: -18;
    }
}

@keyframes scan {
    0%,
    100% {
        top: 8%;
        opacity: 0;
    }

    10%,
    90% {
        opacity: 0.55;
    }

    50% {
        top: 92%;
        opacity: 0.85;
    }
}

@media (prefers-reduced-motion: reduce) {
    .card-inner,
    .card-face,
    .portrait-image,
    .qr-shell {
        transition: none;
    }

    .card-float,
    .holo-foil::after,
    .lanyard-weave,
    .scan-line {
        animation: none;
    }

    .holo-glow,
    .holo-foil,
    .scan-line {
        opacity: 0.35;
    }

    .card-float:hover .portrait-image,
    .card-float:hover .qr-shell {
        transform: none;
    }
}
</style>
