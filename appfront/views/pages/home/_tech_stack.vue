<script lang="ts" setup>
import type { HomePageProps } from '@/types/Page/HomePageProps.ts';
import { computed } from 'vue';
import useRoute from '@/lib/composables/useRoute.ts';
import usePage from '@/lib/composables/usePage.ts';
import oTechStackItem from '@/views/components/tech_stack_item.vue';

defineOptions({
    name: 'o-page-home-tech-stack',
});

const route = useRoute();
const page = usePage<HomePageProps>();

const items = computed(() => page.props.data.tech_stacks);
</script>

<template>
    <o-section
        class="container max-w-5xl py-12"
        :title="$t('page_links.tech_stack.label')"
        :url="route('web:tech_stack')"
    >
        <div class="prose prose-invert">
            <p class="text-app leading-relaxed">
                {{ $t('pages.home.sections.tech_stack.description') }}
            </p>
        </div>

        <ul class="mt-8 flex flex-col gap-4" role="list">
            <o-tech-stack-item v-for="item in items" :key="item.key" as="li" :dots-count="140" :item="item" />
        </ul>
    </o-section>
</template>
