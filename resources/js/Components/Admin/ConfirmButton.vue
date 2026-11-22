<script setup>
import { router } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';
import { useI18n } from '../../composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    href: { type: String, required: true },
    label: { type: String, default: '' },
    message: { type: String, default: '' },
});

function remove() {
    if (window.confirm(props.message || t('common.deleteConfirm'))) {
        router.delete(props.href, { preserveScroll: true });
    }
}
</script>

<template>
    <button type="button" class="inline-flex items-center gap-1.5 text-xs text-muted transition-colors hover:text-red-400" @click="remove">
        <Trash2 :size="14" /> {{ label || t('common.delete') }}
    </button>
</template>
