export type Environment = 'testing' | 'local' | 'staging' | 'production';
export type App = {
    name: string;
    url: string;
    locale: string;
    fallback_locale: string;
    supported_locales: string[];
    env?: Environment | null;
    debug?: boolean | null;
};
