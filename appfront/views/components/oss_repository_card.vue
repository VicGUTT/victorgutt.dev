<script lang="ts" setup>
import type { OssRepositoryIndex } from '@/types/Models/OpenSource/OssRepository.ts';
import useRoute from '@/lib/composables/useRoute.ts';

type Props = {
    repository: OssRepositoryIndex;
};

defineOptions({
    name: 'o-oss-repository-card',
});

defineProps<Props>();

const route = useRoute();
</script>

<template>
    <div :style="`view-transition-name: ${repository.id};`">
        <o-card as="article" variant="wrapped" lang="en">
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-between sm:gap-4">
                <div>
                    <h2 :id="`${repository.id}-title`" :style="`view-transition-name: ${repository.id}-key;`">
                        <span class="text-muted font-mono">{{ repository.full_name.split('/')[0] }}</span>
                        <span>/</span>
                        <span class="font-medium font-mono ml-1">
                            <span>{{ repository.full_name.split('/')[1] }}</span>
                        </span>
                    </h2>

                    <p
                        v-if="repository.description"
                        class="mt-1 max-w-prose-sm"
                        :style="`view-transition-name: ${repository.id}-title;`"
                    >
                        {{ repository.description }}
                    </p>

                    <ul v-if="repository.topics?.length" class="mt-3 flex flex-wrap items-center gap-2" role="list">
                        <li v-for="topic in repository.topics" :key="topic" class="text-sm text-muted">#{{ topic }}</li>
                    </ul>
                </div>

                <div class="flex items-center justify-between gap-2 sm:flex-col sm:items-end">
                    <div class="flex items-center gap-2 sm:flex-col sm:items-end sm:gap-0.5">
                        <div v-if="repository.language" class="flex items-center gap-2" :title="repository.language">
                            <o-icon class="w-4 h-4 text-muted" name="heroicons.outline.code-bracket" />
                            <span class="uppercase text-xs text-muted tracking-widest" aria-hidden="true">
                                {{ repository.language }}
                            </span>
                        </div>

                        <ul
                            v-if="repository.languages"
                            class="flex items-center gap-1 opacity-80 sm:flex-col sm:items-end sm:gap-0"
                            role="list"
                        >
                            <template v-for="language in Object.keys(repository.languages).slice(0, 4)" :key="language">
                                <li v-if="language !== repository.language" class="text-muted text-2xs text-right">
                                    {{ language }}
                                </li>
                            </template>
                        </ul>
                    </div>

                    <div class="flex items-center gap-1" :title="repository.license.name">
                        <o-icon class="w-3 h-3 text-muted" name="heroicons.outline.scale" />
                        <span class="uppercase text-2xs text-muted tracking-widest" aria-hidden="true">
                            {{ repository.license.key }}
                        </span>
                        <span class="sr-only">{{ repository.license.name }}</span>
                    </div>
                </div>
            </div>

            <o-link
                class="card-link absolute inset-0 focus-visible:outline-0"
                :aria-labelledby="`${repository.id}-title`"
                :href="route('web:open_source.show', { path: repository.latest_release.id })"
                cache-for="60m"
                prefetch
            />
        </o-card>
    </div>
</template>
