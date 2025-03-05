<script lang="ts" setup>
type Props = {
    title?: string;
    url?: string;
};

defineOptions({
    name: 'o-section',
});

defineProps<Props>();
</script>

<template>
    <section :class="{ 'flex flex-col gap-4': !!title }">
        <header v-if="title" class="mb-6 flex items-center gap-2 text-muted">
            <h2 class="flex-none text-sm font-bold">
                {{ title }}
            </h2>

            <hr class="w-full border-current opacity-20" aria-hidden="true" />

            <o-link
                v-if="url"
                class="flex items-center justify-center gap-2 text-sm hover:text-app focus-visible:text-app"
                :href="url"
            >
                <span class="text-sm whitespace-nowrap">{{ $t('actions.view_all') }}</span>
                <o-icon class="w-4 h-4 flex-none rotate-90" name="heroicons.outline.arrow-long-up" />
            </o-link>
        </header>

        <slot />
    </section>
</template>
