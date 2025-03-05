import type { PageProps } from '@/types/Page/PageProps.ts';
import type { OssRepositoryIndex } from '@/types/Models/OpenSource/OssRepository.ts';

type Props = {
    readonly repositories: OssRepositoryIndex[];
};

export type OssRepositoryIndexPageProps = PageProps<Props>;
