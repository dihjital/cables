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

    protected array $attributes = [
        'zone.name' => 'Zóna neve'
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

        $this->validate($this->rules(), $this->messages, $this->attributes);

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

