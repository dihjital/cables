<?php

namespace App\Http\Livewire;

use App\Models\Owner;
use Livewire\Component;

class CreateOwner extends Component
{

    public Owner $owner;
    public bool $showOwnerModal = false;

    public function mount() {
        $this->owner = Owner::make(['name' => '']);
    }

    protected array $attributes = [
        'owner.name' => 'Tulajdonos neve'
    ];

    protected array $messages = [
        'required' => 'A(z) :attribute mező megadása kötelező.',
        'min'      => 'A(z) :attribute rövidebb, mint a minimum (:min).',
        'unique'   => 'A(z) :attribute nem egyedi az adatbázisban.'
    ];

    protected array $rules = [
        'owner.name' => [
            'string',
            'required',
            'min:3',
            'unique:owners,name'
        ]
    ];

    public function resetPublicVariables() {
        $this->owner = Owner::make(['name' => '']);
        // $this->reset(); // this will throw an error due to unitialized variable
        $this->resetErrorBag();
    }

    public function toggleShowOwnerModal() {
        if ($this->showOwnerModal) {
            $this->resetPublicVariables();
            $this->showOwnerModal = false;
        } else {
            $this->showOwnerModal = true;
        }
    }

    public function save() {

        $this->validate($this->rules, $this->messages, $this->attributes);

        $this->owner->save();
        $this->showOwnerModal = false;

        $this->emit('ownerAdded', $this->owner->id);

    }

}
