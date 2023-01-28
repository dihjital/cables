<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\ConnectivityDevice\WithBulkActions;
use App\Http\Livewire\DataTable\ConnectivityDevice\WithCachedRows;
use App\Http\Livewire\DataTable\ConnectivityDevice\WithPerPagePagination;
use App\Http\Livewire\DataTable\ConnectivityDevice\WithSorting;
use App\Http\Livewire\DataTable\ConnectivityDevice\WithFiltering;
use App\Models\ConnectivityDevice;
use Livewire\Component;

// BUGS:
// Ha minden be van check-elve, akkor a keresés hibát dob, mivel a full_name nincsen kitöltve a selected_items-ben
// itt majd a search frissítésekor a kijelöléseket törölni kell a reset() metódussal

class ManageConnectivitydevice extends Component
{

    use WithPerPagePagination, WithSorting, WithFiltering, WithBulkActions, WithCachedRows;

    public bool $showDeleteModal = false;
    public bool $showOwnersDropDown = false;
    public bool $showAdvancedSearch = false;

    public ConnectivityDevice $current_cd;

    protected $queryString = [
        'sortField',
        'sortDirection'
    ];

    protected $listeners = [
        'showEmittedFlashMessage'
    ];

    public function showEmittedFlashMessage($message) {
        session()->flash('success', $message);
    }

    public function mount() {
        $this->current_cd = new ConnectivityDevice();
    }

    public function confirmDelete (ConnectivityDevice $cd) {

        $this->current_cd = $cd ?? null;
        $this->showDeleteModal = true;

    }

    public function delete() {

        // TODO: A selectedItems alapértelmezésbe állítása lehet másképpen is?
        // Ha nem töröltünk egyetlen rekordot sem, akkor nem kell a flash message ...
        if (count($this->selectedItems) > 0) {
            (clone $this->rowsQuery)
                ->unless($this->selectAll, fn($query) => $query->whereKey($this->selectedItems))
                ->reorder()
                ->orderBy('id', 'asc')
                ->chunkById(100, function ($rows) {
                    $rows->filter(function ($row) {
                        return $row->cable_count === 0;
                    })->map->delete();
                });
            $this->selectedItems = [];
        } elseif ($this->current_cd->cable_count === 0) {
            $this->current_cd->delete();
        }

        $this->showDeleteModal = false;

        session()->flash('success', 'A kiválasztott kapcsolati eszköz(ök) sikeresen törlésre kerültek');

    }

    public function getRowsQueryProperty() {

        $query = ConnectivityDevice::query()
            ->with(['zone',
                    'location',
                    'owner',
                    'cables',
                    'connectivity_device_type'
            ]);

        $query = $this->applyFiltering($query);
        return $this->applySorting($query);

    }

    public function getRowsProperty() {
        return $this->cache(function() {
            return $this->applyPerPage($this->rowsQuery);
        });
    }

    public function render() {

        return view('livewire.manage-connectivitydevice', [
            'connectivity_devices' => $this->rows
        ]);

    }
}
