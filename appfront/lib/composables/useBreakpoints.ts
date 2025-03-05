import { useBreakpoints as useBaseBreakpoints } from '@vueuse/core';

/**
 * Usage:
 * - `import useBreakpoints from '@/lib/composables/useBreakpoints.ts';`
 * - `const breakpoints = useBreakpoints();`
 * - `:attribute="breakpoints.greaterOrEqual('sm').value"`
 */
export default function useBreakpoints() {
    /**
     * @see https://github.com/VicGUTT/tailwindcss-opinionated-preset/blob/a33c03e14ea8677e8bc46a8b392c6df01e2b6068/src/setup/additions.css#L26
     */
    return useBaseBreakpoints({
        '2xs': 200,
        xs: 320,
        sm: 640,
        md: 768,
        lg: 1024,
        xl: 1280,
        '2xl': 1536,
        '3xl': 1920,
    });
}
