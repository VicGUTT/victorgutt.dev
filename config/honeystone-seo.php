<?php

declare(strict_types=1);

use Honeystone\Seo\Generators\MetaGenerator;
use Honeystone\Seo\Generators\JsonLdGenerator;
use Honeystone\Seo\Generators\TwitterGenerator;
use Honeystone\Seo\Generators\OpenGraphGenerator;

$title = str((string) env('APP_NAME'))->title()->value();

return [
    'generators' => [
        MetaGenerator::class => [
            'title' => $title,
            'titleTemplate' => '{title} | ' . $title,
            'description' => '',
            'keywords' => [],
            'canonicalEnabled' => true,
            'canonical' => null, // null to use current url
            'robots' => [],
            'custom' => [
                // [
                //     'greeting' => 'Hey, thanks for checking out the source code of our website. '.
                //         'Hopefully you find what you are looking for 👍'
                // ],
                // [
                //     'google-site-verification' => 'xxx',
                // ],
            ],
        ],
        /**
         * @see https://developer.twitter.com/en/docs/x-for-websites/cards/overview/abouts-cards
         */
        TwitterGenerator::class => [
            'enabled' => true,
            'site' => '@victorgutt',
            'card' => 'summary_large_image',
            'creator' => '@victorgutt',
            'creatorId' => '',
            'title' => '',
            'description' => '',
            'image' => '', // aspect ratio of 1:1 for "summary" & 2:1 for "summary_large_image"
            'imageAlt' => '',
        ],
        /**
         * @see https://ogp.me
         */
        OpenGraphGenerator::class => [
            'enabled' => true,
            'site' => $title,
            'type' => 'website',
            'title' => '',
            'description' => '',
            'images' => [],
            'audio' => [],
            'videos' => [],
            'determiner' => '',
            'url' => null, // null to use current url
            'locale' => app()->getLocale(),
            'alternateLocales' => [],
            'custom' => [],
        ],
        JsonLdGenerator::class => [
            'enabled' => true,
            'pretty' => env('APP_DEBUG'),
            'type' => 'WebPage',
            'name' => '',
            'description' => '',
            'images' => [],
            'url' => null, // null to use current url
            'custom' => [],

            // determines if the configured json-ld is automatically placed on the graph
            'place-on-graph' => true,
        ],
    ],

    'sync' => [
        'url-canonical' => true,
        'keywords-tags' => false,
    ],
];
