<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'request_type' => ['required', 'string', Rule::in(['product_inquiry', 'platform_demo', 'enterprise_demo', 'partnership'])],
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'organization_name' => ['nullable', 'string', 'max:180'],
            'role_title' => ['nullable', 'string', 'max:120'],
            'interested_modules' => ['nullable', 'array'],
            'interested_modules.*' => ['string', 'max:60'],
            'land_scale' => ['nullable', 'string', 'max:80'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ];
    }
}
