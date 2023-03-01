<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait WithOwnersDescription {

    public function idDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => 'Egyedi azonosító'
        );
    }

    public function nameDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => 'Tulajdonos neve'
        );
    }

    public function createdAtDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => 'Létrehozás időpontja'
        );
    }

    public function updatedAtDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => 'Utolsó módosítás időpontja'
        );
    }

}

