<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'required' => 'A(z) :attribute mező megadása kötelező.',
    'required_array_keys' => 'A(z) :attribute mezőnek tartalmaznia kell: :values.',
    'required_if' => 'A(z) :attribute mező megadása kötelező amennyiben :other értéke :value.',
    'required_if_accepted' => 'A(z) :attribute mező megadása kötelező amennyiben :other elfogadott.',
    'required_unless' => 'A(z) :attribute mező megadása kötelező hacsak :other értéke :values.',
    'required_with' => 'A(z) :attribute mező megadása kötelező amennyiben :values létezik.',
    'required_with_all' => 'A(z) :attribute mező megadása kötelező amennyiben :values léteznek.',
    'required_without' => 'A(z) :attribute mező megadása kötelező amennyiben :values nem létezik.',
    'required_without_all' => 'A(z) :attribute mező megadása amennyiben egyik :values sem létezik.',
    'min' => [
        'array' => 'A(z) :attribute mezőnek legalább :min eleme kell, hogy legyen.',
        'file' => 'A(z) :attribute állomány mérete legalább :min kilobyte kell, hogy legyen.',
        'numeric' => 'A(z) :attribute mező értéke rövidebb mint :min.',
        'string' => 'A(z) :attribute mező hossza legalább :min karakter kell, hogy legyen.',
    ],
    'unique'   => 'A(z) :attribute már létezik az adatbázisban.',
    'string'   => 'A(z) :attribute nem alfanumerikus érték.',
    'exists'   => 'A(z) :attribute érték nem létezik az adatbázisban.',
    'max' => [
        'array' => 'A(z) :attribute mezőnek legfeljebb :max eleme lehet.',
        'file' => 'A(z) :attribute állomány mérete legfeljebb :max kilobyte lehet.',
        'numeric' => 'A(z) :attribute mező értéke hosszabb mint :max.',
        'string' => 'A(z) :attribute mező hossza legfeljebb :max karakter lehet.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // Owner create and edit
        'owner.name' => 'Tulajdonos neve',

        // Zone create and edit
        'zoneName' => 'Zóna neve',
        'zone.name' => 'Zóna neve',

        // Location create and edit
        'locationName' => 'Lokáció neve',
        'location.name' => 'Lokáció neve',

        // Cable import
        'fieldColumnMap.full_name' => 'Név',
        'fieldColumnMap.startCD' => 'Kezdő kapcsolati eszköz',
        'fieldColumnMap.start' => 'Kezdőpont',
        'fieldColumnMap.endCD' => 'Végződő kapcsolati eszköz',
        'fieldColumnMap.end' => 'Végpont',
        'fieldColumnMap.i_time' => 'Telepítés dátuma',
        'fieldColumnMap.status' => 'Kábelpár állapota',
        'fieldColumnMap.purpose' => 'Felhasználási mód'
    ],

];
