<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    @class([
        'no-js',
        'dark' => request()->routeIs('web:resume.*') ? request()->query('theme') !== 'light' : true,
        'text-[89.5%]' => app()->getLocale() === 'en' && request()->routeIs('web:resume.*') && request()->boolean('printing'),
        'text-[87%]' => app()->getLocale() === 'fr' && request()->routeIs('web:resume.*') && request()->boolean('printing'),
    ])
>
    <head prefix="{{ seo()->openGraphPrefix() }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="light dark">
        {{-- <meta name="theme-color" media="(prefers-color-scheme: light)" content="..." /> --}}
        {{-- <meta name="theme-color" media="(prefers-color-scheme: dark)" content="..." /> --}}

        {!! file_get_contents(public_path('/images/favicons/content.html')) !!}

        @if (!app()->isProduction())
            <meta name="robots" content="noindex, nofollow">
        @endif

        @if (request()->routeIs('web:resume.*'))
            <meta name="robots" content="noindex, nofollow">
        @endif


        {{-- @see https://developers.google.com/search/docs/specialty/international/localized-versions#html --}}
        @foreach ($page['props']['app']['supported_locales'] as $locale)
            @php
                $routeParams = [
                    ...(Route::current()->parameters ?: []),
                    'locale' => $locale,
                ];
            @endphp

            <link rel="alternate" hreflang="{{ $locale }}" href="{{ route(Route::currentRouteName(), $routeParams) }}" />
        @endforeach

        <link rel="alternate" hreflang="x-default" href="{{ route('web:home') }}" />


        @php
            seo()
                ->locale(app()->getLocale())
                ->jsonLdNonce(Vite::cspNonce())

                ->title($page['props']['head']['title'] ?? null)
                ->description($page['props']['head']['description'] ?? null)
                ->images($page['props']['head']['og']['image'] ?? null);
        @endphp

        {{ seo()->generate(); }}

        @vite(['appfront/app.ts', "appfront/views/pages/{$page['component']}.vue"])
        @inertiaHead

        <script type="module" nonce="{{ Vite::cspNonce() }}">
            try {
                document.documentElement.classList.remove('no-js');
            } catch (error) {
                console.error(error);
            }
        </script>
        {{-- <script type="module" nonce="{{ Vite::cspNonce() }}">
            try {
                const HTMLClassList = document.documentElement.classList;

                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    HTMLClassList.add('dark');

                    localStorage.theme = 'dark';
                } else {
                    HTMLClassList.remove('dark');
                }
            } catch (error) {
                console.error(error);
            }
        </script> --}}

        @if (!request()->routeIs('dev:og'))
            <style>
                :is(:root, main) {
                    animation-duration: 1500ms;
                    animation-timing-function: ease;
                    animation-iteration-count: 1;
                    animation-fill-mode: forwards;
                }
                :is(:root.no-js) {
                    animation: none;
                }
                :is(.no-js main) {
                    animation-duration: 500ms;
                }
                :where(:root) {
                    animation-name: _root_init_animation;
                }
                :where(main) {
                    animation-name: _main_init_animation;
                }

                @media print {
                    :is(:root, main) {
                        animation: none;
                    }
                }

                @keyframes _root_init_animation {
                    0% {
                        opacity: 0;
                    }

                    100% {
                        opacity: 1;
                    }
                }
                @keyframes _main_init_animation {
                    0% {
                        opacity: 0;
                        translate: 0 -3px;
                    }

                    100% {
                        opacity: 1;
                        translate: none;
                    }
                }
            </style>
        @endif
    </head>
    <body class="relative bg-dots print:[--bg-dots-gradient-color:_transparent]" style="--bg-dots-animation: none;">
        <div class="absolute inset-0 bg-radial-mask pointer-events-none select-none print:hidden" inert aria-hidden="true"></div>

        <div class="fixed inset-0 pt-[250px] flex items-center justify-center select-none pointer-events-none overflow-hidden mix-blend-multiply filter blur-xl opacity-40 dark:mix-blend-color print:hidden" inert aria-hidden="true">
            <div class="container relative w-full">
                <div
                    class="w-[50vw] h-[70vh] ml-auto -mt-72"
                    style="
                        background:
                            radial-gradient(105.68% 45.69% at 92.95% 50%, color-mix(in oklch, var(--color-brand1-50) 50%, transparent) 0%, rgba(160, 255, 244, 0.094) 53.91%, rgba(254, 216, 255, 0) 100%),
                            radial-gradient(103.18% 103.18% at 90.11% 102.39%, color-mix(in oklch, var(--color-brand1-50) 100%, transparent) 0%, rgba(230, 255, 250, 0) 100%),
                            radial-gradient(90.45% 90.45% at 87.84% 9.55%, rgb(255, 210, 245) 0%, rgba(254, 219, 246, 0) 100%),
                            linear-gradient(135.66deg, rgba(203, 185, 255, 0.8) 14.89%, rgba(216, 202, 254, 0) 74.33%)
                        ;
                        background-blend-mode: normal, normal, normal, normal, normal, normal;
                        filter: blur(200px);
                        border-radius: 15rem;
                    "
                ></div>
            </div>
        </div>

        <noscript aria-hidden="true" hidden>
            <img
                src="{{
                    URL::signedRoute('web:visit.nojs', [
                        'locale' => $page['props']['app']['locale'],
                        'token' => csrf_token(),
                        'referer' => $page['props']['meta']['visit']['referer'] ?? '',
                        'href' => request()->fullUrl(),
                    ])
                }}"
                alt=""
            >
        </noscript>

        @inertia
    </body>
</html>
