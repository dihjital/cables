<?php

namespace App\Http\Controllers;

use App\Models\Location;

class AdminLocationController extends Controller
{

    public function index() { return view('admin.locations.index'); }

    public function create () { return view('admin.locations.create');}

    public function edit (Location $location) {
        return view('admin.locations.edit', [
            'location' => $location
        ]);
    }

}
