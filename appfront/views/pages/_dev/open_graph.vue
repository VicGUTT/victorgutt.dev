<script lang="ts" setup>
import type { PageProps } from '@/types/Page/PageProps.ts';
import { defineAsyncComponent, type DefineComponent } from 'vue';
import usePage from '@/lib/composables/usePage.ts';
import baseLayout from '@/views/layouts/base.vue';

type OgPageProps = PageProps<{
    __dev__og: {
        component_path: string;
    };
}>;

defineOptions({
    name: 'o-page-open-graph',
    layout: [baseLayout],
});

const page = usePage<OgPageProps>();
const oPage = defineAsyncComponent(() => {
    const path = page.props.data.__dev__og.component_path;

    const pages = import.meta.glob<DefineComponent>('@/views/pages/**/*.vue');
    const asyncComponent = pages[`/appfront/views/pages/${path}.vue`]();

    return asyncComponent;
});
</script>

<template>
    <main
        class="isolate [--main-safe-space-top:_calc(var(--spacing)_*_12)] [--main-safe-space-bottom:_calc(var(--spacing)_*_12)] pt-(--main-safe-space-top) pb-(--main-safe-space-bottom)"
        style="min-block-size: 100dvb"
    >
        <o-page />
    </main>
</template>
