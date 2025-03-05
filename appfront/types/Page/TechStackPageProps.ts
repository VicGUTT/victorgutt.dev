import type { PageProps } from '@/types/Page/PageProps.ts';
import type { TechStackItemData } from '@/types/TechStackItemData.ts';

type Props = {
    readonly sections: Record<string, TechStackItemData[]>;
};

export type TechStackPageProps = PageProps<Props>;
