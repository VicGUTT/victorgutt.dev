import ensureTextResponsesAreNotRequested from '@/lib/Support/Axios/Interceptors/Request/ensureTextResponsesAreNotRequested.ts';
import ensureVisitHeadersAreSet from '@/lib/Support/Axios/Interceptors/Request/ensureVisitHeadersAreSet.ts';

export default [
    ensureTextResponsesAreNotRequested,
    ensureVisitHeadersAreSet,
    //
];
