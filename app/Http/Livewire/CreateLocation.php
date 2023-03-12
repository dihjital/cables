<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Zone;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateLocation extends Component
{

    public array $zoneIDs = [];
    public string $locationName = '';

    protected function rules(): array {
        return [
            'locationName' => [
                'required',
                'max:3',
                Rule::unique('locations', 'name')
            ],
            'zoneIDs.*' => [
                'nullable',
                Rule::exists('zones', 'id')
            ]
        ];
    }

    public function save() {

        $this->validate();

        $location = new Location;
        $location->name = Str::upper($this->locationName);
        $location->save();

        $location->zones()->attach($this->zoneIDs);

        return redirect(route('locations.index'))
            ->with('success', 'Lokáció ('.$this->locationName.') sikeresen rögzítésre került a rendszerben');

    }

    public function render()
    {
        return view('livewire.create-location', [
            'zones' => Zone::all()
        ]);
    }
}
