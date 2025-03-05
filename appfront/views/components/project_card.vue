<script lang="ts" setup>
import type { ProjectData } from '@/types/ProjectData.ts';

type Props = {
    projectKey: string;
    project: ProjectData;
};

defineOptions({
    name: 'o-project-card',
});

defineProps<Props>();
</script>

<template>
    <o-card
        as="article"
        class="p-6 pt-18 [&:has(.card-link:hover)__div.absolute]:bg-gray-700/30 [&:has(.card-link:hover)__div.absolute]:border-gray-700 [&:has(.card-link:focus-visible)__div.absolute]:bg-brand1-700/10 [&:has(.card-link:focus-visible)__div.absolute]:border-brand1-500/50"
        :style="`view-transition-name: project-${projectKey};`"
    >
        <div class="relative w-fit">
            <div
                class="absolute -inset-2 bg-surface-4 border border-gray-900 transition rounded-full opacity-50"
                aria-hidden="true"
                inert
            ></div>

            <div class="relative p-2 bg-surface-4 transition-colors border border-gray-900/60 rounded-full shadow-md">
                <img v-if="project.logo" class="relative w-11 h-11" :src="project.logo" :alt="project.name" />
                <o-icon
                    v-else
                    class="relative w-11 h-11 p-1 text-muted"
                    name="heroicons.outline.question-mark-circle"
                />
            </div>
        </div>

        <h3 :id="`project-${projectKey}-title`" class="mt-4 text-lg font-bold">{{ project.name }}</h3>
        <p class="mt-2 text-muted">{{ project.description }}</p>

        <a
            v-if="project.url"
            class="card-link absolute inset-0 focus-visible:outline-0"
            :aria-labelledby="`project-${projectKey}-title`"
            :href="project.url"
        />
    </o-card>
</template>
