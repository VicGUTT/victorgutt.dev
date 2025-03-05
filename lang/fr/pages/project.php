<?php

declare(strict_types=1);

return [
    'head' => [
        'title' => 'Mes Projets',
        'description' => 'Un aperçu de divers projets sur lesquels j\'ai travaillé ou que je suis en train de développer, allant du développement de projets personel à des initiatives Open Source',
    ],
    'sections' => [
        'paused' => [
            'title' => 'En pause',
            'data' => [
                'transl' => [
                    'name' => 'Transl.me',
                    'url' => 'https://transl.me',
                    'logo' => '/images/projects/transl.me.svg',
                    'description' => 'Une plateforme de gestion de traductions conçue pour les équipes utilisant Laravel. Permet aux développeurs et aux chefs de produit de mettre en automatique la livraison des traductions, de réduire le travail manuel et de minimiser les erreurs.',
                ],
                'ownai' => [
                    'name' => 'OwnAI.app',
                    'url' => 'https://ownai.app',
                    'logo' => '/images/projects/ownai.app.svg',
                    'description' => "Une application ordinateur et mobile qui met l'utilisateur en charge de ses interactions avec l'IA. Pas de serveurs externes, pas de connexion Internet requise. Le traitement et le stockage se produit sur l'appareil de l'utilisateur.",
                ],
                'boyojs' => [
                    'name' => 'BoyoJs',
                    'url' => 'https://boyojs.dev',
                    'logo' => '/images/projects/boyojs.svg',
                    'description' => "Les projets Boyo visent à faciliter et à accompagner les développeurs web lors de la création d'interfaces utilisateur et d'interfaces interactives UI. Qu'il s'agisse d'un widget ou composant simple, ou d'une application web complète.",
                ],
            ],
        ],
        'upcoming' => [
            'title' => 'À venir',
            'data' => [
                'visitjs' => [
                    'name' => 'VisitJs',
                    'url' => null,
                    'logo' => null,
                    'description' => "Une bibliothèque JavaScript open-source qui implémente l'API \"Navigation\" (API à venir) pour permettre une navigation semblable aux SPA à n'importe quelle page web via des attributs HTML spéciaux.",
                ],
                'jarr' => [
                    'name' => 'J.A.R.R.',
                    'url' => null,
                    'logo' => null,
                    'description' => "JSON API Request Response (J.A.R.R.)est une spécification pour la construction et la consommation d'APIs RESTful avec des paramètres de requête et des formats de réponse étendus et normalisés. Elle a été conçue pour etendre la \"JSON API\" spec.",
                ],
            ],
        ],
    ],
];
