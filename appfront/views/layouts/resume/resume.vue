<script lang="ts" setup>
import oNavbar from '@/views/layouts/resume/_navbar.vue';
import oFooter from '@/views/layouts/default/_footer.vue';

defineOptions({
    name: 'o-layout-resume',
});
</script>

<template>
    <o-navbar class="print:hidden" />

    <main class="isolate container pt-8 pb-30 sm:pt-30 print:px-20! sm:print:py-12" style="min-block-size: 100dvb">
        <slot />
    </main>

    <o-footer class="print:hidden" />
</template>
