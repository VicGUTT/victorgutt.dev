<script lang="ts" setup>
import type { ResumePageProps } from '@/types/Page/ResumePageProps.ts';
import { computed } from 'vue';
import baseLayout from '@/views/layouts/base.vue';
import resumeLayout from '@/views/layouts/resume/resume.vue';
import usePage from '@/lib/composables/usePage.ts';
import app from '@/lib/helpers/app.ts';
import extractUserNameFromSocialNetworkUrl from '@/lib/utils/extractUserNameFromSocialNetworkUrl.ts';
import socials from '@/assets/static/socials.ts';

defineOptions({
    name: 'o-page-resume',
    layout: [baseLayout, resumeLayout],
});

/* Setup
------------------------------------------------*/

const page = usePage<ResumePageProps>();

const appName = app().name;
const currentLocale = app().useLocale();

/* Data
------------------------------------------------*/

const me = computed(() => page.props.data.me);
const experiences = computed(() => page.props.data.experiences);
const educations = computed(() => page.props.data.educations);
const languages = computed(() => page.props.data.languages);
const techs = computed(() => page.props.data.techs);
</script>

<template>
    <header class="mb-20 flex flex-col items-center gap-6 md:flex-row md:justify-between print:mb-0">
        <div class="flex items-center gap-4">
            <o-logo
                class="flex-none size-14 rounded-full border border-gray-900/60 xs:size-16 sm:size-18"
                light
                with-background
            />

            <div class="flex flex-col">
                <h1 class="uppercase text-xl font-extrabold tracking-wider xs:text-2xl sm:text-3xl">
                    {{ me.name }}
                </h1>
                <h2 class="uppercase text-sm text-muted tracking-wider sm:text-base">
                    {{ me.title }}
                </h2>
            </div>
        </div>

        <ul
            class="max-w-prose-lg flex flex-wrap gap-y-2 gap-x-6 justify-center text-sm tracking-wide md:flex-col md:gap-3"
            role="list"
        >
            <li class="list-disc md:list-none">
                {{ me.location.postcode }} {{ me.location.city }}, {{ me.location.country }}
            </li>
            <li class="list-disc md:list-none">
                <a
                    class="hover:underline"
                    :href="`tel:${me.phone.replaceAll(' ', '').replaceAll('-', '').replaceAll('.', '')}`"
                >
                    {{ me.phone }}
                </a>
            </li>
            <li class="list-disc md:list-none">
                <a class="hover:underline" :href="`mailto:${me.email}`">
                    {{ me.email }}
                </a>
            </li>
            <li class="list-disc md:list-none">
                <a class="hover:underline" :href="`https://${me.website}`">
                    {{ me.website }}
                </a>
            </li>
        </ul>
    </header>

    <section class="mt-12">
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-center">
            <div>
                <h2 class="text-xl font-extrabold uppercase tracking-wider">
                    {{ $t('pages.resume.sections.about.title') }}
                </h2>

                <p class="max-w-prose-lg mt-4 text-pretty leading-relaxed">
                    {{ me.text }}
                </p>
            </div>

            <ul class="flex-shrink-0 flex flex-wrap gap-4 md:flex-col md:gap-2" role="list">
                <li v-for="social in socials" :key="social.key">
                    <a
                        class="flex items-center gap-2 text-sm text-muted hover:underline"
                        :href="social.url"
                        :title="`${$t('socials.follow.on', { user: appName, name: social.label })}`"
                    >
                        <o-icon class="w-4.5 h-4.5 text-app" :name="`socials.${social.key}`" />
                        <span>/ @{{ extractUserNameFromSocialNetworkUrl(social) }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </section>

    <hr class="my-12 border-gray-200/80 dark:border-gray-900/70 transition" aria-hidden="true" />

    <div class="md:columns-2 md:gap-4">
        <section class="max-w-prose">
            <h2 class="text-xl font-extrabold uppercase tracking-wider">
                {{ $t('pages.resume.sections.experience.title') }}
            </h2>

            <ul class="mt-8 flex flex-col gap-8" role="list">
                <li
                    v-for="(experience, index) in experiences"
                    :key="index"
                    :class="[
                        'relative group ml-8',
                        {
                            [`${currentLocale === 'en' ? 'print:mt-[6px]' : ''}`]: index === 4, // Hack forcing the 5th item to be on the 2nd column
                        },
                    ]"
                >
                    <span
                        class="absolute top-1 w-2 h-2 -left-8 bg-(--accent-color) rounded-full overflow-hidden"
                        aria-hidden="true"
                    ></span>
                    <span
                        class="absolute top-6 w-px h-full -left-7 bg-(--accent-color) rounded-md overflow-hidden group-last-of-type:h-[85%]"
                        aria-hidden="true"
                    ></span>

                    <article class="-mt-1.5">
                        <h3 class="text-lg font-bold uppercase tracking-wider">{{ experience.role }}</h3>
                        <h4 class="mt-1 text-muted font-medium uppercase tracking-wider">
                            {{ experience.company }} | {{ experience.dates.start }} - {{ experience.dates.end }}
                        </h4>

                        <p class="mt-3 pr-4 text-sm text-pretty leading-loose">
                            {{ experience.text }}
                        </p>
                    </article>
                </li>
            </ul>
        </section>
        <section class="mt-12 max-w-prose">
            <h2 class="text-xl font-extrabold uppercase tracking-wider">
                {{ $t('pages.resume.sections.education.title') }}
            </h2>

            <ul class="mt-8 flex flex-col gap-8" role="list">
                <li v-for="(education, index) in educations" :key="index" class="relative group ml-8">
                    <span
                        class="absolute top-1 w-2 h-2 -left-8 bg-(--accent-color) rounded-full overflow-hidden"
                        aria-hidden="true"
                    ></span>
                    <span
                        class="absolute top-6 w-px h-full -left-7 bg-(--accent-color) rounded-md overflow-hidden group-last-of-type:h-[85%]"
                        aria-hidden="true"
                    ></span>

                    <article class="-mt-1.5">
                        <h3 class="text-lg font-bold uppercase tracking-wider">{{ education.name }}</h3>
                        <h4 class="mt-1 text-muted font-medium uppercase tracking-wider">
                            {{ education.place }} | {{ education.dates.start ? `${education.dates.start} -` : '' }}
                            {{ education.dates.end }}
                        </h4>

                        <p class="mt-3 pr-4 text-sm text-pretty leading-loose">
                            {{ education.text }}
                        </p>
                    </article>
                </li>
            </ul>
        </section>
    </div>

    <hr class="my-12 border-gray-200/80 dark:border-gray-900/70 transition" aria-hidden="true" />

    <div class="flex flex-col justify-between gap-12 md:flex-row md:gap-4">
        <section class="w-full max-w-prose">
            <h2 class="text-xl font-extrabold uppercase tracking-wider">
                {{ $t('pages.resume.sections.language.title') }}
            </h2>

            <ul class="mt-8 flex flex-col gap-3" role="list">
                <li v-for="(language, index) in languages" :key="index" class="flex items-center gap-3">
                    <span class="font-medium">{{ language.name }}</span>
                    <span class="text-muted">{{ language.level }}</span>
                </li>
            </ul>
        </section>
        <section class="w-full max-w-prose">
            <h2 class="text-xl font-extrabold uppercase tracking-wider">
                {{ $t('pages.resume.sections.stack.title') }}
            </h2>

            <ul class="mt-8 flex flex-col gap-6" role="list">
                <li v-for="(tech, index) in techs" :key="index">
                    <h3 class="text-muted font-bold uppercase tracking-wider">{{ tech.title }}</h3>

                    <ul class="mt-2 flex flex-wrap gap-2" role="list">
                        <li v-for="item in tech.items" :key="item" class="flex items-center gap-2">
                            <span class="text-sm">{{ item }}</span>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
    </div>
</template>
