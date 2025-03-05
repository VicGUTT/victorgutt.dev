<?php

declare(strict_types=1);

return [
    'head' => [
        'title' => 'My projects',
        'description' => 'A showcase of various projects I\'ve worked on or am currently developing, ranging from closed-source entrepreneurial endeavors to open-source initiatives',
    ],
    'sections' => [
        'paused' => [
            'title' => 'Paused',
            'data' => [
                'transl' => [
                    'name' => 'Transl.me',
                    'url' => 'https://transl.me',
                    'logo' => '/images/projects/transl.me.svg',
                    'description' => 'A web-based translation management platform built for teams using Laravel. Allows developers and product managers to automate translation delivery, reduce manual work and minimize errors.',
                ],
                'ownai' => [
                    'name' => 'OwnAI.app',
                    'url' => 'https://ownai.app',
                    'logo' => '/images/projects/ownai.app.svg',
                    'description' => 'A desktop and mobile app that puts users in charge of their AI interactions. No external servers, no internet connectivity required. Processing and storage happens on the user\'s device.',
                ],
                'boyojs' => [
                    'name' => 'BoyoJs',
                    'url' => 'https://boyojs.dev',
                    'logo' => '/images/projects/boyojs.svg',
                    'description' => 'The Boyo projects aim to facilitate and assist web developpers when building user interfaces and interactive UIs. Whether it be a simple widget or component, or a full blown web app.',
                ],
            ],
        ],
        'upcoming' => [
            'title' => 'Upcoming',
            'data' => [
                'visitjs' => [
                    'name' => 'VisitJs',
                    'url' => null,
                    'logo' => null,
                    'description' => 'An open-source JavaScript library that implements the upcoming Navigation API to allow SPA-like navigation to any webpage via special HTML attributes.',
                ],
                'jarr' => [
                    'name' => 'J.A.R.R.',
                    'url' => null,
                    'logo' => null,
                    'description' => 'JSON API Request Response (J.A.R.R.) is a specification for building and consuming RESTful APIs with extensive and standardized query parameters and response shapes. It is designed to improve upon the JSON API spec.',
                ],
            ],
        ],
    ],
];
