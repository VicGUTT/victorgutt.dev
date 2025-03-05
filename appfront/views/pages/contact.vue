<script lang="ts" setup>
import type { ContactPageProps } from '@/types/Page/ContactPageProps.ts';
import usePage from '@/lib/composables/usePage.ts';
import socials from '@/assets/static/socials.ts';
import extractUserNameFromSocialNetworkUrl from '@/lib/utils/extractUserNameFromSocialNetworkUrl.ts';
import oPageHeader from '@/views/components/page_header.vue';

defineOptions({
    name: 'o-page-project',
});

const page = usePage<ContactPageProps>();
</script>

<template>
    <div class="container max-w-prose-xl overflow-clip">
        <o-page-header :title="page.props.head.title!" :description="$t('pages.contact.description')" />

        <div class="flex flex-col gap-20">
            <o-section v-if="page.props.data.email" :title="$t('words.email')">
                <div class="flex flex-col gap-4">
                    <o-maybe-anti-spam-email
                        class="w-fit flex items-center gap-2 text-lg text-muted [a[href]:not([href='']):hover]:underline"
                        :href="page.props.data.email"
                    >
                        <o-icon class="w-7 h-7 text-app" name="heroicons.solid.envelope" />
                        <span>{{ page.props.data.email }}</span>
                    </o-maybe-anti-spam-email>
                </div>
            </o-section>

            <o-section :title="$t('terms.social_networks')">
                <ul class="flex flex-col gap-8" role="list">
                    <li v-for="social in socials" :key="social.key">
                        <a
                            class="w-fit flex items-center gap-2 text-lg text-muted hover:underline"
                            :href="social.url"
                            :title="social.label"
                        >
                            <o-icon class="w-7 h-7 text-app" :name="`socials.${social.key}`" />
                            <span>/ @{{ extractUserNameFromSocialNetworkUrl(social) }}</span>
                        </a>
                    </li>
                </ul>
            </o-section>
        </div>
    </div>
</template>
