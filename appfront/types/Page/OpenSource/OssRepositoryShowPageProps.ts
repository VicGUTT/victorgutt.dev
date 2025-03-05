import type { PageProps } from '@/types/Page/PageProps.ts';
import type { OssRepository } from '@/types/Models/OpenSource/OssRepository.ts';
import type { OssRelease } from '@/types/Models/OpenSource/OssRelease.ts';
import type { OssDocumentation } from '@/types/Models/OpenSource/OssDocumentation.ts';

type Props = {
    readonly repository: OssRepository;
    readonly release: OssRelease;
    readonly documentation: OssDocumentation;
};

export type OssRepositoryShowPageProps = PageProps<Props>;
