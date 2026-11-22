import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

/**
 * Small translation helper backed by Laravel's lang/{locale}/ui.php files.
 * Strings are shared globally by HandleInertiaRequests, so switching locale
 * reloads the page props and every translated label updates together.
 */
export function useI18n() {
    const page = usePage();

    // `usePage().props` can be briefly undefined while Inertia boots the initial page.
    const locale = computed(() => page.props?.locale || 'en');
    const locales = computed(() => page.props?.locales || ['en', 'id']);
    const messages = computed(() => page.props?.translations || {});

    function t(key, replacements = {}) {
        let value = key.split('.').reduce(
            (current, part) => (current && current[part] !== undefined ? current[part] : undefined),
            messages.value,
        );

        if (typeof value !== 'string') return key;

        for (const [name, replacement] of Object.entries(replacements)) {
            value = value.replace(new RegExp(`:${name}`, 'g'), String(replacement));
        }

        return value;
    }

    function setLocale(nextLocale) {
        if (!locales.value.includes(nextLocale) || nextLocale === locale.value) return;

        router.post(window.route('locale.update'), { locale: nextLocale }, {
            preserveScroll: true,
            preserveState: false,
        });
    }

    return { t, locale, locales, setLocale };
}
