import type { PageProps } from '@/types/Page/PageProps.ts';
import type { ResumeData } from '@/types/Models/Resume/ResumeData.ts';

type Props = ResumeData & {
    readonly files: Record<'light' | 'dark', string>;
};

export type ResumePageProps = PageProps<Props>;
