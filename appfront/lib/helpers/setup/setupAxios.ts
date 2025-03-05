import axios from 'axios';
import requestInterceptors from '@/lib/Support/Axios/Interceptors/Request/index.ts';
import responseInterceptors from '@/lib/Support/Axios/Interceptors/Reponse/index.ts';

export default function setupAxios(): void {
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    /**
     * @see https://axios-http.com/docs/interceptors
     */
    requestInterceptors.forEach((interceptor) => {
        axios.interceptors.request.use((config) => interceptor(config));
    });
    responseInterceptors.forEach((interceptor) => {
        axios.interceptors.response.use((response) => interceptor(response));
    });
}
