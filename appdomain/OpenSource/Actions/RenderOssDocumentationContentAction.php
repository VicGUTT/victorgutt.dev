<?php

declare(strict_types=1);

namespace Domain\OpenSource\Actions;

use Dom\HTMLElement;
use Dom\HTMLDocument;
use Illuminate\Support\Uri;
use App\Support\Action\Action;
use Illuminate\Support\Facades\Process;
use Domain\OpenSource\Models\OssDocumentation;
use Domain\OpenSource\Data\RenderedOssDocumentationContentData;

final readonly class RenderOssDocumentationContentAction extends Action
{
    public function execute(OssDocumentation $doc): RenderedOssDocumentationContentData
    {
        $binPath = domain_path('/OpenSource/bin/renderMarkdown.js');

        $result = Process::input($doc->content)
            ->run("node {$binPath}")
            ->throw();

        $result = json_decode($result->output(), true);

        $result['content'] = $this->transformRelativeLinksToAbsolute($doc, $result['content']);

        return RenderedOssDocumentationContentData::from($result);
    }

    private function transformRelativeLinksToAbsolute(OssDocumentation $doc, string $html): string
    {
        $dom = HTMLDocument::createFromString($html, LIBXML_NOERROR);

        collect($dom->querySelectorAll('a[href]:not([href=""])'))
            /**
             * Keep links with relative URLs.
             */
            ->filter(static function (HTMLElement $anchor): bool {
                $uri = Uri::of($anchor->getAttribute('href'));

                if (blank($uri->scheme())) {
                    return true;
                }

                if (blank($uri->host())) {
                    return true;
                }

                return false;
            })
            /**
             * Filter out fragments.
             */
            ->filter(static function (HTMLElement $anchor): bool {
                return !str($anchor->getAttribute('href'))->trim()->startsWith('#');
            })
            ->each(static function (HTMLElement $anchor) use ($doc): void {
                $source = Uri::of(str($doc->github_html_url)->before('/blob'));
                $uri = Uri::of($anchor->getAttribute('href'));

                $uri = $uri
                    ->withPath("{$source->path()}/blob/main/{$uri->path()}")
                    ->withHost($source->host())
                    ->withScheme('https');

                $anchor->setAttribute('href', $uri->value());
            });

        return $dom->documentElement->innerHTML;
    }
}
