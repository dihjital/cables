<?php

namespace App\Http\Controllers;

use App\Models\ConnectivityDevice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminConnectivityDeviceController extends Controller
{

    protected array $attributes = [
        'name' => 'Rövid név',
        'zone_id' => 'Zóna',
        'location_id' => 'Lokáció',
        'start' => 'Kezdőpont',
        'end' => 'Végpont',
        'owner_id' => 'Tulajdonos',
        'connectivity_device_type_id' => 'Kapcsolati eszköz típusa'
    ];

    protected array $messages = [
        'required'  => 'A(z) :attribute megadása kötelező.',
        'regex'     => 'A(z) :attribute mező nem megfelelő formátumú.',
        'max'       => 'A(z) :attribute mező mérete meghaladja a megengedett maximumot (:max).',
        'exists'    => 'A(z) :attribute érték nem létezik az adatbázisban.',
        'unique'    => 'A megadott :attribute már létezik a rendszerben.'
    ];

    protected function prepareRulesForStore(): array {
        return [
            'name' => [
                'required',
                'max:3',
                Rule::unique('connectivity_devices', 'name')
                    ->where('zone_id', request()->zone_id)
                    ->where('location_id', request()->location_id)
            ],
            'zone_id' => [
                'required',
                Rule::exists('zones', 'id')
            ],
            'location_id' => [
                'required',
                Rule::exists('locations', 'id')
            ],
            'start' => [
                'required',
                'regex:/^Z[0-9]{3}S[0-9]{2}P[0-9]{3}$/si'
            ],
            'end' => [
                'required',
                'regex:/^Z[0-9]{3}S[0-9]{2}P[0-9]{3}$/si'
            ],
            'owner_id' => [
                'required',
                Rule::exists('owners', 'id')
            ],
            'connectivity_device_type_id' => [
                'required',
                Rule::exists('connectivity_device_types', 'id')
            ]
        ];
    }

    protected function prepareRulesForUpdate($connectivity_device): array {

        $rules = $this->prepareRulesForStore();

        $rules['name'] = [
            'required',
            'max:3',
            Rule::unique('connectivity_devices', 'name')
                ->where('zone_id', request()->zone_id)
                ->where('location_id', request()->location_id)->ignore($connectivity_device)
        ];

        return $rules ?? [];

    }

    public function index () {
        return view('admin.connectivitydevices.index');
    }

    public function edit (ConnectivityDevice $connectivity_device) {
        return view('admin.connectivitydevices.edit', [
            'connectivity_device' => $connectivity_device
        ]);
    }

    public function update (ConnectivityDevice $connectivity_device) {

        // TODO: LocationZone elem létezik-e

        $attributes =
            request()->validate($this->prepareRulesForUpdate($connectivity_device), $this->messages, $this->attributes);

        // TODO
        // Check if start and end contains the same zone given in their names

        if (!$this->compareConnectionPoints($attributes['start'], $attributes['end']))
            throw \Illuminate\Validation\ValidationException::withMessages([
                'start' => ['A kezdő kapcsolati pont nagyobb a végsőnél'],
                'end' => ['A végső kapcsolati pont kisebb a kezdőnél']
            ]);

        $end_max = $connectivity_device
            ->cable_pairs()
            ->where('conn_point', '!=', '')
            ->orderBy('conn_point', 'desc')
            ->take(1)
            ->pluck('conn_point')
            ->first() ?: request()->end;

        $start_min = $connectivity_device
            ->cable_pairs()
            ->where('conn_point', '!=', '')
            ->orderBy('conn_point', 'asc')
            ->take(1)
            ->pluck('conn_point')
            ->first() ?: request()->start;

        if (request()->start > $start_min)
            throw \Illuminate\Validation\ValidationException::withMessages([
                'start' => ['A kezdő kapcsolati pont nagyobb, mint a legkisebb kábelpár kapcsolódási pont']
            ]);

        if (request()->end < $end_max)
            throw \Illuminate\Validation\ValidationException::withMessages([
                'end' => ['A végződő kapcsolati pont kisebb, mint a legnagyobb kábelpár kapcsolódási pont']
            ]);

        $connectivity_device->update($attributes);

        return redirect('/admin/connectivity_devices')->with('success', 'Kapcsolati eszköz módosítása sikeres');

    }

    public function create () {
        return view('admin.connectivitydevices.create', []);
    }

    public function store() {

        $attributes =
            request()->validate($this->prepareRulesForStore(), $this->messages, $this->attributes);

        // TODO
        // Check if start and end contains the same zone given in their names

        if (!$this->compareConnectionPoints($attributes['start'], $attributes['end']))
            throw \Illuminate\Validation\ValidationException::withMessages([
                'start' => ['A kezdő kapcsolati pont nagyobb a végsőnél'],
                'end' => ['A végső kapcsolati pont kisebb a kezdőnél']
            ]);

        $cd = new ConnectivityDevice;
        $cd->fill($attributes);
        $cd->save();

        return redirect('/admin/connectivity_devices')
            ->with('success', 'Kapcsolati eszköz létrehozása sikeres');

    }

    public function destroy (ConnectivityDevice $connectivity_device) {

        $connectivity_device->delete();

        return back()->with('success', 'Kapcsolati eszköz sikeresen törlésre került');

    }

    private function compareConnectionPoints (?string $start, ?string $end): ?bool {

        $start_stripe = $start_zone = $start_port = 0;
        $end_stripe = $end_zone = $end_port = 0;

        $start_matches = $end_matches = array();

        preg_match("/^Z([0-9]{3})S([0-9]{2})P([0-9]{3})$/si",
            $start, $start_matches);

        preg_match("/^Z([0-9]{3})S([0-9]{2})P([0-9]{3})$/si",
            $end, $end_matches);

        $start_zone   = $start_matches[1];
        $start_stripe = $start_matches[2];
        $start_port   = $start_matches[3];

        $end_zone     = $end_matches[1];
        $end_stripe   = $end_matches[2];
        $end_port     = $end_matches[3];

        if ((($end_zone - $start_zone) != 0) ||         // Zones should match
            (($end_stripe - $start_stripe) < 0) ||      // Stripe should be the same or greater
            (($end_port - $start_port) < 0)) {         // Port should be greater
            return false;
        }

        return true;

    }

}
