<script lang="ts" setup>
import { ref, computed } from 'vue';

defineOptions({
    name: 'o-page-colors',
});

const showLabels = ref(true);
const hasSpacing = ref(false);
const showBase = ref(false);

const palettes = ['brand1' /* , 'brand2', 'brand3' */, 'info', 'success', 'warning', 'error', 'gray'];
const swatches = computed(() => {
    const start = [0, 50, 100, 200, 300, 400, 500];
    const middle = [600] as (number | string)[];
    const end = [700, 800, 900, 950, 1000, 1100, 1200];

    if (showBase.value) {
        middle.push('base');
    }

    return [...start, ...middle, ...end];
});
</script>

<template>
    <main class="mt-12 container mx-auto">
        <header>
            <h1 class="text-4xl font-semibold">Colors</h1>
        </header>

        <ul class="mt-6 flex items-center gap-4 bg-brand1-500/0" role="list">
            <li>
                <label class="flex items-center gap-2">
                    <span class="font-medium">Labels</span>
                    <input v-model="showLabels" class="size-4.5 rounded-md" type="checkbox" />
                </label>
            </li>
            <li>
                <label class="flex items-center gap-2">
                    <span class="font-medium">Spacing</span>
                    <input v-model="hasSpacing" class="size-4.5 rounded-md" type="checkbox" />
                </label>
            </li>
            <li>
                <label class="flex items-center gap-2">
                    <span class="font-medium">Base</span>
                    <input v-model="showBase" class="size-4.5 rounded-md" type="checkbox" />
                </label>
            </li>
        </ul>

        <div :class="['mt-6 grid', { 'gap-x-4 gap-y-7': hasSpacing }]">
            <section
                v-for="palette in palettes"
                :key="palette"
                :class="['grid items-center', { 'gap-6': hasSpacing }]"
                :style="`grid-template-columns: repeat(${swatches.length + 1}, minmax(0, 1fr));`"
            >
                <h2 class="font-semibold">{{ palette }}</h2>

                <article v-for="swatch in swatches" :key="swatch" class="relative">
                    <span
                        :class="[
                            'h-12 block',
                            {
                                'rounded-sm outline -outline-offset-1 outline-black/10 sm:rounded-md dark:outline-white/10':
                                    hasSpacing,
                            },
                        ]"
                        :style="`background-color: var(--color-${palette}-${swatch});`"
                    />
                    <span v-if="showLabels" class="absolute inset-x-0 -bottom-5.5 block mt-1 text-sm text-center">
                        {{ swatch }}
                    </span>
                </article>
            </section>
        </div>
    </main>
</template>
