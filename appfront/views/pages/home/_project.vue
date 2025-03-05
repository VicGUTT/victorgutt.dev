<script lang="ts" setup>
import type { HomePageProps } from '@/types/Page/HomePageProps.ts';
import { computed } from 'vue';
import useRoute from '@/lib/composables/useRoute.ts';
import usePage from '@/lib/composables/usePage.ts';
import oProjectCard from '@/views/components/project_card.vue';

defineOptions({
    name: 'o-page-home-project',
});

const route = useRoute();
const page = usePage<HomePageProps>();

const projects = computed(() => page.props.data.projects);
</script>

<template>
    <o-section class="container max-w-5xl py-12" :title="$t('page_links.projects.label')" :url="route('web:projects')">
        <div class="prose prose-invert">
            <p class="text-app leading-relaxed">
                {{ $t('pages.home.sections.project.description') }}
            </p>
        </div>

        <div class="mt-8 grid grid-cols-[repeat(auto-fit,_minmax(30ch,_1fr))] gap-8">
            <o-project-card
                v-for="(project, projectKey) in projects"
                :key="projectKey"
                :project-key="projectKey"
                :project="project"
            />
        </div>
    </o-section>
</template>
