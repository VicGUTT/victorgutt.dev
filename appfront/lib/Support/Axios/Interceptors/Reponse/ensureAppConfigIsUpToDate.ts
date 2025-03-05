import type { AxiosResponse } from 'axios';
import type { PageProps } from '@/types/Page/PageProps.js';
import type { usePage } from '@inertiajs/vue3';
import app from '@/lib/helpers/app.js';

type Page = ReturnType<typeof usePage<PageProps<Record<string, unknown>>>>;
type Response = AxiosResponse<Page>;

export default function ensureAppConfigIsUpToDate(response: Response): AxiosResponse {
    if (response.config.headers['X-Inertia'] !== 'true') {
        return response;
    }

    if (!response.data.props.app) {
        return response;
    }

    app().refreshConfig(response.data.props.app);

    return response;
}
