import type { PageProps } from '@/types/Page/PageProps.ts';

type Props = {
    readonly email?: string | null;
};

export type ContactPageProps = PageProps<Props>;
