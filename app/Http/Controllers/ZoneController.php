<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Zone;

use Illuminate\Http\Request;

class ZoneController extends Controller
{
    //
    public function index()
    {

        return view('zones.index', [
            'zones' => Zone::latest()->filter(
                request(['zone', 'location'])
            )->with('locations')->paginate(5)->withQueryString()
        ]);

    }

    public function store() {

        $attributes = request()->validate([
           'zone_name' => 'required|max:2',
           'location_name' => 'required|max:3'
        ]);

        $zone = Zone::firstWhere('name', $attributes['zone_name']);
        if (!$zone) {
            $zone = new Zone;
            $zone->name = $attributes['zone_name'];
            $zone->save();
        }

        $location = Location::firstWhere('name', $attributes['location_name']);
        if (!$location) {
            $location = new Location;
            $location->name = $attributes['location_name'];
            $location->save();
        }

        if ($zone->locations()
                ->where('name', $attributes['location_name'])
                ->exists()) {
            // return with an error message
            throw \Illuminate\Validation\ValidationException::withMessages([
                'location_name' => ['Ez a lokáció már össze van kapcsolva a megadott zónával']
            ]);
        } else {
            $zone->locations()->attach($location->id);
        }

        return redirect()->back()->with('success', 'Zóna és lokáció sikeresen összekapcsolva');

    }

    public function show() {

    }

    public function destroy() {

    }

}
