<script lang="ts" setup>
import type { OssRepositoryShowPageProps } from '@/types/Page/OpenSource/OssRepositoryShowPageProps.ts';
import { computed, onMounted, onUnmounted } from 'vue';
import useRoute from '@/lib/composables/useRoute.ts';
import usePage from '@/lib/composables/usePage.ts';
import app from '@/lib/helpers/app.ts';

defineOptions({
    name: 'o-page-oss-show',
});

const route = useRoute();
const page = usePage<OssRepositoryShowPageProps>();

const currentLocale = app().useLocale();

const repository = computed(() => page.props.data.repository);
// const release = computed(() => page.props.data.release);
const documentation = computed(() => page.props.data.documentation);

const repositoryTitleViewTransitionName = computed(() => `${repository.value.id}-title`);

/* Table of contents scroll spy
------------------------------------------------*/

const cleanups: (() => void)[] = [];

onMounted(() => {
    trackContentForTableOfContents();
});

onUnmounted(() => {
    cleanups.forEach((callback) => {
        callback();
    });
});

function trackContentForTableOfContents() {
    const content = document.querySelector<HTMLElement>('#rendered-markdown-content');
    const toc = document.querySelector<HTMLDivElement>('#rendered-markdown-toc');

    if (!content || !toc) {
        return;
    }

    setupIntersectionObserver(content, toc);
}

function setupIntersectionObserver(content: HTMLElement, toc: HTMLDivElement) {
    const targets = new Set(content.querySelectorAll<HTMLHeadingElement>('h2[id]:not([id=""]), h3[id]:not([id=""])'));
    const intersected = new Set<HTMLHeadingElement>();

    const options: IntersectionObserverInit = {
        rootMargin: '-100px 0px',
    };

    const observer = new IntersectionObserver((entries) => {
        for (const entry of entries) {
            if (entry.isIntersecting) {
                intersected.add(entry.target as HTMLHeadingElement);
            } else {
                intersected.delete(entry.target as HTMLHeadingElement);
            }
        }

        const found = targets.values().find((heading) => {
            return intersected.has(heading);
        });

        if (!found) {
            return;
        }

        toc.querySelector('[aria-current]')?.removeAttribute('aria-current');

        toc.querySelector(`a[href="#${found.id}"]`)?.setAttribute('aria-current', 'location');
    }, options);

    targets.values().forEach((heading) => {
        observer.observe(heading);
    });

    cleanups.push(() => {
        observer.disconnect();
    });
}
</script>

<template>
    <o-page-not-translated v-if="currentLocale !== 'en'" class="mx-auto -mt-4 mb-4" />

    <!-- eslint-disable vue/no-v-html -->
    <div class="container flex gap-12" lang="en">
        <div class="w-full max-w-5xl flex flex-col">
            <header class="flex flex-wrap items-center justify-between gap-4">
                <o-link
                    class="flex items-center justify-center gap-2 text-muted text-sm hover:text-app focus-visible:text-app"
                    :href="route('web:open_source.index')"
                    :title="$t('pages.oss.show.back_to_packages')"
                    cache-for="60m"
                    prefetch
                >
                    <o-icon class="w-4 h-4 flex-none -rotate-90" name="heroicons.outline.arrow-long-up" />
                    <span>{{ $t('pages.oss.show.packages') }}</span>
                </o-link>

                <a
                    class="flex items-center justify-center gap-2 text-muted text-sm font-mono hover:text-app focus-visible:text-app"
                    :href="repository.github_html_url"
                    :title="`See &quot;${repository.full_name}&quot; on GitHub`"
                    :style="`view-transition-name: ${repository.id}-key;`"
                >
                    <o-icon class="w-5 h-5 flex-none" name="socials.github" />
                    <span>
                        <span class="opacity-60">{{ repository.full_name.split('/')[0] }}</span>
                        <span>/</span>
                        <span class="font-medium">
                            <span>{{ repository.full_name.split('/')[1] }}</span>
                        </span>
                    </span>
                    <o-icon class="w-4 h-4 flex-none" name="heroicons.outline.arrow-top-right-on-square" />
                </a>
            </header>

            <div :style="`view-transition-name: ${repository.id};`">
                <o-card
                    variant="wrapped"
                    class="mt-8 pt-6 pb-10 px-6 rounded-3xl md:py-10 [&_>_:where(.card-design-wrapper)]:-inset-4 [&_>_:where(.card-design-wrapper)]:border-gray-900/80 [&_>_:where(.card-design-wrapper)]:rounded-4xl [&_>_:where(.card-design-wrapper)]:opacity-60"
                >
                    <article
                        id="rendered-markdown-content"
                        class="rendered-markdown-content mx-auto max-w-prose-xl prose prose-invert md:prose-lg"
                        v-html="documentation.rendered_content"
                    />
                </o-card>
            </div>
        </div>

        <aside class="sticky top-16 max-h-[calc(100svh-3.5rem)] py-10 overflow-x-hidden scrollbar-sm">
            <div class="hidden flex-col gap-3 xl:flex">
                <h3 class="font-mono text-xs/6 font-bold tracking-widest text-muted uppercase">
                    {{ $t('pages.oss.show.on_this_page') }}
                </h3>
                <div
                    id="rendered-markdown-toc"
                    class="rendered-markdown-toc mx-auto max-w-prose"
                    v-html="documentation.rendered_table_of_content"
                />
            </div>
        </aside>
    </div>
    <!-- eslint-enable vue/no-v-html -->
</template>

<style>
:where(.rendered-markdown-content h1) {
    view-transition-name: v-bind(repositoryTitleViewTransitionName);
}
</style>
