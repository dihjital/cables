<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Zone;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateZone extends Component
{

    public array $locationIDs = [];
    public string $zoneName = '';

    protected array $attributes = [
        'zoneName' => 'Zóna neve'
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


    public function save() {

        $this->validate([
            'zoneName' => [
                'required',
                'max:2',
                Rule::unique('zones', 'name')
             ],
            'locationIDs.*' => [
                'nullable',
                Rule::exists('locations', 'id')
            ]
        ], $this->messages, $this->attributes);

        $zone = new Zone;
        $zone->name = Str::upper($this->zoneName);
        $zone->save();

        foreach($this->locationIDs as $locationID) {
            $zone->locations()->attach($locationID);
        }

        return redirect(route('zones.index'))
            ->with('success', 'Zóna ('.$this->zoneName.') sikeresen rögzítésre került a rendszerben');

    }

    public function render()
    {
        return view('livewire.create-zone', [
            'locations' => Location::all()
        ]);
    }
}
