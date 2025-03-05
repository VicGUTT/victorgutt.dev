<script lang="ts" setup>
type Props = {
    title: string;
    description: string;
    bordered?: boolean;
};

defineOptions({
    name: 'o-page-header',
});

defineProps<Props>();
</script>

<template>
    <header
        :class="[
            'mb-16',
            {
                'pb-8 border-b border-gray-900/60 ': bordered,
            },
        ]"
    >
        <div class="prose prose-invert">
            <h1 class="text-app">{{ title }}</h1>

            <p class="text-muted">{{ description }}</p>
        </div>
    </header>
</template>
