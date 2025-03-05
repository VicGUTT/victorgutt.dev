<?php

declare(strict_types=1);

use Domain\OpenSource\Actions\ExtractMajorVersionAndMoreFromReleaseTagNameAction;

it('handles non "v" prefixed tag names', function (): void {
    $tagName = '2.7.4';
    $result = ExtractMajorVersionAndMoreFromReleaseTagNameAction::resolve()->execute($tagName);

    expect($result)->toEqual('2.x');
});

it('handles "v" prefixed tag names', function (): void {
    $tagName = 'v2.7.4';
    $result = ExtractMajorVersionAndMoreFromReleaseTagNameAction::resolve()->execute($tagName);

    expect($result)->toEqual('2.x');
});
