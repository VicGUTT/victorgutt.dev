<script lang="ts" setup>
import type { ResumePageProps } from '@/types/Page/ResumePageProps.ts';
import { computed, onBeforeUnmount, watch } from 'vue';
import { useUrlSearchParams } from '@vueuse/core';
import {
    DropdownMenuContent as oDropdownMenuContent,
    DropdownMenuItem as oDropdownMenuItem,
    DropdownMenuPortal as oDropdownMenuPortal,
    DropdownMenuRoot as oDropdownMenuRoot,
    DropdownMenuTrigger as oDropdownMenuTrigger,
} from 'reka-ui';
import usePage from '@/lib/composables/usePage.ts';
import useRoute from '@/lib/composables/useRoute.ts';
import app from '@/lib/helpers/app.ts';
import isSsr from '@/lib/utils/isSsr.ts';

defineOptions({
    name: 'o-layouts-resume-navbar',
});

/* Setup
------------------------------------------------*/

const route = useRoute();
const page = usePage<ResumePageProps>();

const currentLocale = app().useLocale();
const locales = app().getSupportedLocales();

/* Theming
------------------------------------------------*/

const params = useUrlSearchParams('history', {
    initialValue: { theme: 'dark' as 'light' | 'dark' },
    writeMode: 'replace',
});

const isDarkTheme = computed(() => params.theme === 'dark');

function toggleTheme() {
    params.theme = isDarkTheme.value ? 'light' : 'dark';
}

watch(
    isDarkTheme,
    (value) => {
        document.documentElement.classList.toggle('dark', value);
    },
    { immediate: !isSsr() }
);

onBeforeUnmount(() => {
    document.documentElement.classList.add('dark');
});

/* Data
------------------------------------------------*/

const files = computed(() => page.props.data.files);
</script>

<template>
    <o-navbar :scrolled-enough-threshold="50">
        <div
            class="pb-2 flex flex-col-reverse items-center justify-between gap-6 min-[480px]:pb-0 min-[480px]:flex-row min-[509px]:gap-8"
        >
            <o-link
                class="flex items-center justify-center gap-2 text-muted text-sm hover:text-app focus-visible:text-app"
                :href="route('web:home', { locale: currentLocale })"
                :title="$t('actions.go_home')"
                cache-for="60m"
                prefetch
            >
                <o-icon class="w-4 h-4 flex-none -rotate-90" name="heroicons.outline.arrow-long-up" />
                <span>{{ $t('actions.go_home') }}</span>
            </o-link>

            <div class="flex items-center gap-4 min-[509px]:gap-6">
                <ul class="flex items-center gap-2 pr-6 border-r border-gray-200/80 dark:border-gray-900" role="list">
                    <li v-for="(locale, index) in locales" :key="locale" class="flex items-center gap-2">
                        <o-link
                            :class="[
                                'text-sm hover:underline',
                                {
                                    'text-muted': locale !== currentLocale,
                                    'font-semibold': locale === currentLocale,
                                },
                            ]"
                            :href="page.url.replace(`/${currentLocale}`, `/${locale}`)"
                            :title="$t(`languages.${locale}.label`)"
                            cache-for="60m"
                            prefetch
                            preserve-scroll
                        >
                            {{ locale.toUpperCase() }}
                        </o-link>
                        <span v-if="index + 1 !== locales.length" class="text-muted" aria-hidden="true">·</span>
                    </li>
                </ul>

                <div class="pr-4 border-r border-gray-200/80 dark:border-gray-900 min-[509px]:pr-6">
                    <button
                        class="flex items-center gap-3 text-sm text-muted hover:text-app"
                        :title="
                            $t(`pages.resume.navbar.theme_switcher.${isDarkTheme ? 'dark_to_light' : 'light_to_dark'}`)
                        "
                        @click="toggleTheme"
                    >
                        <o-icon class="w-5 h-5" :name="`heroicons.outline.${isDarkTheme ? 'moon' : 'sun'}`" />
                        <span class="sr-only">
                            {{
                                $t(
                                    `pages.resume.navbar.theme_switcher.${isDarkTheme ? 'dark_to_light' : 'light_to_dark'}`
                                )
                            }}
                        </span>
                    </button>
                </div>

                <o-dropdown-menu-root>
                    <o-dropdown-menu-trigger
                        class="flex items-center gap-2 text-xs text-muted hover:text-app aria-expanded:text-app min-[333px]:gap-3 min-[333px]:text-sm"
                    >
                        <o-icon
                            class="w-4 h-4 min-[333px]:w-5 min-[333px]:h-5"
                            name="heroicons.outline.document-arrow-down"
                        />
                        <span>{{ $t('pages.resume.navbar.pdfs.button_label') }}</span>
                    </o-dropdown-menu-trigger>

                    <o-dropdown-menu-portal>
                        <o-dropdown-menu-content
                            class="mt-2 min-w-(--reka-dropdown-menu-trigger-width) p-1.5 flex flex-col gap-1.5 border border-gray-200/80 dark:border-gray-900/70 rounded-xl outline-none shadow-md will-change-[opacity,transform] backdrop-blur-lg bg-[color-mix(in__oklch,__color-mix(in__oklch,__var(--bg-surface-3)__50%,__transparent)__98%,__var(--color-gray-1200))] dark:bg-[color-mix(in__oklch,__color-mix(in__oklch,__var(--bg-surface-4)__50%,__transparent)__98%,__var(--color-gray-1200))] data-[side=top]:animate-slide_down_and_fade data-[side=right]:animate-slide_left_and_fade data-[side=bottom]:animate-slide_up_and_fade data-[side=left]:animate-slide_right_and_fade"
                        >
                            <o-dropdown-menu-item
                                v-for="(fileUrl, fileTheme) in files"
                                :key="fileTheme"
                                class="group select-none outline-none"
                            >
                                <a
                                    class="py-1.5 px-2.5 flex items-center gap-3 text-sm rounded-lg group-data-[highlighted]:bg-gray-200/80 dark:group-data-[highlighted]:bg-gray-900"
                                    :href="fileUrl"
                                    target="_blank"
                                    rel="noopener"
                                >
                                    <o-icon
                                        class="w-4.5 h-4.5 text-muted transition-colors group-data-[highlighted]:text-app"
                                        :name="`heroicons.outline.${fileTheme === 'dark' ? 'moon' : 'sun'}`"
                                    />
                                    <span>{{ $t(`pages.resume.navbar.pdfs.${fileTheme}_theme`) }}</span>
                                </a>
                            </o-dropdown-menu-item>
                        </o-dropdown-menu-content>
                    </o-dropdown-menu-portal>
                </o-dropdown-menu-root>
            </div>
        </div>
    </o-navbar>
</template>
