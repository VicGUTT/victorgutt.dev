import type { InternalAxiosRequestConfig } from 'axios';
import VisitData from '@/lib/Support/Data/VisitData.ts';

export default function ensureVisitHeadersAreSet(config: InternalAxiosRequestConfig): InternalAxiosRequestConfig {
    Object.assign(config.headers, VisitData.asHeaders());

    return config;
}
