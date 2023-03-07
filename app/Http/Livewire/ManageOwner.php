<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\Owner\WithBulkActions;
use App\Http\Livewire\DataTable\Owner\WithCachedRows;
use App\Http\Livewire\DataTable\Owner\WithFiltering;
use App\Http\Livewire\DataTable\Owner\WithPerPagePagination;
use App\Http\Livewire\DataTable\Owner\WithSorting;
use App\Models\Owner;
use Livewire\Component;

class ManageOwner extends Component {

    use WithPerPagePagination, WithSorting, WithFiltering, WithBulkActions, WithCachedRows;

    public bool $showDeleteModal = false;
    public Owner $currentOwner;

    protected $listeners = [
        'ownerAdded' => '$refresh',
        'showEmittedFlashMessage'
    ];

    public function showEmittedFlashMessage($message) {
        session()->flash('success', $message);
    }

    public function mount() {
        $this->currentOwner = new Owner();
    }

    public function toggleNewOwnerModal(Owner $owner) { $this->emit('toggleShowOwnerModal', $owner->id); }

    public function confirmDelete (Owner $owner) {

        $this->currentOwner = $owner;
        $this->showDeleteModal = true;

    }

    public function delete() {

        // TODO: A selectedItems alapértelmezésbe állítása lehet másképpen is?
        // Ha nem töröltünk egyetlen rekordot sem, akkor nem kell a flash message ...

        $deleteCount = 0;

        if (count($this->selectedItems) > 0) {
            (clone $this->rowsQuery)
                ->unless($this->selectAll, fn($query) => $query->whereKey($this->selectedItems))
                ->reorder()
                ->orderBy('id', 'asc')
                ->chunkById(100, function ($rows) use (&$deleteCount) {
                    $deleteCount = count($rows->filter(function ($row) {
                            return $row->connectivity_devices()->count() === 0;
                    })->map->delete());
                });
            $this->selectedItems = [];
        } elseif ($this->currentOwner->connectivity_devices()->count() === 0) {
            $deleteCount = $this->currentOwner->delete();
        }

        $this->showDeleteModal = false;

        session()->flash('success', "$deleteCount db. tulajdonos sikeresen törlésre került");

    }

    public function getRowsQueryProperty() {

        $query = Owner::query()
            ->with([
                'connectivity_devices'
            ]);

        $query = $this->applyFiltering($query);
        return $this->applySorting($query);

    }

    public function getRowsProperty() {
        return $this->cache(function() {
            return $this->applyPerPage($this->rowsQuery);
        });
    }

    public function render()
    {
        return view('livewire.manage-owner', [
            'owners' => $this->rows
        ]);
    }

}
