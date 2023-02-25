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

    protected array $attributes = [
        'locationName' => 'Lokáció neve'
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
            'locationName' => [
                'required',
                'max:3',
                Rule::unique('locations', 'name')
             ],
            'zoneIDs.*' => [
                'nullable',
                Rule::exists('zones', 'id')
            ]
        ], $this->messages, $this->attributes);

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
