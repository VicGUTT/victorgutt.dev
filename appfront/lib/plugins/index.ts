import type { App } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { i18nVue } from 'laravel-vue-i18n';
import routesConfig from '@/assets/static/routes-config.js';
import setupI18nVuePluginParams from '@/lib/helpers/setup/setupI18nVuePluginParams.ts';

export default [
    [ZiggyVue, routesConfig],
    [i18nVue, setupI18nVuePluginParams],
    //
] as Parameters<App['use']>[];
