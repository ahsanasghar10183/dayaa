<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        foreach (['name', 'description', 'contact_person', 'phone', 'address', 'charity_number', 'tax_id', 'website', 'bank_account'] as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $data[$field] = trim($this->input($field));
            }
        }

        if (!empty($data['website']) && !preg_match('~^https?://~i', $data['website'])) {
            $data['website'] = 'https://' . $data['website'];
        }

        foreach (['charity_number', 'tax_id'] as $field) {
            if (!empty($data[$field])) {
                $data[$field] = strtoupper($data[$field]);
            }
        }

        if (!empty($data['bank_account'])) {
            $data['bank_account'] = strtoupper(preg_replace('/\s+/', '', $data['bank_account']));
        }

        if (!empty($data)) {
            $this->merge($data);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'contact_person' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\.\-\']+$/u'],
            'phone' => ['required', 'string', 'min:7', 'max:25', 'regex:/^[\+]?[0-9\s\-\(\)\.\/]+$/'],
            'website' => ['nullable', 'string', 'max:255', 'url'],
            'address' => ['required', 'string', 'min:10', 'max:500'],
            'charity_number' => ['nullable', 'string', 'max:50', 'regex:/^[A-Z0-9\-\/\s]+$/'],
            'tax_id' => ['nullable', 'string', 'max:30', 'regex:/^[A-Z0-9\-\/\s]+$/'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:5120', 'dimensions:max_width=4000,max_height=4000'],
            'remove_logo' => ['nullable', 'boolean'],
            'bank_account' => ['nullable', 'string', 'min:15', 'max:34', 'regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your organization name.',
            'name.min' => 'Organization name must be at least 2 characters.',
            'description.max' => 'Description may not exceed 2000 characters.',
            'contact_person.required' => 'Please enter the contact person\'s name.',
            'contact_person.regex' => 'Contact person name may only contain letters, spaces, dots, hyphens and apostrophes.',
            'phone.required' => 'Please enter a phone number.',
            'phone.min' => 'Phone number is too short.',
            'phone.max' => 'Phone number is too long.',
            'phone.regex' => 'Please enter a valid phone number. Allowed: digits, +, spaces, ( ), - and .',
            'website.url' => 'Please enter a valid website address (e.g., example.com or https://example.com).',
            'address.required' => 'Please enter your organization\'s address.',
            'address.min' => 'Address must be at least 10 characters (include street, city, postal code).',
            'address.max' => 'Address may not exceed 500 characters.',
            'charity_number.regex' => 'Charity number may only contain letters, digits, hyphens and slashes.',
            'tax_id.regex' => 'Tax ID may only contain letters, digits, hyphens and slashes (e.g., DE123456789).',
            'logo.image' => 'The logo must be an image file.',
            'logo.mimes' => 'The logo must be a JPG, PNG, SVG, or WEBP file.',
            'logo.max' => 'The logo may not be larger than 5 MB.',
            'logo.dimensions' => 'The logo dimensions are too large (max 4000x4000 pixels).',
            'bank_account.regex' => 'Please enter a valid IBAN (e.g., DE89 3704 0044 0532 0130 00).',
            'bank_account.min' => 'IBAN is too short.',
            'bank_account.max' => 'IBAN is too long.',
        ];
    }
}
