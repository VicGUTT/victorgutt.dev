import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import type { Head, Link } from '@inertiajs/vue3';
import type { RouteList as ZiggyRouteList, route } from 'ziggy-js';
import type { PageProps as AppPageProps } from '@/types/Page/PageProps.ts';
import type routesConfig from '@/assets/static/routes-config.ts';
import type Logo from '@/views/components/logo.vue';
import type Icon from '@/views/components/icon.vue';
import type Navbar from '@/views/components/navbar.vue';
import type Card from '@/views/components/card.vue';
import type Section from '@/views/components/section.vue';
import type PageNotTranslated from '@/views/components/page_not_translated.vue';
import type MaybeAntiSpamEmail from '@/views/components/maybe_anti_spam_email.vue';

type _RouteList = (typeof routesConfig)['routes'];

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof route;
    }
}

declare module '@vue/runtime-core' {
    export interface GlobalComponents {
        'o-head': typeof Head;
        'o-link': typeof Link;
        'o-logo': typeof Logo;
        'o-icon': typeof Icon;
        'o-navbar': typeof Navbar;
        'o-card': typeof Card;
        'o-section': typeof Section;
        'o-page-not-translated': typeof PageNotTranslated;
        'o-maybe-anti-spam-email': typeof MaybeAntiSpamEmail;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module 'ziggy-js' {
    interface RouteList extends ZiggyRouteList, _RouteList {}
}
