export default function isSsr(): boolean {
    return typeof window === 'undefined';
}
