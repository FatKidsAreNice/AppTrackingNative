<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrackMarriageRequest extends FormRequest
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
            'track_id' => ['required', 'integer', 'min:1'],
            'uid' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'track_id.required' => 'Bitte waehle zuerst einen Track aus.',
            'track_id.integer' => 'Die Track-ID ist ungueltig.',
            'track_id.min' => 'Die Track-ID ist ungueltig.',
            'uid.required' => 'Bitte scanne oder erfasse eine UID.',
            'uid.max' => 'Die UID ist zu lang.',
        ];
    }
}
