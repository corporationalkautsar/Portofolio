<script setup>
import ResourceManager from '../../../Components/Admin/ResourceManager.vue';
import { useI18n } from '../../../composables/useI18n';

const { t } = useI18n();

defineProps({ skills: { type: Array, default: () => [] } });

const columns = [
    { key: 'name', label: t('admin.resource.skill'), emphasis: true },
    { key: 'percentage', label: t('admin.resource.level') },
    { key: 'icon', label: t('common.icon') },
];

const fields = [
    { key: 'name', label: t('admin.resource.skillNameRequired'), placeholder: t('admin.resource.skillPlaceholder') },
    { key: 'percentage', label: t('admin.resource.percentageRequired'), type: 'number', default: 80 },
    { key: 'icon', label: t('admin.resource.iconName'), placeholder: t('admin.resource.iconPlaceholder') },
];
</script>

<template>
    <ResourceManager
        :page-title="t('admin.nav.skills')"
        :subtitle="t('admin.resource.skillsSubtitle')"
        :singular="t('admin.resource.skill')"
        route-name="admin.skills"
        :items="skills"
        :columns="columns"
        :fields="fields"
    />
</template>
