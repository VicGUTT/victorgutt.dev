import type socials from '@/assets/static/socials.ts';

export default function extractUserNameFromSocialNetworkUrl(social: (typeof socials)[keyof typeof socials]) {
    return new URL(social.url).pathname.split('/').at(-1)!;
}
