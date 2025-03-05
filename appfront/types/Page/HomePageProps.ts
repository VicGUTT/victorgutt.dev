import type { PageProps } from '@/types/Page/PageProps.ts';
import type { ProjectData } from '@/types/ProjectData.ts';
import type { OssRepositoryIndex } from '@/types/Models/OpenSource/OssRepository.ts';
import type { TechStackItemData } from '@/types/TechStackItemData.ts';

type Props = {
    readonly projects: Record<string, ProjectData>;
    readonly repositories: OssRepositoryIndex[];
    readonly tech_stacks: TechStackItemData[];
};

export type HomePageProps = PageProps<Props>;
