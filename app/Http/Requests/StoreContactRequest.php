<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page_id' => ['nullable', 'integer', 'exists:pages,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'message' => ['nullable', 'string', 'max:5000'],
            'service_requested' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'max:0'],
            'source_url' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
