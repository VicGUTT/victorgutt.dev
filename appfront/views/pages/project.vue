<script lang="ts" setup>
import type { ProjectPageProps } from '@/types/Page/ProjectPageProps.ts';
import usePage from '@/lib/composables/usePage.ts';
import oProjectCard from '@/views/components/project_card.vue';
import oPageHeader from '@/views/components/page_header.vue';

defineOptions({
    name: 'o-page-project',
});

const page = usePage<ProjectPageProps>();
</script>

<template>
    <div class="container max-w-prose-xl overflow-clip">
        <o-page-header :title="page.props.head.title!" :description="`${page.props.head.description!}.`" />

        <div class="flex flex-col gap-20">
            <o-section
                v-for="(section, sectionKey) in page.props.data.sections"
                :key="sectionKey"
                :title="section.title"
                class="flex flex-col gap-4"
            >
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                    <o-project-card
                        v-for="(project, projectKey) in section.data"
                        :key="projectKey"
                        :project-key="projectKey"
                        :project="project"
                    />
                </div>
            </o-section>
        </div>
    </div>
</template>
