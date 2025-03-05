import type { I18n } from 'laravel-vue-i18n';
import app from '@/lib/helpers/app.ts';
import isSsr from '@/lib/utils/isSsr.ts';

type OptionsInterface = Required<Parameters<I18n['setOptions']>>[0];
type LanguageJsonFileInterface = ReturnType<I18n['resolveLang']>;

export default function setupI18nVuePluginParams(): OptionsInterface {
    return {
        lang: app().getLocale(),
        fallbackLang: app().getFallbackLocale(),
        /**
         * TODO: Fix TypeScript error and dynamically load
         * the languages at some point.
         *
         * This is done like this for now because otherwise,
         * if done as adviced by the docs, we either get Vite
         * warnings or SSR missmatches.
         */
        // @ts-expect-error shush
        resolve(fileName) {
            const files = import.meta.glob<LanguageJsonFileInterface>('../../../../lang/*.json', { eager: true });

            const file = files[`../../../../lang/${fileName}.json`];
            const data = isSsr() ? file.default : new Promise((resolve) => resolve(file));

            return data;
        },
    };
}
