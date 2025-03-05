<script lang="ts" setup>
import type { HomePageProps } from '@/types/Page/HomePageProps.ts';
import { computed } from 'vue';
import useRoute from '@/lib/composables/useRoute.ts';
import usePage from '@/lib/composables/usePage.ts';
import oOssRepositoryCard from '@/views/components/oss_repository_card.vue';

defineOptions({
    name: 'o-page-home-open-source',
});

const route = useRoute();
const page = usePage<HomePageProps>();

const repositories = computed(() => page.props.data.repositories);
</script>

<template>
    <o-section
        class="container max-w-5xl py-12"
        :title="$t('page_links.open_source.label')"
        :url="route('web:open_source.index')"
    >
        <div class="prose prose-invert">
            <p class="text-app leading-relaxed">
                {{ $t('pages.home.sections.open_source.description') }}
            </p>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-8 md:grid-cols-2">
            <o-oss-repository-card v-for="repository in repositories" :key="repository.id" :repository="repository" />
        </div>
    </o-section>
</template>
