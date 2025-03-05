<script lang="ts" setup>
import { computed } from 'vue';
import { useWindowScroll } from '@vueuse/core';

type Props = {
    scrolledEnoughThreshold: number;
};

defineOptions({
    name: 'o-navbar',
});

/* Setup
------------------------------------------------*/

const props = defineProps<Props>();

/* "Scrolled enough"
------------------------------------------------*/

const { y: scrollY } = useWindowScroll();

const scrolledEnough = computed(() => {
    return scrollY.value >= props.scrolledEnoughThreshold;
});
</script>

<template>
    <nav class="isolate fixed inset-x-0 -bottom-[2px] z-10 sm:top-0 sm:bottom-auto">
        <div
            :class="[
                'hidden sm:block absolute inset-0 pointer-events-none select-none backdrop-blur-sm bg-app [mask-image:__linear-gradient(to__bottom,__var(--app-bg-color)__20%,__transparent__calc(100%__-__20%))]',
                {
                    'opacity-60': !scrolledEnough,
                },
            ]"
            aria-hidden="true"
            inert
        ></div>

        <div
            :class="[
                `
                    z-1 relative container pt-3 pb-2 w-full min-w-max transition-colors border-t border-gray-200/80 dark:border-gray-900/70
                    shadow-md backdrop-blur-3xl bg-[color-mix(in__oklch,__color-mix(in__oklch,__var(--app-bg-color)__20%,__transparent)__98%,__var(--color-gray-1200))]

                    sm:px-4 sm:py-4 sm:border md:rounded-t-none md:rounded-2xl

                    md:max-w-[calc(calc(var(--breakpoint-md))__+__calc(var(--spacing)__*__4__*__2))]
                    lg:max-w-[calc(calc(var(--breakpoint-lg))__+__calc(var(--spacing)__*__4__*__2))]
                    xl:max-w-[calc(calc(var(--breakpoint-xl))__+__calc(var(--spacing)__*__4__*__2))]
                    2xl:max-w-[calc(calc(var(--spacing-container))__+__calc(var(--spacing)__*__4__*__2))]
                `,
                {
                    [`
                        not-nojs:sm:border-transparent not-nojs:sm:dark:border-transparent
                        not-nojs:sm:shadow-none not-nojs:sm:backdrop-blur-none not-nojs:sm:bg-transparent
                    `]: !scrolledEnough,
                },
            ]"
        >
            <slot />
        </div>
    </nav>
</template>
