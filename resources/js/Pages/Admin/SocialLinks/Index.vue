<script setup>
import ResourceManager from '../../../Components/Admin/ResourceManager.vue';
import { useI18n } from '../../../composables/useI18n';

const { t } = useI18n();

defineProps({ socialLinks: { type: Array, default: () => [] } });

const columns = [
    { key: 'platform', label: t('common.platform'), emphasis: true },
    { key: 'url', label: t('common.url'), truncate: true },
];

const fields = [
    { key: 'platform', label: t('admin.resource.platformRequired'), placeholder: t('admin.resource.platformPlaceholder') },
    { key: 'url', label: t('admin.resource.urlRequired'), type: 'url', placeholder: t('admin.projects.form.urlPlaceholder') },
];
</script>

<template>
    <ResourceManager
        :page-title="t('admin.nav.socialLinks')"
        :subtitle="t('admin.resource.socialLinksSubtitle')"
        :singular="t('admin.resource.socialLink')"
        route-name="admin.social-links"
        :items="socialLinks"
        :columns="columns"
        :fields="fields"
    />
</template>
