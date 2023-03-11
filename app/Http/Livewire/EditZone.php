<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\LocationZone;
use App\Models\Zone;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditZone extends Component
{

    public array $locationIDs = [];

    public Zone $zone;

    public function rules(): array {
        return [
            'zone.name' => [
                'required',
                'max:2',
                Rule::unique('zones', 'name')->ignore($this->zone)
            ],
            'locationIDs.*' => [
                'nullable',
                Rule::exists('locations', 'id')
            ]
        ];
    }

    public function mount (Zone $zone) {

        $this->zone = $zone ?? Zone::make();

        $this->locationIDs =
            LocationZone::where('zone_id', $this->zone->id)
                ->get('location_id')
                ->pluck('location_id')
                ->toArray();

    }

    public function update() {

        $this->validate();

        $this->zone->name = Str::upper($this->zone->name);
        $this->zone->save();

        $this->zone->locations()->detach();
        $this->zone->locations()->attach($this->locationIDs);

        return redirect(route('zones.index'))
            ->with('success', 'Zóna ('.$this->zone->name.') sikeresen módosításra került a rendszerben');

    }

    public function render()
    {
        return view('livewire.edit-zone', [
            'locations' => Location::all()
        ]);
    }
}

