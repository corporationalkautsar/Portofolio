<script setup>
import { route } from 'ziggy-js';
import { computed, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Pencil, Plus, X } from 'lucide-vue-next';
import AdminCard from './AdminCard.vue';
import ConfirmButton from './ConfirmButton.vue';
import { useI18n } from '../../composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    pageTitle: { type: String, required: true },
    subtitle: { type: String, default: '' },
    items: { type: Array, default: () => [] },
    columns: { type: Array, required: true },
    fields: { type: Array, required: true },
    routeName: { type: String, required: true },
    singular: { type: String, default: '' },
    deleteMessage: { type: String, default: '' },
});

const itemLabel = computed(() => props.singular || t('admin.resource.item'));

const form = useForm(Object.fromEntries(props.fields.map((field) => [field.key, field.default ?? ''])));
const showForm = ref(false);
const editing = ref(null);

function openCreate() {
    editing.value = null;
    form.reset();
    form.clearErrors();
    showForm.value = true;
}

function openEdit(item) {
    editing.value = item.id;
    form.clearErrors();
    props.fields.forEach((field) => {
        form[field.key] = item[field.key] ?? '';
    });
    showForm.value = true;
}

function closeForm() {
    showForm.value = false;
    editing.value = null;
    form.reset();
    form.clearErrors();
}

function submit() {
    const options = {
        preserveScroll: true,
        onSuccess: () => closeForm(),
    };

    if (editing.value) {
        form.put(route(`${props.routeName}.update`, editing.value), options);
    } else {
        form.post(route(`${props.routeName}.store`), options);
    }
}

function display(item, column) {
    const value = item[column.key];
    if (column.truncate && typeof value === 'string' && value.length > 64) {
        return `${value.slice(0, 64)}â€¦`;
    }
    return value ?? 'â€”';
}
</script>

<template>
    <Head><title>{{ pageTitle }}</title></Head>

    <div class="space-y-8">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="eyebrow">{{ t('common.manage') }}</p>
                <h1 class="mt-4 font-display text-3xl font-semibold md:text-4xl">{{ pageTitle }}</h1>
                <p v-if="subtitle" class="mt-2 text-sm text-muted">{{ subtitle }}</p>
            </div>
            <button class="btn btn-primary" type="button" @click="openCreate">
                <Plus :size="15" /> {{ t('common.add') }} {{ itemLabel }}
            </button>
        </div>

        <Transition name="form-panel">
            <AdminCard v-if="showForm" :title="editing ? `${t('common.edit')} ${itemLabel}` : `${t('common.new')} ${itemLabel}`">
                <template #actions>
                    <button class="text-muted transition-colors hover:text-white" :aria-label="t('common.closeForm')" @click="closeForm">
                        <X :size="18" />
                    </button>
                </template>
                <form class="grid gap-5 md:grid-cols-2" @submit.prevent="submit">
                    <div v-for="field in fields" :key="field.key" :class="field.type === 'textarea' ? 'md:col-span-2' : ''">
                        <label class="label" :for="`field-${field.key}`">{{ field.label }}</label>
                        <textarea
                            v-if="field.type === 'textarea'"
                            :id="`field-${field.key}`"
                            v-model="form[field.key]"
                            class="field min-h-28 resize-y"
                            :placeholder="field.placeholder"
                        />
                        <input
                            v-else
                            :id="`field-${field.key}`"
                            v-model="form[field.key]"
                            class="field"
                            :type="field.type || 'text'"
                            :placeholder="field.placeholder"
                        />
                        <p v-if="form.errors[field.key]" class="mt-2 text-xs text-red-400">{{ form.errors[field.key] }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <button class="btn btn-primary" type="submit" :disabled="form.processing">
                            {{ form.processing ? t('common.saving') : editing ? t('common.saveChanges') : `${t('common.create')} ${itemLabel}` }}
                        </button>
                    </div>
                </form>
            </AdminCard>
        </Transition>

        <AdminCard :title="`${t('common.all')} ${pageTitle.toLowerCase()}`">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[36rem] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-[10px] uppercase tracking-[0.14em] text-muted">
                            <th v-for="column in columns" :key="column.key" class="pb-3 font-medium">{{ column.label }}</th>
                            <th class="pb-3 text-right font-medium">{{ t('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        <tr v-for="item in items" :key="item.id" class="transition-colors hover:bg-white/[0.02]">
                            <td v-for="column in columns" :key="column.key" class="py-4 pr-4 text-muted">
                                <span :class="column.emphasis ? 'font-medium text-white' : ''">{{ display(item, column) }}</span>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <button type="button" class="inline-flex items-center gap-1.5 text-xs text-muted transition-colors hover:text-accent" @click="openEdit(item)">
                                        <Pencil :size="14" /> {{ t('common.edit') }}
                                    </button>
                                    <ConfirmButton
                                        :href="route(`${routeName}.destroy`, item.id)"
                                        :message="deleteMessage || `${t('common.delete')} ${itemLabel}?`"
                                    />
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!items.length">
                            <td :colspan="columns.length + 1" class="py-14 text-center text-sm text-muted">{{ t('common.nothingHere') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </AdminCard>
    </div>
</template>

<style scoped>
.form-panel-enter-active, .form-panel-leave-active { transition: opacity .25s ease, transform .25s ease; }
.form-panel-enter-from, .form-panel-leave-to { opacity: 0; transform: translateY(-8px); }
</style>
