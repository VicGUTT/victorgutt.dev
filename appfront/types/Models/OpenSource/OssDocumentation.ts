export type OssDocumentation = {
    id: string;
    name: string;
    path: string;
    sha: string;
    size: number;
    content: string | null;
    rendered_content: string | null;
    rendered_table_of_content: string | null;
    oss_release_id: string;
    github_html_url: string;
};
