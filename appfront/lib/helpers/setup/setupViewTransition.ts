import { router } from '@inertiajs/vue3';

/**
 * TODO: Navigation progress bar improvements needed.
 *
 * Currently, when the view transition takes
 * a snapshot of the outgoing page, it freezes
 * the page's progress bar. On slow navigations
 * the progress bar may be frozen mid-way and
 * on regular/fast navigations the progress bar
 * may not have time to appear at all.
 * Changing the view transition root to be `#app`
 * does not resolve the issue.
 */
export default function setupViewTransition(): void {
    if (!document.startViewTransition) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    router.on('start', (event) => {
        if (event.detail.visit.async) {
            return;
        }

        if (event.detail.visit.interrupted) {
            return;
        }

        if (event.detail.visit.only.length) {
            return;
        }

        if (event.detail.visit.except.length) {
            return;
        }

        transition();
    });
}

function transition() {
    return document.startViewTransition(transitionHandler);
}

async function transitionHandler() {
    return new Promise((resolve) => {
        const residue = router.on('finish', () => {
            resolve(undefined);

            residue();
        });
    });
}
