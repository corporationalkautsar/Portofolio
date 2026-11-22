<script setup>
import ResourceManager from '../../../Components/Admin/ResourceManager.vue';
import { useI18n } from '../../../composables/useI18n';

const { t } = useI18n();

defineProps({ settings: { type: Array, default: () => [] } });

const columns = [
    { key: 'key', label: t('common.key'), emphasis: true },
    { key: 'value', label: t('common.value'), truncate: true },
];

const fields = [
    { key: 'key', label: t('admin.resource.keyRequired'), placeholder: t('admin.resource.keyPlaceholder') },
    { key: 'value', label: t('common.value'), type: 'textarea', placeholder: t('admin.resource.valuePlaceholder') },
];
</script>

<template>
    <ResourceManager
        :page-title="t('admin.nav.settings')"
        :subtitle="t('admin.resource.settingsSubtitle')"
        :singular="t('admin.resource.setting')"
        route-name="admin.settings"
        :items="settings"
        :columns="columns"
        :fields="fields"
    />
</template>
