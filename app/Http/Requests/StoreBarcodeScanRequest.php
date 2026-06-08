<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBarcodeScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'barcode_id' => ['required', 'string', 'max:255'],
            'scanner_id' => ['required', 'string', 'max:100'],
            'direction' => ['required', 'string', 'in:entry,exit'],
            'scanned_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'barcode_id.required' => 'Bitte scanne oder erfasse einen Barcode.',
            'barcode_id.max' => 'Der Barcode ist zu lang.',
            'scanner_id.required' => 'Die Scanner-ID ist erforderlich.',
            'scanner_id.max' => 'Die Scanner-ID ist ungültig.',
            'direction.required' => 'Die Scan-Richtung ist erforderlich.',
            'direction.in' => 'Die Scan-Richtung muss entry oder exit sein.',
            'scanned_at.date' => 'Die Scan-Zeit muss ein gültiges Datum sein.',
        ];
    }
}
