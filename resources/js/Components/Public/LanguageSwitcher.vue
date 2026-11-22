<script setup>
import { Languages } from 'lucide-vue-next';
import { useI18n } from '../../composables/useI18n';

defineProps({
    compact: { type: Boolean, default: false },
});

const { t, locale, locales, setLocale } = useI18n();

const labelFor = (code) => (code === 'id' ? 'ID' : 'EN');
const nameFor = (code) => (code === 'id' ? 'Bahasa Indonesia' : 'English');
</script>

<template>
    <div
        class="inline-flex items-center gap-1.5 rounded-full border border-accent/40 bg-panel/90 px-1.5 py-1 shadow-lg shadow-black/20"
        role="group"
        :aria-label="t('common.language')"
    >
        <Languages v-if="!compact" :size="13" class="ml-0.5 shrink-0 text-accent" />
        <span v-if="!compact" class="text-[9px] font-semibold uppercase tracking-[0.16em] text-muted">
            {{ t('common.language') }}
        </span>
        <button
            v-for="code in locales"
            :key="code"
            type="button"
            class="rounded-full px-2 py-1 text-[10px] font-bold tracking-[0.08em] transition-colors"
            :class="locale === code ? 'bg-accent text-ink' : 'text-white/70 hover:bg-white/10 hover:text-white'"
            :aria-label="nameFor(code)"
            :aria-pressed="locale === code"
            :title="nameFor(code)"
            @click="setLocale(code)"
        >
            {{ labelFor(code) }}
        </button>
    </div>
</template>
