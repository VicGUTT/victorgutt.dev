export type OssRelease = {
    id: string;
    tag_name: string;
    name: string;
    major_version_and_more: string;
    body: string | null;
    draft: boolean;
    prerelease: boolean;
    oss_repository_id: string;
    github_id: string;
    github_html_url: string;
    created_at: string;
    published_at: string;
};

export type OssReleaseJoint = {
    id: OssRelease['id'];
    oss_repository_id: OssRelease['oss_repository_id'];
};
