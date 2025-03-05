import useRoute from '@/lib/composables/useRoute.ts';
import { computed } from 'vue';
import { trans } from 'laravel-vue-i18n';
import usePage from '@/lib/composables/usePage.ts';
import app from '@/lib/helpers/app.ts';

export default function usePageLinks() {
    const route = useRoute();
    const page = usePage();

    const locale = app().useLocale();

    return computed(() => {
        return {
            projects: {
                key: 'projects',
                label: trans('page_links.projects.label'),
                label_short: trans('page_links.projects.label_short'),
                url: route('web:projects', { locale: locale.value }),
                get is_active() {
                    return route().current('web:projects');
                },
            },
            open_source: {
                key: 'open_source',
                label: trans('page_links.open_source.label'),
                label_short: trans('page_links.open_source.label_short'),
                url: route('web:open_source.index', { locale: locale.value }),
                get is_active() {
                    return page.url.startsWith(new URL(this.url).pathname);
                },
            },
            tech_stack: {
                key: 'tech_stack',
                label: trans('page_links.tech_stack.label'),
                label_short: trans('page_links.tech_stack.label_short'),
                url: route('web:tech_stack', { locale: locale.value }),
                get is_active() {
                    return route().current('web:tech_stack');
                },
            },
            contact: {
                key: 'contact',
                label: trans('page_links.contact.label'),
                label_short: trans('page_links.contact.label_short'),
                url: route('web:contact', { locale: locale.value }),
                get is_active() {
                    return route().current('web:contact');
                },
            },
        };
    });
}
