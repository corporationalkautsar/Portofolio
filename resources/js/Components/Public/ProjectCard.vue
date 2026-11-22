<script setup>
import { route } from 'ziggy-js';
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight } from 'lucide-vue-next';
import SkillLogo from './SkillLogo.vue';
import { useI18n } from '../../composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    project: { type: Object, required: true },
    index: { type: Number, default: 0 },
});

const icons = {
    HTML: 'html5',
    CSS: 'css',
    JavaScript: 'javascript',
    'Vue.js': 'vuejs',
    Laravel: 'laravel',
    PHP: 'php',
};

const iconFor = (name) => icons[name] || '';
const stack = computed(() => (props.project.stack || '')
    .split(',')
    .map((item) => item.trim())
    .filter(Boolean));

const shapes = [
    'md:col-span-7',
    'md:col-span-5',
    'md:col-span-5',
    'md:col-span-7',
    'md:col-span-4',
    'md:col-span-4',
    'md:col-span-4',
    'md:col-span-6',
];

const shapeFor = (index) => shapes[index % shapes.length];
</script>

<template>
    <Link
        :href="route('work.show', project.slug)"
        class="group relative block overflow-hidden rounded-2xl border border-line bg-panel transition-all duration-500 hover:-translate-y-1 hover:border-accent/40 hover:shadow-[0_24px_60px_-30px_rgba(181,255,60,0.45)]"
        :class="shapeFor(index)"
    >
        <div
            class="relative overflow-hidden"
            :class="index % 4 === 0 ? 'aspect-[4/3]' : index % 3 === 0 ? 'aspect-[4/5]' : 'aspect-[16/11]'"
        >
            <img
                :src="project.thumbnail_url"
                :alt="`${project.title} â€” ${t('work.placeholderArtwork')}`"
                loading="lazy"
                decoding="async"
                class="h-full w-full object-cover transition-transform duration-[1.2s] ease-out group-hover:scale-[1.07]"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/10 to-transparent opacity-70 transition-opacity duration-500 group-hover:opacity-40" />
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-accent/80 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100" />
            <div class="absolute right-5 top-5 flex h-10 w-10 translate-y-2 items-center justify-center rounded-full border border-white/20 bg-ink/60 opacity-0 backdrop-blur transition-all duration-500 group-hover:translate-y-0 group-hover:opacity-100 group-hover:border-accent group-hover:text-accent">
                <ArrowUpRight :size="18" />
            </div>
            <div class="absolute inset-x-0 bottom-0 p-6 md:p-7">
                <div class="flex items-center gap-3 text-[10px] font-semibold uppercase tracking-[0.18em] text-accent">
                    <span>{{ project.category?.name }}</span>
                    <span class="h-px w-6 bg-accent/50" />
                    <span class="text-muted">{{ project.year }}</span>
                </div>
                <h3 class="mt-3 font-display text-2xl font-semibold tracking-tight md:text-3xl">
                    {{ project.title }}
                </h3>
            </div>
        </div>

        <div v-if="stack.length" class="flex flex-wrap gap-2 border-t border-line/70 px-6 py-4">
            <span
                v-for="item in stack"
                :key="item"
                class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/[0.03] px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[0.1em] text-white/65 transition-colors duration-300 group-hover:border-accent/25 group-hover:text-white"
            >
                <SkillLogo v-if="iconFor(item)" :icon="iconFor(item)" class="h-3.5 w-3.5" />
                {{ item }}
            </span>
        </div>
    </Link>
</template>
