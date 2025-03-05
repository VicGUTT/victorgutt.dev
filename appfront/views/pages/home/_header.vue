<script lang="ts" setup>
import { computed } from 'vue';
import { usePreferredReducedMotion } from '@vueuse/core';
import { Motion as oMotion, useScroll, useTransform } from 'motion-v';

defineOptions({
    name: 'o-page-home-header',
});

/* Motion control
------------------------------------------------*/

const preferredMotion = usePreferredReducedMotion();

const shouldAnimate = computed(() => preferredMotion.value === 'no-preference');

const { scrollY, scrollYProgress } = useScroll({
    axis: 'y',
    offset: ['end', '0vh'],
});

const opacity = shouldAnimate.value ? useTransform(() => (100 - scrollY.get() / 3) / 100) : 1;
const scale = shouldAnimate.value ? useTransform(() => Math.max(0.74, scrollYProgress.get())) : 1;
</script>

<template>
    <o-motion
        as="header"
        class="relative py-20 px-6 w-full min-h-[100dvh] mt-[calc(var(--main-safe-space-top)_*_-1)] flex items-center justify-center bg-app bg-dots"
        tabindex=""
        :style="{
            opacity,
            scale,
        }"
    >
        <div class="absolute inset-0 bg-radial-mask pointer-events-none select-none" inert aria-hidden="true"></div>

        <div class="flex flex-col items-center justify-center text-center gap-4">
            <h1 class="text-gradient text-4xl font-bold uppercase sm:text-5xl md:text-6xl lg:text-7xl">
                Victor Schedlyn Gutt
            </h1>

            <h2 class="text-muted font-medium uppercase opacity-60 sm:text-2xl md:text-3xl lg:text-4xl">
                <span>{{ $t('terms.web_developer') }}</span>
                <span class="opacity-40 text-[120%]"> | </span>
                <span>{{ $t('terms.web_designer') }}</span>
            </h2>
        </div>
    </o-motion>
</template>
