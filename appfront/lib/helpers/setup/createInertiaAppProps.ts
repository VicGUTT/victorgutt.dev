import { type App, type DefineComponent, createSSRApp, h } from 'vue';
import type { InertiaAppProps } from 'node_modules/@inertiajs/vue3/types/app.ts';
import type { CreateInertiaAppProps, CreateInertiaAppPropsOptions } from '@/types/helpers/setup.ts';
import plugins from '@/lib/plugins/index.ts';
import components from '@/views/components/global.ts';
import app from '@/lib/helpers/app.ts';

const appName = app().name;

export default function createInertiaAppProps(options: CreateInertiaAppPropsOptions): CreateInertiaAppProps {
    return {
        title: (title: string) => (appName && title ? `${title} | ${appName}` : title || appName),
        async resolve(name) {
            const pages = import.meta.glob<DefineComponent>('../../../views/pages/**/*.vue');
            const page = (await pages[`../../../views/pages/${name}.vue`]()).default;

            if (!page.layout) {
                page.layout = [
                    (await import('@/views/layouts/base.vue')).default as DefineComponent,
                    (await import('@/views/layouts/default/default.vue')).default as DefineComponent,
                ];
            }

            return page;
        },
        setup({ el, App, props, plugin }) {
            const vueApp = createSSRApp({ render: () => h(App, props) }).use(plugin);

            app().refreshConfig(props.initialPage.props.app);

            registerPlugins(vueApp, props);
            registerComponents(vueApp);

            if (options.asSsr) {
                return vueApp;
            }

            vueApp.mount(el);

            el.removeAttribute('data-page');

            if (import.meta.env.DEV && app().hasDebugModeEnabled()) {
                // @ts-expect-error shush
                window.$app = {
                    app: app(),
                    inertia: {
                        el,
                        App,
                        props,
                        plugin,
                    },
                    vue: vueApp,
                    $props: props.initialPage.props,
                };
            }

            return vueApp;
        },
        progress: {
            color: 'var(--accent-color)',
        },
    };
}

function registerPlugins(vueApp: App, initProps: InertiaAppProps): void {
    plugins.forEach(([plugin, ...options]) => {
        options = options.map((option) => {
            if (typeof option !== 'function') {
                return option;
            }

            return option(vueApp, initProps);
        }) as typeof options;

        vueApp.use(plugin, ...options);
    });
}

function registerComponents(vueApp: App): void {
    Object.entries(components).forEach(([componentName, component]) => {
        vueApp.component(componentName, component);
    });
}
