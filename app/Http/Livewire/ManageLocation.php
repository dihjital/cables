<?php

namespace App\Http\Livewire;

use App\Http\Livewire\DataTable\Location\WithBulkActions;
use App\Http\Livewire\DataTable\Location\WithCachedRows;
use App\Http\Livewire\DataTable\Location\WithFiltering;
use App\Http\Livewire\DataTable\Location\WithPerPagePagination;
use App\Http\Livewire\DataTable\Location\WithSorting;
use App\Models\ConnectivityDevice;
use App\Models\Location;
use App\Models\LocationZone;
use Livewire\Component;

class ManageLocation extends Component
{

    use WithPerPagePagination, WithSorting, WithFiltering, WithBulkActions, WithCachedRows;

    public bool $showDeleteModal = false;

    public Location $currentLocation;

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
        $this->currentLocation = new Location();
    }

    public function confirmDelete (Location $location) {

        $this->currentLocation = $location ?? Location::make();
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
                        foreach ($row->zones as $zone) {
                            $active_cd += count(ConnectivityDevice::query()
                                ->where('location_id', $row->id)
                                ->where('zone_id', $zone->id)->get());
                        }
                        if ($active_cd == 0) {
                            LocationZone::query()->where('location_id', $row->id)->delete();
                            return true;
                        }
                    })->map->delete());
                });
            $this->selectedItems = [];
        } elseif (array_reduce($this->currentLocation->zones->toArray(),
            function ($active_cd, $zone) {
                $active_cd += count(ConnectivityDevice::query()
                    ->where('location_id', $this->currentLocation->id)
                    ->where('zone_id', $zone['id'])->get());
                return $active_cd;
            }, 0) == 0) {
            $deleteCount = $this->currentLocation->delete();
            LocationZone::query()->where('location_id', $this->currentLocation->id)->delete();
        }

        $this->showDeleteModal = false;

        session()->flash('success', "$deleteCount db. lokáció sikeresen törlésre került");

    }

    public function getRowsQueryProperty() {

        $query = Location::query()
            ->with([
                'zones'
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
        return view('livewire.manage-location', [
            'locations' => $this->rows
        ]);
    }
}
