<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AanvraagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // toegang wordt al geregeld door de route-middleware (auth + role:burger)
    }

    // Welke velden verplicht zijn hangt af van de toeslag in de URL
    public function rules(): array
    {
        $regels = [
            'inkomen' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'huishouden' => ['required', 'integer', 'min:1', 'max:20'],
        ];

        return match ($this->route('toeslag')->slug) {
            'huurtoeslag' => $regels + [
                'huur_per_maand' => ['required', 'numeric', 'min:1', 'max:5000'],
            ],
            'kinderopvangtoeslag' => $regels + [
                'aantal_kinderen' => ['required', 'integer', 'min:1', 'max:10'],
                'opvang_uren' => ['required', 'integer', 'min:1', 'max:230'],
            ],
            'zorgtoeslag' => $regels + [
                'zorgverzekering' => ['accepted'],
            ],
            default => $regels,
        };
    }

    public function attributes(): array
    {
        return [
            'inkomen' => 'jaarinkomen',
            'huishouden' => 'aantal personen in huishouden',
            'huur_per_maand' => 'huur per maand',
            'aantal_kinderen' => 'aantal kinderen',
            'opvang_uren' => 'opvanguren per maand',
            'zorgverzekering' => 'Nederlandse zorgverzekering',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Vul :attribute in.',
            'numeric' => ':Attribute moet een getal zijn.',
            'integer' => ':Attribute moet een heel getal zijn.',
            'min' => ':Attribute moet minimaal :min zijn.',
            'max' => ':Attribute mag maximaal :max zijn.',
            'zorgverzekering.accepted' => 'Voor zorgtoeslag moet je een Nederlandse zorgverzekering hebben.',
        ];
    }
}
