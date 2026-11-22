import '../css/app.css';
import './bootstrap';

import { createApp, Fragment, h } from 'vue';
import { createInertiaApp, Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { resolveTitle } from './utils/seo';
import PageLoader from './Components/PageLoader.vue';
import { hideLoader, setProgress, showLoader } from './composables/usePageLoader';

// Makes Laravel's named routes available to all Vue components.
window.route = route;
import PublicLayout from './Layouts/PublicLayout.vue';
import AdminLayout from './Layouts/AdminLayout.vue';

// Full-screen loader for the first paint and every Inertia visit (including ID/EN switches).
router.on('start', () => showLoader());
router.on('progress', (event) => setProgress(event.detail?.progress?.percentage ?? 25));
router.on('finish', () => hideLoader());
router.on('error', () => hideLoader());

let initialRevealed = false;
function revealInitialPage() {
    if (initialRevealed) return;
    initialRevealed = true;
    requestAnimationFrame(() => hideLoader(520));
}

if (document.readyState === 'complete') {
    revealInitialPage();
} else {
    window.addEventListener('load', revealInitialPage, { once: true });
}
// Safety net: never leave a blank screen if an asset hangs.
setTimeout(revealInitialPage, 2200);

createInertiaApp({
    resolve: async (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue');
        const loader = pages[`./Pages/${name}.vue`];

        if (! loader) {
            throw new Error(`Page not found: ${name}`);
        }

        const page = await loader();

        if (! page.default.layout) {
            page.default.layout = name === 'Admin/Login'
                ? null
                : name.startsWith('Admin/') ? AdminLayout : PublicLayout;
        }

        return page.default;
    },
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(Fragment, [h(PageLoader), h(App, props)]),
        })
            .use(plugin)
            .component('Head', Head)
            .component('Link', Link)
            .mixin({
                computed: {
                    $pageTitle() {
                        return resolveTitle(this.$page?.props);
                    },
                },
            })
            .mount(el);
    },
    progress: false,
});
