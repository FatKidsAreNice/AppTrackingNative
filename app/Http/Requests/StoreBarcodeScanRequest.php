<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'mode' => ['nullable', 'string', 'in:marriage'],
            'track_id' => ['nullable', 'integer', 'min:1', Rule::requiredIf(fn (): bool => $this->input('mode') === 'marriage')],
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
            'scanner_id.max' => 'Die Scanner-ID ist ungueltig.',
            'direction.required' => 'Die Scan-Richtung ist erforderlich.',
            'direction.in' => 'Die Scan-Richtung muss entry oder exit sein.',
            'scanned_at.date' => 'Die Scan-Zeit muss ein gueltiges Datum sein.',
            'mode.in' => 'Der Scan-Modus ist ungueltig.',
            'track_id.required' => 'Bitte waehle zuerst einen Track fuer die UID-Zuweisung aus.',
            'track_id.integer' => 'Die Track-ID ist ungueltig.',
            'track_id.min' => 'Die Track-ID ist ungueltig.',
        ];
    }
}
