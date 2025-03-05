<script lang="ts" setup>
import type { TechStackItemData } from '@/types/TechStackItemData.ts';

type Props = {
    as: 'li';
    dotsCount: number;
    item: TechStackItemData;
};

defineOptions({
    name: 'o-tech-stack-item',
});

defineProps<Props>();
</script>

<template>
    <component :is="as" class="flex items-center gap-2">
        <span class="shrink-0 text-sm font-bold">{{ item.label }}</span>
        <div class="flex items-center gap-1 overflow-hidden" aria-hidden="true" inert style="content-visibility: auto">
            <span v-for="i in dotsCount" :key="i" class="text-xs text-muted opacity-80">.</span>
        </div>
        <div v-if="item.usage_start_year" class="shrink-0 flex items-center gap-2 text-sm">
            <span v-if="!item.usage_end_year" class="text-sm text-muted lowercase">
                {{ $t('words.since') }}
            </span>
            <span class="font-bold">{{ item.usage_start_year }}</span>

            <template v-if="item.usage_end_year">
                <span class="-ml-1 text-sm text-muted">-</span>
                <span class="-ml-1 font-bold">{{ item.usage_end_year }}</span>
            </template>
        </div>
        <span v-else class="text-muted">•</span>
    </component>
</template>
