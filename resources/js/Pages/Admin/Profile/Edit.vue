<script setup>
import { route } from 'ziggy-js';
import { Head, useForm } from '@inertiajs/vue3';
import { Save, UserRound } from 'lucide-vue-next';
import AdminCard from '../../../Components/Admin/AdminCard.vue';
import { useI18n } from '../../../composables/useI18n';

const { t } = useI18n();

const props = defineProps({ profile: { type: Object, required: true } });

const form = useForm({
    name: props.profile.name || '',
    profession: props.profile.profession || '',
    biography: props.profile.biography || '',
    biography_id: props.profile.biography_id || '',
    biography_en: props.profile.biography_en || '',
    email: props.profile.email || '',
    whatsapp: props.profile.whatsapp || '',
    location: props.profile.location || '',
    photo: null,
});

function submit() {
    form.put(route('admin.profile.update'), { forceFormData: true, preserveScroll: true });
}
</script>

<template>
    <Head><title>{{ $pageTitle }}</title></Head>

    <div class="space-y-8">
        <div>
            <p class="eyebrow">{{ t('admin.profile.identity') }}</p>
            <h1 class="mt-4 font-display text-3xl font-semibold md:text-4xl">{{ t('admin.profile.title') }}</h1>
            <p class="mt-2 text-sm text-muted">{{ t('admin.profile.description') }}</p>
        </div>

        <form class="grid gap-6 xl:grid-cols-[1fr_1.6fr]" @submit.prevent="submit">
            <AdminCard :title="t('admin.profile.photoContact')">
                <div v-if="profile.photo_url" class="overflow-hidden rounded-xl border border-line">
                    <img :src="profile.photo_url" :alt="t('admin.profile.currentPhoto')" class="aspect-[4/5] w-full object-cover" />
                </div>
                <div class="mt-5">
                    <label class="label" for="photo">{{ t('admin.profile.replacePhoto') }}</label>
                    <input id="photo" class="field file:mr-3 file:rounded-full file:border-0 file:bg-accent file:px-3 file:py-1 file:text-xs file:font-semibold file:text-ink" type="file" accept="image/*" @change="form.photo = $event.target.files[0]" />
                    <p v-if="form.errors.photo" class="mt-2 text-xs text-red-400">{{ form.errors.photo }}</p>
                </div>
                <div class="mt-6 space-y-4">
                    <div>
                        <label class="label" for="email">{{ t('admin.profile.emailRequired') }}</label>
                        <input id="email" v-model="form.email" class="field" type="email" />
                        <p v-if="form.errors.email" class="mt-2 text-xs text-red-400">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label class="label" for="whatsapp">{{ t('admin.profile.whatsappUrl') }}</label>
                        <input id="whatsapp" v-model="form.whatsapp" class="field" type="url" :placeholder="t('admin.profile.whatsappPlaceholder')" />
                        <p v-if="form.errors.whatsapp" class="mt-2 text-xs text-red-400">{{ form.errors.whatsapp }}</p>
                    </div>
                    <div>
                        <label class="label" for="location">{{ t('common.location') }}</label>
                        <input id="location" v-model="form.location" class="field" type="text" :placeholder="t('admin.profile.locationPlaceholder')" />
                    </div>
                </div>
            </AdminCard>

            <AdminCard :title="t('admin.profile.aboutYou')">
                <div class="space-y-5">
                    <div>
                        <label class="label" for="name">{{ t('admin.profile.nameRequired') }}</label>
                        <input id="name" v-model="form.name" class="field" type="text" placeholder="Muhammad Iqbal Fadholi" />
                        <p v-if="form.errors.name" class="mt-2 text-xs text-red-400">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="label" for="profession">{{ t('admin.profile.professionRequired') }}</label>
                        <input id="profession" v-model="form.profession" class="field" type="text" :placeholder="t('admin.profile.professionPlaceholder')" />
                        <p v-if="form.errors.profession" class="mt-2 text-xs text-red-400">{{ form.errors.profession }}</p>
                    </div>
                    <div>
                        <label class="label" for="biography_id">{{ t('admin.profile.biographyId') }}</label>
                        <textarea id="biography_id" v-model="form.biography_id" class="field min-h-72 resize-y" :placeholder="t('admin.profile.biographyPlaceholder')" />
                        <p class="mt-2 text-[11px] text-muted">{{ t('admin.profile.biographyHint') }}</p>
                        <p v-if="form.errors.biography_id" class="mt-2 text-xs text-red-400">{{ form.errors.biography_id }}</p>
                    </div>
                    <div>
                        <label class="label" for="biography_en">{{ t('admin.profile.biographyEn') }}</label>
                        <textarea id="biography_en" v-model="form.biography_en" class="field min-h-72 resize-y" :placeholder="t('admin.profile.biographyPlaceholder')" />
                        <p class="mt-2 text-[11px] text-muted">{{ t('admin.profile.biographyHint') }}</p>
                        <p v-if="form.errors.biography_en" class="mt-2 text-xs text-red-400">{{ form.errors.biography_en }}</p>
                    </div>
                    <button class="btn btn-primary" type="submit" :disabled="form.processing">
                        <Save :size="15" /> {{ form.processing ? t('common.saving') : t('admin.profile.saveProfile') }}
                    </button>
                </div>
            </AdminCard>
        </form>
    </div>
</template>
