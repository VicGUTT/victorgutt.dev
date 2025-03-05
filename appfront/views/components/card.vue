<script lang="ts" setup>
import { computed } from 'vue';

type Props = {
    as?: 'div' | 'article' | 'section';
    variant?: 'default' | 'wrapped';
};
type Component = {
    tag: Required<Props>['as'];
    classes: string[];
};

defineOptions({
    name: 'o-card',
});

const props = defineProps<Props>();

const component = computed<Component>(() => {
    const tag = props.as ?? 'div';
    const classes: string[] = ['card', `card-${props.variant ?? 'default'}`];

    return { tag, classes };
});
</script>

<template>
    <component :is="component.tag" :class="component.classes">
        <div v-if="variant === 'wrapped'" class="card-design-wrapper" aria-hidden="true" inert></div>

        <slot />
    </component>
</template>
