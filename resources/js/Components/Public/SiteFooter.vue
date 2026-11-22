<script setup>
import { computed } from 'vue';
import { ArrowUp, ArrowUpRight } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';
import { useI18n } from '../../composables/useI18n';
import BrandLogo from '../BrandLogo.vue';

const page = usePage();
const { t, locale } = useI18n();
const profile = computed(() => page.props.site?.profile);
const socials = computed(() => page.props.site?.socialLinks || []);
const credits = computed(() => page.props.site?.settings?.[`footer_credits_${locale.value}`]
    || page.props.site?.settings?.footer_credits);
const footerLinks = computed(() => ['about', 'works', 'skills', 'contact'].map((id) => ({
    id,
    label: t(`nav.${id}`),
})));
const year = new Date().getFullYear();

function toTop() {
    window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
}
</script>

<template>
    <footer class="relative border-t border-line bg-panel">
        <div class="shell py-16 md:py-24">
            <div class="grid gap-12 md:grid-cols-[1.4fr_1fr_1fr]">
                <div>
                    <BrandLogo />
                    <p class="mt-6 font-display text-3xl font-bold tracking-[-0.04em]">
                        {{ profile?.name || 'Muhammad Iqbal Fadholi' }}<span class="text-accent">.</span>
                    </p>
                    <p class="mt-4 max-w-sm text-sm leading-relaxed text-muted">
                        {{ profile?.profession || t('home.profession') }}
                    </p>
                    <a
                        v-if="profile?.email"
                        :href="`mailto:${profile.email}`"
                        class="mt-6 inline-flex items-center gap-2 text-sm text-white transition-colors hover:text-accent"
                    >
                        {{ profile.email }} <ArrowUpRight :size="15" />
                    </a>
                </div>

                <div>
                    <p class="label">{{ t('footer.explore') }}</p>
                    <div class="mt-5 flex flex-col items-start gap-3 text-sm text-muted">
                        <a v-for="item in footerLinks" :key="item.id" :href="`#${item.id}`" class="capitalize transition-colors hover:text-accent">
                            {{ item.label }}
                        </a>
                    </div>
                </div>

                <div>
                    <p class="label">{{ t('footer.elsewhere') }}</p>
                    <div class="mt-5 flex flex-col items-start gap-3">
                        <a
                            v-for="social in socials"
                            :key="social.id"
                            :href="social.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center gap-2 text-sm text-muted transition-colors hover:text-accent"
                        >
                            {{ social.platform }} <ArrowUpRight :size="14" />
                        </a>
                        <p v-if="!socials.length" class="text-sm text-muted">{{ t('footer.linksComingSoon') }}</p>
                    </div>
                </div>
            </div>

            <div class="hairline my-12" />

            <div class="flex flex-col gap-5 text-[11px] uppercase tracking-[0.14em] text-muted md:flex-row md:items-center md:justify-between">
                <p>© {{ year }} {{ profile?.name || t('footer.portfolioFallback') }}. {{ t('footer.allRightsReserved') }}</p>
                <p class="normal-case tracking-normal">{{ credits }}</p>
                <button class="flex items-center gap-2 transition-colors hover:text-accent" @click="toTop">
                    {{ t('footer.backToTop') }} <ArrowUp :size="14" />
                </button>
            </div>
        </div>
    </footer>
</template>
