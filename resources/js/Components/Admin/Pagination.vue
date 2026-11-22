<script setup>
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

defineProps({
    pagination: { type: Object, required: true },
});
</script>

<template>
    <nav v-if="pagination.links?.length > 3" class="mt-6 flex flex-wrap items-center justify-center gap-1.5">
        <template v-for="(link, index) in pagination.links" :key="`${link.label}-${index}`">
            <span v-if="!link.url" class="px-1 text-muted" v-html="link.label" />
            <Link
                v-else
                :href="link.url"
                class="flex h-8 min-w-8 items-center justify-center rounded-lg border px-2 text-xs transition-colors"
                :class="link.active ? 'border-accent bg-accent text-ink' : 'border-line text-muted hover:border-accent hover:text-accent'"
                preserve-scroll
            >
                <ChevronLeft v-if="index === 0" :size="14" />
                <span v-else-if="index === pagination.links.length - 1" class="flex items-center"><ChevronRight :size="14" /></span>
                <span v-else v-html="link.label" />
            </Link>
        </template>
    </nav>
</template>
