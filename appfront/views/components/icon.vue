<script lang="ts" setup>
type Props = {
    name: string;
};

defineOptions({
    name: 'o-icon',
});

defineProps<Props>();
</script>

<template>
    <svg class="icon" aria-hidden="true">
        <use :href="`#icon-${name.replaceAll('.', '-')}`" />
    </svg>
</template>
