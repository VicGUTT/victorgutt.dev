import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import setupAxios from '@/lib/helpers/setup/setupAxios.ts';
import setupInertiaEvents from '@/lib/helpers/setup/setupInertiaEvents.ts';
import createInertiaAppProps from '@/lib/helpers/setup/createInertiaAppProps.ts';

setupAxios();
setupInertiaEvents();

createServer((page) => {
    return createInertiaApp({
        page,
        render: renderToString,
        ...createInertiaAppProps({ asSsr: true }),
    });
});
