<?php

namespace App\View\Components;

use App\Models\Location;
use Illuminate\View\Component;

class LocationDropdown extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.location-dropdown', [
            'locations' => Location::all(),
            'currentLocation' => Location::firstWhere('nane', request('location'))
        ]);
    }
}
