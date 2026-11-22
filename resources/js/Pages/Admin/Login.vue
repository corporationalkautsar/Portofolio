<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ArrowRight, LockKeyhole, Mail } from 'lucide-vue-next';
import BrandLogo from '../../Components/BrandLogo.vue';
import LanguageGlobe from '../../Components/Public/LanguageGlobe.vue';
import { useI18n } from '../../composables/useI18n';

const { t } = useI18n();

const form = useForm({ email: '', password: '', remember: false });

function submit() {
    form.post(route('admin.login.store'), {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head><title>{{ t('admin.login.title') }}</title></Head>

    <div class="grain relative flex min-h-screen items-center justify-center overflow-hidden bg-ink px-5 py-12">
        <div class="pointer-events-none absolute left-1/2 top-1/3 h-96 w-96 -translate-x-1/2 rounded-full bg-accent/[0.07] blur-[120px]" />

        <div class="relative w-full max-w-md">
            <div class="mb-6 flex items-center justify-end"><LanguageGlobe /></div>

            <a :href="route('home')" class="mb-4 inline-flex transition-opacity hover:opacity-80">
                <BrandLogo admin />
            </a>

            <div class="rounded-2xl border border-line bg-panel p-8 shadow-2xl md:p-10">
                <p class="eyebrow">{{ t('admin.login.privateArea') }}</p>
                <h1 class="mt-5 font-display text-3xl font-semibold">{{ t('admin.login.welcomeBack') }}</h1>
                <p class="mt-3 text-sm text-muted">{{ t('admin.login.description') }}</p>

                <form class="mt-9 space-y-5" @submit.prevent="submit">
                    <div>
                        <label class="label" for="email">{{ t('common.email') }}</label>
                        <div class="relative">
                            <Mail :size="16" class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted" />
                            <input id="email" v-model="form.email" class="field !pl-11" type="email" autocomplete="username" placeholder="admin@example.com" />
                        </div>
                        <p v-if="form.errors.email" class="mt-2 text-xs text-red-400">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label class="label" for="password">{{ t('common.password') }}</label>
                        <div class="relative">
                            <LockKeyhole :size="16" class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted" />
                            <input id="password" v-model="form.password" class="field !pl-11" type="password" autocomplete="current-password" placeholder="••••••••" />
                        </div>
                        <p v-if="form.errors.password" class="mt-2 text-xs text-red-400">{{ form.errors.password }}</p>
                    </div>

                    <label class="flex items-center gap-3 text-xs text-muted">
                        <input v-model="form.remember" type="checkbox" class="h-4 w-4 rounded border-line accent-accent" />
                        {{ t('admin.login.rememberMe') }}
                    </label>

                    <button class="btn btn-primary w-full" type="submit" :disabled="form.processing">
                        {{ form.processing ? t('admin.login.signingIn') : t('admin.login.signIn') }} <ArrowRight :size="15" />
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-[11px] text-muted">{{ t('admin.login.noRegistration') }}</p>
        </div>
    </div>
</template>
