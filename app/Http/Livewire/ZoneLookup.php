<?php

namespace App\Http\Livewire;

use App\Models\Zone;
use Livewire\Component;

class ZoneLookup extends Component
{

    public bool $showZoneDropDown = false;
    public string $zoneDropDown = '';
    public $zones = [];

    public function mount(Zone $zone) {
        $this->zoneDropDown = $zone->name;
    }

    public function selectZone (Zone $zone) {

        $this->zoneDropDown = $zone->name;
        $this->showZoneDropDown = false;

        $this->dispatchBrowserEvent('zone-updated', ['zone_id' => $zone->id]);

    }

    public function updatedZoneDropDown() {

        if (strlen($this->zoneDropDown >= 1 )) {
            $this->zones =
                Zone::query()
                    ->where('name', 'like', '%'.$this->zoneDropDown.'%')
                    ->orderBy('name', 'asc')
                    ->limit(15)
                    ->get();
        }

        $this->showZoneDropDown = true;

    }

}
