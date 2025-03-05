<script lang="ts" setup>
import { computed } from 'vue';

type Props = {
    light?: boolean;
    withBackground?: boolean;
};

defineOptions({
    name: 'o-logo',
});

const props = defineProps<Props>();

const source = computed(() => {
    if (!props.light && !props.withBackground) {
        return '/images/logo/gvs_logo_dark@2x.png';
    }

    if (props.light && !props.withBackground) {
        return '/images/logo/gvs_logo_light@2x.png';
    }

    if (!props.light && props.withBackground) {
        return '/images/logo/gvs_logo_dark_with_background@2x.png';
    }

    return '/images/logo/gvs_logo_light_with_background@2x.png';
});
</script>

<template>
    <img class="logo" :src="source" alt="Logo" />
</template>
