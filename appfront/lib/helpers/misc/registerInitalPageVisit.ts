import type { PageProps } from '@/types/Page/PageProps.ts';
import axios from 'axios';

export default function registerInitalPageVisit(pageProps: PageProps<Record<string, unknown>>) {
    if (!pageProps.meta.visit?.enabled) {
        return;
    }

    axios.post(`/${pageProps.app.locale}/visit/initial`, {
        href: window.location.href,
        referrer: pageProps.meta.visit?.referrer,
    });
}
