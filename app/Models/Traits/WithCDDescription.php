<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait WithCDDescription {

    public function idDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Egyedi azonosító'
        );
    }

    public function nameDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Kábel teljes neve'
        );
    }

    public function zoneIdDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Kapcsolati eszköz zóna azonosító'
        );
    }

    public function locationIdDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Kapcsolati eszköz lokáció azonosító'
        );
    }

    public function startDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Kezdő kapcsolati pont'
        );
    }

    public function endDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Végződő kapcsolati pont'
        );
    }

    public function ownerIdDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Tulajdonos azonosítója'
        );
    }

    public function connectivityDeviceTypeIdDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Kapcsolati eszköz típusának azonosítója'
        );
    }

    public function createdAtDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Létrehozás időpontja'
        );
    }

    public function updatedAtDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Utolsó módosítás időpontja'
        );
    }

}

