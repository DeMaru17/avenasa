<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $name = $this->input('name');
        $email = $this->input('email');
        $subject = $this->input('subject');
        $phone = $this->input('phone');
        $company = $this->input('company');
        $message = $this->input('message');

        // Sanitize mail header injection characters (\r, \n) from header fields
        $this->merge([
            'name' => is_string($name) ? trim(str_replace(["\r", "\n"], '', $name)) : $name,
            'email' => is_string($email) ? trim(str_replace(["\r", "\n"], '', $email)) : $email,
            'subject' => is_string($subject) ? trim(str_replace(["\r", "\n"], '', $subject)) : $subject,
            'phone' => is_string($phone) ? trim($phone) : $phone,
            'company' => is_string($company) ? trim($company) : $company,
            'message' => is_string($message) ? trim($message) : $message,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'website_url_hp' => ['nullable'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $isEn = app()->getLocale() === 'en';

        return [
            'name.required' => $isEn ? 'Please enter your full name.' : 'Nama lengkap wajib diisi.',
            'name.max' => $isEn ? 'Name may not be greater than 255 characters.' : 'Nama lengkap maksimal 255 karakter.',
            'email.required' => $isEn ? 'Please enter your email address.' : 'Alamat email wajib diisi.',
            'email.email' => $isEn ? 'Please enter a valid email address.' : 'Format alamat email tidak valid.',
            'email.max' => $isEn ? 'Email address may not be greater than 255 characters.' : 'Alamat email maksimal 255 karakter.',
            'phone.max' => $isEn ? 'Phone number may not be greater than 50 characters.' : 'Nomor telepon maksimal 50 karakter.',
            'company.max' => $isEn ? 'Company name may not be greater than 255 characters.' : 'Nama instansi/perusahaan maksimal 255 karakter.',
            'subject.required' => $isEn ? 'Please enter a request subject.' : 'Subjek permintaan wajib diisi.',
            'subject.max' => $isEn ? 'Subject may not be greater than 255 characters.' : 'Subjek permintaan maksimal 255 karakter.',
            'message.required' => $isEn ? 'Please enter your message or requirement details.' : 'Pesan atau rincian kebutuhan wajib diisi.',
            'message.min' => $isEn ? 'Message must be at least 10 characters.' : 'Pesan rincian kebutuhan minimal 10 karakter.',
            'message.max' => $isEn ? 'Message may not be greater than 5000 characters.' : 'Pesan rincian kebutuhan maksimal 5000 karakter.',
        ];
    }
}
