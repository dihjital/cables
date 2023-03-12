<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\LocationZone;
use App\Models\Zone;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditLocation extends Component
{

    public array $zoneIDs = [];

    public Location $location;

    public function rules(): array {
        return [
            'location.name' => [
                'required',
                'max:3',
                Rule::unique('locations', 'name')->ignore($this->location)
            ],
            'zoneIDs.*' => [
                'nullable',
                Rule::exists('zones', 'id')
            ]
        ];
    }

    public function mount (Location $location) {

        $this->location = $location ?? Location::make();

        $this->zoneIDs =
            LocationZone::where('location_id', $this->location->id)
                ->get('zone_id')
                ->pluck('zone_id')
                ->toArray();

    }

    public function update() {

        $this->validate();

        $this->location->name = Str::upper($this->location->name);
        $this->location->save();

        $this->location->zones()->detach();
        $this->location->zones()->attach($this->zoneIDs);

        return redirect(route('locations.index'))
            ->with('success', 'Lokáció ('.$this->location->name.') sikeresen módosításra került a rendszerben');

    }

    public function render()
    {
        return view('livewire.edit-location', [
            'zones' => Zone::all()
        ]);
    }
}

