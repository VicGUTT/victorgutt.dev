<script lang="ts" setup>
import type { TechStackPageProps } from '@/types/Page/TechStackPageProps.ts';
import { computed } from 'vue';
import usePage from '@/lib/composables/usePage.ts';
import oPageHeader from '@/views/components/page_header.vue';
import oTechStackItem from '@/views/components/tech_stack_item.vue';

defineOptions({
    name: 'o-page-tech-stack',
});

const page = usePage<TechStackPageProps>();

const sections = computed(() => page.props.data.sections);
</script>

<template>
    <div class="container max-w-prose-xl overflow-clip">
        <o-page-header :title="page.props.head.title!" :description="`${$t('pages.tech_stack.head.description')}.`" />

        <div class="flex flex-col gap-20">
            <template v-for="(items, sectionKey) in sections" :key="sectionKey">
                <o-section v-if="items.length" :title="$t(`pages.tech_stack.sections.${sectionKey}.title`)">
                    <ul class="flex flex-col gap-4" role="list">
                        <o-tech-stack-item
                            v-for="item in items"
                            :key="item.key"
                            as="li"
                            :dots-count="110"
                            :item="item"
                        />
                    </ul>
                </o-section>
            </template>
        </div>
    </div>
</template>
