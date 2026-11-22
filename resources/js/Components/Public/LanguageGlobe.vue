<script setup>
import { computed } from 'vue';
import { Globe2 } from 'lucide-vue-next';
import { useI18n } from '../../composables/useI18n';

const { t, locale, locales, setLocale } = useI18n();

const nextLocale = computed(() => locales.value.find((code) => code !== locale.value) || 'en');
const nextName = computed(() => (nextLocale.value === 'id' ? 'Bahasa Indonesia' : 'English'));
const currentLabel = computed(() => (locale.value === 'id' ? 'ID' : 'EN'));
</script>

<template>
    <button
        type="button"
        class="group inline-flex h-10 shrink-0 items-center gap-2 rounded-full border border-line bg-panel/80 px-3 shadow-lg shadow-black/20 backdrop-blur transition-all duration-300 hover:border-accent hover:bg-accent/10"
        :aria-label="`${t('common.language')}: ${t('common.changeLanguage')}`"
        :title="`${t('common.language')} → ${nextName}`"
        @click="setLocale(nextLocale)"
    >
        <Globe2 :size="16" class="text-accent transition-transform duration-500 group-hover:rotate-[30deg]" />
        <span class="text-[10px] font-bold tracking-[0.12em] text-white transition-colors group-hover:text-accent">
            {{ currentLabel }}
        </span>
    </button>
</template>
