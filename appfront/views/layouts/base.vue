<script lang="ts" setup>
import type { PageProps } from '@/types/Page/PageProps.ts';
import { onMounted } from 'vue';
import oIconSprite from '@/views/components/icon-sprite.vue';
import usePage from '@/lib/composables/usePage.ts';
import registerInitalPageVisit from '@/lib/helpers/misc/registerInitalPageVisit.ts';
import isSsr from '@/lib/utils/isSsr';

defineOptions({
    name: 'o-layout-base',
});

const page = usePage<PageProps<Record<string, unknown>>>();

if (!isSsr()) {
    document.documentElement.classList.add('loaded');
}

onMounted(() => {
    document.documentElement.classList.add('mounted');

    registerInitalPageVisit(page.props);
});
</script>

<template>
    <o-head>
        <title v-if="page.props.head?.title">{{ page.props.head.title }}</title>
        <meta v-if="page.props.head?.description" name="description" :content="page.props.head.description" />
    </o-head>

    <slot />

    <o-icon-sprite />
</template>
