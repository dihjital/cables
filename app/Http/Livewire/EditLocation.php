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

    protected array $attributes = [
        'location.name' => 'Lokáció neve'
    ];

    protected array $messages = [
        'required' => 'A(z) :attribute mező megadása kötelező.',
        'min'      => ':attribute kisebb, mint a minimum (:min).',
        'numeric'  => ':attribute nem szám.',
        'unique'   => 'A(z) :attribute már létezik az adatbázisban.',
        'max'      => 'A(z) :attribute mező mérete meghaladja a megengedett maximumot (:max).',
        'size'     => ':attribute nagyobb, mint a megengedett méret.',
        'exists'   => 'A(z) :attribute érték nem létezik az adatbázisban.',
        'required_without'  => ':attribute beállítása kötelező, amennyiben :values nincsen megadva.',
        'prohibited_if' => ':attribute nem lehet megadva, amennyiben a kábelpár státusza Spare (:value)'
    ];

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

        $this->validate($this->rules(), $this->messages, $this->attributes);

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

