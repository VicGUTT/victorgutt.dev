<script lang="ts" setup>
defineOptions({
    name: 'o-page-not-translated',
});
</script>

<template>
    <div class="container">
        <div
            class="w-fit py-2 pl-2 pr-4 text-xs text-warning-50 bg-warning-1200/40 transition-colors border border-warning-300/20 rounded-lg"
        >
            <div class="flex items-center justify-center gap-2">
                <o-icon class="w-4 h-4 flex-none text-warning-300" name="heroicons.outline.language" />
                <p class="max-w-prose">
                    {{ $t('misc.page.untranslated.to_french') }}
                </p>
            </div>
        </div>
    </div>

    <!-- <div class="w-fit mx-auto p-4 -mt-4 mb-8 sm:-mt-8 sm:mb-12 lg:-mt-20 lg:mb-16 text-sm text-warning-50 bg-warning-1200/40 transition-colors border border-warning-300/20 rounded-xl">
        <div class="flex items-center justify-center gap-4">
            <o-icon class="w-6 h-6 flex-none text-warning-300" name="heroicons.outline.language" />
            <p class="max-w-prose">
                {{ $t('misc.page.untranslated.to_french') }}
            </p>
        </div>
    </div> -->
</template>
