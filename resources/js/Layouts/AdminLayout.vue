<script setup>
import { route } from 'ziggy-js';
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    ArrowLeft, CheckCircle2, LayoutDashboard, LogOut, Mail, Menu,
    Palette, Settings2, Sparkles, UserRound, Wrench, X, XCircle,
} from 'lucide-vue-next';
import BrandLogo from '../Components/BrandLogo.vue';
import LanguageGlobe from '../Components/Public/LanguageGlobe.vue';
import { useI18n } from '../composables/useI18n';

const page = usePage();
const { t } = useI18n();
const open = ref(false);

const navigation = [
    { label: t('admin.nav.overview'), route: 'admin.dashboard', icon: LayoutDashboard },
    { label: t('admin.nav.projects'), route: 'admin.projects.index', icon: Palette },
    { label: t('admin.nav.categories'), route: 'admin.categories.index', icon: Wrench },
    { label: t('admin.nav.skills'), route: 'admin.skills.index', icon: Sparkles },
    { label: t('admin.nav.profile'), route: 'admin.profile.edit', icon: UserRound },
    { label: t('admin.nav.socialLinks'), route: 'admin.social-links.index', icon: Settings2 },
    { label: t('admin.nav.messages'), route: 'admin.messages.index', icon: Mail },
    { label: t('admin.nav.settings'), route: 'admin.settings.index', icon: Settings2 },
];

const user = computed(() => page.props.auth?.user);
const flash = computed(() => page.props.flash || {});

function isActive(item) {
    const target = route(item.route).replace(/\/$/, '');
    const current = page.url.split('?')[0].replace(/\/$/, '') || '/';

    if (item.route === 'admin.dashboard') return current === target;

    return current === target || current.startsWith(`${target}/`);
}

function logout() {
    router.post(route('admin.logout'));
}
</script>

<template>
    <div class="min-h-screen bg-ink text-white lg:grid lg:grid-cols-[16rem_1fr]">
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-[16rem] flex-col border-r border-line bg-panel transition-transform duration-300 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
            :class="open ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-20 items-center justify-between border-b border-line px-6">
                <Link :href="route('admin.dashboard')" class="transition-opacity hover:opacity-80">
                    <BrandLogo admin />
                </Link>
                <button class="text-muted lg:hidden" :aria-label="t('common.closeMenu')" @click="open = false">
                    <X :size="20" />
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-6">
                <Link
                    v-for="item in navigation"
                    :key="item.route"
                    :href="route(item.route)"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] transition-colors"
                    :class="isActive(item)
                        ? 'bg-accent/10 text-accent'
                        : 'text-muted hover:bg-white/5 hover:text-white'"
                    @click="open = false"
                >
                    <component :is="item.icon" :size="17" :stroke-width="1.7" />
                    {{ item.label }}
                </Link>
            </nav>

            <div class="border-t border-line p-4">
                <Link :href="route('home')" class="mb-3 flex items-center gap-2 px-2 text-xs text-muted hover:text-accent">
                    <ArrowLeft :size="15" /> {{ t('common.viewWebsite') }}
                </Link>
                <button
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-[13px] text-muted hover:bg-white/5 hover:text-white"
                    @click="logout"
                >
                    <LogOut :size="17" :stroke-width="1.7" /> {{ t('common.logOut') }}
                </button>
            </div>
        </aside>

        <div v-if="open" class="fixed inset-0 z-40 bg-black/70 lg:hidden" @click="open = false" />

        <div class="min-w-0">
            <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-line bg-ink/85 px-5 backdrop-blur-xl md:px-8">
                <div class="flex items-center gap-3">
                    <button class="text-muted lg:hidden" :aria-label="t('common.openMenu')" @click="open = true">
                        <Menu :size="22" />
                    </button>
                    <div>
                        <p class="font-display text-base font-semibold">{{ page.props.title || t('common.dashboard') }}</p>
                        <p class="text-[11px] text-muted">{{ user?.email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <LanguageGlobe />
                    <div class="flex h-9 w-9 items-center justify-center rounded-full border border-line bg-panel font-display text-xs text-accent">
                        {{ user?.name?.charAt(0)?.toUpperCase() || 'A' }}
                    </div>
                </div>
            </header>

            <div class="pointer-events-none fixed right-5 top-24 z-50 space-y-2">
                <Transition name="toast">
                    <div
                        v-if="flash.success"
                        class="pointer-events-auto flex items-center gap-2 rounded-xl border border-accent/30 bg-panel px-4 py-3 text-xs shadow-2xl"
                    >
                        <CheckCircle2 :size="16" class="text-accent" /> {{ flash.success }}
                    </div>
                </Transition>
                <Transition name="toast">
                    <div
                        v-if="flash.error"
                        class="pointer-events-auto flex items-center gap-2 rounded-xl border border-red-500/30 bg-panel px-4 py-3 text-xs shadow-2xl"
                    >
                        <XCircle :size="16" class="text-red-400" /> {{ flash.error }}
                    </div>
                </Transition>
            </div>

            <main class="p-5 md:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.toast-enter-active, .toast-leave-active { transition: opacity .3s ease, transform .3s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateX(20px); }
</style>
