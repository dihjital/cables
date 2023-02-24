<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\Zone\WithBulkActions;
use App\Http\Livewire\DataTable\Zone\WithCachedRows;
use App\Http\Livewire\DataTable\Zone\WithFiltering;
use App\Http\Livewire\DataTable\Zone\WithPerPagePagination;
use App\Http\Livewire\DataTable\Zone\WithSorting;
use App\Models\ConnectivityDevice;
use App\Models\LocationZone;
use App\Models\Zone;
use Livewire\Component;

class ManageZone extends Component
{

    use WithPerPagePagination, WithSorting, WithFiltering, WithBulkActions, WithCachedRows;

    public bool $showDeleteModal = false;

    public Zone $currentZone;

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
        $this->currentZone = new Zone();
    }

    public function confirmDelete (Zone $zone) {

        $this->currentZone = $zone ?? null;
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
                        $active_cd = 0;
                        foreach ($row->locations as $location) {
                            $active_cd += count(ConnectivityDevice::query()
                                ->where('zone_id', $row->id)
                                ->where('location_id', $location->id)->get());
                        }
                        if ($active_cd == 0) {
                            LocationZone::query()->where('zone_id', $row->id)->delete();
                            return true;
                        }
                    })->map->delete());
                });
            $this->selectedItems = [];
        } elseif (array_reduce($this->currentZone->locations->toArray(),
            function ($active_cd, $location) {
                $active_cd += count(ConnectivityDevice::query()
                    ->where('zone_id', $this->currentZone->id)
                    ->where('location_id', $location['id'])->get());
                return $active_cd;
            }, 0) == 0) {
            $deleteCount = $this->currentZone->delete();
            LocationZone::query()->where('zone_id', $this->currentZone->id)->delete();
        }

        $this->showDeleteModal = false;

        session()->flash('success', "$deleteCount db. zóna sikeresen törlésre került");

    }

    public function getRowsQueryProperty() {

        $query = Zone::query()
            ->with([
                'locations'
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
        return view('livewire.manage-zone', [
            'zones' => $this->rows
        ]);
    }
}
