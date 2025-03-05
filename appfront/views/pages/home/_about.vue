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

            <o-card class="p-0 rounded-3xl overflow-clip">
                <img
                    class="m-0! rounded-[1.25rem] transition scale-150 hover:scale-99"
                    src="/images/misc/corsica_map.webp"
                    alt="corsica_map.png"
                />
            </o-card>
        </div>
    </o-section>
</template>
