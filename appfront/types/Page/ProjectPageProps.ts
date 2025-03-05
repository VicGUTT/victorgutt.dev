import type { PageProps } from '@/types/Page/PageProps.ts';
import type { ProjectData } from '@/types/ProjectData.ts';

type Section = {
    readonly title: string;
    readonly data: Record<string, ProjectData>;
};

type Props = {
    readonly sections: Record<string, Section>;
};

export type ProjectPageProps = PageProps<Props>;
