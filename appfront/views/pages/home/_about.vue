<script lang="ts" setup>
import { computed } from 'vue';
import { usePreferredReducedMotion } from '@vueuse/core';

defineOptions({
    name: 'o-page-home-about',
});

/* Motion control
------------------------------------------------*/

const preferredMotion = usePreferredReducedMotion();

const animateable = computed(() => preferredMotion.value === 'no-preference');
</script>

<template>
    <o-section class="relative pb-12 overflow-clip">
        <div
            v-if="!animateable"
            class="absolute inset-x-0 top-0 bg-gradient-to-b from-(--app-bg-color) pt-[10%] -z-1 nojs:hidden"
            aria-hidden="true"
            inert
        ></div>

        <div class="container prose text-app max-w-prose-xl sm:min-w-prose-xs">
            <p>
                {{ $t('pages.home.sections.about.description') }}
            </p>

            <o-card class="group p-0 rounded-3xl overflow-clip aspect-[16/10]">
                <div class="relative size-full transition scale-150 group-hover:scale-99">
                    <img
                        class="m-0! absolute inset-0 size-full object-cover rounded-[1.25rem] transition group-hover:opacity-0"
                        src="/images/misc/tahiti_zoomed_in_map.webp"
                        alt=""
                    />
                    <img
                        class="m-0! absolute inset-0 size-full object-cover rounded-[1.25rem] transition-[opacity,visibility] opacity-0 invisible group-hover:opacity-100 group-hover:visible"
                        src="/images/misc/tahiti_zoomed_out_map.webp"
                        alt=""
                    />
                </div>
            </o-card>
        </div>
    </o-section>
</template>
