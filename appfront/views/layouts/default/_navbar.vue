<script lang="ts" setup>
import { computed, ref, watch } from 'vue';
import { useIntervalFn } from '@vueuse/core';
import socials from '@/assets/static/socials.ts';
import usePage from '@/lib/composables/usePage.ts';
import usePageLinks from '@/lib/composables/usePageLinks.ts';
import useRoute from '@/lib/composables/useRoute.ts';
import app from '@/lib/helpers/app.ts';

defineOptions({
    name: 'o-layouts-default-navbar',
});

/* Setup
------------------------------------------------*/

const route = useRoute();
const page = usePage();
const links = usePageLinks();

const appName = app().name;
const currentLocale = app().useLocale();
const locales = app().getSupportedLocales();

/* Location
------------------------------------------------*/

const timezoneKey = 'Europe/Paris';

const timeFormatter = computed(() => {
    return new Intl.DateTimeFormat(currentLocale.value, {
        timeZone: timezoneKey,
        hour: 'numeric',
        minute: 'numeric',
    });
});

const timeZoneName = computed(() => {
    return Intl.DateTimeFormat(currentLocale.value, {
        timeZoneName: 'short',
        timeZone: timezoneKey,
    })
        .formatToParts()
        .find((part) => part.type === 'timeZoneName')?.value;
});

const formatTime = () => timeFormatter.value.format(new Date());

const time = ref(formatTime());
const utc = computed(() => {
    // GMT basically = UTC right? 👀
    return timeZoneName.value ? timeZoneName.value.toUpperCase().replace('GMT', 'UTC') : null;
});

useIntervalFn(() => {
    time.value = formatTime();
}, 1000);

watch(currentLocale, () => {
    time.value = formatTime();
});
</script>

<template>
    <o-navbar :scrolled-enough-threshold="50">
        <div class="flex flex-col items-center justify-center gap-2 sm:flex-row md:justify-between md:gap-6">
            <div class="flex-1 flex items-center gap-4 md:gap-6">
                <o-link
                    class="hidden rounded-full min-[400px]:flex"
                    :href="route('web:home', { locale: currentLocale })"
                    :title="$t('actions.go_home')"
                    cache-for="60m"
                    prefetch
                >
                    <o-logo
                        class="w-10 h-10 rounded-full border border-gray-900/60 sm:w-12 sm:h-12"
                        light
                        with-background
                    />
                    <span class="sr-only">{{ $t('actions.go_home') }}</span>
                </o-link>

                <ul
                    class="mx-auto flex items-center xs:gap-2 p-1 rounded-full shadow-md border border-gray-900/50 backdrop-blur-3xl bg-[color-mix(in__oklch,__color-mix(in__oklab,__var(--app-bg-color)__20%,__transparent)__92%,__var(--color-gray-50))]"
                    role="list"
                >
                    <li v-for="link in links" :key="link.key">
                        <o-link
                            :class="[
                                'block py-1.5 px-2.5 text-sm font-semibold tracking-wider rounded-full border sm:py-1.5 sm:px-3 md:py-2 md:px-4',
                                {
                                    'bg-app border-gray-900/50': link.is_active,
                                    'text-muted border-transparent hover:text-app focus-visible:text-app':
                                        !link.is_active,
                                },
                            ]"
                            :href="link.url"
                            :title="link.label"
                            cache-for="60m"
                            prefetch
                        >
                            <template v-if="link.label_short">
                                <span class="block min-[500px]:hidden sm:block md:hidden">
                                    {{ link.label_short }}
                                </span>
                                <span class="hidden min-[500px]:block sm:hidden md:block">
                                    {{ link.label }}
                                </span>
                            </template>
                            <template v-if="!link.label_short">
                                <span>{{ link.label }}</span>
                            </template>
                        </o-link>
                    </li>
                </ul>
            </div>

            <div class="flex items-center gap-6">
                <div
                    class="hidden flex-col pr-6 border-r border-gray-900 min-[400px]:flex sm:hidden lg:flex"
                    role="list"
                >
                    <span class="text-xs text-muted font-semibold">
                        {{ $t('locations.corsica') }}, {{ $t('locations.france') }}
                    </span>
                    <span class="text-xs font-semibold text-right">
                        <span>{{ time }}</span> <span v-if="utc" class="text-muted text-2xs">({{ utc }})</span>
                    </span>
                </div>

                <ul class="flex items-center gap-2 pr-6 border-r border-gray-900" role="list">
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

                <ul class="flex items-center gap-3 text-sm text-muted lg:gap-4" role="list">
                    <li v-for="social in socials" :key="social.key">
                        <a
                            class="block hover:text-app"
                            :href="social.url"
                            :title="`${$t('socials.follow.on', { user: appName, name: social.label })}`"
                        >
                            <o-icon class="w-4.5 h-4.5" :name="`socials.${social.key}`" />
                            <span class="sr-only">{{ social.label }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </o-navbar>
</template>
