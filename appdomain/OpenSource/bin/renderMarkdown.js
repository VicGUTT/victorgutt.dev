import Shiki from '@shikijs/markdown-it';
import str from '@vicgutt/strjs';
import markdownit from 'markdown-it';
import markdownAnchor from 'markdown-it-anchor';
import markdownTableOfContents from 'markdown-it-toc-done-right';

process.stdin.once('data', async (data) => {
    const content = data + '';

    const rendered = {
        content: await renderContent(content),
        table_of_content: extractTableOfContents(renderTableOfContents(content)),
    };

    console.log(JSON.stringify(rendered));
});

/* Renderers
------------------------------------------------*/

async function renderContent(content) {
    return markdownit({ html: true })
        .use(await useShiki())
        .use(...useAnchor())
        .render(content);
}

function renderTableOfContents(content) {
    return markdownit({ html: true })
        .use(...useAnchor())
        .use(...useTableOfContents())
        .render(`${content.trim()}\n\n|||||\n[[toc]]\n|||||\n`);
}

/* Plugins setup
------------------------------------------------*/

/**
 * @see https://shiki.style/packages/markdown-it
 */
function useShiki() {
    return Shiki({
        themes: {
            light: 'material-theme-ocean',
            dark: 'material-theme-ocean',
        },
    });
}

/**
 * @see https://github.com/valeriangalliat/markdown-it-anchor?tab=readme-ov-file#link-after-header
 */
function useAnchor() {
    return [
        markdownAnchor,
        {
            level: [2, 3, 4, 5, 6],
            /**
             * @see https://github.com/valeriangalliat/markdown-it-anchor/blob/957d7129a6a84bfa3e22283b99ee94c2dbdc16de/index.js#L64
             * @see https://github.com/valeriangalliat/markdown-it-anchor/blob/957d7129a6a84bfa3e22283b99ee94c2dbdc16de/index.js#L3
             */
            slugify: (title) => str(title).slug(),
            permalink: markdownAnchor.permalink.linkAfterHeader({
                style: 'aria-labelledby',
                wrapper: ['<div class="header-anchor-wrapper">', '</div>'],
            }),
        },
    ];
}

/**
 * @see https://github.com/nagaozen/markdown-it-toc-done-right?tab=readme-ov-file#usage
 */
function useTableOfContents() {
    return [
        markdownTableOfContents,
        {
            level: [2, 3],
        },
    ];
}

/* Actions
------------------------------------------------*/

function extractTableOfContents(html) {
    return str(html).between('<p>|||||</p>', '<p>|||||</p>');
}
