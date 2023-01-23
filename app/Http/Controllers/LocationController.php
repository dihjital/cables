<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Zone;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function index()
    {

        return view('locations.index', [
            'locations' => Location::latest()->filter(
                request(['location', 'zone'])
            )->with('zones')->paginate(10)->withQueryString()
        ]);

    }

    public function show() {

    }

    public function destroy() {

        $location = Location::firstWhere('name', request()->location_name);
        $zone = Zone::firstWhere('name', request()->zone_name);

        if ($location && $zone) {
            $location->zones()->detach($zone->id);
        } else {
            return redirect()->back()->with('success', 'Zóna és lokáció szétkapcsolása sikertelen');
        }

        // if this was the last location attached to the given zone then we delete the zone as well ...
        if ($zone->locations()->count() == 0) {
            $zone->delete();
        }
        // if the location does not have any more zones attached to it then delete the location as well ...
        if ($location->zones()->count() == 0) {
            $location->delete();
        }

        return redirect()->back()->with('success', 'Zóna és lokáció sikeresen szétkapcsolva');

    }
}
