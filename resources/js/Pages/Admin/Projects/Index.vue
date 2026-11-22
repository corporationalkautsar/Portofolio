<script setup>
import { route } from 'ziggy-js';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Image as ImageIcon, Pencil, Plus, Search } from 'lucide-vue-next';
import AdminCard from '../../../Components/Admin/AdminCard.vue';
import ConfirmButton from '../../../Components/Admin/ConfirmButton.vue';
import Pagination from '../../../Components/Admin/Pagination.vue';
import { useI18n } from '../../../composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    projects: { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const filters = useForm({
    search: props.filters.search || '',
    category: props.filters.category || '',
    status: props.filters.status || '',
});

const statusLabel = (status) => t(status === 'published' ? 'common.published' : 'common.draft');

function applyFilters() {
    filters.get(route('admin.projects.index'), { preserveState: true, replace: true });
}

function resetFilters() {
    filters.reset();
    router.get(route('admin.projects.index'), {}, { preserveState: true, replace: true });
}
</script>

<template>
    <Head><title>{{ $pageTitle }}</title></Head>

    <div class="space-y-8">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="eyebrow">{{ t('admin.projects.content') }}</p>
                <h1 class="mt-4 font-display text-3xl font-semibold md:text-4xl">{{ t('admin.nav.projects') }}</h1>
                <p class="mt-2 text-sm text-muted">
                    {{ projects.total }} {{ projects.total === 1 ? t('admin.projects.project') : t('admin.nav.projects') }} {{ t('admin.projects.countSuffix') }}
                </p>
            </div>
            <Link :href="route('admin.projects.create')" class="btn btn-primary">
                <Plus :size="15" /> {{ t('admin.projects.newProject') }}
            </Link>
        </div>

        <AdminCard :title="t('admin.projects.allProjects')" :subtitle="t('admin.projects.filterHint')">
            <form class="grid gap-3 md:grid-cols-[1fr_12rem_10rem_auto]" @submit.prevent="applyFilters">
                <div class="relative">
                    <Search :size="15" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted" />
                    <input v-model="filters.search" class="field !pl-9" type="search" :placeholder="t('admin.projects.searchPlaceholder')" />
                </div>
                <select v-model="filters.category" class="field">
                    <option value="">{{ t('admin.projects.allCategories') }}</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
                <select v-model="filters.status" class="field">
                    <option value="">{{ t('admin.projects.allStatuses') }}</option>
                    <option value="published">{{ t('common.published') }}</option>
                    <option value="draft">{{ t('common.draft') }}</option>
                </select>
                <div class="flex gap-2">
                    <button class="btn btn-ghost !min-h-[2.75rem] flex-1" type="submit">{{ t('common.apply') }}</button>
                    <button class="btn btn-ghost !min-h-[2.75rem]" type="button" @click="resetFilters">{{ t('common.reset') }}</button>
                </div>
            </form>

            <div class="mt-8 overflow-x-auto">
                <table class="w-full min-w-[46rem] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-[10px] uppercase tracking-[0.14em] text-muted">
                            <th class="pb-3 font-medium">{{ t('admin.projects.project') }}</th>
                            <th class="pb-3 font-medium">{{ t('work.category') }}</th>
                            <th class="pb-3 font-medium">{{ t('common.year') }}</th>
                            <th class="pb-3 font-medium">{{ t('common.status') }}</th>
                            <th class="pb-3 text-right font-medium">{{ t('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        <tr v-for="project in projects.data" :key="project.id" class="transition-colors hover:bg-white/[0.02]">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <img :src="project.thumbnail_url" :alt="project.title" class="h-11 w-16 rounded-lg border border-line object-cover" loading="lazy" />
                                    <div>
                                        <p class="font-medium">{{ project.title }}</p>
                                        <p class="text-[11px] text-muted">/{{ project.slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 text-muted">{{ project.category?.name }}</td>
                            <td class="py-4 text-muted">{{ project.year }}</td>
                            <td class="py-4">
                                <span class="rounded-full border px-2.5 py-1 text-[10px] uppercase tracking-wider" :class="project.status === 'published' ? 'border-accent/30 text-accent' : 'border-line text-muted'">
                                    {{ statusLabel(project.status) }}
                                </span>
                                <span v-if="project.featured" class="ml-2 text-[10px] text-muted">â˜… {{ t('admin.projects.featured') }}</span>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <Link :href="route('admin.projects.edit', project.id)" class="inline-flex items-center gap-1.5 text-xs text-muted transition-colors hover:text-accent">
                                        <Pencil :size="14" /> {{ t('common.edit') }}
                                    </Link>
                                    <ConfirmButton
                                        :href="route('admin.projects.destroy', project.id)"
                                        :message="t('admin.projects.deleteImages')"
                                    />
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!projects.data.length">
                            <td colspan="5" class="py-14 text-center text-sm text-muted">
                                <ImageIcon :size="22" class="mx-auto mb-3 opacity-50" />
                                {{ t('admin.projects.empty') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :pagination="projects" />
        </AdminCard>
    </div>
</template>
