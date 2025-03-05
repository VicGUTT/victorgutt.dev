<script lang="ts" setup>
import socials from '@/assets/static/socials.ts';
import usePageLinks from '@/lib/composables/usePageLinks.ts';
import useRoute from '@/lib/composables/useRoute.ts';
import app from '@/lib/helpers/app.ts';

defineOptions({
    name: 'o-layouts-default-footer',
});

const route = useRoute();

const pages = usePageLinks();
const currentLocale = app().useLocale();

const appName = app().name;
const year = new Date().getFullYear();
</script>

<template>
    <footer class="isolate pt-16 pb-28 bg-app border-t border-gray-200/80 dark:border-gray-1000/70 lg:py-28">
        <div class="container flex flex-col items-center justify-center gap-4">
            <div class="flex flex-wrap items-center justify-center gap-2 text-sm">
                <o-link
                    class="flex items-center justify-center gap-3"
                    :href="route('web:home', { locale: currentLocale })"
                    :title="appName"
                    cache-for="60m"
                    prefetch
                >
                    <o-logo class="w-8 h-8 rounded-full border border-gray-900/60" light with-background />
                    <p class="font-bold">
                        &copy; {{ year }}
                        <span class="font-bold">{{ appName }}</span>
                    </p>
                </o-link>
                <span>·</span>
                <span>{{ $t('All rights reserved.') }}</span>
            </div>

            <ul class="flex flex-wrap items-center justify-center gap-4 text-sm text-muted" role="list">
                <li v-for="page in pages" :key="page.key">
                    <o-link class="hover:underline" :href="page.url" :title="page.label" cache-for="60m" prefetch>
                        {{ page.label }}
                    </o-link>
                </li>
            </ul>

            <ul class="flex flex-wrap items-center justify-center gap-6 text-sm text-muted" role="list">
                <li v-for="social in socials" :key="social.key">
                    <a
                        class="block hover:text-app"
                        :href="social.url"
                        :title="`${$t('socials.follow.on', { user: appName, name: social.label })}`"
                    >
                        <o-icon class="w-5 h-5" :name="`socials.${social.key}`" />
                        <span class="sr-only">{{ social.label }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </footer>
</template>
