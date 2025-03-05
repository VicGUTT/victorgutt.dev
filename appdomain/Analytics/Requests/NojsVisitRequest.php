<?php

declare(strict_types=1);

namespace Domain\Analytics\Requests;

use Illuminate\Routing\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class NojsVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->ajax()) {
            return false;
        }

        $route = $this->route();

        if (!($route instanceof Route)) {
            return false;
        }

        return $route->parameter('token') === csrf_token();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [];
    }
}
