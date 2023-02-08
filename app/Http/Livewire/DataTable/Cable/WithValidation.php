<?php

namespace App\Http\Livewire\DataTable\Cable;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait WithValidation {

    protected array $attributes = [
        'selectCD.startCDOwner' => 'Kezdő kapcsolati eszköz tulajdonosa',
        'selectCD.endCDOwner' => 'Végződő kapcsolati eszköz tulajdonosa',
        'selectCD.startCDId' => 'Kezdő kapcsolati eszköz',
        'selectCD.endCDId' => 'Végződő kapcsolati eszköz',
        'selectCD.startConnectionPoint' => 'Kezdő kapcsolati pont',
        'selectCD.endConnectionPoint' => 'Végződő kapcsolati pont',
        'cableTypeId' => 'Kábel típusa',
        'cablePurposeId' => 'Kábel felhasználási módja',
        'cablePairStatusId' => 'Kábel státusza',
        'cableName' => 'Kábel neve'
    ];

    protected array $messages = [
        'required' => ':attribute mező megadása kötelező.',
        'min'      => ':attribute moet minimaal :min karakters bevatten.',
        'numeric'  => ':attribute mag alleen cijfers bevatten.',
        'unique'   => ':attribute moet uniek zijn.',
        'max'      => ':attribute mag maximaal :max zijn.',
        'numeric'  => ':attribute is geen geldig getal.',
        'size'     => ':attribute is te groot of bevat te veel karakters.'
    ];

    public function prepareForValidation($attributes) {
        $attributes['cableName'] = substr($this->cableName, 1);
        return $attributes;
    }

    public function validateSave() {

        // TODO: A kapcsolati pontok ellenőrzését ki kell egészíteni
        // ha spare egy kábel, akkor nem lehet kapcsolati pontja

        $this->validate([
            'selectCD.startCDOwner' => [
                'required',
                Rule::exists('owners', 'id')
            ],
            'selectCD.endCDOwner' => [
                'required',
                Rule::exists('owners', 'id')
            ],
            'selectCD.startCDId' => [
                'required',
                Rule::exists('connectivity_devices', 'id')
            ],
            'selectCD.endCDId' => [
                'required',
                Rule::exists('connectivity_devices', 'id')
            ],
            'selectCD.startConnectionPoint' => [
                'bail',
                'prohibited_if:cablePairStatusId,3',
                'exclude_if:cablePairStatusId,3',
                'required_without:selectCD.endConnectionPoint'
            ],
            'selectCD.endConnectionPoint' => [
                'bail',
                'prohibited_if:cablePairStatusId,3',
                'exclude_if:cablePairStatusId,3',
                'required_without:selectCD.startConnectionPoint'
            ],
            'cableTypeId' => [
                'required',
                Rule::exists('cable_types', 'id')
            ],
            'cablePurposeId' => [
                'required',
                Rule::exists('cable_purposes', 'id')
            ],
            'cableName' => [
                'required',
                'regex:/^[0-9]{7}$/',
                Rule::unique('cables', 'name')
                    ->where('cable_type_id', $this->cableTypeId)
            ],
            'cablePairStatusId' => [
                'required',
                Rule::exists('cable_pair_statuses', 'id')
            ]
        ], $this->messages, $this->attributes);

    }

}
