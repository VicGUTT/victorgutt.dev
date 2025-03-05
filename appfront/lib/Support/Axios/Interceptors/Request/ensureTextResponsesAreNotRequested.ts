import type { InternalAxiosRequestConfig } from 'axios';

export default function ensureTextResponsesAreNotRequested(
    config: InternalAxiosRequestConfig
): InternalAxiosRequestConfig {
    if (config.responseType === 'text') {
        delete config.responseType;
    }

    return config;
}
