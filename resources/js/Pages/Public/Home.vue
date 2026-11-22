<script setup>
import { route } from 'ziggy-js';
import { computed, defineAsyncComponent, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import gsap from 'gsap';
import { ArrowDown, ArrowRight, ArrowUpRight, Check, Mail, MapPin, MessageCircle, Send } from 'lucide-vue-next';

const HeroSculpture = defineAsyncComponent(() => import('../../Components/Public/HeroSculpture.vue'));
import SectionHeading from '../../Components/Public/SectionHeading.vue';
import ProfileIdCard from '../../Components/Public/ProfileIdCard.vue';
import ProjectCard from '../../Components/Public/ProjectCard.vue';
import SkillLogo from '../../Components/Public/SkillLogo.vue';
import { resolveDescription } from '../../utils/seo';
import { prefersReducedMotion } from '../../composables/useMotion';
import { useI18n } from '../../composables/useI18n';

const props = defineProps({
    profile: { type: Object, default: null },
    categories: { type: Array, default: () => [] },
    skills: { type: Array, default: () => [] },
    projects: { type: Array, default: () => [] },
    title: { type: String, default: null },
    description: { type: String, default: null },
});

const page = usePage();
const { t, locale } = useI18n();
const settings = computed(() => page.props.site?.settings || {});
const socials = computed(() => page.props.site?.socialLinks || []);
const setting = (key, fallback = '') => settings.value[`${key}_${locale.value}`] || settings.value[key] || fallback;
const metaDescription = computed(() => resolveDescription(props));
const contactTitle = computed(() => setting('contact_title', t('home.contactTitle')));

const headlineLines = computed(() => {
    const words = setting('hero_headline', t('home.heroHeadline')).split(' ').filter(Boolean);
    const lines = [];
    for (let i = 0; i < words.length; i += 2) lines.push(words.slice(i, i + 2).join(' '));
    return lines;
});

function splitEmphasis(text) {
    return text
        .split(/(\*\*[^*]+\*\*)/g)
        .filter(Boolean)
        .map((chunk) => (chunk.startsWith('**') && chunk.endsWith('**')
            ? { text: chunk.slice(2, -2), bold: true }
            : { text: chunk, bold: false }));
}

const heroStack = [
    { label: 'HTML', icon: 'html5' },
    { label: 'CSS', icon: 'css' },
    { label: 'JavaScript', icon: 'javascript' },
    { label: 'Vue.js', icon: 'vuejs' },
    { label: 'Laravel', icon: 'laravel' },
    { label: 'PHP', icon: 'php' },
];

const frontendSkills = ['HTML', 'CSS', 'JavaScript', 'Vue.js'];
const skillKind = (name) => (frontendSkills.includes(name) ? 'frontend' : 'backend');

const biography = computed(() => {
    const raw = props.profile?.[`biography_${locale.value}`] || props.profile?.biography || '';
    const blocks = [];
    let paragraph = [];

    const flush = () => {
        if (!paragraph.length) return;
        blocks.push({ heading: false, parts: splitEmphasis(paragraph.join(' ').trim()) });
        paragraph = [];
    };

    raw.split(/\r?\n/).forEach((line) => {
        const trimmed = line.trim();
        if (!trimmed) {
            flush();
            return;
        }
        if (trimmed.startsWith('## ')) {
            flush();
            blocks.push({ heading: true, parts: splitEmphasis(trimmed.slice(3).trim()) });
            return;
        }
        paragraph.push(trimmed);
    });
    flush();

    return blocks;
});

const whatsappNumber = computed(() => {
    const value = props.profile?.whatsapp || '';
    const digits = value.replace(/\D/g, '');
    if (!digits) return value;

    return digits.startsWith('62') ? `0${digits.slice(2)}` : digits;
});

const activeCategory = ref('all');
const worksGrid = ref(null);
const visibleProjects = computed(() => activeCategory.value === 'all'
    ? props.projects
    : props.projects.filter((project) => project.category_id === activeCategory.value));

watch(activeCategory, async () => {
    if (prefersReducedMotion() || !worksGrid.value) return;
    await nextTick();
    gsap.from(Array.from(worksGrid.value.children), {
        y: 24, autoAlpha: 0, duration: 0.55, stagger: 0.05, ease: 'power2.out',
    });
});

const form = useForm({ name: '', email: '', subject: '', message: '', website: '' });
const sent = ref(false);

function submit() {
    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            sent.value = true;
            window.setTimeout(() => { sent.value = false; }, 5000);
        },
    });
}

const hero = ref(null);
let heroContext = null;

onMounted(() => {
    if (prefersReducedMotion() || !hero.value) return;
    heroContext = gsap.context(() => {
        gsap.timeline({ defaults: { ease: 'power3.out' } })
            .from('[data-hero-line]', { yPercent: 115, duration: 1.05, stagger: 0.1 }, 0.15)
            .from('[data-hero-fade]', { y: 20, autoAlpha: 0, duration: 0.8, stagger: 0.09 }, 0.5)
            .from('[data-hero-canvas]', { autoAlpha: 0, scale: 0.92, duration: 1.6, ease: 'power2.out' }, 0.1);
    }, hero);
});

onBeforeUnmount(() => heroContext?.revert());
</script>

<template>
    <Head>
        <title>{{ $pageTitle }}</title>
        <meta name="description" :content="metaDescription" />
        <meta property="og:title" :content="$pageTitle" />
        <meta property="og:description" :content="metaDescription" />
        <meta property="og:type" content="website" />
    </Head>

    <!-- HERO -->
    <section id="home" ref="hero" class="grain relative flex min-h-screen items-center overflow-hidden pt-24">
        <div class="pointer-events-none absolute right-[8%] top-[12%] h-[36rem] w-[36rem] rounded-full bg-accent/[0.045] blur-[140px]" />
        <div class="pointer-events-none absolute -left-40 bottom-0 h-96 w-96 rounded-full bg-white/[0.03] blur-[120px]" />

        <div class="shell relative grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="relative z-10">
                <div data-hero-fade class="eyebrow">{{ setting('hero_label', t('home.heroLabel')) }}</div>

                <h1 class="mt-8 font-display text-[clamp(2.9rem,7.2vw,7.2rem)] font-semibold leading-[0.92] tracking-[-0.06em]">
                    <span v-for="(line, index) in headlineLines" :key="line" class="block overflow-hidden pb-1">
                        <span
                            data-hero-line
                            class="block"
                            :class="index === headlineLines.length - 1 ? 'text-accent' : ''"
                        >{{ line }}</span>
                    </span>
                </h1>

                <p data-hero-fade class="mt-8 max-w-lg text-sm leading-relaxed text-muted md:text-base">
                    {{ setting('hero_description', t('home.heroDescription')) }}
                </p>

                <div data-hero-fade class="mt-6 flex flex-wrap gap-2">
                    <span
                        v-for="tech in heroStack"
                        :key="tech.label"
                        class="inline-flex items-center gap-2 rounded-full border border-line bg-panel/60 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-white/70 backdrop-blur"
                    >
                        <SkillLogo :icon="tech.icon" class="h-3.5 w-3.5" />
                        {{ tech.label }}
                    </span>
                </div>

                <div data-hero-fade class="mt-10 flex flex-wrap items-center gap-4">
                    <a href="#works" class="btn btn-primary">
                        {{ t('home.exploreWork') }} <ArrowRight :size="15" />
                    </a>
                    <a href="#contact" class="btn btn-ghost">
                        {{ t('nav.letsTalk') }} <ArrowUpRight :size="15" />
                    </a>
                </div>

                <div data-hero-fade class="mt-12 flex items-center gap-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-muted">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent opacity-60" />
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-accent" />
                    </span>
                    {{ setting('hero_status', t('home.heroStatus')) }}
                </div>
            </div>

            <div data-hero-canvas class="relative h-[24rem] sm:h-[32rem] lg:h-[42rem]">
                <div class="absolute inset-0 rounded-full bg-gradient-to-br from-white/[0.04] via-transparent to-accent/[0.05] blur-3xl" />
                <HeroSculpture :photo="profile?.photo_url" />
                <div class="pointer-events-none absolute bottom-6 right-0 hidden font-display text-[10px] uppercase tracking-[0.3em] text-muted lg:block">
                    {{ t('home.playlist') }}
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-3 text-[9px] uppercase tracking-[0.3em] text-muted md:flex">
            {{ t('home.scroll') }} <ArrowDown :size="14" class="animate-bounce text-accent" />
        </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="border-t border-line py-24 md:py-36">
        <div class="shell">
            <SectionHeading :eyebrow="t('home.aboutEyebrow')" :number="'02'" :title="setting('about_title', t('home.aboutTitle'))" />

            <div class="mt-16 grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-20">
                <ProfileIdCard :profile="profile" :projects-count="projects.length" />

                <div>
                    <p data-reveal class="font-display text-2xl leading-snug text-white md:text-3xl">
                        {{ profile?.profession || t('home.profession') }}
                    </p>
                    <div class="mt-8 space-y-6 text-sm leading-[1.9] text-muted md:text-base">
                        <template v-for="(block, index) in biography" :key="index">
                            <h3 v-if="block.heading" data-reveal class="font-display text-xl font-semibold text-white">{{ block.parts.map((part) => part.text).join('') }}</h3>
                            <p v-else data-reveal><template v-for="(part, partIndex) in block.parts" :key="partIndex"><strong v-if="part.bold" class="font-semibold text-white">{{ part.text }}</strong><template v-else>{{ part.text }}</template></template></p>
                        </template>
                        <p v-if="!biography.length" data-reveal>{{ t('home.bioPlaceholder') }}</p>
                    </div>

                    <div data-stagger class="mt-10 grid grid-cols-3 gap-px overflow-hidden rounded-xl border border-line bg-line">
                        <div class="bg-panel p-5">
                            <p class="font-display text-2xl font-semibold text-white">{{ skills.length }}</p>
                            <p class="mt-2 text-[10px] uppercase tracking-wider text-muted">{{ t('home.disciplines') }}</p>
                        </div>
                        <div class="bg-panel p-5">
                            <p class="font-display text-2xl font-semibold text-white">{{ projects.length }}</p>
                            <p class="mt-2 text-[10px] uppercase tracking-wider text-muted">{{ t('home.projects') }}</p>
                        </div>
                        <div class="bg-panel p-5">
                            <p class="font-display text-2xl font-semibold text-white">{{ categories.length }}</p>
                            <p class="mt-2 text-[10px] uppercase tracking-wider text-muted">{{ t('home.categories') }}</p>
                        </div>
                    </div>

                    <div v-if="profile?.location" data-reveal class="mt-8 flex items-center gap-2 text-xs text-muted">
                        <MapPin :size="15" class="text-accent" /> {{ profile.location }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SKILLS -->
    <section id="skills" class="border-t border-line py-24 md:py-36">
        <div class="shell">
            <SectionHeading :eyebrow="t('home.capabilitiesEyebrow')" number="03" :title="t('home.capabilitiesTitle')" :description="t('home.capabilitiesDescription')" />

            <div data-stagger class="mt-16 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="skill in skills"
                    :key="skill.id"
                    class="group relative flex min-h-64 flex-col justify-between overflow-hidden rounded-2xl border border-line bg-panel p-7 transition-all duration-500 hover:-translate-y-1 hover:border-accent/40 hover:shadow-[0_24px_60px_-35px_rgba(181,255,60,0.55)]"
                >
                    <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-accent/[0.06] blur-2xl transition-colors duration-500 group-hover:bg-accent/[0.14]" />

                    <div class="relative flex items-start justify-between gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl border border-line bg-ink/70 p-2.5 transition-all duration-500 group-hover:scale-110 group-hover:border-accent/40">
                            <SkillLogo :icon="skill.icon" class="h-full w-full" />
                        </span>
                        <span class="rounded-full border border-line bg-ink/60 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[0.14em] text-muted">
                            {{ t(`home.${skillKind(skill.name)}`) }}
                        </span>
                    </div>

                    <div class="relative mt-10">
                        <div class="flex items-end justify-between gap-4">
                            <h3 class="font-display text-2xl font-medium leading-tight tracking-tight">{{ skill.name }}</h3>
                            <p class="font-display text-2xl font-semibold text-accent">
                                {{ skill.percentage }}<span class="ml-0.5 text-xs text-muted">%</span>
                            </p>
                        </div>
                        <div class="mt-5 h-1 w-full overflow-hidden rounded-full bg-white/5">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-accent/50 to-accent transition-all duration-700 group-hover:from-accent"
                                :style="{ width: `${skill.percentage}%` }"
                            />
                        </div>
                    </div>
                </article>
                <div v-if="!skills.length" class="rounded-2xl border border-line bg-panel p-8 text-sm text-muted">{{ t('home.noSkills') }}</div>
            </div>
        </div>
    </section>

    <!-- WORKS -->
    <section id="works" class="border-t border-line py-24 md:py-36">
        <div class="shell">
            <SectionHeading :eyebrow="t('home.worksEyebrow')" number="04" :title="t('home.worksTitle')" :description="t('home.worksDescription')" />

            <div data-reveal class="mt-12 flex flex-wrap gap-2">
                <button
                    class="rounded-full border px-4 py-2 text-[10px] font-semibold uppercase tracking-[0.14em] transition-all"
                    :class="activeCategory === 'all' ? 'border-accent bg-accent text-ink' : 'border-line text-muted hover:border-accent hover:text-accent'"
                    @click="activeCategory = 'all'"
                >
                    {{ t('home.allWork') }}
                </button>
                <button
                    v-for="category in categories"
                    :key="category.id"
                    class="rounded-full border px-4 py-2 text-[10px] font-semibold uppercase tracking-[0.14em] transition-all"
                    :class="activeCategory === category.id ? 'border-accent bg-accent text-ink' : 'border-line text-muted hover:border-accent hover:text-accent'"
                    @click="activeCategory = category.id"
                >
                    {{ category.name }}
                </button>
            </div>

            <div ref="worksGrid" class="mt-10 grid grid-cols-1 gap-5 md:grid-cols-12">
                <ProjectCard v-for="(project, index) in visibleProjects" :key="project.id" :project="project" :index="index" />
            </div>
            <p v-if="!visibleProjects.length" class="mt-12 text-sm text-muted">{{ t('home.noProjects') }}</p>
        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="relative overflow-hidden border-t border-line py-24 md:py-36">
        <div class="pointer-events-none absolute left-1/2 top-0 h-96 w-[46rem] -translate-x-1/2 rounded-full bg-accent/[0.05] blur-[140px]" />
        <div class="shell relative">
            <SectionHeading :eyebrow="t('home.contactEyebrow')" number="05" :title="contactTitle" :description="t('home.contactDescription')" />

            <div class="mt-16 grid gap-14 lg:grid-cols-[0.8fr_1.2fr] lg:gap-24">
                <div>
                    <p data-reveal class="font-display text-2xl">{{ t('home.contactLead') }}</p>
                    <p data-reveal class="mt-4 max-w-sm text-sm leading-relaxed text-muted">{{ t('home.contactAvailability') }}</p>

                    <div data-stagger class="mt-10 space-y-5">
                        <a v-if="profile?.email" :href="`mailto:${profile.email}`" class="flex items-center gap-4 text-sm text-white transition-colors hover:text-accent">
                            <Mail :size="18" class="text-accent" /> {{ profile.email }}
                        </a>
                        <a v-if="profile?.whatsapp" :href="profile.whatsapp" target="_blank" rel="noopener noreferrer" class="flex items-center gap-4 text-sm text-white transition-colors hover:text-accent">
                            <MessageCircle :size="18" class="text-accent" /> {{ t('home.whatsapp') }} Â· {{ whatsappNumber }}
                        </a>
                        <a
                            v-for="social in socials"
                            :key="social.id"
                            :href="social.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center gap-4 text-sm text-white transition-colors hover:text-accent"
                        >
                            <ArrowUpRight :size="18" class="text-accent" /> {{ social.platform }}
                        </a>
                    </div>
                </div>

                <form data-reveal class="grid gap-5" @submit.prevent="submit">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="label" for="contact-name">{{ t('home.formName') }}</label>
                            <input id="contact-name" v-model="form.name" class="field" type="text" :placeholder="t('home.formNamePlaceholder')" autocomplete="name" />
                            <p v-if="form.errors.name" class="mt-2 text-xs text-red-400">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="label" for="contact-email">{{ t('common.email') }}</label>
                            <input id="contact-email" v-model="form.email" class="field" type="email" :placeholder="t('home.formEmailPlaceholder')" autocomplete="email" />
                            <p v-if="form.errors.email" class="mt-2 text-xs text-red-400">{{ form.errors.email }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="label" for="contact-subject">{{ t('home.formSubject') }}</label>
                        <input id="contact-subject" v-model="form.subject" class="field" type="text" :placeholder="t('home.formSubjectPlaceholder')" />
                        <p v-if="form.errors.subject" class="mt-2 text-xs text-red-400">{{ form.errors.subject }}</p>
                    </div>
                    <div>
                        <label class="label" for="contact-message">{{ t('home.formMessage') }}</label>
                        <textarea id="contact-message" v-model="form.message" class="field min-h-40 resize-y" :placeholder="t('home.formMessagePlaceholder')" />
                        <p v-if="form.errors.message" class="mt-2 text-xs text-red-400">{{ form.errors.message }}</p>
                    </div>
                    <input v-model="form.website" class="hidden" type="text" tabindex="-1" autocomplete="off" aria-hidden="true" />
                    <p v-if="form.errors.website" class="text-xs text-red-400">{{ form.errors.website }}</p>

                    <div class="flex flex-wrap items-center gap-5">
                        <button class="btn btn-primary" type="submit" :disabled="form.processing">
                            {{ form.processing ? t('home.sending') : t('home.sendMessage') }}
                            <Send :size="15" />
                        </button>
                        <span v-if="sent" class="flex items-center gap-2 text-xs text-accent"><Check :size="15" /> {{ t('home.messageDelivered') }}</span>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>
