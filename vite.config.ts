import { type ExecException, exec } from 'node:child_process';
import { type Plugin, defineConfig } from 'vite';
import type { ViteSvgIconsPlugin } from 'vite-plugin-svg-icons';
import path from 'node:path';
import fs from 'node:fs';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import i18n from 'laravel-vue-i18n/vite';
import vueDevTools from 'vite-plugin-vue-devtools';
import manifestSRI from 'vite-plugin-manifest-sri';
import watchAndRun from '@kitql/vite-plugin-watch-and-run';
import { compilerIcons } from 'vite-plugin-svg-icons';
import favicons from 'favicons';

/**
 * If you want to debug your dependencies by making local edits, you can:
 *     - Temporarily disable cache via the Network tab of your browser devtools;
 *     - Restart Vite dev server with the `--force` flag to re-bundle the deps;
 *     - Reload the page.
 *
 * @see https://vitejs.dev/guide/dep-pre-bundling#browser-cache
 */
export default defineConfig({
    build: {
        /**
         * @see https://vitejs.dev/config/#build-target
         */
        target: `es${new Date().getFullYear() - 2}`,
    },
    resolve: {
        alias: {
            '~': path.resolve('.'),
            '@': path.resolve('./appfront'),
        },
    },
    plugins: [
        laravel({
            input: 'appfront/app.ts',
            ssr: 'appfront/ssr.ts',
            detectTls: 'victorgutt.test',
            refresh: {
                paths: [...refreshPaths, './appfront/views/app.blade.php'],
            },
        }),
        /**
         * @see https://github.com/vitejs/vite-plugin-vue/tree/main/packages/plugin-vue
         * @see https://laravel.com/docs/11.x/vite#vue
         */
        vue({
            /**
             * Requires @vitejs/plugin-vue@^5.1.0
             */
            features: {
                /**
                 * Set to `false` to disable Options API support and allow related code in
                 * Vue core to be dropped via dead-code elimination in production builds,
                 * resulting in smaller bundles.
                 * - **default:** `true`
                 *
                 * Commented out as it breaks Inertia and potentially other dependencies.
                 */
                // optionsAPI: false,
            },
            template: {
                transformAssetUrls: {
                    // The Vue plugin will re-write asset URLs, when referenced
                    // in Single File Components, to point to the Laravel web
                    // server. Setting this to `null` allows the Laravel plugin
                    // to instead re-write asset URLs to point to the Vite
                    // server instead.
                    base: null,

                    // The Vue plugin will parse absolute URLs and treat them
                    // as absolute paths to files on disk. Setting this to
                    // `false` will leave absolute URLs un-touched so they can
                    // reference assets in the public directory as expected.
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
        i18n(),
        vueDevTools(),
        manifestSRI(),
        /**
         * @see https://www.kitql.dev/docs/setup/03_vite-plugin-watch-and-run
         */
        watchAndRun([
            {
                name: 'ide-helper:models -W', //  -M
                run: 'php artisan ide-helper:models -W', //  -M
                watch: path.resolve('appdomain/**/Models/**/*.php').replace(/\\/g, '/'),
            },
            {
                name: 'ziggy:generate', // ziggy:generate --types
                run: 'php artisan ziggy:generate', // php artisan ziggy:generate --types
                watch: path.resolve('routes/**/*.php').replace(/\\/g, '/'),
            },
        ]),
        beforeBuild(),
        customSvgIconsPlugin(),
        faviconsPlugin(),
    ],
});

/* Custom plugins
------------------------------------------------*/

function beforeBuild(): Plugin {
    return {
        name: 'custom-plugin:before-build',
        async buildStart(options) {
            /**
             * This hook runs for both `app.ts` and `ssr.ts`.
             * Since there's no need to run the actions below
             * more than once, we'll just return early when
             * running for one of the mentioned files.
             */
            if (Array.isArray(options.input) && options.input.includes('appfront/ssr.ts')) {
                return;
            }

            /**
             * There's probably a better approache to checking if
             * this is a production build than using `this.meta.watchMode` 🤷‍♂️.
             */
            const envParam = this.meta.watchMode ? '' : '--env=production';

            await run(`php artisan ziggy:generate ${envParam}`.trim());
        },
    };
}

/**
 * @see https://github.com/vbenjs/vite-plugin-svg-icons/blob/7550357300793b96b3561fc708899b9f4309e906/packages/core/src/index.ts#L25
 */
function customSvgIconsPlugin(): Plugin {
    type FileStats = {
        relativeName: string;
        mtimeMs?: number;
        code: string;
        symbolId?: string;
    };

    const cache = new Map<string, FileStats>();

    const iconsDir = path.resolve(process.cwd(), 'appfront/assets/icons');
    const spritePath = path.resolve(process.cwd(), 'appfront/views/components/icon-sprite.vue');

    const options = {
        iconDirs: [iconsDir],
        symbolId: 'icon-[dir]-[name]',
        inject: 'body-last' as const,
        customDomId: 'icons-sprite',
        svgoOptions: {},
    } satisfies ViteSvgIconsPlugin;

    return {
        name: 'custom-plugin:svg-icons',
        closeBundle: async () => {
            const { insertHtml } = await compilerIcons(cache, options.svgoOptions, options);

            fs.writeFileSync(
                `${spritePath}`,
                `
                <template>
                    <svg
                        id="${options.customDomId}"
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:link="http://www.w3.org/1999/xlink"
                        hidden
                    >
                        ${
                            insertHtml.replace(
                                /class="[a-zA-Z0-9:;.\s()\-,]*"/gi,
                                ''
                            ) /* .replace(/<symbol /ig, '<symbol class="icon" ') */
                        }
                    </svg>
                </template>
                `,
                'utf8'
            );
        },
    };
}

/* Favicons plugin
------------------------------------------------*/

/**
 * @see https://github.com/itgalaxy/favicons#usage
 */
function faviconsPlugin(): Plugin {
    return {
        name: 'custom-plugin:favicons',
        closeBundle: async () => {
            try {
                const relativePath = '/images/favicons';
                const fullPath = path.resolve(path.resolve(process.cwd(), `public/${relativePath}`));

                if (fs.existsSync(path.resolve(`${fullPath}/content.html`))) {
                    return;
                }

                const response = await favicons('./public/images/logo/gvs_logo_dark_with_background@2x.png', {
                    path: relativePath, // Path for overriding default icons path. `string`
                    appName: 'Victor Gutt', // Your application's name. `string`
                    appShortName: undefined, // Your application's short_name. `string`. Optional. If not set, appName will be used
                    appDescription: undefined, // Your application's description. `string`
                    developerName: undefined, // Your (or your developer's) name. `string`
                    developerURL: undefined, // Your (or your developer's) URL. `string`
                    dir: 'auto', // Primary text direction for name, short_name, and description
                    lang: 'en-US', // Primary language for name and short_name
                    background: '#0f0b0c', // Background colour for flattened icons. `string`
                    theme_color: '#f0b100', // Theme color user for example in Android's task switcher. `string`
                    appleStatusBarStyle: 'black-translucent', // Style for Apple status bar: "black-translucent", "default", "black". `string`
                    display: 'standalone', // Preferred display mode: "fullscreen", "standalone", "minimal-ui" or "browser". `string`
                    orientation: 'any', // Default orientation: "any", "natural", "portrait" or "landscape". `string`
                    scope: '/', // set of URLs that the browser considers within your app
                    start_url: '/?homescreen=1', // Start URL when launching the application from a device. `string`
                    preferRelatedApplications: false, // Should the browser prompt the user to install the native companion app. `boolean`
                    relatedApplications: undefined, // Information about the native companion apps. This will only be used if `preferRelatedApplications` is `true`. `Array<{ id: string, url: string, platform: string }>`
                    version: '1.0', // Your application's version string. `string`
                    pixel_art: false, // Keeps pixels "sharp" when scaling up, for pixel art.  Only supported in offline mode.
                    loadManifestWithCredentials: false, // Browsers don't send cookies when fetching a manifest, enable this to fix that. `boolean`
                    manifestMaskable: false, // Maskable source image(s) for manifest.json. "true" to use default source. More information at https://web.dev/maskable-icon/. `boolean`, `string`, `buffer` or array of `string`
                    icons: {
                        // Platform Options:
                        // - offset - offset in percentage
                        // - background:
                        //   * false - use default
                        //   * true - force use default, e.g. set background for Android icons
                        //   * color - set background for the specified icons
                        //
                        android: false, // Create Android homescreen icon. `boolean` or `{ offset, background }` or an array of sources
                        appleIcon: false, // Create Apple touch icons. `boolean` or `{ offset, background }` or an array of sources
                        appleStartup: false, // Create Apple startup images. `boolean` or `{ offset, background }` or an array of sources
                        favicons: true, // Create regular favicons. `boolean` or `{ offset, background }` or an array of sources
                        windows: false, // Create Windows 8 tile icons. `boolean` or `{ offset, background }` or an array of sources
                        yandex: false, // Create Yandex browser icon. `boolean` or `{ offset, background }` or an array of sources
                    },
                    // shortcuts: [
                    //     // Your applications's Shortcuts (see: https://developer.mozilla.org/docs/Web/Manifest/shortcuts)
                    //     // Array of shortcut objects:
                    //     {
                    //         name: 'View your Inbox', // The name of the shortcut. `string`
                    //         short_name: 'inbox', // optionally, falls back to name. `string`
                    //         description: 'View your inbox messages', // optionally, not used in any implemention yet. `string`
                    //         url: '/inbox', // The URL this shortcut should lead to. `string`
                    //         icon: 'test/inbox_shortcut.png', // source image(s) for that shortcut. `string`, `buffer` or array of `string`
                    //     },
                    // ],
                    // // more shortcuts objects
                });

                // console.log(response.images); // Array of { name: string, contents: <buffer> }
                // console.log(response.files); // Array of { name: string, contents: <string> }
                // console.log(response.html); // Array of strings (html elements)

                console.log({ response });

                const entries = [
                    ...response.images,
                    ...response.files,
                    ...[{ name: 'content.html', contents: response.html.join('') }],
                ];

                if (!fs.existsSync(fullPath)) {
                    fs.mkdirSync(fullPath, { recursive: true });
                }

                entries.forEach((entry) => {
                    fs.writeFileSync(`${fullPath}/${entry.name}`, entry.contents);
                });
            } catch (error) {
                console.log((error as Error).message); // Error description e.g. "An unknown error has occurred"
            }
        },
    };
}

/* Helpers
------------------------------------------------*/

function run(command: string): Promise<string | ExecException> {
    return new Promise((resolve, reject) => {
        exec(command, (error, stdout, stderr) => {
            if (error) {
                reject(error);

                return;
            }

            if (stderr) {
                reject(stderr);

                return;
            }

            resolve(stdout);
        });
    });
}
