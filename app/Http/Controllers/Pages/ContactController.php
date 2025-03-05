<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pages;

use App\Support\Pages\ContactPage;
use Domain\Resume\Models\ResumeData;

final class ContactController
{
    public function __invoke(): ContactPage
    {
        return ContactPage::new([
            'email' => ResumeData::query()->select('me')->first()?->me['email'],
        ]);
    }
}
