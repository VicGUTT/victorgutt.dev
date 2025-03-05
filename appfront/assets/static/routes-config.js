import app from '@/lib/helpers/app.ts';

export default {
    get url() {
        return app().url;
    },
    "defaults": [],
    "routes": {
        "web:home": {
            "uri": "{locale?}",
            "methods": [
                "GET"
            ],
            "parameters": [
                "locale"
            ]
        },
        "web:projects": {
            "uri": "{locale?}/projects",
            "methods": [
                "GET"
            ],
            "parameters": [
                "locale"
            ]
        },
        "web:open_source.index": {
            "uri": "{locale?}/open-source",
            "methods": [
                "GET"
            ],
            "parameters": [
                "locale"
            ]
        },
        "web:open_source.show": {
            "uri": "{locale?}/open-source/{path}",
            "methods": [
                "GET"
            ],
            "parameters": [
                "locale",
                "path"
            ]
        },
        "web:tech_stack": {
            "uri": "{locale?}/tech-stack",
            "methods": [
                "GET"
            ],
            "parameters": [
                "locale"
            ]
        },
        "web:contact": {
            "uri": "{locale?}/contact",
            "methods": [
                "GET"
            ],
            "parameters": [
                "locale"
            ]
        },
        "web:resume.en": {
            "uri": "{locale?}/resume",
            "methods": [
                "GET"
            ],
            "parameters": [
                "locale"
            ]
        },
        "web:resume.fr": {
            "uri": "{locale?}/cv",
            "methods": [
                "GET"
            ],
            "parameters": [
                "locale"
            ]
        },
        "web:visit.initial": {
            "uri": "{locale?}/visit/initial",
            "methods": [
                "POST"
            ],
            "parameters": [
                "locale"
            ]
        },
        "web:visit.nojs": {
            "uri": "{locale?}/visit/nojs/{token}",
            "methods": [
                "GET"
            ],
            "parameters": [
                "locale",
                "token"
            ]
        },
        "web:locale_aware_fallback": {
            "uri": "{locale?}/{fallbackPlaceholder}",
            "methods": [
                "GET"
            ],
            "parameters": [
                "locale",
                "fallbackPlaceholder"
            ]
        }
    }
};
