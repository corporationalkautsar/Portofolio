<script setup>
import { route } from 'ziggy-js';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowUpRight, FileText, FolderKanban, Layers3, Mail, Sparkles } from 'lucide-vue-next';
import AdminCard from '../../Components/Admin/AdminCard.vue';
import { useI18n } from '../../composables/useI18n';

defineProps({
    stats: { type: Object, required: true },
    recentProjects: { type: Array, default: () => [] },
    recentMessages: { type: Array, default: () => [] },
    byCategory: { type: Array, default: () => [] },
});

const { t } = useI18n();

const cards = [
    { key: 'projects', label: t('admin.dashboard.totalProjects'), icon: FolderKanban },
    { key: 'published', label: t('common.published'), icon: Sparkles },
    { key: 'unread', label: t('admin.dashboard.unreadMessages'), icon: Mail },
    { key: 'categories', label: t('admin.nav.categories'), icon: Layers3 },
];
</script>

<template>
    <Head><title>{{ $pageTitle }}</title></Head>

    <div class="space-y-8">
        <div>
            <p class="eyebrow">{{ t('admin.nav.overview') }}</p>
            <h1 class="mt-4 font-display text-3xl font-semibold md:text-4xl">{{ t('admin.dashboard.goodToSeeYou') }}</h1>
            <p class="mt-2 text-sm text-muted">{{ t('admin.dashboard.summary') }}</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div v-for="card in cards" :key="card.key" class="rounded-2xl border border-line bg-panel p-6">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-muted">{{ card.label }}</p>
                    <component :is="card.icon" :size="18" class="text-accent" :stroke-width="1.6" />
                </div>
                <p class="mt-6 font-display text-4xl font-semibold">{{ stats[card.key].toString().padStart(2, '0') }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
            <AdminCard :title="t('admin.dashboard.recentProjects')" :subtitle="t('admin.dashboard.latestUpdates')">
                <template #actions>
                    <Link :href="route('admin.projects.index')" class="inline-flex items-center gap-1 text-xs text-accent">
                        {{ t('admin.dashboard.viewAll') }} <ArrowUpRight :size="14" />
                    </Link>
                </template>
                <div class="space-y-1">
                    <Link
                        v-for="project in recentProjects"
                        :key="project.id"
                        :href="route('admin.projects.edit', project.id)"
                        class="flex items-center justify-between gap-4 rounded-xl px-3 py-3 transition-colors hover:bg-white/[0.04]"
                    >
                        <div class="flex min-w-0 items-center gap-3">
                            <img :src="project.thumbnail_url" :alt="project.title" class="h-10 w-14 rounded-lg border border-line object-cover" loading="lazy" />
                            <div class="min-w-0">
                                <p class="truncate text-sm">{{ project.title }}</p>
                                <p class="text-[11px] text-muted">{{ project.category?.name }} Â· {{ project.year }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 rounded-full border px-2.5 py-1 text-[10px] uppercase tracking-wider" :class="project.status === 'published' ? 'border-accent/30 text-accent' : 'border-line text-muted'">
                            {{ project.status }}
                        </span>
                    </Link>
                    <p v-if="!recentProjects.length" class="py-8 text-center text-sm text-muted">{{ t('admin.dashboard.noProjects') }}</p>
                </div>
            </AdminCard>

            <AdminCard :title="t('admin.dashboard.unreadTitle')" :subtitle="t('admin.dashboard.inquiries')">
                <template #actions>
                    <Link :href="route('admin.messages.index')" class="inline-flex items-center gap-1 text-xs text-accent">
                        {{ t('admin.dashboard.inbox') }} <ArrowUpRight :size="14" />
                    </Link>
                </template>
                <div class="space-y-1">
                    <Link
                        v-for="message in recentMessages"
                        :key="message.id"
                        :href="route('admin.messages.index')"
                        class="block rounded-xl px-3 py-3 transition-colors hover:bg-white/[0.04]"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <p class="truncate text-sm">{{ message.subject }}</p>
                            <span v-if="!message.is_read" class="h-2 w-2 shrink-0 rounded-full bg-accent" />
                        </div>
                        <p class="mt-1 text-[11px] text-muted">{{ message.name }} Â· {{ message.created_at }}</p>
                    </Link>
                    <p v-if="!recentMessages.length" class="py-8 text-center text-sm text-muted">{{ t('admin.dashboard.noMessages') }}</p>
                </div>
            </AdminCard>
        </div>

        <AdminCard :title="t('admin.dashboard.byCategory')">
            <div class="space-y-4">
                <div v-for="category in byCategory" :key="category.id">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-muted">{{ category.name }}</span>
                        <span class="font-display text-white">{{ category.projects_count }}</span>
                    </div>
                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-line">
                        <div class="h-full rounded-full bg-accent transition-all duration-700" :style="{ width: `${Math.min(100, category.projects_count * 20)}%` }" />
                    </div>
                </div>
                <p v-if="!byCategory.length" class="text-sm text-muted">{{ t('admin.dashboard.noCategories') }}</p>
            </div>
        </AdminCard>

        <div class="grid gap-4 sm:grid-cols-3">
            <Link v-for="item in [
                { label: t('admin.dashboard.manageSkills'), href: route('admin.skills.index'), icon: Sparkles },
                { label: t('admin.dashboard.editProfile'), href: route('admin.profile.edit'), icon: FileText },
                { label: t('admin.dashboard.siteSettings'), href: route('admin.settings.index'), icon: Layers3 },
            ]" :key="item.label" :href="item.href" class="flex items-center justify-between rounded-xl border border-line bg-panel px-5 py-4 text-sm transition-colors hover:border-accent">
                <span class="flex items-center gap-3"><component :is="item.icon" :size="16" class="text-accent" /> {{ item.label }}</span>
                <ArrowUpRight :size="15" class="text-muted" />
            </Link>
        </div>
    </div>
</template>
