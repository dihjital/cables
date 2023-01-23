<?php

namespace App\Http\Livewire;

use App\Models\Location;
use Livewire\Component;

class LocationLookup extends Component
{
    public bool $showLocationDropDown = false;
    public string $locationDropDown = '';
    public $locations = [];

    public function mount(Location $location) {
        $this->locationDropDown = $location->name;
    }

    public function selectLocation (Location $location) {

        $this->locationDropDown = $location->name;
        $this->showLocationDropDown = false;

        $this->dispatchBrowserEvent('location-updated', ['location_id' => $location->id]);

    }

    public function updatedLocationDropDown() {

        if (strlen($this->locationDropDown >= 2 )) {
            $this->locations =
                Location::query()
                    ->where('name', 'like', '%'.$this->locationDropDown.'%')
                    ->orderBy('name', 'asc')
                    ->limit(15)
                    ->get();
        }

        $this->showLocationDropDown = true;

    }

}
