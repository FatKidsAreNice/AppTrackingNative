<?php

namespace App\Http\Requests;

use App\Services\ColdstoreJobs\LineWorkplaceMapper;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowColdstoreJobsRequest extends FormRequest
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
        $supportedLines = app(LineWorkplaceMapper::class)->lines();

        return [
            'selected_line' => ['required', 'integer', Rule::in($supportedLines)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'selected_line.required' => 'Bitte waehle eine Linie aus.',
            'selected_line.integer' => 'Die ausgewaehlte Linie ist ungueltig.',
            'selected_line.in' => 'Die ausgewaehlte Linie wird aktuell nicht unterstuetzt.',
        ];
    }
}
