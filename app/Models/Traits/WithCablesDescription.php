<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait WithCablesDescription {

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

    public function cableTypeIDDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Kábel típus azonosítója'
        );
    }

    public function startDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Kezdő kapcsolati eszköz azonosítója'
        );
    }

    public function endDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Végződő kapcsolati eszköz azonosítója'
        );
    }

    public function iTimeDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Telepítés dátuma'
        );
    }

    public function cablePurposeIdDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Kábel felhasználási módja'
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

    public function commentDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value = 'Megjegyzés'
        );
    }

}

