<script lang="ts" setup>
import type { OssRepositoryIndexPageProps } from '@/types/Page/OpenSource/OssRepositoryIndexPageProps.ts';
import { computed } from 'vue';
import usePage from '@/lib/composables/usePage.ts';
import app from '@/lib/helpers/app.ts';
import oPageHeader from '@/views/components/page_header.vue';
import oOssRepositoryCard from '@/views/components/oss_repository_card.vue';

defineOptions({
    name: 'o-page-oss-index',
});

const page = usePage<OssRepositoryIndexPageProps>();

const currentLocale = app().useLocale();

const repositories = computed(() => page.props.data.repositories);
</script>

<template>
    <o-page-not-translated v-if="currentLocale !== 'en'" class="max-w-prose-xl mb-2" />

    <div class="container max-w-prose-xl">
        <o-page-header :title="page.props.head.title!" :description="`${page.props.head.description!}.`" bordered />

        <section class="flex flex-col gap-8">
            <o-oss-repository-card v-for="repository in repositories" :key="repository.id" :repository="repository" />
        </section>
    </div>
</template>
