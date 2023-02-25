<?php

namespace App\Http\Livewire\DataTable\Cable;

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
        'min'      => ':attribute kisebb, mint a minimum (:min).',
        'numeric'  => ':attribute nem szám.',
        'unique'   => ':attribute nem egyedi.',
        'max'      => 'A(z) :attribute mező mérete meghaladja a megengedett maximumot (:max).',
        'size'     => ':attribute nagyobb, mint a megengedett méret.',
        'exists'   => 'A(z) :attribute érték nem létezik az adatbázisban.',
        'required_without'  => ':attribute beállítása kötelező, amennyiben :values nincsen megadva.',
        'prohibited_if' => ':attribute nem lehet megadva, amennyiben a kábelpár státusza Spare (:value)'
    ];

    // public function prepareForValidation($attributes) {
    //    $attributes['cableName'] = substr($this->cableName, 1);
    //    return $attributes;
    // }

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
            ],
            'massInsert' => [
                'required',
                'numeric'
            ]
        ], $this->messages, $this->attributes);

    }

}
