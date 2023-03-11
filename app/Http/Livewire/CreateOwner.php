<?php

namespace App\Http\Livewire;

use App\Models\Owner;
use Livewire\Component;

class CreateOwner extends Component
{

    public Owner $owner;
    public bool $showOwnerModal = false;

    public bool $renderSelect = true;

    public function mount(Owner $owner) { $this->owner = $owner; }

    protected array $rules = [
        'owner.name' => [
            'required',
            'string',
            'min:3',
            'unique:owners,name'
        ]
    ];

    protected $listeners = [
        'toggleShowOwnerModal' => 'showOwnerModal'
    ];

    public function showOwnerModal(Owner $owner) {
        $this->owner = $owner;
        $this->toggleShowOwnerModal();
    }

    public function toggleShowOwnerModal() {
        if ($this->showOwnerModal) {
            $this->showOwnerModal = false;
            $this->owner = new Owner();
        } else {
            $this->resetValidation('owner.name');
            $this->resetErrorBag('owner.name');
            $this->showOwnerModal = true;
        }
    }

    public function save() {

        $this->validate($this->rules);

        $this->owner->id ? $this->owner->update() : $this->owner->save();

        $this->emitUp('showEmittedFlashMessage', $this->owner->name.' sikeresen rögzítésre került a rendszerben.');
        $this->emit('ownerAdded', $this->owner->id);

        $this->toggleShowOwnerModal();

    }

}
