export function prefersReducedMotion() {
    return typeof window !== 'undefined'
        && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * Fades elements in when they enter the viewport.
 *
 * Uses IntersectionObserver instead of ScrollTrigger so sections are never left
 * invisible when the page is opened directly at an anchor (#skills, #works, ...)
 * or when a browser restores the previous scroll position.
 */
export function revealTimeline(gsap, root) {
    if (!root || prefersReducedMotion()) return null;

    const cleanups = [];

    function observe(observeTarget, hiddenTargets, animate) {
        hiddenTargets.forEach((element) => gsap.set(element, { autoAlpha: 0 }));

        if (typeof IntersectionObserver === 'undefined') {
            animate();
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                observer.disconnect();
                animate();
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

        observer.observe(observeTarget);

        cleanups.push(() => {
            observer.disconnect();
            hiddenTargets.forEach((element) => {
                gsap.set(element, { clearProps: 'opacity,visibility,transform' });
            });
        });
    }

    root.querySelectorAll('[data-reveal]').forEach((element) => {
        observe(element, [element], () => gsap.fromTo(
            element,
            { y: 38, autoAlpha: 0 },
            { y: 0, autoAlpha: 1, duration: 0.9, ease: 'power3.out' },
        ));
    });

    root.querySelectorAll('[data-stagger]').forEach((group) => {
        const children = Array.from(group.children);
        if (!children.length) return;

        observe(group, children, () => gsap.fromTo(
            children,
            { y: 30, autoAlpha: 0 },
            { y: 0, autoAlpha: 1, duration: 0.75, stagger: 0.075, ease: 'power3.out' },
        ));
    });

    root.querySelectorAll('[data-line]').forEach((line) => {
        observe(line, [line], () => gsap.fromTo(
            line,
            { scaleX: 0, transformOrigin: 'left center' },
            { scaleX: 1, duration: 1.1, ease: 'power2.out' },
        ));
    });

    return {
        revert() {
            cleanups.forEach((cleanup) => cleanup());
        },
    };
}
