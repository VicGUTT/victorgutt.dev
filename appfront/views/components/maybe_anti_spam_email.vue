<script lang="ts" setup>
import { onMounted, ref, useAttrs, useSlots } from 'vue';
import { trans } from 'laravel-vue-i18n';

type Props = {
    href: string;
};

defineOptions({
    name: 'o-maybe-anti-spam-email',
});

defineProps<Props>();

/* Setup
------------------------------------------------*/

const slots = useSlots();
const attrs = useAttrs();

const mounted = ref(false);

onMounted(() => {
    mounted.value = true;
});

/* Helpers
------------------------------------------------*/

function kindaObfuscateEmail(value: string) {
    return value
        .replace('@', `[${trans('symbols.@')}]`)
        .replaceAll('.', `[${trans('symbols.\.')}]`)
        .toLowerCase();
}
</script>

<template>
    <span v-if="!mounted || !slots.default" v-bind="attrs">
        {{ kindaObfuscateEmail(href) }}
    </span>
    <a v-else :href="mounted ? `mailto:${href}` : undefined" v-bind="attrs">
        <slot v-if="!mounted">{{ kindaObfuscateEmail(href) }}</slot>
        <slot v-else>{{ href }}</slot>
    </a>
</template>
