export default class VisitData {
    static data() {
        const self = new VisitData();

        return {
            prefers_color_scheme: self.#getPrefersColorScheme(),
            prefers_reduced_motion: self.#getPrefersReducedMotion(),
            prefers_reduced_transparency: self.#getPrefersReducedTransparency(),
            prefers_contrast: self.#getPrefersContrast(),
            prefers_reduced_data: self.#getPrefersReducedData(),
            forced_colors: self.#getForcedcolors(),

            screen_width: self.#getScreenWidth(),
            screen_height: self.#getScreenHeight(),

            timezone: self.#getTimezone(),
        };
    }

    static asHeaders() {
        return Object.entries(VisitData.data()).reduce(
            (acc, [key, value]) => {
                key = key
                    .split('_')
                    .map((value) => value.charAt(0).toUpperCase() + value.slice(1))
                    .join('-');

                acc[`X-Visit-${key}`] = value;

                return acc;
            },
            {} as Record<string, string | number | null>
        );
    }

    #getPrefersColorScheme(): string | null {
        return this.#getMatchedMedia('prefers-color-scheme', ['light', 'dark']);
    }

    #getPrefersReducedMotion(): string | null {
        return this.#getMatchedMedia('prefers-reduced-motion', ['no-preference', 'reduce']);
    }

    #getPrefersReducedTransparency(): string | null {
        return this.#getMatchedMedia('prefers-reduced-transparency', ['no-preference', 'reduce']);
    }

    #getPrefersContrast(): string | null {
        return this.#getMatchedMedia('prefers-contrast', ['no-preference', 'more', 'less', 'custom']);
    }

    #getPrefersReducedData(): string | null {
        return this.#getMatchedMedia('prefers-reduced-data', ['no-preference', 'reduce']);
    }

    #getForcedcolors(): string | null {
        return this.#getMatchedMedia('forced-colors', ['none', 'active']);
    }

    #getScreenWidth(): number {
        // @see https://stackoverflow.com/a/8876069
        return Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
    }

    #getScreenHeight(): number {
        // @see https://stackoverflow.com/a/8876069
        return Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0);
    }

    #getTimezone(): string {
        // @see https://stackoverflow.com/a/34602679
        return Intl.DateTimeFormat().resolvedOptions().timeZone;
    }

    #getMatchedMedia<Values extends string[]>(mediaQuery: string, values: Values): Values[number] | null {
        for (const value of values) {
            if (window.matchMedia(`(${mediaQuery}: ${value})`).matches) {
                return value;
            }
        }

        return null;
    }
}
