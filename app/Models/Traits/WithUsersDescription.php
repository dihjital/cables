<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait WithUsersDescription {

    public function idDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => 'Egyedi azonosító'
        );
    }

    public function nameDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => 'Felhasználó neve'
        );
    }

    public function emailDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => 'Felhasználó e-mail címe'
        );
    }

    public function enabledDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => 'Felhasználó engedélyezett'
        );
    }

    public function isadminDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => 'Felhasználó adminisztrátor'
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

    public function lastloginAtDescription(): Attribute {
        return Attribute::make(
            get: fn ($value) => 'Utolsó bejelentkezés időpontja'
        );
    }

}

