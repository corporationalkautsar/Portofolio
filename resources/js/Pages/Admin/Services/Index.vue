<script setup>
import ResourceManager from '../../../Components/Admin/ResourceManager.vue';
import { useI18n } from '../../../composables/useI18n';

const { t } = useI18n();

defineProps({ services: { type: Array, default: () => [] } });

const columns = [
    { key: 'title', label: t('admin.resource.service'), emphasis: true },
    { key: 'description', label: t('common.description'), truncate: true },
    { key: 'icon', label: t('common.icon') },
];

const fields = [
    { key: 'title', label: t('admin.resource.titleRequired'), placeholder: t('admin.resource.skillPlaceholder') },
    { key: 'description', label: t('admin.resource.descriptionRequired'), type: 'textarea', placeholder: t('admin.resource.descriptionPlaceholder') },
    { key: 'icon', label: t('admin.resource.iconName'), placeholder: t('admin.resource.iconExample') },
];
</script>

<template>
    <ResourceManager
        :page-title="t('admin.nav.services')"
        :subtitle="t('admin.resource.servicesSubtitle')"
        :singular="t('admin.resource.service')"
        route-name="admin.services"
        :items="services"
        :columns="columns"
        :fields="fields"
    />
</template>
