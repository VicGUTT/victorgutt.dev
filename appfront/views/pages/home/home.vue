<script lang="ts" setup>
import { computed } from 'vue';
import { usePreferredReducedMotion } from '@vueuse/core';
import { Motion as oMotion, useScroll, useTransform } from 'motion-v';
import oHeader from '@/views/pages/home/_header.vue';
import oAbout from '@/views/pages/home/_about.vue';
import oProjects from '@/views/pages/home/_project.vue';
import oOpenSource from '@/views/pages/home/_open_source.vue';
import oTechStack from '@/views/pages/home/_tech_stack.vue';

defineOptions({
    name: 'o-page-home',
});

/* Motion control
------------------------------------------------*/

const preferredMotion = usePreferredReducedMotion();

const shouldAnimate = computed(() => preferredMotion.value === 'no-preference');

const { scrollY } = useScroll({
    axis: 'y',
});

const opacity = shouldAnimate.value ? useTransform(() => scrollY.get() / 3 / 100) : 1;
const marginTop = shouldAnimate.value ? useTransform(() => `-${Math.min(45, scrollY.get() / 6.5)}dvh`) : 0;
</script>

<template>
    <o-header />

    <o-motion
        class="relative z-1 nojs:opacity-100! nojs:mt-[-35dvh]!"
        tabindex=""
        :style="{
            opacity,
            marginTop,
        }"
    >
        <o-about />
        <o-projects />
        <o-open-source />
        <o-tech-stack />
    </o-motion>
</template>
