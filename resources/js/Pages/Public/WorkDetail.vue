<script setup>
import { route } from 'ziggy-js';
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, ArrowUpRight } from 'lucide-vue-next';
import ProjectCard from '../../Components/Public/ProjectCard.vue';
import { resolveDescription } from '../../utils/seo';
import { useI18n } from '../../composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    project: { type: Object, required: true },
    related: { type: Array, default: () => [] },
    title: { type: String, default: null },
    description: { type: String, default: null },
});

const metaDescription = computed(() => resolveDescription(props));
const stack = computed(() => (props.project.stack || '')
    .split(',')
    .map((item) => item.trim())
    .filter(Boolean));
</script>

<template>
    <Head>
        <title>{{ $pageTitle }}</title>
        <meta name="description" :content="metaDescription" />
        <meta property="og:title" :content="$pageTitle" />
        <meta property="og:description" :content="metaDescription" />
        <meta property="og:image" :content="project.project_image_url" />
    </Head>

    <article class="pt-32 md:pt-40">
        <div class="shell">
            <Link :href="route('home') + '#works'" class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-muted transition-colors hover:text-accent">
                <ArrowLeft :size="15" /> {{ t('work.backToWork') }}
            </Link>

            <div class="mt-10 grid gap-8 border-b border-line pb-12 md:grid-cols-[1fr_auto] md:items-end">
                <div>
                    <p class="eyebrow">{{ project.category?.name }} / {{ project.year }}</p>
                    <h1 class="mt-6 max-w-4xl font-display text-5xl font-semibold leading-[0.95] tracking-[-0.05em] md:text-7xl">
                        {{ project.title }}
                    </h1>
                </div>
                <a
                    v-if="project.project_url"
                    :href="project.project_url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-ghost"
                >
                    {{ t('work.visitProject') }} <ArrowUpRight :size="15" />
                </a>
            </div>

            <div class="mt-12 overflow-hidden rounded-2xl border border-line bg-panel">
                <img :src="project.project_image_url" :alt="`${project.title} ${t('work.placeholderArtwork')}`" class="aspect-[16/9] w-full object-cover" />
            </div>

            <div class="mt-14 grid gap-10 lg:grid-cols-[0.45fr_1fr]">
                <dl class="space-y-6 text-xs uppercase tracking-[0.14em]">
                    <div>
                        <dt class="text-muted">{{ t('work.category') }}</dt>
                        <dd class="mt-2 text-white">{{ project.category?.name }}</dd>
                    </div>
                    <div>
                        <dt class="text-muted">{{ t('work.year') }}</dt>
                        <dd class="mt-2 text-white">{{ project.year }}</dd>
                    </div>
                    <div v-if="stack.length">
                        <dt class="text-muted">{{ t('work.stack') }}</dt>
                        <dd class="mt-2 flex flex-wrap gap-2">
                            <span v-for="item in stack" :key="item" class="rounded-full border border-accent/25 bg-accent/5 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-accent">
                                {{ item }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-muted">{{ t('work.status') }}</dt>
                        <dd class="mt-2 text-accent">{{ t('work.published') }}</dd>
                    </div>
                </dl>
                <div>
                    <p class="font-display text-2xl leading-relaxed text-white md:text-3xl">{{ project.description }}</p>
                    <p class="mt-8 max-w-2xl text-sm leading-relaxed text-muted">
                        {{ t('work.caseStudyPlaceholder') }}
                    </p>
                </div>
            </div>
        </div>

        <section v-if="related.length" class="mt-28 border-t border-line py-20">
            <div class="shell">
                <p class="eyebrow">{{ t('work.moreInCategory') }}</p>
                <div class="mt-10 grid gap-5 md:grid-cols-3">
                    <ProjectCard v-for="(item, index) in related" :key="item.id" :project="item" :index="index" />
                </div>
            </div>
        </section>
    </article>
</template>
