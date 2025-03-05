import type { OssReleaseJoint } from '@/types/Models/OpenSource/OssRelease.ts';

type OssRepositoryLicense = {
    key: string;
    name: string;
};

export type OssRepository = {
    id: string;
    full_name: string;
    description: string | null;
    language: string | null;
    languages: Record<string, number> | null;
    size: number;
    topics: string[] | null;
    archived: boolean;
    license: OssRepositoryLicense;
    github_id: number;
    github_html_url: string;
    pushed_at: string;
    created_at: string;
    updated_at: string;
};

export type OssRepositoryIndex = {
    id: OssRepository['id'];
    full_name: OssRepository['full_name'];
    description: OssRepository['description'];
    language: OssRepository['language'];
    languages: OssRepository['languages'];
    topics: OssRepository['topics'];
    archived: OssRepository['archived'];
    license: OssRepository['license'] | null;
    created_at: OssRepository['created_at'];

    latest_release: OssReleaseJoint;
};
