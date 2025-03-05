@props([
    'title' => '',
    'titleTemplate' => '',
    'description' => '',
    'keywords' => [],
    'canonicalEnabled' => false,
    'canonical' => url()->current(),
    'robots' => [],
    'custom' => [],
    ...$config,
])

@if($title && !config('inertia.ssr.enabled'))
    <title inertia>{{ str_replace('{title}', $title, $titleTemplate) }}</title>
@endif

@if($description && !config('inertia.ssr.enabled'))
    <meta name="description" content="{{ $description }}" inertia>
@endif

@if(count($keywords))
    <meta name="keywords" content="{{ implode(',', $keywords) }}">
@endif

@if($canonicalEnabled && $canonical)
    <link rel="canonical" href="{{ $canonical }}">
@endif

@if(count($robots))
    <meta name="robots" content="{{ implode(',', $robots) }}">
@endif

@if(count($custom))
    @foreach($custom as $tag)
        @foreach($tag as $name => $value)
            @include('honeystone-seo::meta.custom', compact('name', 'value'))
        @endforeach
    @endforeach
@endif
