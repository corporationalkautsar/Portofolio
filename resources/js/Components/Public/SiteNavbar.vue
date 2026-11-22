<script setup>
import { route } from 'ziggy-js';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowUpRight, Menu, X } from 'lucide-vue-next';
import BrandLogo from '../BrandLogo.vue';
import LanguageGlobe from './LanguageGlobe.vue';
import { useI18n } from '../../composables/useI18n';

const { t } = useI18n();

const links = [
    { label: t('nav.home'), id: 'home' },
    { label: t('nav.about'), id: 'about' },
    { label: t('nav.works'), id: 'works' },
    { label: t('nav.skills'), id: 'skills' },
    { label: t('nav.contact'), id: 'contact' },
];

const page = usePage();
const scrolled = ref(false);
const open = ref(false);
const active = ref('home');
const profile = computed(() => page.props.site?.profile);

const isHome = computed(() => page.url === '/' || page.url.startsWith('/#'));
const hrefFor = (id) => (isHome.value ? `#${id}` : `${route('home')}#${id}`);

const onScroll = () => {
    scrolled.value = window.scrollY > 24;
};

let observer;

onMounted(() => {
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) active.value = entry.target.id;
        });
    }, { rootMargin: '-45% 0px -50% 0px' });

    links.forEach(({ id }) => {
        const section = document.getElementById(id);
        if (section) observer.observe(section);
    });
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll);
    observer?.disconnect();
});
</script>

<template>
    <header
        class="fixed inset-x-0 top-0 z-50 transition-all duration-500"
        :class="scrolled ? 'border-b border-line bg-ink/85 backdrop-blur-xl' : 'border-b border-transparent'"
    >
        <div class="shell flex h-20 items-center justify-between">
            <Link :href="route('home')" class="group flex items-center gap-3" @click="open = false">
                <BrandLogo />
                <span class="hidden text-[10px] uppercase tracking-[0.2em] text-muted lg:inline">
                    {{ profile?.name || 'Creative Portfolio' }}
                </span>
            </Link>

            <nav class="hidden items-center gap-9 md:flex">
                <a
                    v-for="link in links"
                    :key="link.id"
                    :href="hrefFor(link.id)"
                    class="group relative py-2 text-[11px] font-semibold uppercase tracking-[0.18em] transition-colors"
                    :class="active === link.id ? 'text-white' : 'text-muted hover:text-white'"
                >
                    {{ link.label }}
                    <span
                        class="absolute bottom-0 left-0 h-px bg-accent transition-all duration-300"
                        :class="active === link.id ? 'w-full' : 'w-0 group-hover:w-full'"
                    />
                </a>
                <a :href="hrefFor('contact')" class="btn btn-ghost !min-h-10 !px-5">
                    {{ t('nav.letsTalk') }} <ArrowUpRight :size="14" />
                </a>
                <LanguageGlobe />
            </nav>

            <div class="flex items-center gap-3 md:hidden">
                <button class="text-white" :aria-label="open ? t('common.closeMenu') : t('common.openMenu')" @click="open = !open">
                    <X v-if="open" :size="24" />
                    <Menu v-else :size="24" />
                </button>
                <LanguageGlobe />
            </div>
        </div>

        <Transition name="mobile-nav">
            <nav v-if="open" class="border-t border-line bg-ink/98 px-6 pb-8 pt-4 backdrop-blur-xl md:hidden">
                <a
                    v-for="(link, index) in links"
                    :key="link.id"
                    :href="hrefFor(link.id)"
                    class="flex items-center justify-between border-b border-line py-4 font-display text-2xl"
                    @click="open = false"
                >
                    {{ link.label }}
                    <span class="text-xs text-accent">0{{ index + 1 }}</span>
                </a>
            </nav>
        </Transition>
    </header>
</template>

<style scoped>
.mobile-nav-enter-active, .mobile-nav-leave-active { transition: opacity .25s ease, transform .25s ease; }
.mobile-nav-enter-from, .mobile-nav-leave-to { opacity: 0; transform: translateY(-10px); }
</style>
