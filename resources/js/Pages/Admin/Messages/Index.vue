<script setup>
import { route } from 'ziggy-js';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ChevronDown, Mail, MailOpen } from 'lucide-vue-next';
import AdminCard from '../../../Components/Admin/AdminCard.vue';
import ConfirmButton from '../../../Components/Admin/ConfirmButton.vue';
import Pagination from '../../../Components/Admin/Pagination.vue';
import { useI18n } from '../../../composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    messages: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const filters = useForm({ status: props.filters.status || '' });
const expanded = ref(null);

function applyFilters() {
    filters.get(route('admin.messages.index'), { preserveState: true, replace: true });
}

function toggleRead(message) {
    router.put(route('admin.messages.update', message.id), {}, { preserveScroll: true });
}
</script>

<template>
    <Head><title>{{ $pageTitle }}</title></Head>

    <div class="space-y-8">
        <div>
            <p class="eyebrow">{{ t('admin.dashboard.inbox') }}</p>
            <h1 class="mt-4 font-display text-3xl font-semibold md:text-4xl">{{ t('admin.nav.messages') }}</h1>
            <p class="mt-2 text-sm text-muted">
                {{ messages.total }} {{ messages.total === 1 ? t('admin.messages.message') : t('admin.nav.messages') }} {{ t('admin.messages.countSuffix') }}
            </p>
        </div>

        <AdminCard :title="t('admin.messages.submissions')">
            <form class="flex flex-wrap gap-3" @submit.prevent="applyFilters">
                <select v-model="filters.status" class="field max-w-52">
                    <option value="">{{ t('admin.messages.allMessages') }}</option>
                    <option value="unread">{{ t('common.unread') }}</option>
                    <option value="read">{{ t('common.read') }}</option>
                </select>
                <button class="btn btn-ghost !min-h-[2.75rem]" type="submit">{{ t('admin.messages.filter') }}</button>
            </form>

            <div class="mt-8 overflow-x-auto">
                <table class="w-full min-w-[44rem] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-[10px] uppercase tracking-[0.14em] text-muted">
                            <th class="pb-3 font-medium">{{ t('admin.messages.from') }}</th>
                            <th class="pb-3 font-medium">{{ t('admin.messages.subject') }}</th>
                            <th class="pb-3 font-medium">{{ t('admin.messages.received') }}</th>
                            <th class="pb-3 font-medium">{{ t('common.status') }}</th>
                            <th class="pb-3 text-right font-medium">{{ t('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody v-for="message in messages.data" :key="message.id" class="divide-y divide-line">
                        <tr class="transition-colors hover:bg-white/[0.02]">
                            <td class="py-4">
                                <p class="font-medium" :class="!message.is_read ? 'text-white' : 'text-muted'">{{ message.name }}</p>
                                <p class="text-[11px] text-muted">{{ message.email }}</p>
                            </td>
                            <td class="py-4">
                                <button type="button" class="text-left transition-colors hover:text-accent" @click="expanded = expanded === message.id ? null : message.id">
                                    {{ message.subject }}
                                    <ChevronDown :size="13" class="ml-1 inline transition-transform" :class="expanded === message.id ? 'rotate-180' : ''" />
                                </button>
                            </td>
                            <td class="py-4 text-muted">{{ message.created_at }}</td>
                            <td class="py-4">
                                <span v-if="message.is_read" class="inline-flex items-center gap-1.5 text-[11px] text-muted"><MailOpen :size="14" /> {{ t('common.read') }}</span>
                                <span v-else class="inline-flex items-center gap-1.5 text-[11px] text-accent"><Mail :size="14" /> {{ t('common.unread') }}</span>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <button type="button" class="text-xs text-muted transition-colors hover:text-accent" @click="toggleRead(message)">
                                        {{ message.is_read ? t('admin.messages.markUnread') : t('admin.messages.markRead') }}
                                    </button>
                                    <ConfirmButton
                                        :href="route('admin.messages.destroy', message.id)"
                                        :label="t('common.delete')"
                                        :message="t('admin.messages.deleteConfirm')"
                                    />
                                </div>
                            </td>
                        </tr>
                        <tr v-if="expanded === message.id">
                            <td colspan="5" class="bg-ink/40 px-5 py-5">
                                <p class="whitespace-pre-line text-sm leading-relaxed text-muted">{{ message.message }}</p>
                                <a :href="`mailto:${message.email}`" class="mt-4 inline-block text-xs text-accent">{{ t('admin.messages.replyEmail') }} â†’</a>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="!messages.data.length">
                        <tr><td colspan="5" class="py-14 text-center text-sm text-muted">{{ t('admin.messages.empty') }}</td></tr>
                    </tbody>
                </table>
            </div>

            <Pagination :pagination="messages" />
        </AdminCard>
    </div>
</template>
