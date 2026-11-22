<script setup>
import { onBeforeUnmount, ref } from 'vue';
import {
    AudioLines,
    Bird,
    Disc3,
    Flower2,
    Heart,
    Pause,
    Play,
    SkipBack,
    SkipForward,
    Volume2,
} from 'lucide-vue-next';
import { prefersReducedMotion } from '../../composables/useMotion';
import { useI18n } from '../../composables/useI18n';

const props = defineProps({
    photo: { type: String, default: '/images/profile/muhammad-iqbal-fadholi.png' },
});

const { t } = useI18n();

const stage = ref(null);
const active = ref(false);

let frame = 0;
let currentX = 0;
let currentY = 0;
let targetX = 0;
let targetY = 0;

function applyParallax() {
    frame = 0;

    if (!stage.value || prefersReducedMotion()) return;

    currentX += (targetX - currentX) * 0.08;
    currentY += (targetY - currentY) * 0.08;

    stage.value.style.setProperty('--mx', currentX.toFixed(3));
    stage.value.style.setProperty('--my', currentY.toFixed(3));

    if (Math.abs(targetX - currentX) > 0.002 || Math.abs(targetY - currentY) > 0.002) {
        frame = requestAnimationFrame(applyParallax);
    }
}

function onPointerMove(event) {
    if (!stage.value || prefersReducedMotion()) return;

    const rect = stage.value.getBoundingClientRect();
    targetX = ((event.clientX - rect.left) / rect.width - 0.5) * 2;
    targetY = ((event.clientY - rect.top) / rect.height - 0.5) * 2;

    if (!frame) frame = requestAnimationFrame(applyParallax);
}

function onPointerEnter() {
    active.value = true;
}

function onPointerLeave() {
    active.value = false;
    targetX = 0;
    targetY = 0;

    if (!frame) frame = requestAnimationFrame(applyParallax);
}

onBeforeUnmount(() => {
    cancelAnimationFrame(frame);
});
</script>

<template>
    <div
        ref="stage"
        class="hero-stage relative h-full w-full"
        :class="{ 'is-active': active }"
        aria-hidden="true"
        @pointerenter="onPointerEnter"
        @pointerleave="onPointerLeave"
        @pointermove.passive="onPointerMove"
    >
        <div class="stage-glow" />
        <div class="stage-grid" />
        <div class="stage-vignette" />

        <div class="stage-world">
            <svg class="floating-shape shape-bird shape-bird-a" viewBox="0 0 100 100">
                <path d="M6 52c16 0 27-8 36-30 2 15 11 25 32 30-21 0-30 8-36 26-4-14-14-24-32-26z" fill="currentColor" />
            </svg>
            <svg class="floating-shape shape-bird shape-bird-b" viewBox="0 0 100 100">
                <path d="M6 52c16 0 27-8 36-30 2 15 11 25 32 30-21 0-30 8-36 26-4-14-14-24-32-26z" fill="currentColor" />
            </svg>
            <svg class="floating-shape shape-butterfly" viewBox="0 0 100 100">
                <path d="M50 50C34 22 6 24 6 44c0 18 20 26 44 10 24 16 44 8 44-10 0-20-28-22-44 4z" fill="currentColor" />
                <path d="M50 47v22" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
            </svg>

            <div class="portrait-layer">
                <div class="portrait-float">
                    <div class="vinyl" />
                    <div class="portrait-halo" />
                    <img :src="photo" alt="" class="portrait-photo" draggable="false" />
                    <div class="portrait-bottom-fade" />
                    <div class="portrait-badge">
                        <span class="live-dot" />
                        {{ t('hero.nowPlaying') }}
                        <span class="equalizer">
                            <i />
                            <i />
                            <i />
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-layer card-main">
                <article class="music-card">
                    <div class="album-art album-bye">
                        <Heart :size="34" class="album-symbol" />
                    </div>

                    <div class="music-body">
                        <div class="music-top">
                            <div class="min-w-0">
                                <p class="music-title">bye</p>
                                <p class="music-artist">Ariana Grande</p>
                            </div>
                            <Heart :size="11" class="music-heart" />
                        </div>

                        <div class="music-progress">
                            <span class="music-progress-fill" style="width: 36%" />
                            <span class="music-progress-knob" style="left: 36%" />
                        </div>
                        <div class="music-time"><span>00:55</span><span>2:32</span></div>

                        <div class="music-controls">
                            <SkipBack :size="11" />
                            <span class="music-play"><Pause :size="10" /></span>
                            <SkipForward :size="11" />
                            <Volume2 :size="11" class="ml-auto" />
                        </div>
                    </div>
                </article>
            </div>

            <div class="card-layer card-right">
                <article class="music-card">
                    <div class="album-art album-rumah">
                        <Bird :size="34" class="album-symbol" />
                    </div>

                    <div class="music-body">
                        <div class="music-top">
                            <div class="min-w-0">
                                <p class="music-title">Rumah ke Rumah</p>
                                <p class="music-artist">Hindia</p>
                            </div>
                        </div>

                        <div class="music-progress">
                            <span class="music-progress-fill" style="width: 58%" />
                            <span class="music-progress-knob" style="left: 58%" />
                        </div>
                        <div class="music-controls compact">
                            <SkipBack :size="10" />
                            <span class="music-play"><Play :size="9" /></span>
                            <SkipForward :size="10" />
                        </div>
                    </div>
                </article>
            </div>

            <div class="card-layer card-bottom-left">
                <article class="music-card is-stacked">
                    <div class="album-art album-evaluasi">
                        <Flower2 :size="38" class="album-symbol" />
                    </div>

                    <div class="music-body">
                        <p class="music-title">Evaluasi</p>
                        <p class="music-artist">Hindia</p>
                        <div class="music-progress">
                            <span class="music-progress-fill" style="width: 42%" />
                            <span class="music-progress-knob" style="left: 42%" />
                        </div>
                        <div class="music-controls compact">
                            <Play :size="10" />
                            <span class="music-time-inline">0:51</span>
                            <Volume2 :size="10" class="ml-auto" />
                        </div>
                    </div>
                </article>
            </div>

            <div class="card-layer card-bottom-right">
                <article class="music-card is-stacked">
                    <div class="album-art album-cincin">
                        <Disc3 :size="42" class="album-symbol" />
                    </div>

                    <div class="music-body">
                        <p class="music-title">Cincin</p>
                        <p class="music-artist">Hindia</p>
                        <div class="music-progress">
                            <span class="music-progress-fill" style="width: 68%" />
                            <span class="music-progress-knob" style="left: 68%" />
                        </div>
                        <div class="music-controls compact">
                            <Pause :size="10" />
                            <span class="music-time-inline">1:48</span>
                            <AudioLines :size="10" class="ml-auto" />
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <div class="stage-footer">
            <span class="stage-footer-dot" />
            {{ t('home.playlist') }}
        </div>
    </div>
</template>

<style scoped>
.hero-stage {
    --mx: 0;
    --my: 0;
    position: relative;
    perspective: 1200px;
    isolation: isolate;
    user-select: none;
}

.stage-glow {
    position: absolute;
    inset: -12%;
    background: radial-gradient(
        circle at calc(50% + var(--mx) * 16%) calc(46% + var(--my) * 14%),
        rgba(181, 255, 60, 0.12),
        rgba(181, 255, 60, 0.035) 32%,
        transparent 62%
    );
    filter: blur(10px);
    transition: background 0.25s ease-out;
}

.stage-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.045) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.045) 1px, transparent 1px);
    background-size: 46px 46px;
    mask-image: radial-gradient(circle at 50% 44%, black, transparent 72%);
    opacity: 0.32;
}

.stage-vignette {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 50% 42%, transparent 28%, rgba(8, 8, 8, 0.6) 74%, #080808 100%);
}

.stage-world {
    position: absolute;
    inset: 0;
    transform-style: preserve-3d;
    pointer-events: none;
}

.portrait-layer {
    position: absolute;
    left: 50%;
    bottom: -3%;
    z-index: 14;
    width: 58%;
    translate: -50% 0;
    transform: translate3d(
        calc(var(--mx) * 10px),
        calc(var(--my) * 8px),
        0
    );
    transition: transform 0.2s ease-out;
}

.portrait-float {
    position: relative;
    animation: portrait-float 7s ease-in-out infinite;
}

.vinyl {
    position: absolute;
    left: 50%;
    top: 12%;
    z-index: 0;
    width: 112%;
    aspect-ratio: 1;
    translate: -50% 0;
    border-radius: 50%;
    border: 1px solid rgba(181, 255, 60, 0.16);
    background:
        conic-gradient(from 30deg, transparent 0 20%, rgba(181, 255, 60, 0.08) 25%, transparent 30% 100%),
        repeating-radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.055) 0 1px, transparent 1px 5px),
        radial-gradient(circle at 50% 50%, #1b1b1b 0 32%, #080808 33% 100%);
    box-shadow:
        0 35px 90px -30px rgba(0, 0, 0, 0.95),
        inset 0 0 70px rgba(0, 0, 0, 0.85);
    animation: vinyl-spin 32s linear infinite;
    transition: border-color 0.35s ease, box-shadow 0.35s ease;
}

.vinyl::before {
    content: '';
    position: absolute;
    inset: 36%;
    border-radius: 50%;
    border: 1px solid rgba(181, 255, 60, 0.24);
    background: radial-gradient(circle at 35% 30%, #303030, #0a0a0a 72%);
}

.vinyl::after {
    content: '';
    position: absolute;
    left: 50%;
    top: 50%;
    width: 8px;
    height: 8px;
    translate: -50% -50%;
    border-radius: 50%;
    background: #b5ff3c;
    box-shadow: 0 0 14px rgba(181, 255, 60, 0.8);
}

.portrait-halo {
    position: absolute;
    left: 50%;
    top: 17%;
    z-index: 1;
    width: 84%;
    aspect-ratio: 1;
    translate: -50% 0;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(181, 255, 60, 0.2), rgba(181, 255, 60, 0.05) 44%, transparent 70%);
    filter: blur(18px);
}

.portrait-photo {
    position: relative;
    z-index: 2;
    display: block;
    width: 100%;
    height: auto;
    filter: saturate(0.94) contrast(1.06) drop-shadow(0 30px 55px rgba(0, 0, 0, 0.8));
    transition: filter 0.45s ease;
}

.portrait-bottom-fade {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 3;
    height: 32%;
    background: linear-gradient(to top, #080808 0%, rgba(8, 8, 8, 0.82) 28%, transparent 100%);
}

.portrait-badge {
    position: absolute;
    left: 50%;
    bottom: 4%;
    z-index: 4;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    translate: -50% 0;
    padding: 0.36rem 0.7rem;
    border: 1px solid rgba(181, 255, 60, 0.28);
    border-radius: 999px;
    background: rgba(8, 10, 8, 0.7);
    color: rgba(255, 255, 255, 0.66);
    font-size: 7px;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    white-space: nowrap;
    backdrop-filter: blur(10px);
}

.live-dot {
    width: 5px;
    height: 5px;
    border-radius: 999px;
    background: #b5ff3c;
    box-shadow: 0 0 10px rgba(181, 255, 60, 0.8);
    animation: live-pulse 1.8s ease-in-out infinite;
}

.equalizer {
    display: inline-flex;
    align-items: flex-end;
    gap: 1.5px;
    height: 8px;
    margin-left: 0.1rem;
}

.equalizer i {
    display: block;
    width: 2px;
    height: 100%;
    border-radius: 999px;
    background: #b5ff3c;
    transform-origin: bottom;
    animation: equalize 0.9s ease-in-out infinite alternate;
}

.equalizer i:nth-child(2) {
    animation-delay: -0.35s;
}

.equalizer i:nth-child(3) {
    animation-delay: -0.65s;
}

.card-layer {
    position: absolute;
    z-index: 20;
    transform: translate3d(
        calc(var(--mx) * 14px),
        calc(var(--my) * 12px),
        0
    );
    transition: transform 0.2s ease-out;
}

.card-main {
    top: 0;
    left: 50%;
    width: 60%;
    translate: -50% 0;
    transform: translate3d(
        calc(var(--mx) * 18px),
        calc(var(--my) * 15px),
        0
    );
}

.card-right {
    top: 22%;
    right: 0;
    width: 36%;
    transform: translate3d(
        calc(var(--mx) * 26px),
        calc(var(--my) * 20px),
        0
    );
}

.card-bottom-left {
    bottom: 5%;
    left: 0;
    width: 31%;
    transform: translate3d(
        calc(var(--mx) * 22px),
        calc(var(--my) * 18px),
        0
    );
}

.card-bottom-right {
    right: 0;
    bottom: 2%;
    width: 34%;
    transform: translate3d(
        calc(var(--mx) * 30px),
        calc(var(--my) * 22px),
        0
    );
}

.music-card {
    --rot: 0deg;
    display: flex;
    gap: 0.55rem;
    padding: 0.55rem;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 1rem;
    background: linear-gradient(145deg, rgba(23, 28, 23, 0.84), rgba(7, 9, 7, 0.92));
    box-shadow:
        0 28px 65px -25px rgba(0, 0, 0, 0.95),
        inset 0 1px 0 rgba(255, 255, 255, 0.08);
    color: #fff;
    rotate: var(--rot);
    animation: card-float 8s ease-in-out infinite;
    backdrop-filter: blur(18px);
    transition: border-color 0.35s ease, box-shadow 0.35s ease;
}

.card-main .music-card {
    --rot: -2deg;
}

.card-right .music-card {
    --rot: 3deg;
    animation-delay: -2.2s;
}

.card-bottom-left .music-card {
    --rot: -4deg;
    animation-delay: -4.1s;
}

.card-bottom-right .music-card {
    --rot: 2deg;
    animation-delay: -1.3s;
}

.music-card.is-stacked {
    flex-direction: column;
    gap: 0.45rem;
}

.album-art {
    position: relative;
    flex-shrink: 0;
    width: 5.2rem;
    aspect-ratio: 1;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 0.7rem;
}

.card-right .album-art {
    width: 4rem;
}

.music-card.is-stacked .album-art {
    width: 100%;
    height: 4.6rem;
    aspect-ratio: auto;
}

.album-art::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.35), transparent 55%);
    pointer-events: none;
}

.album-bye {
    background:
        radial-gradient(circle at 30% 22%, rgba(244, 174, 214, 0.55), transparent 48%),
        linear-gradient(145deg, #7a3a60, #241026 78%);
}

.album-bye .album-symbol {
    color: rgba(255, 255, 255, 0.82);
}

.album-rumah {
    background:
        radial-gradient(circle at 28% 22%, rgba(128, 190, 116, 0.42), transparent 42%),
        linear-gradient(145deg, #1d3020, #070b08 72%);
}

.album-cincin {
    background:
        radial-gradient(circle at 38% 20%, rgba(95, 210, 224, 0.38), transparent 44%),
        linear-gradient(145deg, #163844, #050d12 72%);
}

.album-evaluasi {
    background:
        radial-gradient(circle at 40% 28%, #f2f2f2, #a6a6a6 46%, #2a2a2a 78%, #111);
}

.album-symbol {
    position: absolute;
    left: 50%;
    top: 50%;
    translate: -50% -50%;
    opacity: 0.34;
    color: rgba(255, 255, 255, 0.9);
}

.album-evaluasi .album-symbol {
    color: rgba(10, 10, 10, 0.58);
}

.album-cincin .album-symbol {
    color: rgba(181, 255, 60, 0.75);
}

.music-body {
    display: flex;
    flex: 1;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
}

.music-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.35rem;
}

.music-title {
    overflow: hidden;
    color: #fff;
    font-size: 0.62rem;
    font-weight: 600;
    line-height: 1.2;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.music-artist {
    margin-top: 0.18rem;
    color: rgba(255, 255, 255, 0.45);
    font-size: 0.48rem;
    letter-spacing: 0.13em;
    text-transform: uppercase;
}

.music-heart {
    flex-shrink: 0;
    color: rgba(255, 255, 255, 0.42);
}

.music-progress {
    position: relative;
    height: 3px;
    margin-top: 0.6rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.13);
}

.music-progress-fill {
    display: block;
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, #b5ff3c, #eaffb0);
    box-shadow: 0 0 10px rgba(181, 255, 60, 0.45);
}

.music-progress-knob {
    position: absolute;
    top: 50%;
    width: 6px;
    height: 6px;
    translate: -50% -50%;
    border-radius: 50%;
    background: #b5ff3c;
    box-shadow: 0 0 8px rgba(181, 255, 60, 0.8);
}

.music-time {
    display: flex;
    justify-content: space-between;
    margin-top: 0.3rem;
    color: rgba(255, 255, 255, 0.34);
    font-size: 0.42rem;
    letter-spacing: 0.08em;
}

.music-controls {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    margin-top: 0.5rem;
    color: rgba(255, 255, 255, 0.62);
}

.music-controls.compact {
    margin-top: 0.4rem;
    gap: 0.4rem;
}

.music-play {
    display: grid;
    place-items: center;
    width: 1.35rem;
    height: 1.35rem;
    border-radius: 50%;
    background: #f4f4f4;
    color: #090909;
    box-shadow: 0 0 12px rgba(255, 255, 255, 0.16);
}

.music-controls.compact .music-play {
    width: 1.1rem;
    height: 1.1rem;
}

.music-time-inline {
    color: rgba(255, 255, 255, 0.35);
    font-size: 0.42rem;
    letter-spacing: 0.08em;
}

.floating-shape {
    position: absolute;
    z-index: 16;
    color: rgba(255, 255, 255, 0.17);
    filter: drop-shadow(0 0 16px rgba(181, 255, 60, 0.14));
    pointer-events: none;
}

.shape-bird-a {
    top: 17%;
    left: 3%;
    width: 2.1rem;
    animation: drift-a 9s ease-in-out infinite;
}

.shape-bird-b {
    top: 38%;
    right: 4%;
    width: 1.35rem;
    color: rgba(181, 255, 60, 0.3);
    animation: drift-b 11s ease-in-out infinite;
}

.shape-butterfly {
    bottom: 27%;
    left: 7%;
    width: 1.7rem;
    color: rgba(255, 255, 255, 0.15);
    animation: drift-c 10s ease-in-out infinite;
}

.stage-footer {
    position: absolute;
    right: 0;
    bottom: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    color: rgba(255, 255, 255, 0.28);
    font-size: 0.46rem;
    font-weight: 600;
    letter-spacing: 0.26em;
    text-transform: uppercase;
}

.stage-footer-dot {
    width: 4px;
    height: 4px;
    border-radius: 999px;
    background: #b5ff3c;
    box-shadow: 0 0 9px rgba(181, 255, 60, 0.7);
}

.hero-stage.is-active .music-card {
    border-color: rgba(181, 255, 60, 0.28);
    box-shadow:
        0 32px 75px -25px rgba(0, 0, 0, 0.98),
        0 0 38px -18px rgba(181, 255, 60, 0.28),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.hero-stage.is-active .portrait-photo {
    filter: saturate(1.02) contrast(1.08) drop-shadow(0 32px 58px rgba(0, 0, 0, 0.82));
}

.hero-stage.is-active .vinyl {
    border-color: rgba(181, 255, 60, 0.38);
    box-shadow:
        0 35px 95px -30px rgba(0, 0, 0, 0.98),
        0 0 45px -18px rgba(181, 255, 60, 0.25),
        inset 0 0 70px rgba(0, 0, 0, 0.85);
}

@keyframes portrait-float {
    0%,
    100% {
        translate: 0 0;
    }

    50% {
        translate: 0 -7px;
    }
}

@keyframes card-float {
    0%,
    100% {
        translate: 0 0;
    }

    50% {
        translate: 0 -8px;
    }
}

@keyframes vinyl-spin {
    to {
        rotate: 360deg;
    }
}

@keyframes live-pulse {
    0%,
    100% {
        opacity: 0.55;
        transform: scale(0.85);
    }

    50% {
        opacity: 1;
        transform: scale(1.15);
    }
}

@keyframes equalize {
    0% {
        transform: scaleY(0.35);
    }

    100% {
        transform: scaleY(1);
    }
}

@keyframes drift-a {
    0%,
    100% {
        translate: 0 0;
        rotate: -8deg;
    }

    50% {
        translate: 10px -14px;
        rotate: 4deg;
    }
}

@keyframes drift-b {
    0%,
    100% {
        translate: 0 0;
        rotate: 12deg;
    }

    50% {
        translate: -12px 12px;
        rotate: -6deg;
    }
}

@keyframes drift-c {
    0%,
    100% {
        translate: 0 0;
        rotate: -4deg;
    }

    50% {
        translate: 8px 12px;
        rotate: 8deg;
    }
}

@media (max-width: 1023px) {
    .card-main {
        width: 64%;
    }

    .card-right {
        width: 39%;
    }

    .card-bottom-left {
        width: 34%;
    }

    .card-bottom-right {
        width: 37%;
    }
}

@media (max-width: 639px) {
    .card-right,
    .card-bottom-left,
    .card-bottom-right,
    .stage-footer,
    .shape-bird-b,
    .shape-butterfly {
        display: none;
    }

    .card-main {
        top: 0;
        width: 86%;
    }

    .portrait-layer {
        width: 72%;
        bottom: -2%;
    }

    .portrait-badge {
        font-size: 6px;
    }

    .music-card {
        padding: 0.45rem;
    }

    .album-art {
        width: 4.4rem;
    }

    .music-title {
        font-size: 0.58rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    .portrait-float,
    .music-card,
    .vinyl,
    .floating-shape,
    .live-dot,
    .equalizer i {
        animation: none;
    }

    .portrait-layer,
    .card-layer,
    .music-card,
    .portrait-photo,
    .vinyl,
    .stage-glow {
        transition: none;
    }

    .portrait-layer,
    .card-layer {
        transform: none;
    }
}
</style>
