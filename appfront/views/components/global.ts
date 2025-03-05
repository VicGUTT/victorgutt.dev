import type { App } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Logo from '@/views/components/logo.vue';
import Icon from '@/views/components/icon.vue';
import Navbar from '@/views/components/navbar.vue';
import Card from '@/views/components/card.vue';
import Section from '@/views/components/section.vue';
import PageNotTranslated from '@/views/components/page_not_translated.vue';
import MaybeAntiSpamEmail from '@/views/components/maybe_anti_spam_email.vue';

export default {
    'o-head': Head,
    'o-link': Link,
    'o-logo': Logo,
    'o-icon': Icon,
    'o-navbar': Navbar,
    'o-card': Card,
    'o-section': Section,
    'o-page-not-translated': PageNotTranslated,
    'o-maybe-anti-spam-email': MaybeAntiSpamEmail,
} satisfies Record<Parameters<App['component']>[0], Parameters<App['component']>[1]>;
