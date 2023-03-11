<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Zone;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateZone extends Component {

    public array $locationIDs = [];
    public string $zoneName = '';

    protected function rules(): array {
        return [
            'zoneName' => [
                'required',
                'max:2',
                Rule::unique('zones', 'name')
            ],
            'locationIDs.*' => [
                'nullable',
                Rule::exists('locations', 'id')
            ]
        ];
    }

    public function save() {

        $this->validate();

        $zone = new Zone;
        $zone->name = Str::upper($this->zoneName);
        $zone->save();

        foreach($this->locationIDs as $locationID) {
            $zone->locations()->attach($locationID);
        }

        return redirect(route('zones.index'))
            ->with('success', 'Zóna ('.$this->zoneName.') sikeresen rögzítésre került a rendszerben');

    }

    public function render() {
        return view('livewire.create-zone', [
            'locations' => Location::all()
        ]);
    }
}
