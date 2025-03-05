<?php

declare(strict_types=1);

namespace Domain\Analytics\Requests;

use Illuminate\Support\Uri;
use Domain\Analytics\Pirsch\Pirsch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class InitialVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->ajax() && Pirsch::resolve()->enabled();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'href' => ['required', 'url'],
            'referrer' => ['nullable', 'url'],
        ];
    }

    public function href(): ?string
    {
        $href = Uri::of($this->validated('href'));

        if (blank($href->host())) {
            return null;
        }

        if ($href->host() !== $this->host()) {
            return null;
        }

        return $href->value();
    }

    public function referrer(): ?string
    {
        $referrer = $this->validated('referrer');

        if (blank($referrer)) {
            return null;
        }

        $referrer = Uri::of($referrer);

        if (blank($referrer->host())) {
            return null;
        }

        return $referrer->value();
    }
}
