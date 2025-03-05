<script lang="ts" setup>
import oNavbar from '@/views/layouts/default/_navbar.vue';
import oFooter from '@/views/layouts/default/_footer.vue';

defineOptions({
    name: 'o-layout-default',
});
</script>

<template>
    <o-navbar />

    <main
        class="isolate [--main-safe-space-top:_calc(var(--spacing)_*_8)] [--main-safe-space-bottom:_calc(var(--spacing)_*_30)] sm:[--main-safe-space-top:_calc(var(--spacing)_*_30)] lg:[--main-safe-space-top:_calc(var(--spacing)_*_44)] pt-(--main-safe-space-top) pb-(--main-safe-space-bottom)"
        style="min-block-size: 100dvb"
    >
        <slot />
    </main>

    <o-footer />
</template>
