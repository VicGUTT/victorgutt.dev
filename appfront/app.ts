import { createInertiaApp } from '@inertiajs/vue3';
import setupAxios from '@/lib/helpers/setup/setupAxios.ts';
import setupInertiaEvents from '@/lib/helpers/setup/setupInertiaEvents.ts';
import createInertiaAppProps from '@/lib/helpers/setup/createInertiaAppProps.ts';
import setupViewTransition from '@/lib/helpers/setup/setupViewTransition.ts';

import '@/app.css';

setupAxios();
setupInertiaEvents();
setupViewTransition();

createInertiaApp(createInertiaAppProps({ asSsr: false }));
