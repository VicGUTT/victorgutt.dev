import { computed, type ComputedRef } from 'vue';
import type { App as Config, Environment } from '@/types/configs/app.ts';
import { loadLanguageAsync } from 'laravel-vue-i18n';
import usePage from '@/lib/composables/usePage.ts';

export default class App {
    static #instance: App | null = null;
    static #hydrated = false;

    #config: Config;

    constructor(config?: Config) {
        if (!config) {
            config = {
                name: import.meta.env.VITE_APP_NAME,
                url: import.meta.env.VITE_APP_URL,
                locale: 'en',
                fallback_locale: 'en',
                supported_locales: ['en'],
            };
        }

        this.#config = config;
    }

    public static instance(config?: Config): App {
        if (!App.#instance) {
            App.#instance = new App(config);
        }

        return App.#instance;
    }

    public get name(): string {
        return this.#config.name;
    }

    public get url(): string {
        return this.#config.url;
    }

    /**
     * Retrieve the environment.
     */
    public environment(): Environment {
        return this.#config.env || 'production';
    }

    /**
     * Determine if the application is in the local environment.
     */
    public isLocal(): boolean {
        return this.environment() === 'local';
    }

    /**
     * Determine if the application is in the production environment.
     */
    public isProduction(): boolean {
        return this.environment() === 'production';
    }

    /**
     * Determine if the application is running with debug mode enabled.
     */
    public hasDebugModeEnabled(): boolean {
        return this.#config.debug || false;
    }

    /**
     * Set the application's locale.
     */
    public setLocale(locale: string): void {
        this.#config.locale = locale;

        if (!App.#hydrated) {
            return;
        }

        loadLanguageAsync(this.#config.locale);
    }

    /**
     * Get the application's locale.
     */
    public getLocale(): string {
        return this.#config.locale;
    }

    /**
     * Get the application's locale reactively.
     */
    public useLocale(): ComputedRef<string> {
        return computed(() => {
            const locale = usePage().props.app.locale;

            if (locale !== this.getLocale()) {
                this.setLocale(locale);
            }

            return locale;
        });
    }

    /**
     * Get the application's fallback locale.
     */
    public getFallbackLocale(): string {
        return this.#config.fallback_locale;
    }

    /**
     * Get the application's supported locales.
     */
    public getSupportedLocales(): string[] {
        return this.#config.supported_locales;
    }

    /**
     * Set the application's config.
     */
    public refreshConfig(config: Config): void {
        this.#config = {
            name: config.name,
            url: config.url,
            locale: config.locale,
            fallback_locale: config.fallback_locale,
            supported_locales: config.supported_locales,
            env: config.env,
            debug: config.debug,
        };

        this.setLocale(this.#config.locale);

        if (App.#hydrated) {
            return;
        }

        App.#hydrated = true;
    }
}
